<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de Docente
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del Docente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Información Personal</h3>
                    <p><strong>Nombre:</strong> {{ $teacher->nombre }} {{ $teacher->apellido }}</p>
                    <p><strong>Cédula:</strong> {{ $teacher->tipo_cedula }}-{{ $teacher->numero_cedula }}</p>
                    <p><strong>Correo:</strong> {{ $teacher->correo }}</p>
                    <p><strong>Título:</strong> {{ $teacher->titulo }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grupos Asignados -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Grupos Asignados</h3>
                        @if($assignedGroups->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2 text-left">Código</th>
                                            <th class="px-4 py-2 text-left">Nombre</th>
                                            <th class="px-4 py-2 text-left">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignedGroups as $group)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">{{ $group->codigo }}</td>
                                                <td class="px-4 py-2">{{ $group->nombre }}</td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 rounded text-sm {{ $group->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($group->estado) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No tienes grupos asignados.</p>
                        @endif
                    </div>
                </div>

                <!-- Materias Asignadas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Materias Asignadas</h3>
                        @if($assignedSubjects->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-2 text-left">Código</th>
                                            <th class="px-4 py-2 text-left">Nombre</th>
                                            <th class="px-4 py-2 text-left">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignedSubjects as $subject)
                                            <tr class="border-t">
                                                <td class="px-4 py-2">{{ $subject->codigo }}</td>
                                                <td class="px-4 py-2">{{ $subject->nombre }}</td>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('teacher.grades.index', ['subject' => $subject->id]) }}" 
                                                       class="text-blue-600 hover:text-blue-800">
                                                        Ver Calificaciones
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No tienes materias asignadas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
