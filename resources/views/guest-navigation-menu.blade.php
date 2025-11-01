<nav class="bg-transparent border-b border-transparent">
    <style>
        .btn-comienza {
            padding: 0.625rem 1.5rem;
            background: #7534c9;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            letter-spacing: -0.01em;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(117, 52, 201, 0.25);
        }
        .btn-comienza:hover { 
            background: #6428b0; 
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(117, 52, 201, 0.35);
        }
        .btn-comienza:active { 
            transform: translateY(0); 
            box-shadow: 0 3px 10px rgba(117, 52, 201, 0.25);
        }
        .btn-comienza:focus { outline: none; }
        .btn-comienza:focus-visible { 
            box-shadow: 0 0 0 3px rgba(117, 52, 201, 0.45);
        }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="/" class="flex items-center">
                <x-application-mark class="block h-8 w-auto" />
            </a>

            <ul class="flex items-center gap-6 text-sm/6 text-white/80">
                <li><a href="{{ route('services') }}" class="hover:text-white transition-colors">Servicio</a></li>
                <li><a href="{{ route('plans') }}" class="hover:text-white transition-colors">Planes</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contacto</a></li>
            </ul>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg text-sm text-white/80 hover:text-white transition-colors">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn-comienza">Comienza hoy</a>
            </div>
        </div>
    </div>
</nav>
