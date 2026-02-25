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
        Schema::table('grades', function (Blueprint $table) {
            // Agregar columna nota_final con decimal(3,2) para almacenar valores de 0.00 a 5.00
            $table->decimal('nota_final', 3, 2)->after('subject_id')->nullable();
            // Agregar columna observacion como texto opcional
            $table->text('observacion')->nullable()->after('nota_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['nota_final', 'observacion']);
        });
    }
};
