<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Solicitud enviada • Gestior</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body { font-family: 'Inter', sans-serif; background: #000; }

    .glow-ring {
      box-shadow: 0 0 0 1px rgba(167,139,250,.25), 0 0 32px rgba(139,92,246,.15);
    }

    .step-card {
      background: rgba(109,40,217,.08);
      border: 1px solid rgba(139,92,246,.2);
    }

    .step-card-done {
      background: rgba(109,40,217,.12);
      border: 1px solid rgba(167,139,250,.3);
    }

    .btn-primary {
      background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
      box-shadow: 0 4px 24px rgba(109,40,217,.35);
      transition: box-shadow .2s, transform .1s;
    }
    .btn-primary:hover {
      box-shadow: 0 6px 32px rgba(109,40,217,.55);
      transform: translateY(-1px);
    }
    .btn-primary:active { transform: translateY(0); }
  </style>
</head>
<body class="h-full">
  <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12" style="background:#000;">

    <!-- Logo -->
    <img src="{{ asset('images/Gestior.png') }}" alt="Gestior" class="h-12 w-auto mb-10 select-none" />

    <!-- Card principal -->
    <div class="w-full max-w-md rounded-2xl glow-ring p-8" style="background:rgba(15,8,30,.95);">

      <!-- Ícono de check -->
      <div class="flex items-center justify-center w-16 h-16 rounded-full mx-auto mb-6"
           style="background:rgba(109,40,217,.18); border:1px solid rgba(167,139,250,.35); box-shadow:0 0 20px rgba(139,92,246,.2);">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="rgba(196,181,253,1)" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
      </div>

      <!-- Título y subtítulo -->
      <h1 class="text-2xl font-semibold text-white text-center mb-2 tracking-tight">
        ¡Solicitud enviada!
      </h1>
      <p class="text-center text-sm mb-8" style="color:rgba(196,181,253,.7); line-height:1.6;">
        Tu cuenta fue creada. Revisaremos tu solicitud y recibirás<br>
        acceso completo una vez que sea aprobada.
      </p>

      @if(session('message'))
        <div class="mb-6 p-3 rounded-lg text-sm text-center" style="background:rgba(139,92,246,.1); border:1px solid rgba(167,139,250,.2); color:rgba(216,180,254,1);">
          {{ session('message') }}
        </div>
      @endif

      <!-- Pasos de estado -->
      <div class="space-y-3 mb-8">

        <div class="step-card-done flex items-center gap-3 rounded-xl px-4 py-3">
          <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-full"
                style="background:rgba(109,40,217,.25);">
            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="rgba(196,181,253,1)">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
          </span>
          <span class="text-sm font-medium" style="color:rgba(233,213,255,1);">Cuenta creada exitosamente</span>
        </div>

        <div class="step-card flex items-center gap-3 rounded-xl px-4 py-3">
          <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-full"
                style="background:rgba(109,40,217,.15);">
            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="rgba(167,139,250,.8)">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
          </span>
          <span class="text-sm" style="color:rgba(196,181,253,.8);">Aprobación pendiente</span>
        </div>

        <div class="step-card flex items-center gap-3 rounded-xl px-4 py-3">
          <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-full"
                style="background:rgba(109,40,217,.15);">
            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="rgba(167,139,250,.6)">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
            </svg>
          </span>
          <span class="text-sm" style="color:rgba(167,139,250,.7);">Te avisaremos por email al activar</span>
        </div>

      </div>

      <!-- Botón -->
      <a href="{{ route('login') }}"
         class="btn-primary block w-full py-3 px-4 rounded-xl text-white text-sm font-semibold text-center">
        Ir al inicio de sesión
      </a>

    </div>

    <p class="text-xs mt-8" style="color:rgba(107,70,193,.5);">
      &copy; {{ date('Y') }} Gestior — Todos los derechos reservados.
    </p>

  </div>
</body>
</html>
