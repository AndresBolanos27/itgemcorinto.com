<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard de Docente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    
                    @if(session('error'))
                        <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Información Personal -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h4 class="text-md font-medium text-gray-700 mb-4">Información Personal</h4>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600"><span class="font-medium">Nombre:</span> {{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                                <p class="text-sm text-gray-600"><span class="font-medium">Rol:</span> Docente</p>
                            </div>
                        </div>

                        <!-- Accesos Rápidos -->
                        <div class="bg-indigo-50 p-6 rounded-lg shadow">
                            <h4 class="text-md font-medium text-indigo-700 mb-4">Accesos Rápidos</h4>
                            <div class="space-y-3">
                                <a href="{{ route('teacher.grades.index') }}" class="flex items-center p-3 bg-white rounded-md shadow-sm hover:bg-indigo-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-gray-700 font-medium">Gestión de Calificaciones</span>
                                </a>
                                <a href="#" class="flex items-center p-3 bg-white rounded-md shadow-sm hover:bg-indigo-100 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="text-gray-700 font-medium">Ver Mis Materias Asignadas</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Próximas Funcionalidades -->
                    <div class="bg-blue-50 p-6 rounded-lg shadow">
                        <h4 class="text-md font-medium text-blue-700 mb-4">Próximas Funcionalidades</h4>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Generación de reportes de calificaciones</li>
                            <li>Calendario de actividades académicas</li>
                            <li>Comunicación directa con estudiantes</li>
                            <li>Seguimiento de asistencia</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
