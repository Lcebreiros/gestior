@auth
<nav x-data="{ open: false }" class="bg-transparent border-b border-white/[0.06]">
    <style>
        /* Minimalist Navigation - Professional & Clean */
        .nav-link-minimal {
            padding: 0.5rem 1rem;
            color: rgba(255, 255, 255, 0.65);
            font-size: 14px;
            font-weight: 400;
            letter-spacing: -0.01em;
            transition: color 0.2s ease;
        }

        .nav-link-minimal:hover {
            color: rgba(255, 255, 255, 0.95);
        }

        .nav-link-minimal.active {
            color: #ffffff;
            font-weight: 500;
        }

        .btn-panel {
            padding: 0.625rem 1.5rem;
            background: #7534c9;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            letter-spacing: -0.01em;
            transition: all 0.2s ease;
        }

        .btn-panel:hover {
            background: #6428b0;
            transform: translateY(-1px);
        }

        .profile-trigger-minimal {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .profile-trigger-minimal:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .profile-avatar-minimal {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            transition: border-color 0.2s ease;
        }

        .profile-trigger-minimal:hover .profile-avatar-minimal {
            border-color: rgba(255, 255, 255, 0.25);
        }

        .dropdown-minimal {
            margin-top: 0.5rem;
            min-width: 200px;
            border-radius: 12px;
            background: rgba(15, 15, 15, 0.97);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        .dropdown-item-minimal {
            display: block;
            padding: 0.75rem 1.25rem;
            color: rgba(255, 255, 255, 0.75);
            font-size: 14px;
            font-weight: 400;
            transition: all 0.15s ease;
            letter-spacing: -0.01em;
        }

        .dropdown-item-minimal:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .dropdown-divider-minimal {
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
            margin: 0.25rem 0;
        }

        .mobile-menu-minimal {
            background: rgba(10, 10, 10, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }
    </style>

    <!-- Primary Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo - Left -->
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}">
                    <x-application-mark class="block h-8 w-auto" />
                </a>
            </div>

            <!-- Centered Navigation Links -->
            <div class="hidden md:flex items-center gap-8 absolute left-1/2 transform -translate-x-1/2">
                <a href="{{ route('dashboard') }}"
                   class="nav-link-minimal {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('services') }}" class="nav-link-minimal {{ request()->routeIs('services') ? 'active' : '' }}">
                    Servicio
                </a>
                <a href="{{ route('plans') }}" class="nav-link-minimal {{ request()->routeIs('plans') ? 'active' : '' }}">
                    Planes
                </a>
                <a href="{{ route('contact') }}" class="nav-link-minimal">
                    Contacto
                </a>
            </div>

            <!-- Right Side - Panel Button + Profile -->
            <div class="hidden md:flex items-center gap-4">
                <a href="https://panel.gestior.com.ar" class="btn-panel">
                    Ir al panel
                </a>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            @click.away="open = false" 
                            class="profile-trigger-minimal">
                        <img class="profile-avatar-minimal object-cover" 
                             src="{{ Auth::user()->profile_photo_url }}" 
                             alt="{{ Auth::user()->name }}" />
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="dropdown-minimal absolute right-0 z-50"
                         style="display: none;">
                        
                        <a href="{{ route('profile.show') }}" class="dropdown-item-minimal">
                            Mi Perfil
                        </a>
                        
                        <a href="{{ route('profile.show') }}" class="dropdown-item-minimal">
                            Configuración
                        </a>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <a href="{{ route('api-tokens.index') }}" class="dropdown-item-minimal">
                                API Tokens
                            </a>
                        @endif

                        <div class="dropdown-divider-minimal"></div>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit" class="dropdown-item-minimal w-full text-left">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" 
                        class="p-2 rounded-lg text-white/70 hover:text-white hover:bg-white/5 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path :class="{'hidden': open, 'inline-flex': !open}" 
                              stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" 
                              stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden mobile-menu-minimal"
         style="display: none;">
        
        <!-- User Info -->
        <div class="px-4 pt-4 pb-3 border-b border-white/[0.06]">
            <div class="flex items-center gap-3">
                <img class="w-10 h-10 rounded-full object-cover border border-white/10" 
                     src="{{ Auth::user()->profile_photo_url }}" 
                     alt="{{ Auth::user()->name }}" />
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-white/50 truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="dropdown-item-minimal {{ request()->routeIs('dashboard') ? 'text-white font-medium' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('services') }}" class="dropdown-item-minimal {{ request()->routeIs('services') ? 'text-white font-medium' : '' }}">
                Servicio
            </a>
            <a href="{{ route('plans') }}" class="dropdown-item-minimal {{ request()->routeIs('plans') ? 'text-white font-medium' : '' }}">
                Planes
            </a>
            <a href="{{ route('contact') }}" class="dropdown-item-minimal {{ request()->routeIs('contact') ? 'text-white font-medium' : '' }}">
                Contacto
            </a>
        </div>

        <div class="dropdown-divider-minimal mx-4"></div>

        <!-- Account -->
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('profile.show') }}" class="dropdown-item-minimal">
                Mi Perfil
            </a>
            <a href="{{ route('profile.show') }}" class="dropdown-item-minimal">
                Configuración
            </a>
            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                <a href="{{ route('api-tokens.index') }}" class="dropdown-item-minimal">
                    API Tokens
                </a>
            @endif
        </div>

        <div class="dropdown-divider-minimal mx-4"></div>

        <!-- Panel Button & Logout -->
        <div class="px-4 py-3 space-y-2">
            <a href="https://panel.gestior.com.ar" class="btn-panel block text-center">
                Ir al panel
            </a>
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit" class="dropdown-item-minimal w-full text-left">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>
@endauth

@guest
<nav class="bg-transparent border-b border-white/[0.06]">
    <style>
        .nav-link-minimal {
            padding: 0.5rem 1rem;
            color: rgba(255, 255, 255, 0.65);
            font-size: 14px;
            font-weight: 400;
            letter-spacing: -0.01em;
            transition: color 0.2s ease;
        }
        .nav-link-minimal:hover { color: rgba(255, 255, 255, 0.95); }
        .nav-link-minimal.active { color: #ffffff; font-weight: 500; }

        .btn-panel {
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
        .btn-panel:hover { background: #6428b0; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(117, 52, 201, 0.35); }
        .btn-panel:active { transform: translateY(0); box-shadow: 0 3px 10px rgba(117, 52, 201, 0.25); }
        .btn-panel:focus { outline: none; }
        .btn-panel:focus-visible { box-shadow: 0 0 0 3px rgba(117, 52, 201, 0.45); }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo - Left -->
            <div class="flex-shrink-0">
                <a href="/">
                    <x-application-mark class="block h-8 w-auto" />
                </a>
            </div>

            <!-- Centered Navigation -->
            <div class="hidden md:flex items-center gap-8 absolute left-1/2 transform -translate-x-1/2">
                <a href="{{ route('services') }}" class="nav-link-minimal">Servicio</a>
                <a href="{{ route('plans') }}" class="nav-link-minimal">Planes</a>
                <a href="{{ route('contact') }}" class="nav-link-minimal">Contacto</a>
            </div>

            <!-- Right Side -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" 
                   class="px-4 py-2 text-sm text-white/70 hover:text-white transition-colors">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="btn-panel">
                    Comienza hoy
                </a>
            </div>

            <!-- Mobile -->
            <div class="md:hidden flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm text-white/70">Entrar</a>
                <a href="{{ route('register') }}" class="btn-panel text-xs px-3 py-1.5">Registrarse</a>
            </div>
        </div>
    </div>
</nav>
@endguest
