<section id="screenshots" class="screenshots-section">
    <style>
        /* SECCIÓN: Screenshots 3D - SIN ESPACIO VERTICAL EXTRA */
        .screenshots-section {
            position: relative;
            background: #000000;
            padding: 4rem 0 8rem 0;
            overflow: hidden;
            margin-top: -4rem;
        }

        .screenshots-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6rem;
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .screenshots-text {
            flex: 0 0 400px;
            padding-right: 2rem;
        }

        .screenshots-text h2 {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .screenshots-text .highlight {
            color: #c4b5fd;
        }

        .screenshots-text p {
            font-size: 1.25rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.7);
            transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
        }

        .screenshot-container {
            position: relative;
            flex: 1;
            height: 700px;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1200px;
            perspective-origin: 50% 30%;
        }

        .screenshot-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100px;
            bottom: 0;
            width: 300px;
            background: linear-gradient(to left, 
                rgba(139, 92, 246, 0.15) 0%,
                rgba(139, 92, 246, 0.08) 20%,
                transparent 70%);
            filter: blur(25px);
            z-index: 4;
            pointer-events: none;
        }

        .screenshot-container::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to top, 
                rgba(139, 92, 246, 0.12) 0%,
                rgba(139, 92, 246, 0.06) 30%,
                transparent 80%);
            filter: blur(30px);
            z-index: 4;
            pointer-events: none;
        }

        /* ⬅️ CONTENEDOR SIN ASPECT-RATIO FIJO */
        .screenshot {
            position: absolute;
            background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.02));
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 40px rgba(0,0,0,0.35), 0 20px 60px rgba(88, 28, 135, 0.25);
            border-radius: 16px;
            overflow: hidden;
            width: 850px;
            /* Sin aspect-ratio - la imagen define el alto */
            display: flex;
            flex-direction: column;
            transform-style: preserve-3d;
            transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* ESTADOS INICIALES */
        .s1, .s2, .s3 {
            opacity: 0;
            filter: blur(15px) brightness(0.5);
        }

        .s1 {
            left: 50%;
            top: 50%;
            transform: 
                translate(-50%, -50%) 
                translateX(400px) 
                translateZ(-300px)
                rotateX(10deg) 
                rotateY(-35deg) 
                scale(0.6);
            z-index: 1;
            transition-delay: 0.4s; /* ⬅️ Última en entrar (más lejana) */
        }

        .s2 {
            left: 50%;
            top: 50%;
            transform: 
                translate(-50%, -50%) 
                translateX(200px) 
                translateZ(-200px)
                rotateX(12deg) 
                rotateY(-25deg) 
                scale(0.7);
            z-index: 2;
            transition-delay: 0.2s; /* ⬅️ Segunda en entrar (media) */
        }

        .s3 {
            left: 50%;
            top: 50%;
            transform: 
                translate(-50%, -50%) 
                translateX(0px) 
                translateZ(-100px)
                rotateX(12deg) 
                rotateY(-15deg) 
                scale(0.8);
            z-index: 3;
            transition-delay: 0s; /* ⬅️ Primera en entrar (más cercana) */
        }

        /* ESTADO VISIBLE */
        .screenshots-section.visible .s1 {
            transform: 
                translate(-50%, -50%) 
                translateX(280px) 
                translateZ(-150px)
                rotateX(15deg) 
                rotateY(-25deg) 
                scale(0.8);
            opacity: 0.7;
            filter: brightness(0.8); /* ⬅️ REMOVIDO blur(1px) */
            transition-delay: 0s; /* ⬅️ CAMBIADO: Primera en llegar */
        }

        .screenshots-section.visible .s2 {
            transform: 
                translate(-50%, -50%) 
                translateX(80px) 
                translateZ(-50px)
                rotateX(15deg) 
                rotateY(-15deg) 
                scale(0.9);
            opacity: 0.85;
            filter: brightness(0.9); /* ⬅️ REMOVIDO blur(0.5px) */
            transition-delay: 0.2s; /* ⬅️ Segunda */
        }

        .screenshots-section.visible .s3 {
            transform: 
                translate(-50%, -50%) 
                translateX(-120px) 
                translateZ(50px)
                rotateX(15deg) 
                rotateY(-5deg) 
                scale(1);
            opacity: 1;
            filter: brightness(1); /* ⬅️ YA NO tenía blur, pero explícito */
            transition-delay: 0.4s; /* ⬅️ CAMBIADO: Última en llegar (frontal) */
        }

        .screenshots-section.visible .screenshots-text h2,
        .screenshots-section.visible .screenshots-text p {
            opacity: 1;
            transform: translateX(0);
            filter: blur(0px);
        }

        /* ESTADO EXIT */
        .screenshots-section.exit .s1,
        .screenshots-section.exit .s2,
        .screenshots-section.exit .s3 {
            opacity: 0;
            filter: blur(15px) brightness(0.5);
        }

        .screenshots-section.exit .s1 {
            transform: 
                translate(-50%, -50%) 
                translateX(400px) 
                translateZ(-300px)
                rotateX(10deg) 
                rotateY(-35deg) 
                scale(0.6);
            transition-delay: 0s; /* ⬅️ CAMBIADO: Primera en salir (fondo) */
        }

        .screenshots-section.exit .s2 {
            transform: 
                translate(-50%, -50%) 
                translateX(200px) 
                translateZ(-200px)
                rotateX(12deg) 
                rotateY(-25deg) 
                scale(0.7);
            transition-delay: 0.2s; /* ⬅️ Segunda en salir */
        }

        .screenshots-section.exit .s3 {
            transform: 
                translate(-50%, -50%) 
                translateX(0px) 
                translateZ(-100px)
                rotateX(12deg) 
                rotateY(-15deg) 
                scale(0.8);
            transition-delay: 0.4s; /* ⬅️ CAMBIADO: Última en salir (frontal) */
        }

        .screenshots-section.exit .screenshots-text h2,
        .screenshots-section.exit .screenshots-text p {
            opacity: 0;
            transform: translateX(-30px);
            filter: blur(8px);
        }

        /* Chrome bar */
        .chrome-bar { 
            height: 32px; 
            background: rgba(255,255,255,0.06); 
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0 0.75rem;
            flex-shrink: 0;
        }

        .dot { 
            width: 10px; 
            height: 10px; 
            border-radius: 50%;
        }

        .dot-red { background: rgba(239, 68, 68, 0.7); }
        .dot-yellow { background: rgba(250, 204, 21, 0.7); }
        .dot-green { background: rgba(34, 197, 94, 0.7); }

        /* ⬅️ IMAGEN CON ALTURA AUTOMÁTICA - ALTA CALIDAD */
        .screenshot img {
            width: 100%;
            height: auto;
            object-fit: contain;
            object-position: center;
            background: #0a0a0a;
            display: block;
            image-rendering: -webkit-optimize-contrast; /* ⬅️ Mejor calidad en webkit */
            image-rendering: crisp-edges; /* ⬅️ Bordes nítidos */
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .screenshots-content {
                flex-direction: column;
                gap: 3rem;
            }

            .screenshots-text {
                flex: none;
                padding-right: 0;
                text-align: center;
            }

            .screenshots-text h2 {
                font-size: 2rem;
            }

            .screenshots-text p {
                font-size: 1.125rem;
            }

            .screenshot-container {
                height: 500px;
                perspective: 1000px;
                width: 100%;
                padding: 0 1rem;
            }

            .screenshot {
                width: min(550px, 90vw);
            }

            /* Ajustar posiciones para móvil */
            .s1 {
                transform:
                    translate(-50%, -50%)
                    translateX(200px)
                    translateZ(-200px)
                    rotateX(8deg)
                    rotateY(-30deg)
                    scale(0.5);
            }

            .s2 {
                transform:
                    translate(-50%, -50%)
                    translateX(100px)
                    translateZ(-100px)
                    rotateX(10deg)
                    rotateY(-20deg)
                    scale(0.65);
            }

            .s3 {
                transform:
                    translate(-50%, -50%)
                    translateX(0px)
                    translateZ(-50px)
                    rotateX(10deg)
                    rotateY(-10deg)
                    scale(0.75);
            }

            .screenshots-section.visible .s1 {
                transform:
                    translate(-50%, -50%)
                    translateX(150px)
                    translateZ(-100px)
                    rotateX(12deg)
                    rotateY(-20deg)
                    scale(0.7);
            }

            .screenshots-section.visible .s2 {
                transform:
                    translate(-50%, -50%)
                    translateX(50px)
                    translateZ(-30px)
                    rotateX(12deg)
                    rotateY(-12deg)
                    scale(0.85);
            }

            .screenshots-section.visible .s3 {
                transform:
                    translate(-50%, -50%)
                    translateX(-80px)
                    translateZ(30px)
                    rotateX(12deg)
                    rotateY(-5deg)
                    scale(0.95);
            }
        }

        @media (max-width: 640px) {
            .screenshots-section {
                padding: 3rem 0 6rem 0;
            }

            .screenshot-container {
                height: 400px;
                padding: 0 0.5rem;
            }

            .screenshot {
                width: min(400px, 95vw);
            }

            .screenshots-text h2 {
                font-size: 1.75rem;
            }

            .screenshots-text p {
                font-size: 1rem;
            }

            /* Posiciones más compactas para móvil pequeño */
            .screenshots-section.visible .s1 {
                transform:
                    translate(-50%, -50%)
                    translateX(120px)
                    translateZ(-80px)
                    rotateX(10deg)
                    rotateY(-18deg)
                    scale(0.6);
            }

            .screenshots-section.visible .s2 {
                transform:
                    translate(-50%, -50%)
                    translateX(40px)
                    translateZ(-20px)
                    rotateX(10deg)
                    rotateY(-10deg)
                    scale(0.75);
            }

            .screenshots-section.visible .s3 {
                transform:
                    translate(-50%, -50%)
                    translateX(-60px)
                    translateZ(20px)
                    rotateX(10deg)
                    rotateY(-3deg)
                    scale(0.85);
            }
        }
    </style>

    <div class="screenshots-content">
        <!-- Texto -->
        <div class="screenshots-text">
            <h2>
                Interfaz simple y facil.
            </h2>
            <p>
                Diseñada para la velocidad. Cada pantalla optimizada para que tu equipo trabaje sin fricciones.
            </p>
        </div>

        <!-- Screenshots -->
        <div class="screenshot-container">
            <div class="screenshot s1">
                <div class="chrome-bar">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <img src="{{ asset('images/screenshots/crear-pedido.png') }}" 
                     alt="Crear pedido - Gestior" 
                     loading="lazy"
                     decoding="async"
                     fetchpriority="high" />
            </div>

            <div class="screenshot s2">
                <div class="chrome-bar">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <img src="{{ asset('images/screenshots/stock.png') }}" 
                     alt="Control de stock - Gestior" 
                     loading="lazy"
                     decoding="async"
                     fetchpriority="high" />
            </div>

            <div class="screenshot s3">
                <div class="chrome-bar">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <img src="{{ asset('images/screenshots/historial.png') }}" 
                     alt="Historial - Gestior" 
                     loading="lazy"
                     decoding="async"
                     fetchpriority="high" />
            </div>
        </div>
    </div>

    <script>
        const section = document.querySelector('.screenshots-section');
        
        function updateAnimation() {
            const rect = section.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const sectionTop = rect.top;
            const sectionHeight = rect.height;
            const sectionCenter = sectionTop + sectionHeight / 2;
            const distanceFromCenter = Math.abs(windowHeight / 2 - sectionCenter);
            
            if (distanceFromCenter < windowHeight * 0.4) {
                section.classList.add('visible');
                section.classList.remove('exit');
            } 
            else if (sectionTop < -sectionHeight * 0.3) {
                section.classList.remove('visible');
                section.classList.add('exit');
            }
            else if (sectionTop > windowHeight * 0.8) {
                section.classList.remove('visible');
                section.classList.add('exit');
            }
            else {
                section.classList.remove('visible', 'exit');
            }
        }
        
        window.addEventListener('scroll', updateAnimation);
        window.addEventListener('resize', updateAnimation);
        document.addEventListener('DOMContentLoaded', updateAnimation);
        setTimeout(updateAnimation, 100);
    </script>
</section>