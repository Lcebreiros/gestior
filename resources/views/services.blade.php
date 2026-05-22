<x-guest-layout>
    <style>
        /* Smooth scroll behavior - Native smooth scrolling disabled for custom control */
        html {
            scroll-behavior: auto;
        }

        /* Prevent layout shift during animations */
        body {
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch; /* Momentum scrolling on iOS */
        }

        /* Scroll progress indicator */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, #7534c9, #6366f1);
            z-index: 9999;
            transition: width 0.1s ease-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transition: background 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Page header - Apple style */
        .services-hero {
            position: relative;
            background: radial-gradient(ellipse 100% 60% at 50% -10%, rgba(117, 52, 201, 0.08), transparent 60%);
            padding: 8rem 2rem 5rem;
            text-align: center;
            border-bottom: 0.5px solid rgba(255, 255, 255, 0.06);
            overflow: hidden;
        }

        .services-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.4) 100%);
            pointer-events: none;
        }

        .services-hero-content {
            position: relative;
            z-index: 1;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .services-hero-title {
            font-size: clamp(3rem, 7vw, 5rem);
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
            color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, sans-serif;
        }

        .services-hero-subtitle {
            font-size: clamp(1.25rem, 2.5vw, 1.625rem);
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.5);
            max-width: 720px;
            margin: 0 auto;
            font-weight: 400;
            letter-spacing: -0.015em;
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            animation: fadeIn 1s ease 0.5s forwards, bounce 2s ease infinite 1.5s;
        }

        /* API CTA container */
.api-cta {
    margin-top: 4rem;
    text-align: center;
}

.api-cta-wrapper {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

/* Base button */
.api-cta-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    border-radius: 12px;
    user-select: none;
}

/* Disabled style */
.api-cta-disabled {
    background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.06),
        rgba(255, 255, 255, 0.03)
    );
    color: rgba(255, 255, 255, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.12);
    cursor: not-allowed;
    backdrop-filter: blur(6px);
    box-shadow: none;
}

/* No hover interaction */
.api-cta-disabled:hover {
    transform: none;
    box-shadow: none;
}

/* Icon */
.api-cta-disabled svg {
    width: 20px;
    height: 20px;
    opacity: 0.5;
}

/* "Próximamente" label */
.api-cta-soon {
    font-size: 0.875rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
}


        @keyframes fadeIn {
            to { opacity: 0.6; }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(-50%) translateY(0);
            }
            40% {
                transform: translateX(-50%) translateY(-10px);
            }
            60% {
                transform: translateX(-50%) translateY(-5px);
            }
        }

        .scroll-indicator svg {
            width: 24px;
            height: 24px;
            color: rgba(255, 255, 255, 0.4);
        }

        /* Scroll reveal animations */
        .scroll-reveal {
            opacity: 0;
            transform: translateY(60px) scale(0.98);
            transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Parallax effect - smoother transitions */
        .parallax-section {
            will-change: transform;
        }

        /* Add depth with subtle shadows on scroll */
        .scroll-reveal.revealed .security-card,
        .scroll-reveal.revealed .support-channel-card,
        .scroll-reveal.revealed .api-card {
            animation: floatIn 0.8s ease-out forwards;
        }

        @keyframes floatIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* API Integration Section */
        .api-section {
            position: relative;
            padding: 6rem 2rem;
            background: radial-gradient(ellipse 80% 50% at 50% 50%, rgba(99, 102, 241, 0.05), transparent);
            border-bottom: 0.5px solid rgba(255, 255, 255, 0.06);
        }

        .api-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .api-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .api-title {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 600;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            color: #ffffff;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.7) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .api-subtitle {
            font-size: clamp(1.125rem, 2vw, 1.375rem);
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.6);
            max-width: 800px;
            margin: 0 auto;
        }

        .api-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .api-card {
            position: relative;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .api-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(
                600px circle at var(--mouse-x, 50%) var(--mouse-y, 50%),
                rgba(117, 52, 201, 0.1),
                transparent 40%
            );
            opacity: 0;
            transition: opacity 0.4s;
        }

        .api-card:hover::before {
            opacity: 1;
        }

        .api-card:hover {
            transform: translateY(-8px);
            border-color: rgba(117, 52, 201, 0.3);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(117, 52, 201, 0.2) inset;
        }

        .api-card-icon {
            width: 56px;
            height: 56px;
            margin-bottom: 1.5rem;
            padding: 14px;
            background: linear-gradient(135deg, rgba(117, 52, 201, 0.2), rgba(99, 102, 241, 0.2));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .api-card-icon svg {
            width: 100%;
            height: 100%;
            color: #7534c9;
        }

        .api-card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #ffffff;
            letter-spacing: -0.01em;
        }

        .api-card-description {
            font-size: 1rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 1.5rem;
        }

        .api-card-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .api-card-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .api-card-features li:last-child {
            border-bottom: none;
        }

        .api-card-features li svg {
            width: 18px;
            height: 18px;
            color: #7534c9;
            flex-shrink: 0;
        }

        /* CTA Section */
        .api-cta {
            margin-top: 4rem;
            text-align: center;
        }

        .api-cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #7534c9, #6366f1);
            color: #ffffff;
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(117, 52, 201, 0.3);
        }

        .api-cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(117, 52, 201, 0.4);
        }

        .api-cta-button svg {
            width: 20px;
            height: 20px;
        }

        @media (max-width: 768px) {
            .api-content {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Scroll progress indicator -->
    <div class="scroll-progress"></div>

    <!-- Hero Section -->
    <div class="services-hero">
        <div class="services-hero-content">
            <h1 class="services-hero-title">
                Servicio completo<br>para tu tranquilidad
            </h1>
            <p class="services-hero-subtitle">
                Seguridad de nivel empresarial y soporte dedicado 24/7. Todo lo que necesitás para que tu negocio funcione sin interrupciones.
            </p>
        </div>

        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </div>

    <!-- API Integration Section -->
    <div class="scroll-reveal parallax-section api-section" data-scroll-speed="0.4">
        <div class="api-container">
            <div class="api-header">
                <h2 class="api-title">
                    Helipso impulsa tu negocio<br>desde el centro
                </h2>
                <p class="api-subtitle">
                    Conectá tu web, tu app o tus sistemas a Helipso y manejá todo desde un solo lugar. Nuestra API te permite usar Helipso como el cerebro operativo de tu empresa: ventas, stock, clientes y automatizaciones, todo sincronizado en tiempo real.
                </p>
            </div>

            <div class="api-content">
                <!-- Integration Card -->
                <div class="api-card">
                    <div class="api-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <h3 class="api-card-title">Integración Total</h3>
                    <p class="api-card-description">
                        Conectá cualquier plataforma o sistema a Helipso y unificá todas tus operaciones.
                    </p>
                    <ul class="api-card-features">
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>API RESTful completa</span>
                        </li>
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Documentación detallada</span>
                        </li>
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Webhooks en tiempo real</span>
                        </li>
                    </ul>
                </div>

                <!-- Sync Card -->
                <div class="api-card">
                    <div class="api-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <h3 class="api-card-title">Sincronización Automática</h3>
                    <p class="api-card-description">
                        Mantené tu información actualizada en todos tus canales sin intervención manual.
                    </p>
                    <ul class="api-card-features">
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Stock en tiempo real</span>
                        </li>
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Precios actualizados</span>
                        </li>
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Pedidos centralizados</span>
                        </li>
                    </ul>
                </div>

                <!-- Automation Card -->
                <div class="api-card">
                    <div class="api-card-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="api-card-title">Automatización Inteligente</h3>
                    <p class="api-card-description">
                        Dejá que Helipso trabaje por vos, automatizando procesos y decisiones de negocio.
                    </p>
                    <ul class="api-card-features">
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Reglas de negocio personalizadas</span>
                        </li>
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Notificaciones automáticas</span>
                        </li>
                        <li>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Reportes programados</span>
                        </li>
                    </ul>
                </div>
            </div>

<!-- API CTA -->
<div class="api-cta">
    <div class="api-cta-wrapper">
        <span class="api-cta-button api-cta-disabled" aria-disabled="true">
            <span>Ver documentación de API</span>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </span>

        <span class="api-cta-soon">
            Próximamente
        </span>
    </div>
</div>


        </div>
    </div>

    <!-- Security Section -->
    <div class="scroll-reveal parallax-section" data-scroll-speed="0.5">
        @include('components.landing.security-section')
    </div>

    <!-- Support Section -->
    <div class="scroll-reveal parallax-section" data-scroll-speed="0.3">
        @include('components.landing.support-section')
    </div>

    <script>
        // Consolidated smooth scroll and animations for services page
        (function() {
            'use strict';

            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }

            function init() {
                // 1. Scroll progress indicator
                setupScrollProgress();

                // 2. Scroll reveal animations
                setupScrollReveal();

                // 3. Parallax effect
                setupParallax();

                // 4. Smooth scroll for anchor links
                setupSmoothScroll();

                // 5. Scroll indicator
                setupScrollIndicator();

                // 6. Mouse tracking for cards (security and support sections)
                setupMouseTracking();
            }

            // Scroll progress indicator
            function setupScrollProgress() {
                const progressBar = document.querySelector('.scroll-progress');
                if (!progressBar) return;

                window.addEventListener('scroll', () => {
                    const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    const scrolled = (window.pageYOffset / windowHeight) * 100;
                    progressBar.style.width = scrolled + '%';
                }, { passive: true });
            }

            // Scroll reveal with Intersection Observer
            function setupScrollReveal() {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -100px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                // Observe scroll-reveal elements
                document.querySelectorAll('.scroll-reveal').forEach(el => {
                    observer.observe(el);
                });
            }

            // Enhanced parallax scrolling effect
            function setupParallax() {
                let ticking = false;
                const parallaxElements = document.querySelectorAll('.parallax-section');

                // Precalculate element positions
                const elementData = Array.from(parallaxElements).map(el => ({
                    element: el,
                    speed: parseFloat(el.dataset.scrollSpeed) || 0.5,
                    initialTop: el.getBoundingClientRect().top + window.pageYOffset
                }));

                function updateParallax() {
                    const scrolled = window.pageYOffset;
                    const windowHeight = window.innerHeight;

                    elementData.forEach(({ element, speed, initialTop }) => {
                        const rect = element.getBoundingClientRect();

                        // Only apply parallax if element is in viewport
                        if (rect.top < windowHeight && rect.bottom > 0) {
                            // Calculate scroll progress (0 to 1)
                            const scrollProgress = (scrolled - initialTop + windowHeight) / (windowHeight + rect.height);

                            // Apply smooth parallax transformation
                            const movement = (scrollProgress - 0.5) * 100 * speed;
                            const clampedMovement = Math.max(-50, Math.min(50, movement));

                            element.style.transform = `translateY(${clampedMovement}px)`;
                        }
                    });

                    ticking = false;
                }

                // Initial call
                updateParallax();

                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(updateParallax);
                        ticking = true;
                    }
                }, { passive: true });

                // Recalculate on window resize
                window.addEventListener('resize', () => {
                    elementData.forEach((data, index) => {
                        data.initialTop = parallaxElements[index].getBoundingClientRect().top + window.pageYOffset;
                    });
                    updateParallax();
                }, { passive: true });
            }

            // Enhanced smooth scroll for anchor links with custom easing
            function setupSmoothScroll() {
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        const href = this.getAttribute('href');
                        if (href === '#') return;

                        e.preventDefault();
                        const target = document.querySelector(href);

                        if (target) {
                            const headerOffset = 80;
                            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerOffset;
                            const startPosition = window.pageYOffset;
                            const distance = targetPosition - startPosition;
                            const duration = 1200; // milliseconds
                            let start = null;

                            // Easing function (ease-in-out cubic)
                            function easeInOutCubic(t) {
                                return t < 0.5
                                    ? 4 * t * t * t
                                    : 1 - Math.pow(-2 * t + 2, 3) / 2;
                            }

                            function animation(currentTime) {
                                if (start === null) start = currentTime;
                                const timeElapsed = currentTime - start;
                                const progress = Math.min(timeElapsed / duration, 1);
                                const ease = easeInOutCubic(progress);

                                window.scrollTo(0, startPosition + (distance * ease));

                                if (timeElapsed < duration) {
                                    requestAnimationFrame(animation);
                                }
                            }

                            requestAnimationFrame(animation);
                        }
                    });
                });
            }

            // Scroll indicator behavior
            function setupScrollIndicator() {
                const scrollIndicator = document.querySelector('.scroll-indicator');
                if (!scrollIndicator) return;

                let scrollTimeout;

                window.addEventListener('scroll', () => {
                    if (window.scrollY > 100) {
                        scrollIndicator.style.opacity = '0';
                        scrollIndicator.style.pointerEvents = 'none';
                    } else {
                        clearTimeout(scrollTimeout);
                        scrollTimeout = setTimeout(() => {
                            scrollIndicator.style.opacity = '0.6';
                            scrollIndicator.style.pointerEvents = 'auto';
                        }, 100);
                    }
                }, { passive: true });
            }

            // Mouse tracking effect for cards
            function setupMouseTracking() {
                const cards = document.querySelectorAll('.security-card, .support-channel-card, .api-card');

                cards.forEach(card => {
                    card.addEventListener('mousemove', (e) => {
                        const rect = card.getBoundingClientRect();
                        const x = ((e.clientX - rect.left) / rect.width) * 100;
                        const y = ((e.clientY - rect.top) / rect.height) * 100;

                        card.style.setProperty('--mouse-x', `${x}%`);
                        card.style.setProperty('--mouse-y', `${y}%`);
                    });

                    card.addEventListener('mouseleave', () => {
                        card.style.setProperty('--mouse-x', '50%');
                        card.style.setProperty('--mouse-y', '50%');
                    });
                });
            }
        })();
    </script>
</x-guest-layout>