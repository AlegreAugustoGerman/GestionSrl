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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Crea un BIGINT UNSIGNED de forma nativa
            $table->string('nombre', 50); // En Laravel se usa 'string' para VARCHAR
            $table->string('apellido', 50);
            $table->string('dni', 12)->unique(); // Conviene string para DNIs con letras (extranjeros) o pasaportes
            $table->string('email')->unique();
            $table->string('telefono', 25)->nullable(); // String evita que se rompa con caracteres como "+" o "-"
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(); // Nullable por si entran directo con Google Login
            $table->string('google_id')->nullable()->unique(); // Listo para tu Login de Google
            $table->string('avatar')->nullable();
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
