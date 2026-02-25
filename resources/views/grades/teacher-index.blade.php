<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="subject_selector" class="block text-sm font-medium text-gray-700 mb-2">Materia</label>
                                <select id="subject_selector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccionar Materia</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="group_selector" class="block text-sm font-medium text-gray-700 mb-2">Grupo</label>
                                <select id="group_selector" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccionar Grupo</option>
                                    @foreach($teacher->groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estudiante
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Calificación
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="students_table_body" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Selecciona una materia y un grupo para ver los estudiantes
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectSelector = document.getElementById('subject_selector');
            const groupSelector = document.getElementById('group_selector');
            const studentsTableBody = document.getElementById('students_table_body');

            function loadStudentGrades() {
                const subjectId = subjectSelector.value;
                const groupId = groupSelector.value;

                if (!subjectId || !groupId) {
                    studentsTableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Selecciona una materia y un grupo para ver los estudiantes
                            </td>
                        </tr>
                    `;
                    return;
                }

                fetch(`/api/groups/${groupId}/students?subject_id=${subjectId}`)
                    .then(response => response.json())
                    .then(data => {
                        studentsTableBody.innerHTML = data.students.map(student => `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        ${student.nombre} ${student.apellido}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" 
                                           class="grade-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           value="${student.grade ? student.grade : ''}"
                                           data-student-id="${student.id}"
                                           data-grade-id="${student.grade_id || ''}"
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <textarea 
                                        class="description-input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                        rows="1"
                                        data-student-id="${student.id}"
                                    >${student.description || ''}</textarea>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        class="save-grade bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm"
                                        data-student-id="${student.id}"
                                    >
                                        Guardar
                                    </button>
                                </td>
                            </tr>
                        `).join('');

                        // Agregar event listeners para los botones de guardar
                        document.querySelectorAll('.save-grade').forEach(button => {
                            button.addEventListener('click', function() {
                                const studentId = this.dataset.studentId;
                                const gradeInput = document.querySelector(`.grade-input[data-student-id="${studentId}"]`);
                                const descriptionInput = document.querySelector(`.description-input[data-student-id="${studentId}"]`);
                                const gradeId = gradeInput.dataset.gradeId;

                                const data = {
                                    student_id: studentId,
                                    subject_id: subjectSelector.value,
                                    grade: gradeInput.value,
                                    description: descriptionInput.value
                                };

                                const method = gradeId ? 'PUT' : 'POST';
                                const url = gradeId ? `/grades/${gradeId}` : '/grades';

                                fetch(url, {
                                    method: method,
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Mostrar mensaje de éxito
                                        alert('Calificación guardada exitosamente');
                                        // Recargar los datos
                                        loadStudentGrades();
                                    } else {
                                        alert('Error al guardar la calificación');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Error al guardar la calificación');
                                });
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        studentsTableBody.innerHTML = `
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-red-500">
                                    Error al cargar los estudiantes
                                </td>
                            </tr>
                        `;
                    });
            }

            subjectSelector.addEventListener('change', loadStudentGrades);
            groupSelector.addEventListener('change', loadStudentGrades);
        });
    </script>
    @endpush
</x-app-layout>
