<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Calificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(isset($noGroup) && $noGroup)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                            <p>{{ $noGroupMessage }}</p>
                        </div>
                    @elseif(isset($noSubjects) && $noSubjects)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                            <p>{{ $noSubjectsMessage }}</p>
                        </div>
                    @else
                        <!-- Información del estudiante -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Información del Estudiante</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p><span class="font-medium">Nombre:</span> {{ $student->nombre }} {{ $student->apellido }}</p>
                                    <p><span class="font-medium">Documento:</span> {{ $student->documento }}</p>
                                </div>
                                <div>
                                    <p><span class="font-medium">Grupo:</span> {{ $group->nombre }}</p>
                                    <p><span class="font-medium">Estado:</span> {{ ucfirst($student->estado) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de calificaciones -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia</th>
                                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calificación</th>
                                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subjects as $subject)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-4 px-4 border-b border-gray-200">{{ $subject->codigo }}</td>
                                            <td class="py-4 px-4 border-b border-gray-200">{{ $subject->nombre }}</td>
                                            <td class="py-4 px-4 border-b border-gray-200">
                                                @if(isset($grades[$subject->id]))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $grades[$subject->id]->nota_final >= 3 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ number_format($grades[$subject->id]->nota_final, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Sin calificar</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 border-b border-gray-200">
                                                @if(isset($grades[$subject->id]) && $grades[$subject->id]->observacion)
                                                    {{ $grades[$subject->id]->observacion }}
                                                @else
                                                    <span class="text-gray-400">Sin observaciones</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-4 px-4 border-b border-gray-200 text-center text-gray-500">
                                                No hay materias asignadas a tu grupo.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
