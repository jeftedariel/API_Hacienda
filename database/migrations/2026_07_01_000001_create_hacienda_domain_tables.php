<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Esquema normalizado del dominio de facturación. Sustituye a las tablas
 * dinámicas por empresa del legacy (`<idUser>_master_*`) usando company_id,
 * y a las tablas globales del framework (sessions, files).
 *
 * Correspondencia legacy -> nuevo (ver informe en el plan de migración):
 *   sessions                          -> legacy_sessions
 *   <id>_master_config_companny(EAV)  -> companies (pivotado) + hacienda_credentials
 *   <id>_master_users                 -> company_users
 *   <id>_master_sessions              -> company_user_sessions
 *   <id>_master_sucursales            -> branches
 *   <id>_master_terminales            -> terminals
 *   <id>_master_consecutive           -> consecutives
 *   <id>_master_receiver              -> receivers
 *   <id>_master_vouchers              -> documents
 *   <id>_master_inventary_sucursal_N  -> products (columna sucursal)
 *   <id>_master_rol / _permission     -> company_roles / company_permissions
 *   <id>_master_logs                  -> activity_logs
 *   files                             -> stored_files
 *   codificacion_mh / tipo_* / etc.   -> catálogos (mismo nombre de columnas)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Sesiones del framework legacy (tabla `sessions` original). Se
        // conservan las sesiones vivas al migrar datos.
        Schema::create('legacy_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('session_key');
            $table->string('ip', 45);
            $table->unsignedInteger('last_access');
            $table->index('session_key');
        });

        // Empresa: una por usuario master. El EAV <id>_master_config_companny
        // queda pivotado en columnas. companies.id == users.id del dueño
        // (== prefijo de las tablas dinámicas legacy).
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nombre')->default('');              // NOMBRE
            $table->string('tipo_cedula', 10)->default('01');   // TIPOCED
            $table->string('cedula', 20)->default('');          // CEDULA
            $table->string('nombre_comercial')->default('');    // NOMCOMER
            $table->string('email')->default('');               // EMAIL
            $table->string('id_provincia', 10)->default('');    // PROVINCIA
            $table->string('id_canton', 10)->default('');       // CANTON
            $table->string('id_distrito', 10)->default('');     // DISTRITO
            $table->string('id_barrio', 10)->default('');       // BARRIO
            $table->string('sennas')->default('');              // SENNAS
            $table->string('tel_cod_pais', 5)->default('506');  // NCODPAIS
            $table->string('tel_numero', 20)->default('');      // NNUMER
            $table->string('fax_cod_pais', 5)->default('506');  // FCODPAIS
            $table->string('fax_numero', 20)->default('');      // FNUMER
            $table->string('env', 10)->default('api-stag');     // ENV
            $table->string('situacion', 20)->default('normal'); // situacion
            $table->string('tipo_cambio', 20)->default('');     // TIPOCAMBIO
            $table->timestamps();
        });

        // Credenciales ATV de Hacienda por ambiente. password y pin viajan
        // cifrados (cast encrypted en el Model), no en claro como el legacy.
        Schema::create('hacienda_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('environment', 10); // stag | prod
            $table->text('username')->nullable();
            $table->text('password')->nullable();
            $table->string('p12_download_code')->nullable();
            $table->text('pin')->nullable();
            $table->unique(['company_id', 'environment']);
        });

        // Sub-usuarios de la empresa (ex <id>_master_users).
        Schema::create('company_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('full_name')->default('');
            $table->string('user_name', 100);
            $table->string('email', 100);
            $table->string('about')->default('');
            $table->string('country', 3)->default('crc');
            $table->string('status', 1)->default('1');
            $table->unsignedInteger('legacy_timestamp')->default(0);
            $table->unsignedInteger('last_access')->default(0);
            $table->string('password')->nullable();
            $table->string('legacy_md5', 32)->nullable();
            $table->string('avatar', 200)->default('');
            // En el legacy `settings` guarda el idTerminal asignado al
            // usuario (se usa como FK en los JOIN de getUsersCompanny).
            $table->text('settings')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'user_name']);
            $table->unique(['company_id', 'email']);
        });

        // Sesiones de sub-usuarios (ex <id>_master_sessions).
        Schema::create('company_user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_user_id')->constrained('company_users')->cascadeOnDelete();
            $table->string('session_key');
            $table->string('ip', 45);
            $table->unsignedInteger('last_access');
            $table->index('session_key');
        });

        // Sucursales (ex <id>_master_sucursales).
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('nombre_sucursal')->default('');
            $table->string('sucursal', 10); // código NNN
            $table->unique(['company_id', 'sucursal']);
        });

        // Terminales (ex <id>_master_terminales).
        Schema::create('terminals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('nombre_terminal')->default('');
            $table->string('terminal', 10); // código NNNNN
            $table->unique(['company_id', 'terminal']);
        });

        // Consecutivos por empresa/ambiente/tipo/usuario (ex _master_consecutive).
        Schema::create('consecutives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('env', 10);
            $table->string('company_name')->default('');
            $table->unsignedInteger('numero_consecutivo')->default(0);
            $table->string('tipo_comprobante', 10);
            $table->foreignId('company_user_id')->nullable()->constrained('company_users')->nullOnDelete();
            $table->index(['company_id', 'env', 'tipo_comprobante']);
        });

        // Receptores / clientes (ex <id>_master_receiver).
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_user_id')->nullable()->constrained('company_users')->nullOnDelete();
            $table->string('nombre_cliente', 100)->default('');
            $table->string('numero_cedula', 30)->default('');
            $table->string('tipo_cedula', 20)->default('');
            $table->string('telefono', 20)->default('');
            $table->string('id_provincia', 10)->default('');
            $table->string('id_canton', 10)->default('');
            $table->string('id_distrito', 10)->default('');
            $table->string('id_barrio', 10)->default('');
            $table->string('otras_senas', 200)->default('');
            $table->string('nombre_comercial')->default('');
            $table->string('correo_principal')->default('');
            $table->string('copias_correo')->default('');
            $table->string('codigo_pais', 10)->default('506');
            $table->string('numero_fax', 30)->default('');
            $table->string('estado_cliente', 1)->default('1');
            $table->timestamps();
        });

        // Comprobantes emitidos (ex <id>_master_vouchers).
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('consecutivo', 30)->default('');
            $table->string('clave', 60)->default('');
            $table->string('documento_referencia', 30)->nullable();
            $table->foreignId('company_user_id')->nullable()->constrained('company_users')->nullOnDelete();
            $table->string('tipo_documento', 50)->default('');
            $table->string('estado', 50)->default('');
            $table->mediumText('xml_enviado_base64')->nullable();
            $table->mediumText('respuesta_mh_base64')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreignId('receiver_id')->nullable()->constrained('receivers')->nullOnDelete();
            $table->string('env', 10)->default('');
            $table->index(['company_id', 'env']);
            $table->index('clave');
        });

        // Inventario por sucursal (ex <id>_master_inventary_sucursal_NNN).
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('sucursal', 10); // código de sucursal NNN
            $table->string('nombre')->default('');
            $table->string('descripcion')->default('');
            $table->string('unidad_medida', 20)->default('');
            $table->double('precio_venta')->default(0);
            $table->unsignedInteger('id_impuesto')->default(0);
            $table->integer('cantidad_impuesto')->default(0);
            $table->string('codigo_barras', 100)->default('');
            $table->integer('disponible')->default(0);
            $table->unique(['company_id', 'sucursal', 'codigo_barras']);
        });

        // Roles y permisos por empresa (ex _master_rol / _master_permission).
        Schema::create('company_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('rol_code')->default(0);
            $table->string('descripcion')->default('');
        });

        Schema::create('company_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_user_id')->constrained('company_users')->cascadeOnDelete();
            $table->foreignId('company_role_id')->constrained('company_roles')->cascadeOnDelete();
        });

        // Log de actividad (ex <id>_master_logs; el código legacy insertaba
        // en una columna `json` inexistente — aquí la columna es real).
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('log');
            $table->timestamp('created_at')->useCurrent();
        });

        // Archivos subidos (ex tabla `files`): certificados .p12, XML, avatares.
        Schema::create('stored_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('md5', 32)->default('');
            $table->unsignedInteger('legacy_timestamp')->default(0);
            $table->unsignedInteger('size')->default(0);
            $table->string('download_code', 50)->unique();
            $table->string('file_type', 25)->default('');
            $table->string('type', 15)->default(''); // hacienda | xml | avatar | ...
            $table->string('path')->default('');     // relativo a storage/app
            $table->timestamps();
        });

        // --- Catálogos globales (mismas columnas que el legacy) ---

        // codificacion_mh: geografía de CR según el Ministerio de Hacienda.
        Schema::create('codificacion_mh', function (Blueprint $table) {
            $table->id();
            $table->string('id_provincia', 10);
            $table->string('nombre_provincia', 100);
            $table->string('id_canton', 10);
            $table->string('nombre_canton', 100);
            $table->string('id_distrito', 10);
            $table->string('nombre_distrito', 100);
            $table->string('id_barrio', 10);
            $table->string('nombre_barrio', 100);
            $table->index(['id_provincia', 'id_canton', 'id_distrito']);
        });

        Schema::create('tipo_cedula', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10);
            $table->string('descripcion');
        });

        Schema::create('tipo_impuestos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10);
            $table->string('descripcion');
        });

        Schema::create('medio_pago', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10);
            $table->string('descripcion');
        });

        Schema::create('tipo_situacion', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10);
            $table->string('descripcion');
        });

        Schema::create('unidad_medida', function (Blueprint $table) {
            $table->id();
            $table->string('simbolo', 10);
            $table->string('descripcion', 150);
        });
    }

    public function down(): void
    {
        foreach ([
            'unidad_medida', 'tipo_situacion', 'medio_pago', 'tipo_impuestos',
            'tipo_cedula', 'codificacion_mh', 'stored_files', 'activity_logs',
            'company_permissions', 'company_roles', 'products', 'documents',
            'receivers', 'consecutives', 'terminals', 'branches',
            'company_user_sessions', 'company_users', 'hacienda_credentials',
            'companies', 'legacy_sessions',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
