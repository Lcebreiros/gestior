<style>
    /* Profile form styles - Refined and minimalist */
    .profile-form-container {
        padding: 2rem;
    }

    .photo-container {
        position: relative;
        display: inline-block;
    }

    .photo-ring {
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .photo-ring:hover {
        border-color: rgba(255, 255, 255, 0.2);
        transform: scale(1.02);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    .verification-alert {
        border-radius: 12px;
        border: 1px solid;
        padding: 1rem;
        animation: fade-in 0.3s ease-out;
    }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-warning {
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.08) 0%, rgba(245, 158, 11, 0.05) 100%);
        border-color: rgba(251, 191, 36, 0.2);
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.05) 100%);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(37, 99, 235, 0.05) 100%);
        border-color: rgba(59, 130, 246, 0.2);
    }

    .success-message {
        animation: slide-in-right 0.4s ease-out;
    }

    @keyframes slide-in-right {
        from { opacity: 0; transform: translateX(10px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>

<div class="profile-form-container">
    <x-form-section submit="updateProfileInformation">
        <x-slot name="title">
            <span class="text-xl font-medium text-white">Información de perfil</span>
        </x-slot>

        <x-slot name="description">
            <span class="text-sm text-gray-400 font-light leading-relaxed">
                Actualiza la información de tu cuenta y dirección de correo electrónico
            </span>
        </x-slot>

        <x-slot name="form">
            <!-- Profile Photo -->
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                    <input type="file" id="photo" class="hidden"
                                wire:model.live="photo"
                                x-ref="photo"
                                x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                " />

                    <x-custom-label for="photo" value="Foto de perfil" class="mb-3" />

                    <!-- Current Profile Photo -->
                    <div class="mt-3 photo-container" x-show="! photoPreview">
                        <img src="{{ auth()->user()->profile_photo_url }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="photo-ring h-24 w-24 object-cover">
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="mt-3 photo-container" x-show="photoPreview" style="display: none;">
                        <span class="block photo-ring w-24 h-24 bg-cover bg-no-repeat bg-center"
                              x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-custom-button 
                            type="button"
                            variant="secondary" 
                            size="sm"
                            x-on:click.prevent="$refs.photo.click()"
                            :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg>'">
                            Seleccionar foto
                        </x-custom-button>

                        @if (auth()->user()->profile_photo_path)
                            <x-custom-button 
                                type="button"
                                variant="danger" 
                                size="sm"
                                wire:click="deleteProfilePhoto"
                                :icon="'<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\' stroke-width=\'2\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16\'/></svg>'">
                                Eliminar
                            </x-custom-button>
                        @endif
                    </div>

                    @error('photo')
                        <p class="mt-2 text-sm text-red-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endif

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-custom-label for="name" :required="true">
                    Nombre completo
                </x-custom-label>
                
                <x-custom-input 
                    id="name" 
                    type="text" 
                    wire:model="state.name"
                    placeholder="Tu nombre completo"
                    required
                    autocomplete="name"
                    :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'/></svg>'"
                    :error="$errors->first('name')"
                />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <x-custom-label for="email" :required="true">
                    Correo electrónico
                </x-custom-label>
                
                <x-custom-input 
                    id="email" 
                    type="email" 
                    wire:model="state.email"
                    placeholder="tu@correo.com"
                    required
                    autocomplete="username"
                    :icon="'<svg class=\'w-5 h-5 text-gray-400\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75\'/></svg>'"
                    :error="$errors->first('email')"
                />

                {{-- Estado de verificación --}}
                @if (!auth()->user()->hasVerifiedEmail())
                    <div class="mt-3 verification-alert alert-warning">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-amber-200">Correo no verificado</p>
                                <p class="text-xs text-amber-300/80 mt-1 font-light">
                                    Verifica tu correo para acceder a todas las funciones
                                </p>
                                <button type="button"
                                        class="inline-flex items-center gap-1.5 mt-2 text-xs text-amber-200 hover:text-amber-100 font-medium transition-colors"
                                        wire:click.prevent="sendEmailVerification">
                                    Reenviar correo de verificación
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if ($verificationLinkSent)
                        <div class="mt-3 verification-alert alert-success">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-medium text-emerald-200">
                                    Correo de verificación enviado
                                </p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="mt-3 verification-alert alert-success">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-emerald-200">Correo verificado</p>
                                <p class="text-xs text-emerald-300/80 mt-0.5 font-light">
                                    Tu dirección de correo está verificada
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Email modificado --}}
                @if (auth()->user()->isDirty('email') && auth()->user()->hasVerifiedEmail())
                    <div class="mt-3 verification-alert alert-info">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-200">Verificación requerida</p>
                                <p class="text-xs text-blue-300/80 mt-1 font-light">
                                    Necesitarás verificar tu nueva dirección de correo
                                </p>
                                <button type="button" 
                                        class="inline-flex items-center gap-1.5 mt-2 text-xs text-blue-200 hover:text-blue-100 font-medium transition-colors" 
                                        wire:click.prevent="sendEmailVerification">
                                    Enviar verificación
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if (session('verificationLinkSent'))
                        <div class="mt-3 verification-alert alert-success">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-medium text-emerald-200">
                                    Email de verificación enviado
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                <div class="success-message flex items-center gap-2 text-emerald-400">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium text-sm">Guardado</span>
                </div>
            </x-action-message>

            <x-custom-button 
                type="submit"
                variant="primary"
                :loading="false"
                wire:loading.attr="disabled"
                wire:target="photo">
                <span wire:loading.remove wire:target="photo">Guardar cambios</span>
                <span wire:loading wire:target="photo">Guardando...</span>
            </x-custom-button>
        </x-slot>
    </x-form-section>
</div>