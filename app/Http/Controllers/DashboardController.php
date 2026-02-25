<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas para el dashboard
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $groupCount = Group::count();
        $currentYear = date('Y');
        
        // Contar estudiantes por género
        $studentsByGender = Student::select('genero', DB::raw('count(*) as total'))
            ->whereIn('genero', ['Masculino', 'Femenino'])
            ->groupBy('genero')
            ->get()
            ->pluck('total', 'genero')
            ->toArray();
        
        // Asegurar que tenemos valores predeterminados si no hay datos
        $maleStudents = $studentsByGender['Masculino'] ?? 0;
        $femaleStudents = $studentsByGender['Femenino'] ?? 0;
        
        return view('dashboard', compact(
            'studentCount',
            'teacherCount',
            'groupCount',
            'currentYear',
            'maleStudents',
            'femaleStudents'
        ));
    }
}
