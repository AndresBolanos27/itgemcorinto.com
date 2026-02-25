<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Docentes a ') . $subject->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('subjects.update-teachers', ['subject' => $subject->id]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label :value="__('Docentes Disponibles')" />
                                <div class="mt-2 space-y-2">
                                    @foreach($teachers as $teacher)
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                id="teacher_{{ $teacher->id }}"
                                                name="teachers[]"
                                                value="{{ $teacher->id }}"
                                                {{ $subject->teachers->contains($teacher->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <label for="teacher_{{ $teacher->id }}" class="ml-2 text-sm text-gray-600">
                                                {{ $teacher->codigo }} - {{ $teacher->nombre }} {{ $teacher->apellido }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Guardar Asignaciones') }}</x-primary-button>
                            <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
