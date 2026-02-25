<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class TeacherDashboardController extends Controller
{
    public function index(): View
    {
        if (!Gate::allows('teacher')) {
            abort(403, 'No tienes permiso para acceder al panel de docente.');
        }

        // Obtener el docente actual
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        // Obtener grupos y materias asignadas (solo activos)
        $assignedGroups = $teacher->groups()->where('estado', 'activo')->get();
        $assignedSubjects = $teacher->subjects()->where('estado', 'activo')->get();

        return view('teacher.dashboard', [
            'teacher' => $teacher,
            'assignedGroups' => $assignedGroups,
            'assignedSubjects' => $assignedSubjects
        ]);
    }
}
