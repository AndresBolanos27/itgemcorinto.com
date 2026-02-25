<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($grade) ? __('Editar Nota') : __('Nueva Nota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ isset($grade) ? route('grades.update', $grade) : route('grades.store') }}">
                        @csrf
                        @if(isset($grade))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Estudiante -->
                            <div>
                                <x-input-label for="student_id" :value="__('Estudiante')" />
                                <select id="student_id" name="student_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Seleccionar Estudiante') }}</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ (old('student_id', isset($grade) ? $grade->student_id : '') == $student->id) ? 'selected' : '' }}>
                                            {{ $student->nombre }} {{ $student->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                            </div>

                            <!-- Materia -->
                            <div>
                                <x-input-label for="subject_id" :value="__('Materia')" />
                                <select id="subject_id" name="subject_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Seleccionar Materia') }}</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ (old('subject_id', isset($grade) ? $grade->subject_id : '') == $subject->id) ? 'selected' : '' }}>
                                            {{ $subject->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                            </div>

                            <!-- Calificación -->
                            <div>
                                <x-input-label for="grade" :value="__('Calificación')" />
                                <x-text-input id="grade" name="grade" type="number" step="0.01" min="0" max="100" class="mt-1 block w-full" :value="old('grade', isset($grade) ? $grade->grade : '')" />
                                <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                            </div>

                            <!-- Descripción -->
                            <div>
                                <x-input-label for="description" :value="__('Descripción')" />
                                <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('description', isset($grade) ? $grade->description : '') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                    {{ __('Cancelar') }}
                                </x-secondary-button>
                                <x-primary-button>
                                    {{ isset($grade) ? __('Actualizar') : __('Crear') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
