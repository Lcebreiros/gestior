<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selecciona tu plan - {{ config('app.name', 'Gestior') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #000000;
            color: white;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .bg-gradient {
            background: radial-gradient(ellipse at top, rgba(124, 58, 237, 0.15) 0%, #000000 50%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            filter: drop-shadow(0 8px 24px rgba(124, 58, 237, 0.3));
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #a78bfa, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #9ca3af;
            font-size: 1.125rem;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .plan-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2rem;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(124, 58, 237, 0.3);
            border-color: rgba(167, 139, 250, 0.4);
        }

        .plan-card.selected {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
            background: rgba(139, 92, 246, 0.1);
        }

        .plan-badge {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .plan-name {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .plan-price {
            font-size: 1.25rem;
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }

        .plan-features {
            list-style: none;
            margin-bottom: 1.5rem;
        }

        .plan-features li {
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #d1d5db;
        }

        .plan-features li:last-child {
            border-bottom: none;
        }

        .check-icon {
            color: #8b5cf6;
            flex-shrink: 0;
        }

        .select-button {
            width: 100%;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.3);
        }

        .select-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(124, 58, 237, 0.4);
        }

        .select-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .alert {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 2rem;
            color: #fca5a5;
        }

        @media (max-width: 768px) {
            .title {
                font-size: 2rem;
            }

            .plans-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .plan-card {
            animation: fadeIn 0.6s ease-out;
        }

        .plan-card:nth-child(1) { animation-delay: 0.1s; }
        .plan-card:nth-child(2) { animation-delay: 0.2s; }
        .plan-card:nth-child(3) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>

    <div class="container">
        <div class="header">
            <img src="/images/gestior.png" alt="Gestior" class="logo">
            <h1 class="title">Elige tu plan</h1>
            <p class="subtitle">Selecciona el plan que mejor se adapte a tus necesidades</p>
        </div>

        @if (session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div class="info-message" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 0.75rem; padding: 1rem; margin-bottom: 2rem; color: #60a5fa; font-size: 0.875rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <form id="planForm" method="POST" action="{{ route('subscription.select') }}">
            @csrf
            <input type="hidden" name="plan" id="selectedPlan" value="">

            <div class="plans-grid">
                @foreach ($plans as $key => $plan)
                    <div class="plan-card" data-plan="{{ $key }}" onclick="selectPlan('{{ $key }}')">
                        @if ($key === 'premium')
                            <span class="plan-badge">Más popular</span>
                        @elseif ($key === 'enterprise')
                            <span class="plan-badge">Recomendado</span>
                        @endif

                        <h2 class="plan-name">{{ $plan['name'] }}</h2>
                        <div class="plan-price">
                            @if ($plan['price'] == 0)
                                Gratis
                            @else
                                ${{ number_format($plan['price'], 0, ',', '.') }}/mes
                            @endif
                        </div>

                        <ul class="plan-features">
                            @foreach ($plan['features'] as $feature)
                                <li>
                                    <svg class="check-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        <button type="button" class="select-button" onclick="selectAndSubmit('{{ $key }}')">
                            Seleccionar {{ $plan['name'] }}
                        </button>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <script>
        let selectedPlanValue = null;

        function selectPlan(plan) {
            // Remover selección previa
            document.querySelectorAll('.plan-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Seleccionar nuevo plan
            document.querySelector(`[data-plan="${plan}"]`).classList.add('selected');
            selectedPlanValue = plan;
            document.getElementById('selectedPlan').value = plan;
        }

        function selectAndSubmit(plan) {
            event.preventDefault();
            selectPlan(plan);

            // Pequeño delay para mostrar la animación de selección
            setTimeout(() => {
                document.getElementById('planForm').submit();
            }, 200);
        }
    </script>
</body>
</html>
