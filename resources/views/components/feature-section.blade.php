@props([
    'badge' => '',
    'title' => '',
    'description' => '',
    'features' => [],
    'image' => '',
    'inverted' => false,
    'background' => 'black' // black, gradient, or custom
])

<section class="feature-section bg-{{ $background }} text-white py-24 md:py-40 relative overflow-hidden" data-feature-section>
    <style>
        /* Background effects */
        .feature-section {
            position: relative;
        }

        .feature-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse 80% 50% at 50% 50%, rgba(117, 52, 201, 0.08), transparent);
            opacity: 0;
            transition: opacity 0.8s ease;
            pointer-events: none;
        }

        .feature-section.in-view::before {
            opacity: 1;
        }

        .feature-section-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 4rem;
            align-items: center;
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 1024px) {
            .feature-section-content {
                grid-template-columns: 1fr 1.2fr;
                gap: 8rem;
            }

            .feature-section-content.inverted {
                direction: rtl;
            }

            .feature-section-content.inverted > * {
                direction: ltr;
            }
        }

        /* Badge */
        .feature-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, rgba(117, 52, 201, 0.15), rgba(117, 52, 201, 0.08));
            border: 1px solid rgba(117, 52, 201, 0.3);
            border-radius: 100px;
            color: #c4b5fd;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(117, 52, 201, 0.1);
            transition: all 0.3s ease;
        }

        .feature-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #7534c9;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(117, 52, 201, 0.6);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.2); }
        }

        .feature-text-content:hover .feature-badge {
            border-color: rgba(117, 52, 201, 0.5);
            box-shadow: 0 6px 16px rgba(117, 52, 201, 0.2);
        }

        /* Title */
        .feature-title {
            font-size: clamp(2.25rem, 5vw, 3.5rem);
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1.75rem;
            background: linear-gradient(180deg, #ffffff 0%, rgba(255, 255, 255, 0.85) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Description */
        .feature-description {
            font-size: clamp(1.125rem, 2.5vw, 1.375rem);
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        /* Features list */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .feature-list-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.0625rem;
            font-weight: 400;
            line-height: 1.6;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateX(-20px);
        }

        .feature-list-item.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .feature-list-item:hover {
            background: rgba(117, 52, 201, 0.08);
            border-color: rgba(117, 52, 201, 0.2);
            transform: translateX(4px);
        }

        .feature-list-item::before {
            content: '✓';
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #7534c9, #9333ea);
            color: white;
            border-radius: 6px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(117, 52, 201, 0.3);
            margin-top: 2px;
        }

        /* Image wrapper - más grande y dinámico */
        .feature-image-wrapper {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 25px 70px rgba(0, 0, 0, 0.4),
                0 10px 30px rgba(117, 52, 201, 0.2);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(60px) scale(0.95);
        }

        .feature-image-wrapper.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .feature-image-wrapper::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, rgba(117, 52, 201, 0.4), rgba(147, 51, 234, 0.4));
            border-radius: 24px;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }

        .feature-image-wrapper:hover::before {
            opacity: 1;
        }

        .feature-image-wrapper:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow:
                0 35px 90px rgba(0, 0, 0, 0.5),
                0 15px 40px rgba(117, 52, 201, 0.35);
        }

        /* Efecto parallax en la imagen */
        .feature-image {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 24px;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-image-wrapper:hover .feature-image {
            transform: scale(1.05);
        }

        /* Glow effect */
        .feature-image-wrapper::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(117, 52, 201, 0.2) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.6s ease;
            pointer-events: none;
        }

        .feature-image-wrapper:hover::after {
            opacity: 1;
        }

        /* Scroll animations */
        .feature-text-content {
            opacity: 0;
            transform: translateX(-40px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-text-content.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .feature-section-content.inverted .feature-text-content {
            transform: translateX(40px);
        }

        .feature-section-content.inverted .feature-text-content.visible {
            transform: translateX(0);
        }

        /* Stagger animation for list items */
        .feature-list-item:nth-child(1) { transition-delay: 0.1s; }
        .feature-list-item:nth-child(2) { transition-delay: 0.2s; }
        .feature-list-item:nth-child(3) { transition-delay: 0.3s; }
        .feature-list-item:nth-child(4) { transition-delay: 0.4s; }
        .feature-list-item:nth-child(5) { transition-delay: 0.5s; }
        .feature-list-item:nth-child(6) { transition-delay: 0.6s; }

        /* Responsive */
        @media (max-width: 1024px) {
            .feature-section-content {
                gap: 3rem;
            }
        }

        @media (max-width: 768px) {
            .feature-badge {
                font-size: 0.75rem;
                padding: 0.5rem 1rem;
            }

            .feature-list-item {
                font-size: 0.9375rem;
                padding: 0.625rem 0.875rem;
            }

            .feature-image-wrapper {
                border-radius: 20px;
            }
        }
    </style>

    <div class="feature-section-content {{ $inverted ? 'inverted' : '' }}">

        <!-- Text Content -->
        <div class="feature-text-content">
            @if($badge)
                <span class="feature-badge">{{ $badge }}</span>
            @endif

            <h2 class="feature-title">{{ $title }}</h2>

            @if($description)
                <p class="feature-description">{{ $description }}</p>
            @endif

            @if(count($features) > 0)
                <div class="feature-list">
                    @foreach($features as $feature)
                        <div class="feature-list-item">
                            <span>{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{ $slot }}
        </div>

        <!-- Image -->
        <div class="feature-image-wrapper">
            <img
                src="{{ asset($image) }}"
                alt="{{ $title }}"
                class="feature-image"
                loading="lazy"
            >
        </div>

    </div>
</section>

<script>
// Scroll animations para las features
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');

                // Animar elementos hijos
                const textContent = entry.target.querySelector('.feature-text-content');
                const imageWrapper = entry.target.querySelector('.feature-image-wrapper');
                const listItems = entry.target.querySelectorAll('.feature-list-item');

                if (textContent) textContent.classList.add('visible');
                if (imageWrapper) imageWrapper.classList.add('visible');
                listItems.forEach(item => item.classList.add('visible'));
            }
        });
    }, observerOptions);

    // Observar todas las secciones de features
    document.querySelectorAll('[data-feature-section]').forEach(section => {
        observer.observe(section);
    });

    // Efecto parallax suave en scroll
    let ticking = false;

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const featureImages = document.querySelectorAll('.feature-image-wrapper.visible');

                featureImages.forEach(wrapper => {
                    const rect = wrapper.getBoundingClientRect();
                    const scrollProgress = 1 - (rect.top / window.innerHeight);

                    if (scrollProgress > 0 && scrollProgress < 1) {
                        const image = wrapper.querySelector('.feature-image');
                        if (image) {
                            const translateY = (scrollProgress - 0.5) * 20;
                            image.style.transform = `translateY(${translateY}px) scale(1)`;
                        }
                    }
                });

                ticking = false;
            });

            ticking = true;
        }
    });

    // Efecto de mouse move en las imágenes
    document.querySelectorAll('.feature-image-wrapper').forEach(wrapper => {
        wrapper.addEventListener('mousemove', (e) => {
            const rect = wrapper.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;

            const rotateY = (x - 0.5) * 10;
            const rotateX = (0.5 - y) * 10;

            wrapper.style.transform = `translateY(-12px) scale(1.02) perspective(1000px) rotateY(${rotateY}deg) rotateX(${rotateX}deg)`;
        });

        wrapper.addEventListener('mouseleave', () => {
            wrapper.style.transform = '';
        });
    });
});
</script>