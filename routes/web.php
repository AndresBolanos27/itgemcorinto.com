<?php



Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    return '¡Caché limpiada!';
});

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TeacherGradeController;
use App\Http\Controllers\AcademicLoadController;
use App\Http\Controllers\StudentGradeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de grupos
    Route::get('groups/search', [GroupController::class, 'search'])->name('groups.search');
    Route::resource('groups', GroupController::class);

    // Rutas de materias
    Route::get('subjects/search', [SubjectController::class, 'search'])->name('subjects.search');
    Route::resource('subjects', SubjectController::class);

    // Rutas de docentes
    Route::get('teachers/search', [TeacherController::class, 'search'])->name('teachers.search');
    Route::resource('teachers', TeacherController::class);

    // Rutas de estudiantes
    Route::resource('students', StudentController::class);

    // Rutas para notas
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{student}', [GradeController::class, 'show'])->name('grades.show');
    Route::post('/grades/batch-update', [GradeController::class, 'batchUpdate'])->name('grades.batch-update');
    Route::put('/grades/{student}', [GradeController::class, 'update'])->name('grades.update');

    // Rutas de administradores
    Route::resource('admins', AdminController::class);

    // Rutas para cargas académicas
    Route::resource('academic-loads', AcademicLoadController::class);
    Route::get('academic-loads/assign/teacher/{teacher}', [AcademicLoadController::class, 'assignForTeacher'])->name('academic-loads.assign-for-teacher');
    Route::get('academic-loads/assign/subject/{subject}', [AcademicLoadController::class, 'assignForSubject'])->name('academic-loads.assign-for-subject');
    Route::get('academic-loads/assign/group/{group}', [AcademicLoadController::class, 'assignForGroup'])->name('academic-loads.assign-for-group');
});

// Rutas para docentes (solo autenticación, la verificación de rol se hace en el controlador)
Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/dashboard', function () {
        // Verificar si el usuario es docente
        if (Auth::user()->role !== 'teacher') {
            return redirect('/dashboard')->with('error', 'Acceso no autorizado.');
        }
        return view('teacher-dashboard');
    })->name('teacher.dashboard');
    
    Route::get('/teacher/grades', [TeacherGradeController::class, 'index'])
        ->name('teacher.grades.index');
    Route::get('/teacher/grades/{subject}', [TeacherGradeController::class, 'getGrade'])
        ->name('teacher.grades.get');
    Route::post('/teacher/grades/batch-update', [TeacherGradeController::class, 'batchUpdate'])
        ->name('teacher.grades.batch-update');
});

// Rutas para estudiantes (solo autenticación, la verificación de rol se hace en el controlador)
Route::middleware(['auth'])->group(function () {
    Route::get('/student/dashboard', function () {
        // Verificar si el usuario es estudiante
        if (Auth::user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Acceso no autorizado.');
        }
        return view('student-dashboard');
    })->name('student.dashboard');
    
    Route::get('/student/grades', [StudentGradeController::class, 'index'])
        ->name('student.grades.index');
});

require __DIR__.'/auth.php';
