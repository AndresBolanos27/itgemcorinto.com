<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todas las tablas excepto administradores y sus usuarios relacionados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('¿Estás seguro de que deseas limpiar la base de datos? Esta acción no se puede deshacer.')) {
            $this->info('Operación cancelada.');
            return;
        }

        // Desactivar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Tablas a preservar (no limpiar)
        $preserveTables = [
            'migrations',
            'personal_access_tokens',
            'password_reset_tokens',
            'failed_jobs',
        ];

        // Obtener IDs de administradores para preservarlos
        $adminIds = DB::table('admins')->pluck('user_id')->toArray();
        
        // Limpiar tablas
        $tables = $this->getTables();
        
        foreach ($tables as $table) {
            if (in_array($table, $preserveTables)) {
                $this->info("Tabla {$table} preservada.");
                continue;
            }
            
            // Manejar casos especiales
            if ($table === 'users') {
                // Mantener solo usuarios admin
                if (!empty($adminIds)) {
                    DB::table('users')->whereNotIn('id', $adminIds)->delete();
                    $this->info("Usuarios no administradores eliminados de la tabla {$table}.");
                } else {
                    $this->info("No se encontraron administradores, se mantienen todos los usuarios.");
                }
                continue;
            }
            
            if ($table === 'admins') {
                $this->info("Tabla {$table} preservada para mantener acceso al sistema.");
                continue;
            }
            
            // Truncar otras tablas
            DB::table($table)->truncate();
            $this->info("Tabla {$table} limpiada.");
        }

        // Reactivar verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Base de datos limpiada exitosamente, manteniendo administradores y sus usuarios.');
    }

    /**
     * Obtener todas las tablas de la base de datos.
     *
     * @return array
     */
    private function getTables()
    {
        return Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableNames();
    }
}
