<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Crear cuenta • Gestior</title>

  {{-- Fuentes --}}
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

  {{-- Vite --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    :root {
      --violet-700: #7e22ce;
      --violet-800: #6b21a8;
      --violet-900: #581c87;
      --violet-950: #2a0b47;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      background: #000000;
      color: white;
      min-height: 100vh;
    }

    [x-cloak] { display: none!important; }

    /* FONDO CON DEGRADADO */
    .abstract-bg {
      background: radial-gradient(ellipse at top, rgba(124, 58, 237, 0.15) 0%, #000000 50%);
      min-height: 100vh;
      width: 100%;
      position: fixed;
      top: 0;
      left: 0;
      z-index: -1;
    }

    /* CONTENEDOR PRINCIPAL */
    .main-container {
      position: relative;
      z-index: 10;
    }

    /* PROGRESS INDICATOR - Apple Style */
    .progress-step {
      transition: all 0.5s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    .progress-step.active {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
      border-color: #8b5cf6;
      box-shadow: 0 4px 16px rgba(124, 58, 237, 0.4);
      transform: scale(1.05);
    }

    .progress-line {
      transition: all 0.6s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    .progress-line.active {
      background: linear-gradient(90deg, #8b5cf6, #7c3aed);
    }

    /* INPUTS PREMIUM - Apple Style */
    .form-input {
      width: 100%;
      background: rgba(255, 255, 255, 0.04);
      border: 1.5px solid rgba(255, 255, 255, 0.1);
      border-radius: 1rem;
      padding: 1rem 1.125rem;
      color: white;
      font-size: 0.9375rem;
      font-weight: 400;
      letter-spacing: 0.01em;
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(10px);
    }

    .form-input::placeholder {
      color: #6b7280;
      font-weight: 400;
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    .form-input:hover:not(:focus) {
      border-color: rgba(255, 255, 255, 0.15);
      background: rgba(255, 255, 255, 0.06);
    }

    .form-input:focus {
      outline: none;
      border-color: rgba(139, 92, 246, 0.6);
      box-shadow:
        0 0 0 4px rgba(139, 92, 246, 0.12),
        0 8px 24px rgba(124, 58, 237, 0.2),
        0 0 20px rgba(139, 92, 246, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.05);
      background: rgba(255, 255, 255, 0.08);
      transform: translateY(-1px) scale(1.005);
    }

    .form-input:focus::placeholder {
      color: #9ca3af;
      transform: translateX(2px);
    }

    /* LABELS PREMIUM */
    .form-label {
      display: block;
      font-size: 0.8125rem;
      font-weight: 600;
      letter-spacing: 0.025em;
      color: #d1d5db;
      margin-bottom: 0.625rem;
      text-transform: uppercase;
      transition: all 0.3s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    /* INPUT GROUPS */
    .input-group {
      position: relative;
    }

    .input-group .input-icon {
      position: absolute;
      left: 1.125rem;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      pointer-events: none;
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
      z-index: 1;
      width: 1.25rem;
      height: 1.25rem;
    }

    .input-group .form-input:focus ~ .input-icon,
    .input-group:focus-within .input-icon {
      color: #a78bfa;
      transform: translateY(-50%) scale(1.08);
    }

    .input-group .form-input:hover:not(:focus) ~ .input-icon {
      color: #9ca3af;
    }

    /* BOTONES PREMIUM - Apple Style */
    .btn-primary {
      background: linear-gradient(135deg, #7c3aed, #6d28d9);
      color: white;
      border: none;
      border-radius: 0.875rem;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
      box-shadow: 0 4px 16px rgba(124, 58, 237, 0.35);
    }

    .btn-primary:hover:not(:disabled) {
      transform: translateY(-1px) scale(1.01);
      box-shadow: 0 8px 28px rgba(124, 58, 237, 0.45);
    }

    .btn-primary:active:not(:disabled) {
      transform: translateY(0) scale(0.99);
      transition: all 0.1s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    .btn-primary:disabled {
      background: rgba(255, 255, 255, 0.1);
      cursor: not-allowed;
      box-shadow: none;
      opacity: 0.5;
    }

    .btn-secondary {
      background: rgba(255, 255, 255, 0.08);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 0.875rem;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.15);
      border-color: rgba(255, 255, 255, 0.25);
      transform: translateY(-1px);
    }

    .btn-secondary:active {
      transform: translateY(0) scale(0.99);
      transition: all 0.1s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    /* BUSINESS CARDS CON GLASS EFFECT */
    .business-card {
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
      border: 2px solid rgba(255, 255, 255, 0.08);
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(16px);
      border-radius: 1.25rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .business-card:hover {
      border-color: rgba(167, 139, 250, 0.5);
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(124, 58, 237, 0.35);
    }

    .business-card.selected {
      border-color: #8b5cf6;
      background: linear-gradient(135deg, rgba(124, 58, 237, 0.18), rgba(139, 92, 246, 0.12));
      box-shadow: 0 12px 32px rgba(124, 58, 237, 0.45);
      transform: translateY(-2px);
    }

    /* PLAN CARDS */
    .plan-card {
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
      border: 2px solid rgba(255, 255, 255, 0.08);
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(16px);
      border-radius: 1.25rem;
      position: relative;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .plan-card:hover {
      border-color: rgba(167, 139, 250, 0.5);
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(124, 58, 237, 0.35);
    }

    .plan-card.selected {
      border-color: #8b5cf6;
      background: linear-gradient(135deg, rgba(124, 58, 237, 0.18), rgba(139, 92, 246, 0.12));
      box-shadow: 0 12px 32px rgba(124, 58, 237, 0.45);
      transform: translateY(-2px);
    }

    /* BADGES */
    .badge-recommended {
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
      box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
    }

    /* STEP TRANSITIONS - Apple Style */
    .step-content {
      animation: appleSlideIn 0.6s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    @keyframes appleSlideIn {
      from {
        opacity: 0;
        transform: translateX(20px) scale(0.98);
      }
      to {
        opacity: 1;
        transform: translateX(0) scale(1);
      }
    }

    /* ERRORES PREMIUM */
    .validation-errors {
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), rgba(220, 38, 38, 0.06));
      border: 1.5px solid rgba(239, 68, 68, 0.2);
      border-radius: 1rem;
      padding: 1.125rem 1.25rem;
      margin-bottom: 1.5rem;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 16px rgba(239, 68, 68, 0.12);
      animation: errorSlideIn 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    @keyframes errorSlideIn {
      from {
        opacity: 0;
        transform: translateY(-10px) scale(0.98);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .validation-errors ul {
      list-style: none;
      color: #fca5a5;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .validation-errors ul li {
      padding: 0.25rem 0;
    }

    /* INFO BOX PREMIUM */
    .info-box {
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(99, 102, 241, 0.06));
      border: 1.5px solid rgba(59, 130, 246, 0.15);
      border-radius: 1rem;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 16px rgba(59, 130, 246, 0.08);
      transition: all 0.3s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    .info-box:hover {
      border-color: rgba(59, 130, 246, 0.25);
      box-shadow: 0 6px 24px rgba(59, 130, 246, 0.12);
    }

    /* TÍTULOS CON GRADIENTE */
    .title-gradient {
      background: linear-gradient(135deg, #a78bfa, #8b5cf6);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* ICONOS DE LAS CARDS - Apple Style */
    .icon-wrapper {
      transition: all 0.4s cubic-bezier(0.16, 0.84, 0.44, 1);
    }

    .business-card:hover .icon-wrapper,
    .plan-card:hover .icon-wrapper {
      transform: scale(1.15) rotate(5deg);
    }

    .business-card.selected .icon-wrapper,
    .plan-card.selected .icon-wrapper {
      transform: scale(1.05);
    }
  </style>

  @livewireStyles
</head>
<body class="h-full" x-data="{
  step: 1,
  businessType: '',
  plan: '{{ request()->get("plan", "") }}',
  name: '',
  email: '',
  password: '',
  passwordConfirmation: '',

  nextStep() {
    if(this.step < 3) this.step++;
  },
  prevStep() {
    if(this.step > 1) this.step--;
  },
  selectBusinessType(type) {
    this.businessType = type;
  },
  selectPlan(planType) {
    this.plan = planType;
  },
  canProceedStep1() {
    return this.businessType !== '';
  },
  canProceedStep2() {
    return this.plan !== '';
  },
  canProceedStep3() {
    return this.name !== '' && this.email !== '' && this.password !== '' && this.passwordConfirmation !== '';
  }
}" x-init="
  // Si hay un plan preseleccionado desde la URL, ir directamente al paso 2
  if(plan && ['basic', 'premium', 'enterprise'].includes(plan)) {
    step = 2;
  }
">
  <div class="abstract-bg"></div>

  {{-- Header con Progress Indicator --}}
  <div class="w-full px-4 py-8">
    <div class="w-full max-w-3xl mx-auto">

      {{-- Progress Indicator --}}
      <div class="flex items-center max-w-md mx-auto">
        {{-- Step 1 --}}
        <div class="flex items-center">
          <div class="progress-step flex items-center justify-center w-10 h-10 rounded-full border-2"
               :class="step >= 1 ? 'active' : 'border-gray-600 bg-gray-800 text-gray-400'">
            <span class="text-sm font-semibold">1</span>
          </div>
        </div>

        {{-- Line 1-2 --}}
        <div class="progress-line flex-1 h-1 mx-3 bg-gray-700 rounded"
             :class="step > 1 ? 'active' : ''"></div>

        {{-- Step 2 --}}
        <div class="flex items-center">
          <div class="progress-step flex items-center justify-center w-10 h-10 rounded-full border-2"
               :class="step >= 2 ? 'active' : 'border-gray-600 bg-gray-800 text-gray-400'">
            <span class="text-sm font-semibold">2</span>
          </div>
        </div>

        {{-- Line 2-3 --}}
        <div class="progress-line flex-1 h-1 mx-3 bg-gray-700 rounded"
             :class="step > 2 ? 'active' : ''"></div>

        {{-- Step 3 --}}
        <div class="flex items-center">
          <div class="progress-step flex items-center justify-center w-10 h-10 rounded-full border-2"
               :class="step >= 3 ? 'active' : 'border-gray-600 bg-gray-800 text-gray-400'">
            <span class="text-sm font-semibold">3</span>
          </div>
        </div>
      </div>

      {{-- Step Labels --}}
      <div class="flex items-center justify-between max-w-md mx-auto mt-4 text-xs font-medium px-1">
        <span :class="step >= 1 ? 'text-violet-400' : 'text-gray-500'">Tipo de negocio</span>
        <span :class="step >= 2 ? 'text-violet-400' : 'text-gray-500'">Plan</span>
        <span :class="step >= 3 ? 'text-violet-400' : 'text-gray-500'">Datos personales</span>
      </div>
    </div>
  </div>

  {{-- Contenido Principal --}}
  <div class="flex-1 flex items-start justify-center px-4 pb-12">
    <div class="w-full max-w-6xl main-container">

        {{-- Errores --}}
        @if ($errors->any())
          <div class="validation-errors">
            <ul class="space-y-1">
              @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register.wizard.store') }}">
          @csrf

          {{-- STEP 1: Business Type --}}
          <div x-show="step === 1" x-cloak class="step-content">
            <div class="mb-8">
              <h2 class="text-3xl font-bold tracking-tight mb-3 title-gradient">¿Qué tipo de negocio tienes?</h2>
              <p class="text-gray-400">Selecciona el tipo que mejor describe tu actividad</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
              {{-- Comercio/Tienda --}}
              <div class="business-card p-6"
                   :class="businessType === 'comercio' ? 'selected' : ''"
                   @click="selectBusinessType('comercio')">
                <div class="flex items-start gap-4">
                  <div class="icon-wrapper flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-white mb-2">Comercio / Tienda</h3>
                    <p class="text-sm text-gray-400 mb-3">Gestiona productos, ventas, stock y pedidos para tu negocio</p>
                    <ul class="space-y-2 text-xs text-gray-400">
                      <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Control de inventario
                      </li>
                      <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Gestión de ventas
                      </li>
                      <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Reportes de productos
                      </li>
                    </ul>
                  </div>
                  <div class="flex-shrink-0">
                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                         :class="businessType === 'comercio' ? 'border-violet-400 bg-violet-500' : 'border-gray-600'">
                      <svg x-show="businessType === 'comercio'" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Alquiler / Clubes --}}
              <div class="business-card p-6"
                   :class="businessType === 'alquiler' ? 'selected' : ''"
                   @click="selectBusinessType('alquiler')">
                <div class="flex items-start gap-4">
                  <div class="icon-wrapper flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-white mb-2">Alquiler / Clubes</h3>
                    <p class="text-sm text-gray-400 mb-3">Administra espacios, servicios, turnos y cobros</p>
                    <ul class="space-y-2 text-xs text-gray-400">
                      <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Control de espacios
                      </li>
                      <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Gestión de turnos
                      </li>
                      <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Panel de operarios
                      </li>
                    </ul>
                  </div>
                  <div class="flex-shrink-0">
                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all"
                         :class="businessType === 'alquiler' ? 'border-violet-400 bg-violet-500' : 'border-gray-600'">
                      <svg x-show="businessType === 'alquiler'" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="business_type" :value="businessType">

            <button type="button"
                    @click="nextStep()"
                    :disabled="!canProceedStep1()"
                    class="btn-primary w-full py-3 px-4">
              Siguiente: Elegir plan →
            </button>
          </div>

          {{-- STEP 2: Plan Selection --}}
<div x-show="step === 2" x-cloak class="step-content">
    <div class="mb-8">
        <h2 class="text-3xl font-bold tracking-tight mb-3 title-gradient">
            Elegí tu plan
        </h2>
        <p class="text-gray-400 max-w-xl">
            Todos los planes están disponibles durante la fase inicial para que puedas probar Gestior sin límites.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

        {{-- Plan Básico --}}
        <div class="plan-card p-5"
             style="background: rgba(31, 41, 55, 0.5); border: 2px solid rgba(75, 85, 99, 0.5);"
             :class="plan === 'basic' ? 'selected' : ''"
             @click="selectPlan('basic')">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-0.5">Básico</h3>
                    <p class="text-xs text-gray-400">Para comenzar</p>
                </div>
                <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-all duration-200"
                     :class="plan === 'basic' ? 'border-violet-500 bg-violet-500/10' : 'border-gray-600/60'">
                    <svg x-show="plan === 'basic'" class="w-2.5 h-2.5 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="mb-4">
                <span class="text-2xl font-bold text-white">Gratis</span>
            </div>

            <ul class="space-y-2 text-sm text-gray-400">
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Hasta 5 usuarios</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>1 sucursal</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Soporte por email</span>
                </li>
            </ul>
        </div>

        {{-- Plan Premium --}}
        <div class="plan-card p-5 relative"
             style="background: rgba(139, 92, 246, 0.03); border: 2px solid rgba(139, 92, 246, 0.2);"
             :class="plan === 'premium' ? 'selected' : ''"
             @click="selectPlan('premium')">

            {{-- Badge Premium Compacto --}}
            <div class="absolute -top-2 left-1/2 -translate-x-1/2 z-10">
                <div style="
                    padding: 0.25rem 0.75rem;
                    border-radius: 9999px;
                    backdrop-filter: blur(8px);
                    background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(192, 132, 252, 0.1));
                    border: 1px solid rgba(139, 92, 246, 0.4);
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                    display: flex;
                    align-items: center;
                    gap: 0.25rem;
                ">
                    <span style="
                        font-size: 10px;
                        font-weight: 600;
                        letter-spacing: 0.05em;
                        color: rgba(196, 181, 253, 0.95);
                    ">EARLY ACCESS</span>
                </div>
            </div>

            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-0.5">Premium</h3>
                    <p class="text-xs text-gray-400">Funciones avanzadas</p>
                </div>
                <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-all duration-200"
                     :class="plan === 'premium' ? 'border-violet-500 bg-violet-500/10' : 'border-gray-600/60'">
                    <svg x-show="plan === 'premium'" class="w-2.5 h-2.5 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="mb-4">
                <span class="text-2xl font-bold text-white">Gratis</span>
                <span class="block text-xs text-gray-500 mt-0.5">
                    Durante la fase inicial
                </span>
            </div>

            <ul class="space-y-2 text-sm text-gray-400">
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Todo lo incluido en Básico</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Hasta 50 usuarios</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Hasta 5 sucursales</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Reportes avanzados</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Soporte prioritario</span>
                </li>
            </ul>
        </div>

        {{-- Plan Enterprise --}}
        <div class="plan-card p-5 relative"
             style="background: rgba(156, 163, 175, 0.3); border: 2px solid rgba(107, 114, 128, 0.5);"
             :class="plan === 'enterprise' ? 'selected' : ''"
             @click="selectPlan('enterprise')">

            {{-- Badge Enterprise Compacto --}}
            <div class="absolute -top-2 left-1/2 -translate-x-1/2 z-10">
                <div style="
                    padding: 0.25rem 0.75rem;
                    border-radius: 9999px;
                    backdrop-filter: blur(8px);
                    background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(156, 163, 175, 0.05));
                    border: 1px solid rgba(255, 255, 255, 0.4);
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                    display: flex;
                    align-items: center;
                    gap: 0.25rem;
                ">
                    <span style="
                        font-size: 10px;
                        font-weight: 600;
                        letter-spacing: 0.05em;
                        color: rgba(255, 255, 255, 0.95);
                    ">PREVIEW</span>
                </div>
            </div>

            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-0.5">Enterprise</h3>
                    <p class="text-xs text-gray-400">Para operaciones complejas</p>
                </div>
                <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-all duration-200"
                     :class="plan === 'enterprise' ? 'border-violet-500 bg-violet-500/10' : 'border-gray-600/60'">
                    <svg x-show="plan === 'enterprise'" class="w-2.5 h-2.5 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="mb-4">
                <span class="text-2xl font-bold text-white">Gratis</span>
                <span class="block text-xs text-gray-500 mt-0.5">
                    Acceso de evaluación
                </span>
            </div>

            <ul class="space-y-2 text-sm text-gray-400">
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Todo lo incluido en Premium</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Usuarios ilimitados</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Sucursales ilimitadas</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>API completa</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Soporte dedicado 24/7</span>
                </li>
            </ul>
        </div>
    </div>

    <input type="hidden" name="plan" :value="plan">

    <div class="flex gap-3">
        <button type="button"
                @click="prevStep()"
                class="btn-secondary flex-1 py-3 px-4">
            ← Anterior
        </button>
        <button type="button"
                @click="nextStep()"
                :disabled="!canProceedStep2()"
                class="btn-primary flex-1 py-3 px-4">
            Siguiente: Datos personales →
        </button>
    </div>
</div>

          {{-- STEP 3: Personal Data --}}
          <div x-show="step === 3" x-cloak class="step-content">
            <div class="mb-8">
              <h2 class="text-3xl font-bold tracking-tight mb-3 title-gradient">Datos personales</h2>
              <p class="text-gray-400">Completa tus datos para finalizar el registro</p>
            </div>

            <div class="space-y-6 mb-8">
              {{-- Nombre --}}
              <div>
                <label for="name" class="form-label">Nombre completo</label>
                <div class="input-group">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  <input id="name" name="name" type="text" x-model="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                         class="form-input"
                         style="padding-left: 3.5rem;"
                         placeholder="Ingresa tu nombre completo">
                </div>
              </div>

              {{-- Email --}}
              <div>
                <label for="email" class="form-label">Correo electrónico</label>
                <div class="input-group">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  <input id="email" name="email" type="email" x-model="email" value="{{ old('email') }}" required autocomplete="username"
                         class="form-input"
                         style="padding-left: 3.5rem;"
                         placeholder="tu@email.com">
                </div>
              </div>

              {{-- Password --}}
              <div x-data="{showPass:false}">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                  <input id="password" name="password" :type="showPass ? 'text' : 'password'" x-model="password" required autocomplete="new-password"
                         class="form-input"
                         style="padding-left: 3.5rem; padding-right: 3.25rem;"
                         placeholder="Mínimo 8 caracteres">
                  <button type="button" @click="showPass=!showPass"
                          class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-violet-400 transition-colors duration-300 z-10">
                    <svg class="h-5 w-5" x-show="!showPass" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg class="h-5 w-5" x-show="showPass" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                  </button>
                </div>
              </div>

              {{-- Confirm Password --}}
              <div x-data="{showPass2:false}">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                  <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <input id="password_confirmation" name="password_confirmation" :type="showPass2 ? 'text' : 'password'" x-model="passwordConfirmation" required autocomplete="new-password"
                         class="form-input"
                         style="padding-left: 3.5rem; padding-right: 3.25rem;"
                         placeholder="Repite tu contraseña">
                  <button type="button" @click="showPass2=!showPass2"
                          class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-violet-400 transition-colors duration-300 z-10">
                    <svg class="h-5 w-5" x-show="!showPass2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg class="h-5 w-5" x-show="showPass2" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            {{-- Info box --}}
            <div class="info-box p-4 mb-6">
              <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm">
                  <p class="font-semibold mb-1 text-blue-200">¿Qué sucede después?</p>
                  <ul class="space-y-1 text-blue-300">
                    <li>• Revisaremos tu solicitud</li>
                    <li>• Te enviaremos las credenciales de acceso por email</li>
                    <li>• Podrás comenzar a usar Gestior inmediatamente</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="flex gap-3 mb-5">
              <button type="button"
                      @click="prevStep()"
                      class="btn-secondary flex-1 py-3 px-4">
                ← Anterior
              </button>
              <button type="submit"
                      :disabled="!canProceedStep3()"
                      class="btn-primary flex-1 py-3 px-4">
                Solicitar acceso gratis
              </button>
            </div>

            <div class="text-center text-sm text-gray-400">
              <a href="{{ route('login') }}" class="font-medium text-violet-400 hover:text-violet-300 transition-colors">
                ¿Ya tienes cuenta? Inicia sesión
              </a>
            </div>
          </div>
        </form>
    </div>
  </div>

  {{-- Footer --}}
  <footer class="w-full py-8">
    <div class="text-center">
      <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Gestior — Todos los derechos reservados.</p>
    </div>
  </footer>

  @livewireScripts
</body>
</html>
