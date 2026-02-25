<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Materias a Grupos') }}
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

                    <form method="POST" action="{{ route('subjects.update-groups-batch') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Selección de Materia -->
                            <div>
                                <x-input-label for="subject" :value="__('Materia')" />
                                <select id="subject" name="subject" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Seleccione una materia...</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" data-groups="{{ $subject->groups->pluck('id') }}">
                                            {{ $subject->codigo }} - {{ $subject->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Selección de Grupos -->
                            <div>
                                <x-input-label :value="__('Grupos')" />
                                <div id="selected-groups" class="flex flex-wrap gap-2 min-h-[50px] p-4 bg-gray-50 rounded-md">
                                    <!-- Los badges se agregarán aquí dinámicamente -->
                                </div>
                                <select id="group-select" class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Seleccione grupos para asignar...</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" data-code="{{ $group->codigo }}" data-name="{{ $group->nombre }}">
                                            {{ $group->codigo }} - {{ $group->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="groups" id="groups-input">
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Guardar Asignaciones') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelect = document.getElementById('subject');
            const groupSelect = document.getElementById('group-select');
            const selectedGroupsContainer = document.getElementById('selected-groups');
            const groupsInput = document.getElementById('groups-input');
            let selectedGroups = new Set();

            // Función para crear un badge
            function createBadge(id, code, name) {
                const badge = document.createElement('div');
                badge.className = 'inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                badge.innerHTML = `
                    ${code}
                    <button type="button" class="ml-1 text-blue-600 hover:text-blue-900" data-group-id="${id}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                badge.title = name;
                return badge;
            }

            // Función para actualizar los badges y el input oculto
            function updateBadges() {
                selectedGroupsContainer.innerHTML = '';
                selectedGroups.forEach(id => {
                    const option = groupSelect.querySelector(`option[value="${id}"]`);
                    if (option) {
                        const badge = createBadge(
                            id,
                            option.dataset.code,
                            option.dataset.name
                        );
                        selectedGroupsContainer.appendChild(badge);
                    }
                });
                // Actualizar el input oculto con los IDs de los grupos
                groupsInput.value = Array.from(selectedGroups).join(',');
            }

            // Evento de cambio de materia
            subjectSelect.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                if (option && option.dataset.groups) {
                    selectedGroups = new Set(option.dataset.groups.split(',').filter(id => id.trim() !== ''));
                } else {
                    selectedGroups = new Set();
                }
                updateBadges();
            });

            // Evento de selección de grupo
            groupSelect.addEventListener('change', function() {
                const selectedId = this.value;
                if (selectedId) {
                    selectedGroups.add(selectedId);
                    updateBadges();
                    this.value = ''; // Resetear el select
                }
            });

            // Evento para eliminar badges
            selectedGroupsContainer.addEventListener('click', function(e) {
                const button = e.target.closest('button');
                if (button) {
                    const id = button.dataset.groupId;
                    selectedGroups.delete(id);
                    updateBadges();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
