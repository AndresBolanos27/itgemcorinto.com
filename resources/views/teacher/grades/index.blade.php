<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Calificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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

                    @if(isset($noAssignments) && $noAssignments)
                        <div class="mb-6 px-6 py-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                            <p class="font-bold">No tienes asignaciones completas</p>
                            <p>{{ $noAssignmentsMessage ?? 'No se te ha asignado ningún grupo o materia para calificar.' }}</p>
                        </div>
                    @elseif(isset($noSubjectsInGroup) && $noSubjectsInGroup)
                        <div class="mb-6 px-6 py-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                            <p class="font-bold">No tienes materias asignadas en este grupo</p>
                            <p>No se te ha asignado ninguna materia para calificar en el grupo seleccionado.</p>
                        </div>
                        <!-- Selector de Grupo -->
                        <div class="mb-6">
                            <label for="group_select" class="block text-sm font-medium text-gray-700">Seleccionar Grupo:</label>
                            <select id="group_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un grupo</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <!-- Selector de Grupo -->
                        <div class="mb-6">
                            <label for="group_select" class="block text-sm font-medium text-gray-700">Seleccionar Grupo:</label>
                            <select id="group_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione un grupo</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if(request('group_id'))
                            <!-- Selector de Materia -->
                            <div class="mb-6">
                                <label for="subject_select" class="block text-sm font-medium text-gray-700">Seleccionar Materia:</label>
                                <select id="subject_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione una materia</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->nombre }}</option>
                                    @endforeach
                                </select>
                                @if($subjects->isEmpty())
                                    <p class="mt-2 text-sm text-red-600">No tienes materias asignadas en este grupo.</p>
                                @endif
                            </div>

                            <!-- Advertencia cuando no hay materia seleccionada -->
                            <div id="subject-warning" class="flex items-center justify-center bg-yellow-50 p-4 mb-4 rounded-md border border-yellow-400">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Selecciona una materia para ver y editar las calificaciones</span>
                            </div>

                            <!-- Grupo seleccionado (Badge) -->
                            <div class="mb-4 flex flex-wrap gap-2">
                                @foreach($groups as $group)
                                    @if(request('group_id') == $group->id)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Grupo: {{ $group->nombre }}
                                        </span>
                                    @endif
                                @endforeach
                                
                                <!-- Badge para materia seleccionada (inicialmente oculto) -->
                                <span id="subject-badge" class="hidden inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Materia: <span id="subject-name"></span>
                                </span>
                            </div>

                            <!-- Tabla de Notas (inicialmente oculta) -->
                            <div id="grades-table" class="overflow-x-auto" style="display: none;">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Calificaciones</h3>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nota Final</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($students as $student)
                                            <tr class="student-row" data-student-id="{{ $student->id }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ $student->nombre }} {{ $student->apellido }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <input type="number" 
                                                           class="grade-input w-20 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                           min="0" 
                                                           max="5" 
                                                           step="0.1"
                                                           placeholder="0.0"
                                                           disabled>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <textarea 
                                                        class="observation-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                        rows="1"
                                                        placeholder="Observación"
                                                        disabled></textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Botón de guardar general -->
                                <div class="mt-4 text-right">
                                    <button id="save-all-grades" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Guardar Todas las Notas
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const groupSelect = document.getElementById('group_select');
        const subjectSelect = document.getElementById('subject_select');
        const subjectWarning = document.getElementById('subject-warning');
        const gradesTable = document.getElementById('grades-table');
        const subjectBadge = document.getElementById('subject-badge');
        const subjectName = document.getElementById('subject-name');
        
        // Verificar estado inicial del badge de advertencia
        if (subjectSelect && subjectWarning) {
            if (subjectSelect.value) {
                subjectWarning.style.display = 'none';
                gradesTable.style.display = 'block';
                subjectBadge.classList.remove('hidden');
                subjectName.textContent = subjectSelect.options[subjectSelect.selectedIndex].text;
            } else {
                subjectWarning.style.display = 'flex';
                if (gradesTable) {
                    gradesTable.style.display = 'none';
                }
                subjectBadge.classList.add('hidden');
            }
        }
        
        // Cambiar de grupo
        if (groupSelect) {
            groupSelect.addEventListener('change', function() {
                const groupId = this.value;
                if (groupId) {
                    window.location.href = `/teacher/grades?group_id=${groupId}`;
                } else {
                    window.location.href = '/teacher/grades';
                }
            });
        }
        
        // Cambiar de materia
        if (subjectSelect) {
            subjectSelect.addEventListener('change', async function() {
                const subjectId = this.value;
                const groupId = groupSelect.value;
                
                if (subjectId && groupId) {
                    try {
                        // Mostrar tabla de calificaciones y ocultar advertencia
                        subjectWarning.style.display = 'none';
                        gradesTable.style.display = 'block';
                        subjectBadge.classList.remove('hidden');
                        subjectName.textContent = subjectSelect.options[subjectSelect.selectedIndex].text;
                        
                        // Cargar las calificaciones para esta materia
                        const response = await fetch(`/teacher/grades/${subjectId}?group_id=${groupId}`);
                        const data = await response.json();
                        
                        // Habilitar los campos de entrada
                        const gradeInputs = document.querySelectorAll('.grade-input');
                        const observationInputs = document.querySelectorAll('.observation-input');
                        
                        gradeInputs.forEach(input => input.disabled = false);
                        observationInputs.forEach(input => input.disabled = false);
                        
                        // Rellenar los campos con los datos existentes
                        const studentRows = document.querySelectorAll('.student-row');
                        
                        studentRows.forEach(row => {
                            const studentId = row.dataset.studentId;
                            const gradeInput = row.querySelector('.grade-input');
                            const observationInput = row.querySelector('.observation-input');
                            
                            // Buscar la calificación para este estudiante
                            const studentGrade = data.grades.find(grade => grade.student_id == studentId);
                            
                            if (studentGrade) {
                                gradeInput.value = studentGrade.nota_final;
                                observationInput.value = studentGrade.observacion || '';
                            } else {
                                gradeInput.value = '';
                                observationInput.value = '';
                            }
                        });
                        
                    } catch (error) {
                        console.error('Error al cargar las calificaciones:', error);
                    }
                } else {
                    // Ocultar tabla de calificaciones y mostrar advertencia
                    subjectWarning.style.display = 'flex';
                    gradesTable.style.display = 'none';
                    subjectBadge.classList.add('hidden');
                    
                    // Deshabilitar los campos de entrada
                    const gradeInputs = document.querySelectorAll('.grade-input');
                    const observationInputs = document.querySelectorAll('.observation-input');
                    
                    gradeInputs.forEach(input => {
                        input.disabled = true;
                        input.value = '';
                    });
                    
                    observationInputs.forEach(input => {
                        input.disabled = true;
                        input.value = '';
                    });
                }
            });
        }

        // Guardar todas las notas
        const saveButton = document.getElementById('save-all-grades');
        if (saveButton) {
            saveButton.addEventListener('click', async function() {
                const subjectId = document.getElementById('subject_select').value;
                const groupId = document.getElementById('group_select').value;
                
                if (!subjectId) {
                    alert('Por favor seleccione una materia antes de guardar calificaciones.');
                    return;
                }
                
                const gradeInputs = document.querySelectorAll('.grade-input');
                const observationInputs = document.querySelectorAll('.observation-input');
                
                const grades = [];
                
                for (let i = 0; i < gradeInputs.length; i++) {
                    // Obtenemos el studentId del atributo data del row
                    const row = gradeInputs[i].closest('.student-row');
                    const studentId = row.dataset.studentId;
                    const value = gradeInputs[i].value;
                    const observation = observationInputs[i].value;
                    
                    // Solo agregamos si hay un valor numérico válido
                    if (value && !isNaN(parseFloat(value))) {
                        grades.push({
                            student_id: studentId,
                            nota_final: parseFloat(value),
                            observacion: observation
                        });
                    }
                }
                
                if (grades.length === 0) {
                    alert('No hay calificaciones para guardar.');
                    return;
                }
                
                try {
                    const response = await fetch('{{ route('teacher.grades.batch-update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            subject_id: subjectId,
                            group_id: groupId,
                            grades: grades
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        alert('Notas guardadas exitosamente');
                    } else {
                        throw new Error(data.error || 'Error al guardar las notas');
                    }
                } catch (error) {
                    alert(error.message);
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
