<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-white leading-tight">
                    Bienvenido a Gestior
                </h2>
                <p class="mt-1 text-sm text-gray-400">Es hora de dar los primeros pasos</p>
            </div>
            <a href="https://panel.gestior.com.ar" class="px-4 py-2 rounded-lg btn-primary text-sm font-semibold">Ir al panel</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Paso 1 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 info-card">
                    <div class="flex items-start gap-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-violet-500/20 text-violet-300">1</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white">Crea tu primera organización</h3>
                            <p class="mt-2 text-sm text-gray-300">Configura el nombre y los datos básicos para empezar a trabajar.</p>
                        </div>
                    </div>
                </div>

                <!-- Paso 2 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 info-card">
                    <div class="flex items-start gap-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-violet-500/20 text-violet-300">2</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white">Agrega sucursales y usuarios</h3>
                            <p class="mt-2 text-sm text-gray-300">Invita a tu equipo y define permisos según el rol.</p>
                        </div>
                    </div>
                </div>

                <!-- Paso 3 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 info-card">
                    <div class="flex items-start gap-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-violet-500/20 text-violet-300">3</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white">Configura tus productos/servicios</h3>
                            <p class="mt-2 text-sm text-gray-300">Carga tus catálogos, listas de precios y stock inicial.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white/5 border border-white/10 rounded-2xl p-8 text-center">
                <h3 class="text-xl font-semibold text-white">Todo listo para comenzar</h3>
                <p class="mt-2 text-gray-300">Accede al panel completo para continuar con la configuración.</p>
                <div class="mt-6">
                    <a href="https://panel.gestior.com.ar" class="px-5 py-3 rounded-xl btn-primary font-semibold inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        Ir al panel
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
