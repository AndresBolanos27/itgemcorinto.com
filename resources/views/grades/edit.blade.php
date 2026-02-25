<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Notas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Estudiante: {{ $student->nombre }} {{ $student->apellido }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Materia: {{ $subject->nombre }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('grades.update', ['student' => $student->id, 'subject' => $subject->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @for($periodo = 1; $periodo <= 3; $periodo++)
                                <div>
                                    <x-input-label for="calificaciones[{{ $periodo }}]" value="Parcial {{ $periodo }}" />
                                    <x-text-input
                                        id="calificaciones[{{ $periodo }}]"
                                        name="calificaciones[{{ $periodo }}]"
                                        type="number"
                                        step="0.1"
                                        min="0"
                                        max="100"
                                        class="mt-1 block w-full"
                                        value="{{ isset($grades[$periodo]) ? $grades[$periodo]->calificacion : '' }}"
                                    />
                                    <x-input-error :messages="$errors->get('calificaciones.'.$periodo)" class="mt-2" />
                                </div>
                            @endfor
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                            <a href="{{ route('grades.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
