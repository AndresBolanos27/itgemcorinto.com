<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Tarjeta de Docentes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Docentes</h3>
                        <p class="text-3xl font-bold">{{ $teachersCount }}</p>
                        <a href="{{ route('admin.teachers.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">Ver todos →</a>
                    </div>
                </div>

                <!-- Tarjeta de Estudiantes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Estudiantes</h3>
                        <p class="text-3xl font-bold">{{ $studentsCount }}</p>
                        <a href="{{ route('admin.students.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">Ver todos →</a>
                    </div>
                </div>

                <!-- Tarjeta de Grupos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Grupos</h3>
                        <p class="text-3xl font-bold">{{ $groupsCount }}</p>
                        <a href="{{ route('admin.groups.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">Ver todos →</a>
                    </div>
                </div>

                <!-- Tarjeta de Materias -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">Materias</h3>
                        <p class="text-3xl font-bold">{{ $subjectsCount }}</p>
                        <a href="{{ route('admin.subjects.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">Ver todas →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
