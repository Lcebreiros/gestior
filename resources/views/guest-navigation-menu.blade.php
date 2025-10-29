<nav class="bg-transparent border-b border-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="/" class="flex items-center">
                <x-application-mark class="block h-8 w-auto" />
            </a>

            <ul class="flex items-center gap-6 text-sm/6 text-white/80">
                <li><a href="#producto" class="hover:text-white transition-colors">Servicio</a></li>
                <li><a href="#planes" class="hover:text-white transition-colors">Planes</a></li>
                <li><a href="#contacto" class="hover:text-white transition-colors">Contacto</a></li>
            </ul>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg text-sm text-white/80 hover:text-white transition-colors">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-lg btn-primary text-sm">Comienza hoy</a>
            </div>
        </div>
    </div>
</nav>
