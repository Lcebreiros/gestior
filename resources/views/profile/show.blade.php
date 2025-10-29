<x-app-layout>
    <style>
        /* User Show Page - Custom Elegant Design */
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in-up 0.5s ease-out forwards;
            opacity: 0;
        }

        .profile-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .profile-avatar:hover {
            border-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.03);
        }

        .info-section {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 0%, rgba(255, 255, 255, 0.01) 100%);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .info-section:hover {
            border-color: rgba(255, 255, 255, 0.1);
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.02) 100%);
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 0.875rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .info-item:hover .info-icon {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: -0.01em;
        }

        .badge-active {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: rgb(16, 185, 129);
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: rgb(239, 68, 68);
        }

        .badge-verified {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: rgb(59, 130, 246);
        }

        .badge-subscription {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid;
            letter-spacing: -0.01em;
        }

        .action-btn-primary {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.08) 100%);
            border-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .action-btn-primary:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0.12) 100%);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .action-btn-secondary {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.8);
        }

        .action-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>

    @php
        $user = auth()->user();
        
        $hierarchyMap = [
            \App\Models\User::HIERARCHY_MASTER => ['name' => 'Master', 'color' => 'text-purple-400'],
            \App\Models\User::HIERARCHY_COMPANY => ['name' => 'Empresa', 'color' => 'text-blue-400'],
            \App\Models\User::HIERARCHY_ADMIN => ['name' => 'Administrador', 'color' => 'text-green-400'],
            \App\Models\User::HIERARCHY_USER => ['name' => 'Usuario', 'color' => 'text-gray-400'],
        ];
        
        $hierarchy = $hierarchyMap[$user->hierarchy_level] ?? ['name' => 'Usuario', 'color' => 'text-gray-400'];
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-3xl text-white leading-tight tracking-tight">
                    Mi Perfil
                </h2>
                <p class="mt-2 text-sm text-gray-400 font-light">
                    Información de tu cuenta y configuración
                </p>
            </div>
            <a href="{{ route('profile.show') }}" class="action-btn action-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar perfil
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Card with Avatar --}}
            <div class="profile-header p-8 mb-8 animate-fade-in">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <img src="{{ $user->profile_photo_url }}" 
                         alt="{{ $user->name }}" 
                         class="profile-avatar object-cover">
                    
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-semibold text-white mb-2">{{ $user->name }}</h3>
                        <p class="text-gray-400 text-sm mb-4">{{ $user->email }}</p>
                        
                        <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                            {{-- Estado de cuenta --}}
                            <span class="status-badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                                @if($user->is_active)
                                    <div class="pulse-dot bg-emerald-500"></div>
                                    Cuenta activa
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.293 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Cuenta inactiva
                                @endif
                            </span>

                            {{-- Verificación de email --}}
                            @if($user->hasVerifiedEmail())
                                <span class="status-badge badge-verified">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Email verificado
                                </span>
                            @endif

                            {{-- Suscripción --}}
                            <span class="status-badge badge-subscription capitalize">
                                {{ $user->subscription_level ?? 'basic' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Información de la cuenta --}}
                <div class="info-section animate-fade-in" style="animation-delay: 0.1s;">
                    <h4 class="text-lg font-medium text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        Información de la cuenta
                    </h4>

                    <div class="space-y-1">
                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Nombre completo</p>
                                <p class="text-sm text-white font-medium mt-1">{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Correo electrónico</p>
                                <p class="text-sm text-white font-medium mt-1">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Tipo de cuenta</p>
                                <p class="text-sm font-medium mt-1 {{ $hierarchy['color'] }}">{{ $hierarchy['name'] }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Miembro desde</p>
                                <p class="text-sm text-white font-medium mt-1">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Configuración y seguridad --}}
                <div class="info-section animate-fade-in" style="animation-delay: 0.2s;">
                    <h4 class="text-lg font-medium text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                        Seguridad
                    </h4>

                    <div class="space-y-1">
                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Contraseña</p>
                                    <p class="text-sm text-white font-medium mt-1">••••••••</p>
                                </div>
                                <a href="{{ route('profile.show') }}#update-password" class="text-xs text-gray-400 hover:text-white transition-colors">
                                    Cambiar
                                </a>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Autenticación de dos factores</p>
                                    <p class="text-sm text-white font-medium mt-1">
                                        @if($user->two_factor_secret)
                                            <span class="text-emerald-400">Activada</span>
                                        @else
                                            <span class="text-gray-400">Desactivada</span>
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('profile.show') }}#two-factor" class="text-xs text-gray-400 hover:text-white transition-colors">
                                    Configurar
                                </a>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z"/>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Sesiones activas</p>
                                    <p class="text-sm text-white font-medium mt-1">Gestionar dispositivos</p>
                                </div>
                                <a href="{{ route('profile.show') }}#sessions" class="text-xs text-gray-400 hover:text-white transition-colors">
                                    Ver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Límites y suscripción --}}
                @if($user->isCompany() || $user->isAdmin())
                <div class="info-section animate-fade-in" style="animation-delay: 0.3s;">
                    <h4 class="text-lg font-medium text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                        </svg>
                        Límites y recursos
                    </h4>

                    <div class="space-y-1">
                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Límite de usuarios</p>
                                <p class="text-sm text-white font-medium mt-1">
                                    {{ $user->user_limit ?? 'Ilimitado' }}
                                </p>
                            </div>
                        </div>

                        @if($user->isCompany())
                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Límite de sucursales</p>
                                <p class="text-sm text-white font-medium mt-1">
                                    {{ $user->branch_limit ?? 'Ilimitado' }}
                                </p>
                            </div>
                        </div>
                        @endif

                        <div class="info-item">
                            <div class="info-icon">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Plan de suscripción</p>
                                    <p class="text-sm text-white font-medium mt-1 capitalize">
                                        {{ $user->subscription_level ?? 'basic' }}
                                    </p>
                                </div>
                                <a href="{{ route('pricing') }}" class="text-xs text-gray-400 hover:text-white transition-colors">
                                    Cambiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Acciones rápidas --}}
                <div class="info-section animate-fade-in" style="animation-delay: 0.4s;">
                    <h4 class="text-lg font-medium text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                        Acciones rápidas
                    </h4>

                    <div class="space-y-3">
                        <a href="/user/profile" class="action-btn action-btn-secondary w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar información personal
                        </a>

                        <a href="/user/profile#update-password" class="action-btn action-btn-secondary w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                            </svg>
                            Cambiar contraseña
                        </a>

                        @if(!$user->hasVerifiedEmail())
                        <a href="{{ route('verification.notice') }}" class="action-btn action-btn-primary w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                            </svg>
                            Verificar correo electrónico
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>