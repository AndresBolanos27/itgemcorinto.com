<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Notas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                        </div>

                        <!-- Badge de advertencia para seleccionar materia -->
                        <div id="subject-warning" class="mb-4 px-4 py-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">¡Atención!</span>
                            <span class="ml-2">Por favor seleccione una materia para habilitar los campos de notas</span>
                        </div>

                        <!-- Tabla de Notas -->
                        <div class="overflow-x-auto">
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
                                                <input type="text" 
                                                    class="observation-input w-full text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                                    placeholder="Ingrese observación"
                                                    disabled>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Botón para guardar todas las notas -->
                            <div class="mt-4 flex justify-end">
                                <x-primary-button id="save-all-grades" class="ml-3" disabled>
                                    {{ __('Guardar todas las notas') }}
                                </x-primary-button>
                            </div>
                        </div>
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
        
        // Verificar estado inicial del badge de advertencia
        if (subjectSelect && subjectWarning) {
            if (subjectSelect.value) {
                subjectWarning.style.display = 'none';
            } else {
                subjectWarning.style.display = 'flex';
            }
        }
        
        groupSelect.addEventListener('change', function() {
            if (this.value) {
                window.location.href = `{{ route('grades.index') }}?group_id=${this.value}`;
            } else {
                window.location.href = "{{ route('grades.index') }}";
            }
        });

        // Cuando cambia la selección de materia
        document.getElementById('subject_select').addEventListener('change', async function() {
            const rows = document.querySelectorAll('.student-row');
            const saveButton = document.getElementById('save-all-grades');
            
            if (this.value) {
                // Habilitar campos y botón de guardar
                rows.forEach(row => {
                    row.querySelector('.grade-input').disabled = false;
                    row.querySelector('.observation-input').disabled = false;
                });
                saveButton.disabled = false;
                document.getElementById('subject-warning').style.display = 'none';
                
                // Cargar notas existentes para esta materia
                rows.forEach(async row => {
                    const studentId = row.dataset.studentId;
                    
                    try {
                        const response = await fetch(`/grades/${studentId}?subject_id=${this.value}`);
                        const data = await response.json();
                        
                        if (response.ok && data.grade) {
                            const gradeInput = row.querySelector('.grade-input');
                            const observationInput = row.querySelector('.observation-input');
                            if (gradeInput) gradeInput.value = data.grade.nota_final;
                            if (observationInput) observationInput.value = data.grade.observacion || '';
                        }
                    } catch (error) {
                        console.error('Error al cargar las notas:', error);
                    }
                });
            } else {
                // Deshabilitar campos y botón de guardar
                rows.forEach(row => {
                    const gradeInput = row.querySelector('.grade-input');
                    const observationInput = row.querySelector('.observation-input');
                    
                    if (gradeInput) {
                        gradeInput.value = '';
                        gradeInput.disabled = true;
                    }
                    
                    if (observationInput) {
                        observationInput.value = '';
                        observationInput.disabled = true;
                    }
                });
                saveButton.disabled = true;
                document.getElementById('subject-warning').style.display = 'flex';
            }
        });

        // Guardar todas las notas
        document.getElementById('save-all-grades').addEventListener('click', async function() {
            const subjectId = document.getElementById('subject_select').value;
            
            if (!subjectId) {
                alert('Por favor seleccione una materia');
                return;
            }

            const grades = [];
            let hasGrades = false;
            
            document.querySelectorAll('.student-row').forEach(row => {
                const studentId = row.dataset.studentId;
                const notaFinalInput = row.querySelector('.grade-input');
                const observacionInput = row.querySelector('.observation-input');
                
                if (notaFinalInput && notaFinalInput.value && !isNaN(parseFloat(notaFinalInput.value))) {
                    hasGrades = true;
                    grades.push({
                        student_id: studentId,
                        nota_final: parseFloat(notaFinalInput.value),
                        observacion: observacionInput ? observacionInput.value : ''
                    });
                }
            });
            
            if (!hasGrades) {
                alert('No hay calificaciones para guardar');
                return;
            }

            try {
                // Mostrar indicador de carga
                this.disabled = true;
                this.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Guardando...
                `;
                
                const response = await fetch('/grades/batch-update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        subject_id: subjectId,
                        grades: grades
                    })
                });

                const data = await response.json();
                
                // Restablecer el botón
                this.disabled = false;
                this.innerHTML = 'Guardar todas las notas';
                
                if (response.ok) {
                    alert('Notas guardadas exitosamente');
                } else {
                    throw new Error(data.error || 'Error al guardar las notas');
                }
            } catch (error) {
                // Restablecer el botón en caso de error
                this.disabled = false;
                this.innerHTML = 'Guardar todas las notas';
                
                console.error('Error:', error);
                alert(error.message);
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
