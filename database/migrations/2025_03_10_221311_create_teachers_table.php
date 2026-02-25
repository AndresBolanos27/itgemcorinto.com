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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->enum('tipo_documento', ['CC', 'CE', 'PAS'])->default('CC');
            $table->string('cedula')->unique();
            $table->string('correo')->unique();
            $table->string('celular');
            $table->string('titulo');
            $table->date('fecha_nacimiento');
            $table->text('direccion');
            $table->enum('sexo', ['masculino', 'femenino', 'otro'])->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->string('eps')->default('NUEVA EPS');
            $table->string('pension')->default('Colpensiones');
            $table->string('caja_compensacion')->default('Comfama');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
