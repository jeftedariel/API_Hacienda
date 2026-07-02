<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Consecutive;
use App\Models\Document;
use App\Models\HaciendaCredential;
use App\Models\LegacySession;
use App\Models\Receiver;
use App\Models\StoredFile;
use App\Models\Terminal;
use App\Models\User;
use App\Services\LegacyCrypto;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Importa los datos del API legacy al esquema nuevo. Es idempotente
 * (upsert por claves naturales) y read-only sobre el origen, por lo que
 * puede re-ejecutarse y el rollback es trivial (basta descartar el destino).
 *
 * El descubrimiento de empresas se apoya en las tablas dinámicas legacy
 * `<idUser>_master_config_companny`; ver el informe del facturador.
 */
class MigrateLegacyData extends Command
{
    protected $signature = 'legacy:migrate-data
        {--legacy-connection=legacy : Conexión Eloquent al origen legacy}
        {--company= : Migrar solo esta empresa (idUser legacy)}
        {--chunk=500 : Tamaño de lote}
        {--dry-run : No escribe; solo reporta}';

    protected $description = 'Migra usuarios, empresas y datos del API legacy al esquema Laravel';

    private string $conn;

    private bool $dryRun;

    private LegacyCrypto $crypto;

    public function handle(): int
    {
        $this->conn = (string) $this->option('legacy-connection');
        $this->dryRun = (bool) $this->option('dry-run');
        $this->crypto = LegacyCrypto::fromConfig();

        if ($this->dryRun) {
            $this->warn('DRY-RUN: no se escribirá nada.');
        }

        $this->migrateUsers();
        $this->migrateLegacySessions();
        $this->migrateFiles();

        foreach ($this->discoverCompanies() as $companyId) {
            $this->migrateCompany($companyId);
        }

        $this->info('Migración completada.');

        return self::SUCCESS;
    }

    private function legacy(string $table): Builder
    {
        return DB::connection($this->conn)->table($table);
    }

    private function migrateUsers(): void
    {
        $count = 0;
        foreach ($this->legacy('users')->orderBy('idUser')->cursor() as $u) {
            $this->upsert(User::class, ['id' => $u->idUser], [
                'full_name' => $u->fullName,
                'user_name' => $u->userName,
                'email' => $u->email,
                'about' => $u->about ?? '',
                'country' => $u->country ?? 'crc',
                'status' => $u->status ?? '1',
                'legacy_timestamp' => $u->timestamp ?? 0,
                'last_access' => $u->lastAccess ?? 0,
                ...$this->passwordColumns($u->pwd ?? ''),
                'avatar' => (string) ($u->avatar ?? '0'),
                'settings' => $u->settings ?? null,
            ]);
            $count++;
        }
        $this->line("users: $count");
    }

    private function migrateLegacySessions(): void
    {
        $count = 0;
        foreach ($this->legacy('sessions')->cursor() as $s) {
            if (! $this->dryRun && ! User::whereKey($s->idUser)->exists()) {
                continue; // sesión huérfana
            }
            $this->upsert(LegacySession::class, ['session_key' => $s->sessionKey], [
                'user_id' => $s->idUser,
                'ip' => $s->ip,
                'last_access' => $s->lastAccess,
            ]);
            $count++;
        }
        $this->line("legacy_sessions: $count");
    }

    private function migrateFiles(): void
    {
        $count = 0;
        foreach ($this->legacy('files')->cursor() as $f) {
            $this->upsert(StoredFile::class, ['download_code' => $f->downloadCode], [
                'user_id' => $f->idUser,
                'name' => $f->name,
                'md5' => $f->md5 ?? '',
                'legacy_timestamp' => $f->timestamp ?? 0,
                'size' => $f->size ?? 0,
                'file_type' => $f->fileType ?? '',
                'type' => $f->type ?? '',
                'path' => 'legacy-files/'.$f->idUser.'/'.$f->type.'/'.$f->name,
            ]);
            $count++;
        }
        $this->line("stored_files: $count (mover binarios de api/files/ a storage/app/legacy-files/ aparte)");
    }

    /** @return list<int> idUser de cada empresa (por sus tablas dinámicas) */
    private function discoverCompanies(): array
    {
        if ($only = $this->option('company')) {
            return [(int) $only];
        }

        $rows = DB::connection($this->conn)->select(
            "SHOW TABLES LIKE '%\\_master\\_config\\_companny'"
        );
        $ids = [];
        foreach ($rows as $row) {
            $name = array_values((array) $row)[0];
            if (preg_match('/^(\d+)_master_config_companny$/', $name, $m)) {
                $ids[] = (int) $m[1];
            }
        }
        sort($ids);

        return $ids;
    }

    private function migrateCompany(int $id): void
    {
        $this->info("Empresa $id");

        $config = $this->pivotConfig($id);
        if ($config === []) {
            $this->warn('  sin config_companny; se omite');

            return;
        }

        $this->upsert(Company::class, ['id' => $id], [
            'owner_user_id' => $id,
            'nombre' => $config['NOMBRE'] ?? '',
            'tipo_cedula' => $config['TIPOCED'] ?? '01',
            'cedula' => $config['CEDULA'] ?? '',
            'nombre_comercial' => $config['NOMCOMER'] ?? '',
            'email' => $config['EMAIL'] ?? '',
            'id_provincia' => $config['PROVINCIA'] ?? '',
            'id_canton' => $config['CANTON'] ?? '',
            'id_distrito' => $config['DISTRITO'] ?? '',
            'id_barrio' => $config['BARRIO'] ?? '',
            'sennas' => $config['SENNAS'] ?? '',
            'tel_cod_pais' => $config['NCODPAIS'] ?? '506',
            'tel_numero' => $config['NNUMER'] ?? '',
            'fax_cod_pais' => $config['FCODPAIS'] ?? '506',
            'fax_numero' => $config['FNUMER'] ?? '',
            'env' => $config['ENV'] ?? 'api-stag',
            'situacion' => $config['situacion'] ?? 'normal',
            'tipo_cambio' => $config['TIPOCAMBIO'] ?? '',
        ]);

        // Credenciales ATV re-cifradas con la APP_KEY (cast encrypted).
        foreach (['stag', 'prod'] as $env) {
            $this->upsert(HaciendaCredential::class,
                ['company_id' => $id, 'environment' => $env],
                [
                    'username' => $config[$env.'UserName'] ?? null,
                    'password' => $config[$env.'Password'] ?? null,
                    'p12_download_code' => $config[$env.'P12Code'] ?? null,
                    'pin' => $config[$env.'Pin'] ?? null,
                ]
            );
        }

        $this->migrateCompanyUsers($id);
        $this->migrateDynamic($id, 'sucursales', Branch::class, fn ($r) => [
            'match' => ['company_id' => $id, 'sucursal' => $r->sucursal],
            'data' => ['nombre_sucursal' => $r->nombreSucursal],
        ]);
        $this->migrateDynamic($id, 'terminales', Terminal::class, fn ($r) => [
            'match' => ['company_id' => $id, 'terminal' => $r->terminal],
            'data' => ['nombre_terminal' => $r->nombreTerminal],
        ]);
        $this->migrateDynamic($id, 'consecutive', Consecutive::class, fn ($r) => [
            'match' => ['company_id' => $id, 'env' => $r->ENV, 'tipo_comprobante' => $r->tipoComprobante],
            'data' => ['company_name' => $r->companyName ?? '', 'numero_consecutivo' => $r->numeroConsecutivo ?? 0],
        ]);
        $this->migrateDynamic($id, 'receiver', Receiver::class, fn ($r) => [
            'match' => ['company_id' => $id, 'numero_cedula' => $r->numeroCedula, 'nombre_cliente' => $r->nombreCliente],
            'data' => [
                'tipo_cedula' => $r->tipoCedula ?? '', 'telefono' => $r->telefono ?? '',
                'id_provincia' => $r->idProvincia ?? '', 'id_canton' => $r->idCanton ?? '',
                'id_distrito' => $r->idDistrito ?? '', 'id_barrio' => $r->idBarrio ?? '',
                'otras_senas' => $r->otrasSenas ?? '', 'nombre_comercial' => $r->nombreComercial ?? '',
                'correo_principal' => $r->correoPrincipal ?? '', 'copias_correo' => $r->copiasCorreo ?? '',
                'codigo_pais' => $r->codigoPais ?? '506', 'numero_fax' => $r->numeroFax ?? '',
                'estado_cliente' => (string) ($r->estadoCliente ?? '1'),
            ],
        ]);
        $this->migrateDynamic($id, 'vouchers', Document::class, fn ($r) => [
            'match' => ['company_id' => $id, 'clave' => $r->clave, 'consecutivo' => $r->consecutivo],
            'data' => [
                'documento_referencia' => $r->idComprobanteReferencia ?? null,
                'tipo_documento' => $r->tipoDocumento ?? '', 'estado' => $r->estado ?? '',
                'xml_enviado_base64' => $r->xmlEnviadoBase64 ?? null,
                'respuesta_mh_base64' => $r->respuestaMHBase64 ?? null,
                'env' => $r->env ?? '',
            ],
        ]);
    }

    /** Pivota <id>_master_config_companny (EAV) a un mapa name => value. */
    private function pivotConfig(int $id): array
    {
        $table = $id.'_master_config_companny';
        if (! $this->tableExists($table)) {
            return [];
        }

        $out = [];
        foreach ($this->legacy($table)->get(['name', 'value']) as $row) {
            $out[$row->name] = $row->value;
        }

        return $out;
    }

    private function migrateCompanyUsers(int $id): void
    {
        $table = $id.'_master_users';
        if (! $this->tableExists($table)) {
            return;
        }

        $count = 0;
        foreach ($this->legacy($table)->cursor() as $u) {
            $this->upsert(CompanyUser::class,
                ['company_id' => $id, 'user_name' => $u->userName],
                [
                    'full_name' => $u->fullName ?? '',
                    'email' => $u->email ?? '',
                    'about' => $u->about ?? '',
                    'country' => $u->country ?? 'crc',
                    'status' => $u->status ?? '1',
                    'legacy_timestamp' => $u->timestamp ?? 0,
                    'last_access' => $u->lastAccess ?? 0,
                    ...$this->passwordColumns($u->pwd ?? ''),
                    'avatar' => (string) ($u->avatar ?? ''),
                    'settings' => $u->settings ?? null,
                ]
            );
            $count++;
        }
        $this->line("  company_users: $count");
    }

    /**
     * @param  \Closure(object): array{match: array, data: array}  $mapper
     */
    private function migrateDynamic(int $id, string $suffix, string $model, \Closure $mapper): void
    {
        $table = $id.'_master_'.$suffix;
        if (! $this->tableExists($table)) {
            return;
        }

        $count = 0;
        foreach ($this->legacy($table)->cursor() as $row) {
            $mapped = $mapper($row);
            $this->upsert($model, $mapped['match'], $mapped['data']);
            $count++;
        }
        $this->line("  $suffix: $count");
    }

    private function passwordColumns(string $legacyPwd): array
    {
        if ($legacyPwd === '') {
            return ['password' => null, 'legacy_md5' => null];
        }

        // ¿Es un md5 puro? (esquema legacy antiguo)
        if (preg_match('/^[a-f0-9]{32}$/i', $legacyPwd)) {
            return ['password' => null, 'legacy_md5' => $legacyPwd];
        }

        // base64(AES(bcrypt)) -> bcrypt puro.
        $bcrypt = $this->crypto->extractBcrypt($legacyPwd);

        return $bcrypt !== false
            ? ['password' => $bcrypt, 'legacy_md5' => null]
            : ['password' => null, 'legacy_md5' => null];
    }

    private function tableExists(string $table): bool
    {
        return DB::connection($this->conn)->getSchemaBuilder()->hasTable($table);
    }

    /**
     * @param  class-string<Model>  $model
     */
    private function upsert(string $model, array $match, array $data): void
    {
        if ($this->dryRun) {
            return;
        }

        $model::query()->updateOrCreate($match, $data);
    }
}
