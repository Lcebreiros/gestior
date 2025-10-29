<div class="feature-cards-wrapper">
    <style>
        /* Reset y base */
        .feature-cards-wrapper * {
            box-sizing: border-box;
        }

        .feature-cards-wrapper {
            padding: 2rem 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            position: relative;
        }

        /* Grid de tarjetas */
        .cards-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2.5rem;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 640px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }

        @media (min-width: 1024px) {
            .cards-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 2.5rem;
            }
        }

        /* Card individual */
        .feature-card {
        position: relative;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 1.5rem;
        padding: 3.5rem 2rem 3rem;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        overflow: hidden;
        opacity: 0;
        transform: translateY(60px) scale(0.98);
        filter: blur(12px);
        transition: all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        min-height: 320px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

        /* Estado inicial - completamente invisible */
        .feature-card.pre-enter {
            opacity: 0;
            transform: translateY(80px) scale(0.95);
            filter: blur(12px);
        }

        /* Estado durante entrada - materialización progresiva */
        .feature-card.entering {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0px);
            background: rgba(255, 255, 255, 0.95);
        }

        /* Estado completamente visible */
        .feature-card.visible {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0px);
        background: white;
    }


        /* Estado de salida - desmaterialización */
        .feature-card.exiting {
        opacity: 0;
        transform: translateY(40px) scale(0.98);
        filter: blur(8px);
        background: rgba(255, 255, 255, 0.3);
    }

        /* Hover effects solo cuando está visible */
        .feature-card.visible:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 
            0 20px 60px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }

        /* Línea decorativa de color con transición suave */
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            border-radius: 0 0 4px 4px;
            background: var(--card-color, #8b5cf6);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 0.7;
        }

        .feature-card.visible:hover::before {
            width: 100%;
            opacity: 1;
            height: 5px;
        }

        /* Efecto de brillo sutil al hover */
        .feature-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.3), 
                transparent);
            transition: left 0.8s ease;
            z-index: 1;
        }

        .feature-card.visible:hover::after {
            left: 100%;
        }

        /* Contenido de la card */
        .card-content {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        justify-content: center;
        /* Sin transiciones separadas - se anima con la tarjeta */
    }

        .feature-card.visible .card-content {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card.exiting .card-content {
            opacity: 0;
            transform: translateY(5px);
        }

        /* Icono */
        .card-icon-wrapper {
        position: relative;
        width: 5rem;
        height: 5rem;
        margin-bottom: 2rem;
    }
        .feature-card.visible .card-icon-wrapper {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        .feature-card.exiting .card-icon-wrapper {
            opacity: 0;
            transform: scale(0.9) rotate(5deg);
        }

        .card-icon {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-gradient, linear-gradient(135deg, #8b5cf6, #a855f7));
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

        .feature-card.visible:hover .card-icon {
        transform: scale(1.12) rotate(-3deg);
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.25);
    }

        .card-icon svg {
            width: 2.5rem;
            height: 2.5rem;
            stroke: white;
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Título */
        .card-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        line-height: 1.3;
        transition: color 0.4s ease;
    }

        .feature-card.visible:hover .card-title {
        color: #111827;
    }

        .feature-card.visible:hover .card-title {
            color: #111827;
            transform: translateY(-2px);
        }

        /* Descripción */
        .card-description {
        font-size: 1rem;
        line-height: 1.7;
        color: #6b7280;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1.5rem;
        flex-grow: 1;
        transition: color 0.4s ease;
    }

        .feature-card.visible .card-description {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card.visible:hover .card-description {
        color: #4b5563;
    }

        /* Estado vacío */
        .empty-state {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 6rem 1.5rem;
        text-align: center;
        opacity: 0;
        transform: translateY(60px) scale(0.98);
        filter: blur(12px);
        transition: all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: 1.5rem;
        border: 2px dashed rgba(209, 213, 219, 0.6);
        min-height: 300px;
    }

        .empty-state.visible {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0px);
        background: rgba(255, 255, 255, 0.5);
    }

        .empty-icon-wrapper {
            width: 6rem;
            height: 6rem;
            margin-bottom: 2rem;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.6s ease;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s;
        }

        .empty-state.visible .empty-icon-wrapper {
            opacity: 1;
            transform: scale(1);
        }

        .empty-state:hover .empty-icon-wrapper {
            transform: scale(1.05);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .empty-icon-wrapper svg {
            width: 3rem;
            height: 3rem;
            color: rgba(156, 163, 175, 1);
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.75rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s;
        }

        .empty-state.visible .empty-title {
            opacity: 1;
            transform: translateY(0);
        }

        .empty-description {
            color: rgba(107, 114, 128, 1);
            font-size: 1.0625rem;
            max-width: 32rem;
            line-height: 1.6;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.5s;
        }

        .empty-state.visible .empty-description {
            opacity: 1;
            transform: translateY(0);
        }

        /* Stagger delays mejorados */
        .feature-card:nth-child(1) { transition-delay: 0.1s; }
    .feature-card:nth-child(2) { transition-delay: 0.2s; }
    .feature-card:nth-child(3) { transition-delay: 0.3s; }
    .feature-card:nth-child(4) { transition-delay: 0.4s; }
    .feature-card:nth-child(5) { transition-delay: 0.5s; }
    .feature-card:nth-child(6) { transition-delay: 0.6s; }
    .feature-card:nth-child(7) { transition-delay: 0.7s; }
    .feature-card:nth-child(8) { transition-delay: 0.8s; }
    .feature-card:nth-child(9) { transition-delay: 0.9s; }
    </style>

    <div class="cards-grid">
        @forelse($cards as $i => $card)
            <div class="feature-card pre-enter" 
                 data-card-index="{{ $i }}"
                 style="--card-color: {{ $card['color'] ?? '#8b5cf6' }}; 
                        --card-gradient: linear-gradient(135deg, {{ $card['color'] ?? '#8b5cf6' }}, {{ $card['secondary_color'] ?? '#a855f7' }});">
                
                <!-- Contenido -->
                <div class="card-content">
                    <!-- Icono -->
                    <div class="card-icon-wrapper">
                        <div class="card-icon">
                            @if(!empty($card['icon']))
                                {!! $card['icon'] !!}
                            @else
                                <svg viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Título -->
                    <h3 class="card-title">{{ $card['title'] }}</h3>

                    <!-- Descripción -->
                    <p class="card-description">{{ $card['description'] }}</p>
                </div>
            </div>
        @empty
            <!-- Estado vacío -->
            <div class="empty-state pre-enter" data-card-index="empty">
                <div class="empty-icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </div>
                <h3 class="empty-title">No hay funciones disponibles</h3>
                <p class="empty-description">Las tarjetas de funciones aparecerán aquí cuando estén configuradas en el sistema.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.feature-card, .empty-state');
    
    // Configuración del Intersection Observer
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -50px 0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            const card = entry.target;
            
            if (entry.isIntersecting) {
                // Entrada: tarjeta y contenido aparecen juntos
                const delay = (card.dataset.cardIndex * 100) || 0;
                
                setTimeout(() => {
                    card.classList.remove('exiting');
                    card.classList.add('visible');
                }, delay);
                
            } else {
                // Salida: tarjeta y contenido desaparecen juntos
                card.classList.remove('visible');
                card.classList.add('exiting');
            }
        });
    }, observerOptions);

    // Observar cada tarjeta
    cards.forEach(card => {
        observer.observe(card);
    });

    // Re-inicializar cuando se añadan nuevas tarjetas (para Livewire)
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('element.updated', (el) => {
            const newCards = el.querySelectorAll('.feature-card:not(._observed), .empty-state:not(._observed)');
            newCards.forEach(card => {
                card._observed = true;
                observer.observe(card);
            });
        });
    }
});
</script>