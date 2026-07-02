<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Usuarios de plataforma (ex tabla legacy `users`). Su id es además
        // el id de la empresa (companies.id) de la que son dueños: en el
        // legacy el idUser era el prefijo de las tablas dinámicas.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('user_name', 100)->unique();
            $table->string('email', 100)->unique();
            $table->string('about')->default('');
            $table->string('country', 3)->default('crc');
            $table->string('status', 1)->default('1');
            $table->unsignedInteger('legacy_timestamp')->default(0);
            $table->unsignedInteger('last_access')->default(0);
            // bcrypt puro (el legacy guardaba base64(AES(bcrypt)); se
            // descifra en la migración de datos). Nullable: usuarios cuyo
            // hash quedó en md5 conservan solo legacy_md5.
            $table->string('password')->nullable();
            $table->string('legacy_md5', 32)->nullable();
            $table->string('avatar', 200)->default('0');
            $table->text('settings')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
