<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Materias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($subjects->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2 text-left">Código</th>
                                        <th class="px-4 py-2 text-left">Nombre</th>
                                        <th class="px-4 py-2 text-left">Descripción</th>
                                        <th class="px-4 py-2 text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $subject->codigo }}</td>
                                            <td class="px-4 py-2">{{ $subject->nombre }}</td>
                                            <td class="px-4 py-2">{{ $subject->descripcion }}</td>
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
</x-app-layout>
