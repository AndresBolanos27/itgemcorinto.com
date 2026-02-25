<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $subject->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información de la Materia -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Información de la Materia</h3>
                    <p><strong>Código:</strong> {{ $subject->codigo }}</p>
                    <p><strong>Nombre:</strong> {{ $subject->nombre }}</p>
                    <p><strong>Descripción:</strong> {{ $subject->descripcion }}</p>
                </div>
            </div>

            <!-- Grupos Asignados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Grupos Asignados</h3>
                        <a href="{{ route('teacher.grades.index', ['subject' => $subject->id]) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Ver Calificaciones
                        </a>
                    </div>

                    @if($subject->groups->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Código</th>
                                        <th class="px-4 py-2 text-left">Nombre</th>
                                        <th class="px-4 py-2 text-left">Estado</th>
                                        <th class="px-4 py-2 text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subject->groups as $group)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $group->codigo }}</td>
                                            <td class="px-4 py-2">{{ $group->nombre }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 rounded text-sm {{ $group->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($group->estado) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <a href="{{ route('teacher.grades.index', ['subject' => $subject->id, 'group' => $group->id]) }}" 
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
                        <p class="text-gray-500">No hay grupos asignados a esta materia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
