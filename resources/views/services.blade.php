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
        .scroll-reveal.revealed .support-channel-card {
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
                const cards = document.querySelectorAll('.security-card, .support-channel-card');

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
