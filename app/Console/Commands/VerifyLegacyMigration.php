<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\HaciendaCredential;
use App\Models\StoredFile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Verifica una migración de datos legacy: compara conteos origen/destino y
 * hace spot-checks del descifrado de credenciales.
 */
class VerifyLegacyMigration extends Command
{
    protected $signature = 'legacy:verify-migration
        {--legacy-connection=legacy : Conexión al origen legacy}';

    protected $description = 'Verifica la migración de datos desde el API legacy';

    public function handle(): int
    {
        $conn = (string) $this->option('legacy-connection');
        $ok = true;

        $rows = [];
        foreach ([
            ['users', User::count(), DB::connection($conn)->table('users')->count()],
            ['files -> stored_files', StoredFile::count(), DB::connection($conn)->table('files')->count()],
        ] as [$label, $dest, $src]) {
            $match = $dest === $src ? '<info>OK</info>' : '<error>DIFF</error>';
            $ok = $ok && $dest === $src;
            $rows[] = [$label, $src, $dest, $match];
        }

        $this->table(['Origen', 'Legacy', 'Nuevo', 'Estado'], $rows);

        // Spot-check: cada empresa tiene credenciales descifrables.
        foreach (Company::all() as $company) {
            foreach (HaciendaCredential::where('company_id', $company->id)->get() as $cred) {
                try {
                    $cred->username; // fuerza el descifrado del cast
                    $cred->pin;
                } catch (\Throwable $e) {
                    $this->error("Empresa {$company->id} ({$cred->environment}): credenciales ilegibles — {$e->getMessage()}");
                    $ok = false;
                }
            }
        }

        $this->line('company_users migrados: '.CompanyUser::count());

        if ($ok) {
            $this->info('Verificación OK.');

            return self::SUCCESS;
        }

        $this->error('Verificación con diferencias.');

        return self::FAILURE;
    }
}
