@php
    use Illuminate\Support\Facades\Auth;
    if (Auth::user() && Auth::user()->role === 'student') {
        header('Location: ' . route('student.dashboard'));
        exit;
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 sm:p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Bienvenido') }}, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">Has iniciado sesión correctamente en el sistema ITGEM.</p>
                </div>
            </div>

            <!-- Estadísticas (Bento Layout) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 border-b border-gray-200 bg-blue-50">
                        <p class="text-xs text-blue-600 uppercase font-semibold">Estudiantes</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $studentCount }}</h3>
                        <p class="text-sm text-gray-600 mt-1">Total registrados</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 border-b border-gray-200 bg-green-50">
                        <p class="text-xs text-green-600 uppercase font-semibold">Docentes</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $teacherCount }}</h3>
                        <p class="text-sm text-gray-600 mt-1">Total registrados</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 border-b border-gray-200 bg-purple-50">
                        <p class="text-xs text-purple-600 uppercase font-semibold">Grupos</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $groupCount }}</h3>
                        <p class="text-sm text-gray-600 mt-1">Total activos</p>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-6 border-b border-gray-200 bg-yellow-50">
                        <p class="text-xs text-yellow-600 uppercase font-semibold">Año académico</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $currentYear }}</h3>
                        <p class="text-sm text-gray-600 mt-1">Periodo actual</p>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de Acceso Rápido -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @if(Auth::user()->role === 'admin')
                <!-- Tarjetas para administradores -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg responsive-card">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Gestión de Docentes</h3>
                        <p class="text-gray-600 mb-4">Administra la información de los docentes del sistema.</p>
                        <a href="{{ route('teachers.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ver Docentes
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg responsive-card">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Gestión de Estudiantes</h3>
                        <p class="text-gray-600 mb-4">Administra la información de los estudiantes del sistema.</p>
                        <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ver Estudiantes
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg responsive-card">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Cargas Académicas</h3>
                        <p class="text-gray-600 mb-4">Gestiona las asignaciones de materias a docentes y grupos.</p>
                        <a href="{{ route('academic-loads.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-800 focus:outline-none focus:border-purple-800 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ver Cargas
                        </a>
                    </div>
                </div>
                @elseif(Auth::user()->role === 'teacher')
                <!-- Tarjetas para docentes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg responsive-card">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Mis Calificaciones</h3>
                        <p class="text-gray-600 mb-4">Gestiona las calificaciones de tus estudiantes.</p>
                        <a href="{{ route('teacher.grades.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ver Calificaciones
                        </a>
                    </div>
                </div>
                @elseif(Auth::user()->role === 'student')
                <!-- Tarjetas para estudiantes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg responsive-card">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Mis Calificaciones</h3>
                        <p class="text-gray-600 mb-4">Consulta tus calificaciones académicas.</p>
                        <a href="{{ route('student.grades.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ver Calificaciones
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>


</x-app-layout>
