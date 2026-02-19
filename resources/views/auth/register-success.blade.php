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
    body { font-family: 'Inter', sans-serif; }
    .abstract-bg {
      position: fixed; inset: 0; z-index: -1;
      background:
        radial-gradient(1200px 600px at 90% 110%, rgba(37,99,235,.22), transparent 60%),
        radial-gradient(900px 500px at -10% -20%, rgba(91,33,182,.24), transparent 60%),
        linear-gradient(180deg, #0f172a 0%, #0b1020 100%);
    }
  </style>
</head>
<body class="h-full">
  <div class="abstract-bg"></div>
  <div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md text-center">

      <img src="{{ asset('images/Gestior.png') }}" alt="Gestior" class="h-14 w-auto mx-auto mb-8 select-none" />

      <div class="rounded-2xl border border-white/5 bg-white/5 backdrop-blur p-8 shadow-2xl">

        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-violet-600/20 border border-violet-500/30 mx-auto mb-5">
          <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>

        <h1 class="text-2xl font-semibold text-white mb-2">¡Solicitud enviada!</h1>
        <p class="text-slate-400 text-sm mb-6">
          Tu cuenta fue creada. Revisaremos tu solicitud y recibirás acceso completo
          una vez que sea aprobada.
        </p>

        @if(session('message'))
          <div class="mb-5 p-3 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm">
            {{ session('message') }}
          </div>
        @endif

        <div class="space-y-3 text-sm text-slate-400 mb-6">
          <div class="flex items-center gap-3 bg-white/5 rounded-lg px-4 py-3">
            <svg class="w-4 h-4 text-violet-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Cuenta creada exitosamente
          </div>
          <div class="flex items-center gap-3 bg-white/5 rounded-lg px-4 py-3">
            <svg class="w-4 h-4 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
            Aprobación pendiente
          </div>
          <div class="flex items-center gap-3 bg-white/5 rounded-lg px-4 py-3">
            <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
              <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
            </svg>
            Te avisaremos por email al activar
          </div>
        </div>

        <a href="{{ route('login') }}"
           class="block w-full py-3 px-4 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold text-center transition-colors">
          Ir al inicio de sesión
        </a>
      </div>

      <p class="text-xs text-slate-500 mt-6">&copy; {{ date('Y') }} Gestior — Todos los derechos reservados.</p>
    </div>
  </div>
</body>
</html>
