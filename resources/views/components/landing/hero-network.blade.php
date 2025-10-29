<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Network Component - Professional Icons</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden; 
            background: transparent; 
            color: white;
        }

        /* FONDO PRINCIPAL CON DEGRADADO MÁS PEQUEÑO */
        .hero-bg {
            background: transparent;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* PARTÍCULAS DE FONDO ANIMADAS */
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

        /* PRIMERA SECCIÓN: Logo + Módulos */
        .hero-intro-section {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 2rem 2rem;
        }

        .hero-intro-container {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 4rem;
            align-items: center;
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
        }

        /* Logo central */
        .intro-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            height: 480px;
            width: 100%;
            margin-left: 2rem;
            margin-top: -2rem;
        }

        /* Canvas para conexiones */
        .network-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        /* CONTENEDOR DEL LOGO */
        .logo-container {
            position: relative;
            width: 280px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: 50px;
            z-index: 20;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid transparent;
        }

        .logo-text {
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #7c3aed, #8b5cf6, #a78bfa);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 20px rgba(124, 58, 237, 0.8));
            letter-spacing: -1px;
        }

        /* Módulos - ICONOS PROFESIONALES */
        .module-icon {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            z-index: 10;
            opacity: 0;
            cursor: pointer;
            padding: 0;
            background: transparent !important;
            border: none !important;
            backdrop-filter: none !important;
            box-shadow: none !important;
            transition: all 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .module-icon svg {
            width: 40px;
            height: 40px;
            stroke: #a78bfa;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: all 0.4s ease;
            filter: drop-shadow(0 0 8px rgba(167, 139, 250, 0.6));
        }

        .module-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 600;
            text-align: center;
            letter-spacing: 0.3px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
            background: rgba(0, 0, 0, 0.7);
            padding: 3px 6px;
            border-radius: 4px;
            backdrop-filter: blur(10px);
            white-space: nowrap;
        }

        /* POSICIONES INICIALES */
        .node-productos { top: -180px; left: 50%; transform: translate(-50%, 0); }
        .node-stock { top: -130px; right: -120px; }
        .node-servicios { top: 50px; right: -140px; }
        .node-clientes { bottom: -130px; right: -120px; }
        .node-gastos { bottom: -180px; left: 50%; transform: translateX(-50%); }
        .node-pedidos { bottom: -130px; left: -120px; }
        .node-sucursales { top: 50px; left: -140px; }
        .node-empleados { top: -130px; left: -120px; }
        .node-tablas { top: 50%; right: -160px; transform: translateY(-50%); }

        /* POSICIONES FINALES */
        .node-productos.attracted { top: -70px; left: 50%; transform: translate(-50%, 0); opacity: 1; }
        .node-stock.attracted { top: -90px; right: -60px; opacity: 1; }
        .node-servicios.attracted { top: 30px; right: -130px; opacity: 1; }
        .node-clientes.attracted { bottom: -40px; right: -60px; opacity: 1; }
        .node-gastos.attracted { bottom: -70px; left: 50%; transform: translateX(-50%); opacity: 1; }
        .node-pedidos.attracted { bottom: -20px; left: -40px; opacity: 1; }
        .node-sucursales.attracted { top: 80px; left: -50px; opacity: 1; }
        .node-empleados.attracted { top: -80px; left: -40px; opacity: 1; }
        .node-tablas.attracted { top: 60%; right: -160px; transform: translateY(-50%); opacity: 1; }

        /* ANIMACIÓN DE SALIDA */
        .hero-intro-section.exit .node-productos.attracted { top: -180px; opacity: 0; }
        .hero-intro-section.exit .node-stock.attracted { top: -130px; right: -120px; opacity: 0; }
        .hero-intro-section.exit .node-servicios.attracted { top: 50px; right: -140px; opacity: 0; }
        .hero-intro-section.exit .node-clientes.attracted { bottom: -130px; right: -120px; opacity: 0; }
        .hero-intro-section.exit .node-gastos.attracted { bottom: -180px; opacity: 0; }
        .hero-intro-section.exit .node-pedidos.attracted { bottom: -130px; left: -120px; opacity: 0; }
        .hero-intro-section.exit .node-sucursales.attracted { top: 50px; left: -140px; opacity: 0; }
        .hero-intro-section.exit .node-empleados.attracted { top: -130px; left: -120px; opacity: 0; }
        .hero-intro-section.exit .node-tablas.attracted { top: 50%; right: -160px; opacity: 0; }

        /* Texto */
        .intro-text {
            text-align: right;
            padding-left: 2rem;
            padding-right: 0;
        }

        .intro-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            color: white;
            transition: all 1s ease-out;
        }

        .intro-text h1 .text-violet-300 {
            background: linear-gradient(135deg, #a78bfa, #c4b5fd);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .intro-text p {
            font-size: 1.25rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2.5rem;
            font-weight: 400;
            transition: all 1s ease-out 0.2s;
        }

        .intro-buttons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 1s ease-out 0.4s;
            justify-content: flex-end;
        }

        .hero-intro-section.exit .intro-text h1,
        .hero-intro-section.exit .intro-text p,
        .hero-intro-section.exit .intro-buttons {
            opacity: 0;
            transform: translateY(-30px);
        }

        .intro-buttons a {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.125rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }

        .intro-buttons a:first-child {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            box-shadow: 0 8px 32px rgba(124, 58, 237, 0.4);
        }

        .intro-buttons a:first-child:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 12px 40px rgba(124, 58, 237, 0.6);
        }

        .intro-buttons a:last-child {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .intro-buttons a:last-child:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Efectos hover */
        .module-icon:hover svg {
            stroke: #c4b5fd;
            transform: scale(1.15);
            filter: drop-shadow(0 0 15px rgba(167, 139, 250, 0.9));
        }

        .module-icon:hover .module-label {
            background: rgba(0, 0, 0, 0.9);
            color: #c4b5fd;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .hero-intro-container { grid-template-columns: 1fr; gap: 4rem; }
            .intro-logo { height: 400px; margin-left: 0; margin-top: 0; }
            .logo-container { width: 220px; height: 80px; }
            .logo-text { font-size: 2.8rem; }
            .module-icon svg { width: 36px; height: 36px; }
            .intro-text { text-align: center; padding-left: 0; }
            .intro-buttons { justify-content: center; }
        }

        @media (max-width: 768px) {
            .hero-intro-section { padding: 3rem 1rem; }
            .intro-logo { height: 350px; }
            .logo-container { width: 180px; height: 65px; }
            .logo-text { font-size: 2.2rem; }
            .module-icon svg { width: 30px; height: 30px; }
            .module-label { font-size: 0.65rem; padding: 2px 4px; }
            .intro-text h1 { font-size: 2.5rem; }
            .intro-text p { font-size: 1.125rem; }
            .intro-buttons { flex-direction: column; width: 100%; }
            .intro-buttons a { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="hero-bg">
        <div class="particles" id="particles"></div>

        <section class="hero-intro-section">
            <div class="hero-intro-container">
                <div class="intro-logo">
                    <canvas class="network-canvas" id="networkCanvas"></canvas>
                    
                    <div class="logo-container" id="logoElement">
                        <div class="logo-text">Gestior</div>
                    </div>
                    
                    <!-- PRODUCTOS - Caja/Paquete -->
                    <div class="module-icon node-productos" data-module="productos">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                        <span class="module-label">Productos</span>
                    </div>
                    
                    <!-- STOCK - Cajas apiladas -->
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
                    
                    <!-- SERVICIOS - Herramientas -->
                    <div class="module-icon node-servicios" data-module="servicios">
                        <svg viewBox="0 0 24 24">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                        <span class="module-label">Servicios</span>
                    </div>
                    
                    <!-- CLIENTES - Usuarios múltiples -->
                    <div class="module-icon node-clientes" data-module="clientes">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span class="module-label">Clientes</span>
                    </div>
                    
                    <!-- GASTOS - Tarjeta de crédito -->
                    <div class="module-icon node-gastos" data-module="gastos">
                        <svg viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        <span class="module-label">Gastos</span>
                    </div>
                    
                    <!-- PEDIDOS - Carrito de compras -->
                    <div class="module-icon node-pedidos" data-module="pedidos">
                        <svg viewBox="0 0 24 24">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <span class="module-label">Pedidos</span>
                    </div>
                    
                    <!-- SUCURSALES - Edificio/Tienda -->
                    <div class="module-icon node-sucursales" data-module="sucursales">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <span class="module-label">Sucursales</span>
                    </div>
                    
                    <!-- EMPLEADOS - Usuario único -->
                    <div class="module-icon node-empleados" data-module="empleados">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span class="module-label">Empleados</span>
                    </div>
                    
                    <!-- TABLAS - Base de datos -->
                    <div class="module-icon node-tablas" data-module="tablas">
                        <svg viewBox="0 0 24 24">
                            <ellipse cx="12" cy="5" rx="9" ry="3"/>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                        </svg>
                        <span class="module-label">Tablas</span>
                    </div>
                </div>
                
                <div class="intro-text">
                    <h1>
                        Una mirada
                    </h1>
                    <h1>
                        <span class="text-violet-300">Todo bajo control</span>
                    </h1>
                    <p>
                        Cada área de tu negocio, un solo panel.  
                    </p>
                    <div class="intro-buttons">
                        <a href="#register">Comienza hoy</a>
                        <a href="#product">Ver producto</a>
                    </div>
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

        const gestiorCanvas = document.getElementById('networkCanvas');
        const gestiorCtx = gestiorCanvas.getContext('2d');
        const gestiorLogo = document.getElementById('logoElement');
        const gestiorModules = document.querySelectorAll('.module-icon');
        const gestiorSection = document.querySelector('.hero-intro-section');

        function resizeGestiorCanvas() {
            const introLogo = document.querySelector('.intro-logo');
            gestiorCanvas.width = introLogo.offsetWidth;
            gestiorCanvas.height = introLogo.offsetHeight;
        }
        resizeGestiorCanvas();
        window.addEventListener('resize', resizeGestiorCanvas);

        function getGestiorPosition(element) {
            const sectionRect = document.querySelector('.intro-logo').getBoundingClientRect();
            const rect = element.getBoundingClientRect();
            return {
                x: rect.left - sectionRect.left + rect.width / 2,
                y: rect.top - sectionRect.top + rect.height / 2
            };
        }

        setTimeout(() => {
            gestiorModules.forEach((module, i) => {
                setTimeout(() => {
                    module.classList.add('attracted');
                }, i * 150);
            });
        }, 600);

        function drawGestiorConnections() {
            gestiorCtx.clearRect(0, 0, gestiorCanvas.width, gestiorCanvas.height);
            
            const logoPos = getGestiorPosition(gestiorLogo);
            
            gestiorModules.forEach(module => {
                if (module.classList.contains('attracted') && !gestiorSection.classList.contains('exit')) {
                    const modulePos = getGestiorPosition(module);
                    const opacity = parseFloat(window.getComputedStyle(module).opacity);
                    
                    const dx = modulePos.x - logoPos.x;
                    const dy = modulePos.y - logoPos.y;
                    const angle = Math.atan2(dy, dx);
                    
                    const logoConnectionX = logoPos.x + Math.cos(angle) * 60;
                    const logoConnectionY = logoPos.y + Math.sin(angle) * 60;
                    
                    const mainGradient = gestiorCtx.createLinearGradient(
                        logoConnectionX, logoConnectionY,
                        modulePos.x, modulePos.y
                    );
                    mainGradient.addColorStop(0, `rgba(255, 255, 255, ${0.6 * opacity})`);
                    mainGradient.addColorStop(0.5, `rgba(255, 255, 255, ${0.4 * opacity})`);
                    mainGradient.addColorStop(1, `rgba(255, 255, 255, ${0.2 * opacity})`);
                    
                    gestiorCtx.beginPath();
                    gestiorCtx.moveTo(logoConnectionX, logoConnectionY);
                    gestiorCtx.lineTo(modulePos.x, modulePos.y);
                    gestiorCtx.strokeStyle = mainGradient;
                    gestiorCtx.lineWidth = 1.5;
                    gestiorCtx.lineCap = 'round';
                    gestiorCtx.stroke();
                    
                    const midX = (logoConnectionX + modulePos.x) / 2;
                    const midY = (logoConnectionY + modulePos.y) / 2;
                    
                    const dotGradient = gestiorCtx.createRadialGradient(midX, midY, 0, midX, midY, 3);
                    dotGradient.addColorStop(0, `rgba(199, 210, 254, ${0.5 * opacity})`);
                    dotGradient.addColorStop(1, 'rgba(199, 210, 254, 0)');
                    
                    gestiorCtx.beginPath();
                    gestiorCtx.arc(midX, midY, 3, 0, Math.PI * 2);
                    gestiorCtx.fillStyle = dotGradient;
                    gestiorCtx.fill();
                }
            });
            
            requestAnimationFrame(drawGestiorConnections);
        }

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
                module.querySelector('svg').style.filter = 'drop-shadow(0 0 15px rgba(167, 139, 250, 0.9))';
                module.querySelector('.module-label').style.background = 'rgba(0, 0, 0, 0.9)';
                module.querySelector('.module-label').style.color = '#c4b5fd';
            });

            module.addEventListener('mouseleave', () => {
                module.querySelector('svg').style.filter = 'drop-shadow(0 0 8px rgba(167, 139, 250, 0.6))';
                module.querySelector('.module-label').style.background = 'rgba(0, 0, 0, 0.7)';
                module.querySelector('.module-label').style.color = 'rgba(255, 255, 255, 0.95)';
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            drawGestiorConnections();
            updateGestiorAnimation();
        });

        window.addEventListener('scroll', updateGestiorAnimation);
        window.addEventListener('resize', updateGestiorAnimation);
    </script>
</body>
</html>
