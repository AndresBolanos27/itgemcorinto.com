<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Elimina las tablas de relación antiguas que ya no son necesarias
     * debido a que se ha implementado el sistema de cargas académicas.
     */
    public function up(): void
    {
        // Eliminar las tablas de relación antiguas
        Schema::dropIfExists('group_subject');
        Schema::dropIfExists('subject_teacher');
        Schema::dropIfExists('group_teacher');
    }

    /**
     * Reverse the migrations.
     * Restaura las tablas de relación en caso de reversión.
     */
    public function down(): void
    {
        // Recrear la tabla group_subject
        Schema::create('group_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['group_id', 'subject_id']);
        });

        // Recrear la tabla subject_teacher
        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['subject_id', 'teacher_id']);
        });

        // Recrear la tabla group_teacher
        Schema::create('group_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['group_id', 'teacher_id']);
        });
    }
};
