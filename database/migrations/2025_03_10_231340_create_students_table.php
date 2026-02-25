<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->enum('tipo_documento', ['CC', 'TI', 'CE', 'PAS'])->default('CC');
            $table->string('cedula')->unique();
            $table->string('correo')->unique();
            $table->string('celular');
            $table->date('fecha_nacimiento');
            $table->string('direccion');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->enum('genero', ['masculino', 'femenino', 'otro'])->nullable();
            $table->enum('grupo_etnico', ['ninguno', 'afrodescendiente', 'indigena', 'raizal', 'rom', 'palenquero'])->default('ninguno');
            $table->string('eps')->default('NUEVA EPS');
            $table->string('acudiente')->default('Por asignar');
            $table->string('telefono_acudiente')->default('No especificado');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
