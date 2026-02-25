<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Docente') }}
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

                    <form method="POST" action="{{ route('teachers.store') }}" class="w-full">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-4">
                            <x-input-label for="nombre" :value="__('Nombre')" />
                            <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre')" required autofocus />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <!-- Apellido -->
                        <div class="mb-4">
                            <x-input-label for="apellido" :value="__('Apellido')" />
                            <x-text-input id="apellido" class="block mt-1 w-full" type="text" name="apellido" :value="old('apellido')" required />
                            <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
                        </div>

                        <!-- Tipo de Documento -->
                        <div class="mb-4">
                            <x-input-label for="tipo_documento" :value="__('Tipo de Documento')" />
                            <select id="tipo_documento" name="tipo_documento" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                <option value="PAS" {{ old('tipo_documento') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipo_documento')" class="mt-2" />
                        </div>

                        <!-- Número de Documento -->
                        <div class="mb-4">
                            <x-input-label for="cedula" :value="__('Número de Documento')" />
                            <x-text-input id="cedula" class="block mt-1 w-full" type="text" name="cedula" :value="old('cedula')" required />
                            <x-input-error :messages="$errors->get('cedula')" class="mt-2" />
                        </div>

                        <!-- Correo -->
                        <div class="mb-4">
                            <x-input-label for="correo" :value="__('Correo Electrónico')" />
                            <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" required />
                            <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                        </div>

                        <!-- Celular -->
                        <div class="mb-4">
                            <x-input-label for="celular" :value="__('Teléfono')" />
                            <x-text-input id="celular" class="block mt-1 w-full" type="text" name="celular" :value="old('celular')" required />
                            <x-input-error :messages="$errors->get('celular')" class="mt-2" />
                        </div>

                        <!-- Título -->
                        <div class="mb-4">
                            <x-input-label for="titulo" :value="__('Título')" />
                            <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo')" required />
                            <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="mb-4">
                            <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
                            <x-text-input id="fecha_nacimiento" class="block mt-1 w-full" type="date" name="fecha_nacimiento" :value="old('fecha_nacimiento')" required />
                            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                        </div>

                        <!-- Dirección -->
                        <div class="mb-4">
                            <x-input-label for="direccion" :value="__('Dirección')" />
                            <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" required />
                            <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                        </div>

                        <!-- Sexo -->
                        <div class="mb-4">
                            <x-input-label for="sexo" :value="__('Sexo')" />
                            <select id="sexo" name="sexo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccione una opción</option>
                                <option value="masculino" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="femenino" {{ old('sexo') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="otro" {{ old('sexo') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
                        </div>

                        <!-- EPS -->
                        <div class="mb-4">
                            <x-input-label for="eps" :value="__('EPS')" />
                            <select id="eps" name="eps" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccione una EPS</option>
                                <option value="SURA" {{ old('eps') == 'SURA' ? 'selected' : '' }}>SURA</option>
                                <option value="NUEVA EPS" {{ old('eps') == 'NUEVA EPS' ? 'selected' : '' }}>Nueva EPS</option>
                                <option value="SANITAS" {{ old('eps') == 'SANITAS' ? 'selected' : '' }}>Sanitas</option>
                                <option value="SALUD TOTAL" {{ old('eps') == 'SALUD TOTAL' ? 'selected' : '' }}>Salud Total</option>
                                <option value="COMPENSAR" {{ old('eps') == 'COMPENSAR' ? 'selected' : '' }}>Compensar</option>
                                <option value="FAMISANAR" {{ old('eps') == 'FAMISANAR' ? 'selected' : '' }}>Famisanar</option>
                                <option value="ASIGNAR EPS" {{ old('eps') == 'ASIGNAR EPS' ? 'selected' : '' }}>Asignar EPS</option>
                            </select>
                            <x-input-error :messages="$errors->get('eps')" class="mt-2" />
                        </div>

                        <!-- Pensión -->
                        <div class="mb-4">
                            <x-input-label for="pension" :value="__('Fondo de Pensión')" />
                            <select id="pension" name="pension" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccione un fondo de pensión</option>
                                <option value="Colpensiones" {{ old('pension') == 'Colpensiones' ? 'selected' : '' }}>Colpensiones</option>
                                <option value="Porvenir" {{ old('pension') == 'Porvenir' ? 'selected' : '' }}>Porvenir</option>
                                <option value="Proteccion" {{ old('pension') == 'Proteccion' ? 'selected' : '' }}>Protección</option>
                                <option value="Colfondos" {{ old('pension') == 'Colfondos' ? 'selected' : '' }}>Colfondos</option>
                                <option value="ASIGNAR PENSION" {{ old('pension') == 'ASIGNAR PENSION' ? 'selected' : '' }}>Asignar Pension</option>
                            </select>
                            <x-input-error :messages="$errors->get('pension')" class="mt-2" />
                        </div>

                        <!-- Caja de Compensación -->
                        <div class="mb-4">
                            <x-input-label for="caja_compensacion" :value="__('Caja de Compensación')" />
                            <select id="caja_compensacion" name="caja_compensacion" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccione una caja de compensación</option>
                                <option value="Comfama" {{ old('caja_compensacion') == 'Comfama' ? 'selected' : '' }}>Comfama</option>
                                <option value="Compensar" {{ old('caja_compensacion') == 'Compensar' ? 'selected' : '' }}>Compensar</option>
                                <option value="Cafam" {{ old('caja_compensacion') == 'Cafam' ? 'selected' : '' }}>Cafam</option>
                                <option value="Colsubsidio" {{ old('caja_compensacion') == 'Colsubsidio' ? 'selected' : '' }}>Colsubsidio</option>
                                <option value="ASIGNAR CAJA DE COMPENSACION" {{ old('caja_compensacion') == 'ASIGNAR CAJA DE COMPENSACION' ? 'selected' : '' }}>Asignar Caja de Compensacion</option>
                            </select>
                            <x-input-error :messages="$errors->get('caja_compensacion')" class="mt-2" />
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button type="button" class="mr-3" onclick="window.location='{{ route('teachers.index') }}'">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Crear') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
