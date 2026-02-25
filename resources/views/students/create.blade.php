<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Estudiante') }}
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

                    <form method="POST" action="{{ route('students.store') }}" class="w-full">
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
                                <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                <option value="PAS" {{ old('tipo_documento') == 'PAS' ? 'selected' : '' }}>Pasaporte</option>
                            </select>
                            <x-input-error :messages="$errors->get('tipo_documento')" class="mt-2" />
                        </div>

                        <!-- Cédula -->
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
                            <x-text-input id="celular" class="block mt-1 w-full" type="text" name="celular" value="" required autocomplete="off" />
                            <x-input-error :messages="$errors->get('celular')" class="mt-2" />
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

                        <!-- Estado -->
                        <div class="mb-4">
                            <x-input-label for="estado" :value="__('Estado')" />
                            <select id="estado" name="estado" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                        </div>

                        <!-- Género -->
                        <div class="mb-4">
                            <x-input-label for="genero" :value="__('Género')" />
                            <select id="genero" name="genero" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Seleccione un género</option>
                                <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="otro" {{ old('genero') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            <x-input-error :messages="$errors->get('genero')" class="mt-2" />
                        </div>

                        <!-- Grupo Étnico -->
                        <div class="mb-4">
                            <x-input-label for="grupo_etnico" :value="__('Grupo Étnico')" />
                            <select id="grupo_etnico" name="grupo_etnico" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="ninguno" {{ old('grupo_etnico') == 'ninguno' ? 'selected' : '' }}>Ninguno</option>
                                <option value="afrodescendiente" {{ old('grupo_etnico') == 'afrodescendiente' ? 'selected' : '' }}>Afrodescendiente</option>
                                <option value="indigena" {{ old('grupo_etnico') == 'indigena' ? 'selected' : '' }}>Indígena</option>
                                <option value="raizal" {{ old('grupo_etnico') == 'raizal' ? 'selected' : '' }}>Raizal</option>
                                <option value="rom" {{ old('grupo_etnico') == 'rom' ? 'selected' : '' }}>Rom</option>
                                <option value="palenquero" {{ old('grupo_etnico') == 'palenquero' ? 'selected' : '' }}>Palenquero</option>
                            </select>
                            <x-input-error :messages="$errors->get('grupo_etnico')" class="mt-2" />
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
                                <option value="COOMEVA" {{ old('eps') == 'COOMEVA' ? 'selected' : '' }}>Coomeva</option>
                                <option value="COOSALUD" {{ old('eps') == 'COOSALUD' ? 'selected' : '' }}>Coosalud</option>
                                <option value="MUTUAL SER" {{ old('eps') == 'MUTUAL SER' ? 'selected' : '' }}>Mutual Ser</option>
                                <option value="Emmsanar" {{ old('eps') == 'Emmsanar' ? 'selected' : '' }}>Emmsanar</option>
                                
                                <option value="AIC" {{ old('eps') == 'AIC' ? 'selected' : '' }}>AIC</option>
                                
                                <option value="Capital Salud" {{ old('Capital Salud') == 'Capital Salud' ? 'selected' : '' }}>Capital Salud</option>

                                <option value="Dispensario de Cali" {{ old('Dispensario de Cali') == 'Dispensario de Cali' ? 'selected' : '' }}>Dispensario de Cali</option>
                                
                                 <option value="Asmet Salud" {{ old('Asmet Salud') == 'Dispensario de Cali' ? 'selected' : '' }}>Asmet Salud</option>
                                 
                                  <option value="Cosmitec" {{ old('Cosmitec') == 'Cosmitec' ? 'selected' : '' }}>Cosmitec</option>
                                  
                                     <option value="SOS" {{ old('SOS') == 'SOS' ? 'selected' : '' }}>SOS</option>

                                        
                            </select>
                            <x-input-error :messages="$errors->get('eps')" class="mt-2" />
                        </div>

                        <!-- Acudiente -->
                        <div class="mb-4">
                            <x-input-label for="acudiente" :value="__('Nombre del Acudiente')" />
                            <x-text-input id="acudiente" class="block mt-1 w-full" type="text" name="acudiente" :value="old('acudiente')" required />
                            <x-input-error :messages="$errors->get('acudiente')" class="mt-2" />
                        </div>

                        <!-- Teléfono Acudiente -->
                        <div class="mb-4">
                            <x-input-label for="telefono_acudiente" :value="__('Teléfono del Acudiente')" />
                            <x-text-input id="telefono_acudiente" class="block mt-1 w-full" type="text" name="telefono_acudiente" :value="old('telefono_acudiente')" required />
                            <x-input-error :messages="$errors->get('telefono_acudiente')" class="mt-2" />
                        </div>

                        <!-- Grupo -->
                        <div class="mb-4">
                            <x-input-label for="group_id" :value="__('Grupo')" />
                            <select id="group_id" name="group_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Seleccione un grupo</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('group_id')" class="mt-2" />
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" value="" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" value="" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button type="button" class="mr-3" onclick="window.location='{{ route('students.index') }}'">
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
