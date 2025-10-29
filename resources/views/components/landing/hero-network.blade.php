<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Network - Larger Icons No Lines</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden; 
            background: #000000;
            color: white;
        }

        /* FONDO PRINCIPAL */
        .hero-bg {
            background: #000000;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* PARTÍCULAS */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(167, 139, 250, 0.3);
            border-radius: 50%;
            animation: float 20s infinite;
        }

        @keyframes float {
            0%, 100% { 
                transform: translate(0, 0) scale(1);
                opacity: 0.3;
            }
            50% { 
                transform: translate(50px, -50px) scale(1.2);
                opacity: 0.6;
            }
        }

        /* SECCIÓN HERO */
        .hero-intro-section {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
        }

        /* RED */
        .intro-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            width: 100%;
            max-width: 900px;
            height: 500px;
        }

        /* SLOGAN EN EL CENTRO - CONTENEDOR OVALADO INVISIBLE */
        .slogan-container {
            position: relative;
            z-index: 20;
            text-align: center;
            max-width: 700px;
            padding: 3rem 4rem;
            background: rgba(0, 0, 0, 0.98);
            border-radius: 50%;
            border: none;
            backdrop-filter: blur(20px);
            box-shadow: 
                0 0 100px rgba(0, 0, 0, 0.9),
                inset 0 0 60px rgba(0, 0, 0, 0.5);
        }

        .slogan-container h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            color: white;
            transition: all 1s ease-out;
        }

        .slogan-container h1 .highlight {
            background: linear-gradient(135deg, #a78bfa, #c4b5fd, #ddd6fe);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 30px rgba(167, 139, 250, 0.6));
        }

        .slogan-container p {
            font-size: 1.5rem;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
            transition: all 1s ease-out 0.2s;
        }

        /* Módulos - ICONOS MÁS GRANDES */
        .module-icon {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            z-index: 10;
            opacity: 0;
            cursor: pointer;
            padding: 0;
            background: transparent !important;
            border: none !important;
            backdrop-filter: none !important;
            box-shadow: none !important;
            transition: all 1.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .module-icon svg {
            width: 56px; /* ⬅️ Aumentado de 48px */
            height: 56px;
            stroke: #a78bfa;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: all 0.4s ease;
            filter: drop-shadow(0 0 12px rgba(167, 139, 250, 0.7));
        }

        .module-label {
            font-size: 0.95rem; /* ⬅️ Aumentado de 0.85rem */
            color: rgba(255, 255, 255, 0.95);
            font-weight: 600;
            text-align: center;
            letter-spacing: 0.3px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
            background: rgba(0, 0, 0, 0.7);
            padding: 6px 12px; /* ⬅️ Más padding */
            border-radius: 6px;
            backdrop-filter: blur(10px);
            white-space: nowrap;
        }

        /* POSICIONES INICIALES */
        .node-productos { top: -200px; left: 50%; transform: translate(-50%, 0); }
        .node-stock { top: -140px; right: -140px; }
        .node-servicios { top: 60px; right: -150px; }
        .node-clientes { bottom: -140px; right: -140px; }
        .node-gastos { bottom: -200px; left: 50%; transform: translateX(-50%); }
        .node-pedidos { bottom: -140px; left: -140px; }
        .node-sucursales { top: 60px; left: -150px; }
        .node-empleados { top: -140px; left: -140px; }
        .node-tablas { top: 50%; right: -170px; transform: translateY(-50%); }

        /* POSICIONES FINALES - MUY CERCA */
        .node-productos.attracted { top: -35px; left: 50%; transform: translate(-50%, 0); opacity: 1; }
        .node-stock.attracted { top: -45px; right: 45px; opacity: 1; }
        .node-servicios.attracted { top: 10px; right: -60px; opacity: 1; }
        .node-clientes.attracted { bottom: -15px; right: -10px; opacity: 1; }
        .node-gastos.attracted { bottom: -35px; left: 50%; transform: translateX(-50%); opacity: 1; }
        .node-pedidos.attracted { bottom: 5px; left: -5px; opacity: 1; }
        .node-sucursales.attracted { top: 45px; left: -10px; opacity: 1; }
        .node-empleados.attracted { top: -75px; left: 120px; opacity: 1; }
        .node-tablas.attracted { top: 60%; right: -90px; transform: translateY(-50%); opacity: 1; }

        /* ANIMACIÓN DE SALIDA */
        .hero-intro-section.exit .slogan-container h1,
        .hero-intro-section.exit .slogan-container p {
            opacity: 0;
            transform: scale(0.9);
        }

        .hero-intro-section.exit .node-productos.attracted { top: -200px; opacity: 0; }
        .hero-intro-section.exit .node-stock.attracted { top: -140px; right: -140px; opacity: 0; }
        .hero-intro-section.exit .node-servicios.attracted { top: 60px; right: -150px; opacity: 0; }
        .hero-intro-section.exit .node-clientes.attracted { bottom: -140px; right: -140px; opacity: 0; }
        .hero-intro-section.exit .node-gastos.attracted { bottom: -200px; opacity: 0; }
        .hero-intro-section.exit .node-pedidos.attracted { bottom: -140px; left: -140px; opacity: 0; }
        .hero-intro-section.exit .node-sucursales.attracted { top: 60px; left: -150px; opacity: 0; }
        .hero-intro-section.exit .node-empleados.attracted { top: -140px; left: -140px; opacity: 0; }
        .hero-intro-section.exit .node-tablas.attracted { top: 50%; right: -170px; opacity: 0; }

        /* Efectos hover */
        .module-icon:hover svg {
            stroke: #c4b5fd;
            transform: scale(1.25); /* ⬅️ Más grande en hover */
            filter: drop-shadow(0 0 25px rgba(167, 139, 250, 1));
        }

        .module-icon:hover .module-label {
            background: rgba(0, 0, 0, 0.9);
            color: #c4b5fd;
            transform: scale(1.08);
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .intro-logo {
                max-width: 1000px;
                height: 550px;
            }
            .slogan-container h1 { font-size: 4rem; }
        }

        @media (max-width: 1200px) {
            .intro-logo {
                max-width: 850px;
                height: 500px;
            }
            .slogan-container h1 { font-size: 3.5rem; }
            .slogan-container p { font-size: 1.375rem; }
        }

        @media (max-width: 900px) {
            .intro-logo {
                max-width: 700px;
                height: 450px;
            }
            .slogan-container h1 { font-size: 3rem; }
            .slogan-container p { font-size: 1.25rem; }
            .module-icon svg { width: 48px; height: 48px; }
        }

        @media (max-width: 768px) {
            .hero-intro-section { padding: 3rem 1rem; }
            .intro-logo { max-width: 600px; height: 450px; }
            .slogan-container { padding: 1.5rem; }
            .slogan-container h1 { font-size: 2.5rem; }
            .slogan-container p { font-size: 1.125rem; }
            .module-icon svg { width: 42px; height: 42px; }
            .module-label { font-size: 0.8rem; padding: 5px 10px; }
        }

        @media (max-width: 480px) {
            .intro-logo { height: 400px; max-width: 500px; }
            .slogan-container h1 { font-size: 2rem; }
            .slogan-container p { font-size: 1rem; }
            .module-icon svg { width: 38px; height: 38px; }
            .module-label { font-size: 0.75rem; }
        }
    </style>
</head>
<body>
    <div class="hero-bg">
        <div class="particles" id="particles"></div>

        <section class="hero-intro-section">

            <!-- RED CON SLOGAN EN EL CENTRO -->
            <div class="intro-logo">
                
                <!-- SLOGAN EN EL CENTRO -->
                <div class="slogan-container" id="sloganElement">
                    <h1>
                        Una mirada<br>
                        <span class="highlight">Todo bajo control</span>
                    </h1>
                    <p>
                        Cada área de tu negocio, un solo panel.
                    </p>
                </div>
                
                <!-- PRODUCTOS -->
                <div class="module-icon node-productos" data-module="productos">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                    <span class="module-label">Productos</span>
                </div>
                
                <!-- STOCK -->
                <div class="module-icon node-stock" data-module="stock">
                    <svg viewBox="0 0 24 24">
                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/>
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="7.5 4.21 12 6.81 16.5 4.21"/>
                        <polyline points="7.5 19.79 7.5 14.6 3 12"/>
                        <polyline points="21 12 16.5 14.6 16.5 19.79"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                    <span class="module-label">Stock</span>
                </div>
                
                <!-- SERVICIOS -->
                <div class="module-icon node-servicios" data-module="servicios">
                    <svg viewBox="0 0 24 24">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    <span class="module-label">Servicios</span>
                </div>
                
                <!-- CLIENTES -->
                <div class="module-icon node-clientes" data-module="clientes">
                    <svg viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="module-label">Clientes</span>
                </div>
                
                <!-- GASTOS -->
                <div class="module-icon node-gastos" data-module="gastos">
                    <svg viewBox="0 0 24 24">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    <span class="module-label">Gastos</span>
                </div>
                
                <!-- PEDIDOS -->
                <div class="module-icon node-pedidos" data-module="pedidos">
                    <svg viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span class="module-label">Pedidos</span>
                </div>
                
                <!-- SUCURSALES -->
                <div class="module-icon node-sucursales" data-module="sucursales">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span class="module-label">Sucursales</span>
                </div>
                
                <!-- EMPLEADOS -->
                <div class="module-icon node-empleados" data-module="empleados">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span class="module-label">Empleados</span>
                </div>
                
                <!-- TABLAS -->
                <div class="module-icon node-tablas" data-module="tablas">
                    <svg viewBox="0 0 24 24">
                        <ellipse cx="12" cy="5" rx="9" ry="3"/>
                        <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                    </svg>
                    <span class="module-label">Tablas</span>
                </div>
            </div>

        </section>
    </div>

    <script>
        const particlesContainer = document.getElementById('particles');

        function createParticles() {
            for (let i = 0; i < 30; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.width = Math.random() * 4 + 1 + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        createParticles();

        const gestiorModules = document.querySelectorAll('.module-icon');
        const gestiorSection = document.querySelector('.hero-intro-section');

        setTimeout(() => {
            gestiorModules.forEach((module, i) => {
                setTimeout(() => {
                    module.classList.add('attracted');
                }, i * 150);
            });
        }, 600);

        function updateGestiorAnimation() {
            const rect = gestiorSection.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const sectionTop = rect.top;
            const sectionHeight = rect.height;
            
            if (sectionTop < -sectionHeight * 0.3) {
                gestiorSection.classList.add('exit');
            } else if (sectionTop > windowHeight * 0.8) {
                gestiorSection.classList.add('exit');
            } else {
                gestiorSection.classList.remove('exit');
            }
        }

        gestiorModules.forEach(module => {
            module.addEventListener('mouseenter', () => {
                module.querySelector('svg').style.filter = 'drop-shadow(0 0 25px rgba(167, 139, 250, 1))';
                module.querySelector('.module-label').style.background = 'rgba(0, 0, 0, 0.9)';
                module.querySelector('.module-label').style.color = '#c4b5fd';
            });

            module.addEventListener('mouseleave', () => {
                module.querySelector('svg').style.filter = 'drop-shadow(0 0 12px rgba(167, 139, 250, 0.7))';
                module.querySelector('.module-label').style.background = 'rgba(0, 0, 0, 0.7)';
                module.querySelector('.module-label').style.color = 'rgba(255, 255, 255, 0.95)';
            });
        });

        document.addEventListener('DOMContentLoaded', updateGestiorAnimation);

        window.addEventListener('scroll', updateGestiorAnimation);
        window.addEventListener('resize', updateGestiorAnimation);
    </script>
</body>
</html>