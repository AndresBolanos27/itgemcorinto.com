@php
    use Illuminate\Support\Facades\Auth;
    if (Auth::user() && Auth::user()->role === 'student') {
        header('Location: ' . route('student.dashboard'));
        exit;
    }
@endphp

<x-app-layout>
    
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Docentes') }}
            </h2>
            <a href="{{ route('teachers.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('NUEVO DOCENTE') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filtro de búsqueda automático -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1 min-w-0 mb-4 sm:mb-0">
                        <h2 class="text-lg font-medium text-gray-900">Listado de Docentes</h2>
                        <p class="mt-1 text-sm text-gray-500">Administre los docentes del sistema</p>
                    </div>
                    <div class="w-full sm:max-w-xs">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search-input" name="search" value="{{ request('search') }}" class="bg-white block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Buscar docente...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtro por estado -->
            <div class="mt-2 mb-6">
                <div class="inline-flex overflow-hidden bg-white border divide-x rounded-lg rtl:flex-row-reverse">
                    <a href="{{ route('teachers.index') }}" class="px-5 py-2 text-xs font-medium {{ !request()->has('estado') ? 'text-gray-600 bg-gray-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        Ver todos
                    </a>
                    <a href="{{ route('teachers.index', ['estado' => 'activo']) }}" class="px-5 py-2 text-xs font-medium {{ request('estado') === 'activo' ? 'text-gray-600 bg-gray-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        Activos
                    </a>
                    <a href="{{ route('teachers.index', ['estado' => 'inactivo']) }}" class="px-5 py-2 text-xs font-medium {{ request('estado') === 'inactivo' ? 'text-gray-600 bg-gray-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        Inactivos
                    </a>
                </div>
            </div>
        
            <!-- Script para búsqueda automática -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('search-input');
                    let typingTimer;
                    const doneTypingInterval = 500; // tiempo en ms (medio segundo)
                    
                    searchInput.addEventListener('input', function() {
                        clearTimeout(typingTimer);
                        
                        typingTimer = setTimeout(function() {
                            const currentUrl = new URL(window.location.href);
                            
                            // Si el campo está vacío, elimina el parámetro search
                            if (searchInput.value.trim() === '') {
                                currentUrl.searchParams.delete('search');
                            } else {
                                currentUrl.searchParams.set('search', searchInput.value);
                            }
                            
                            // Ir a la primera página al buscar
                            currentUrl.searchParams.delete('page');
                            
                            window.location.href = currentUrl.toString();
                        }, doneTypingInterval);
                    });
                });
            </script>
            
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden border border-gray-200 md:rounded-lg shadow">

                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            <button class="flex items-center gap-x-3 focus:outline-none">
                                                <span>Docente</span>

                                                <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                    <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                    <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                                </svg>
                                            </button>
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            Correo
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            Documento
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            Contacto
                                        </th>

                                        <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            Título
                                        </th>

                                        <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500">
                                            Estado
                                        </th>

                                        <th scope="col" class="relative py-3.5 px-4">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($teachers as $teacher)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-gray-800">{{ $teacher->nombre }} {{ $teacher->apellido }}</h2>
                                                <p class="text-sm font-normal text-gray-600">ID: {{ $teacher->id }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-gray-700">{{ $teacher->correo }}</h4>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-gray-700">{{ $teacher->tipo_documento ?? 'CC' }}: {{ $teacher->cedula }}</h4>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-gray-700">{{ $teacher->celular }}</h4>
                                                <p class="text-xs text-gray-500">{{ ucfirst($teacher->sexo ?? 'No especificado') }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-gray-700">{{ $teacher->titulo }}</h4>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div @class([
                                                'inline px-3 py-1 text-sm font-normal rounded-full gap-x-2',
                                                'text-emerald-500 bg-emerald-100/60' => $teacher->estado === 'activo',
                                                'text-gray-500 bg-gray-100 gap-x-2' => $teacher->estado !== 'activo',
                                            ])>
                                                {{ ucfirst($teacher->estado ?? 'Activo') }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center justify-end space-x-4">
                                                <a href="{{ route('academic-loads.assign-for-teacher', $teacher) }}" class="text-gray-500 transition-colors duration-200 hover:text-blue-500 focus:outline-none" title="Asignar cargas">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('teachers.edit', $teacher) }}" class="text-gray-500 transition-colors duration-200 hover:text-yellow-500 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </a>

                                                <form class="inline-block" action="{{ route('teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este docente?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-500 transition-colors duration-200 hover:text-red-500 focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación personalizada -->
            @if(method_exists($teachers, 'links'))
            <div class="flex items-center justify-between mt-6">
                <!-- Botón Anterior -->
                @if ($teachers->onFirstPage())
                    <span class="flex items-center px-5 py-2 text-sm text-gray-400 bg-white border rounded-md gap-x-2 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>
                        <span>Anterior</span>
                    </span>
                @else
                    <a href="{{ $teachers->previousPageUrl() }}" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>
                        <span>Anterior</span>
                    </a>
                @endif

                <!-- Números de página -->
                <div class="items-center hidden md:flex gap-x-3">
                    {{-- Mostrar enlaces de página --}}
                    @php
                        $start = max(1, $teachers->currentPage() - 2);
                        $end = min($teachers->lastPage(), $teachers->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $teachers->url(1) }}" class="px-2 py-1 text-sm text-gray-500 rounded-md hover:bg-gray-100">1</a>
                        @if($start > 2)
                            <span class="px-2 py-1 text-sm text-gray-500">...</span>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        <a href="{{ $teachers->url($i) }}" class="px-2 py-1 text-sm rounded-md {{ $i == $teachers->currentPage() ? 'text-blue-500 bg-blue-100/60' : 'text-gray-500 hover:bg-gray-100' }}">{{ $i }}</a>
                    @endfor

                    @if($end < $teachers->lastPage())
                        @if($end < $teachers->lastPage() - 1)
                            <span class="px-2 py-1 text-sm text-gray-500">...</span>
                        @endif
                        <a href="{{ $teachers->url($teachers->lastPage()) }}" class="px-2 py-1 text-sm text-gray-500 rounded-md hover:bg-gray-100">{{ $teachers->lastPage() }}</a>
                    @endif
                </div>

                <!-- Botón Siguiente -->
                @if ($teachers->hasMorePages())
                    <a href="{{ $teachers->nextPageUrl() }}" class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100">
                        <span>Siguiente</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                @else
                    <span class="flex items-center px-5 py-2 text-sm text-gray-400 bg-white border rounded-md gap-x-2 cursor-not-allowed">
                        <span>Siguiente</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </span>
                @endif
            </div>
            @endif
        </section>
</x-app-layout>
