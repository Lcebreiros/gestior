<div class="overlay-mockup-component">
    <style>
        .overlay-mockup-component * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .overlay-mockup-component {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 5rem 0;
        }

        /* Contenedor principal MÁS ANCHO */
        .mockup-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3rem;
            align-items: center;
            max-width: 1500px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        @media (min-width: 1024px) {
            .mockup-container {
                grid-template-columns: 1fr 1.2fr;
                gap: 4rem;
            }
        }

        /* Texto descriptivo - ESTILO LINEAR */
        .mockup-text {
            text-align: center;
            opacity: 0;
            transform: translateY(30px);
            transition: all 1s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        @media (min-width: 1024px) {
            .mockup-text {
                text-align: left;
                transform: translateX(-40px) translateY(15px);
            }
        }

        .mockup-text.visible {
            opacity: 1;
            transform: translateX(0) translateY(0);
        }

        /* Título minimalista - Sin gradientes innecesarios */
        .mockup-title {
            font-size: 2.5rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        @media (min-width: 768px) {
            .mockup-title {
                font-size: 3.5rem;
            }
        }

        /* Descripción más conversacional */
        .mockup-description {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
            margin-bottom: 2.5rem;
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        /* Lista minimalista - SIN iconos */
        .features-list {
            display: flex;
            flex-direction: column;
            gap: 0.875rem;
            max-width: 380px;
            margin: 0 auto;
        }

        @media (min-width: 1024px) {
            .features-list {
                margin: 0;
            }
        }

        .feature-item {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.95rem;
            opacity: 0;
            transform: translateX(-15px);
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            letter-spacing: -0.01em;
            font-weight: 400;
        }

        /* Bullet minimalista */
        .feature-item::before {
            content: '';
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .mockup-text.visible .feature-item:nth-child(1) {
            transition-delay: 0.2s;
        }
        .mockup-text.visible .feature-item:nth-child(2) {
            transition-delay: 0.3s;
        }
        .mockup-text.visible .feature-item:nth-child(3) {
            transition-delay: 0.4s;
        }

        .mockup-text.visible .feature-item {
            opacity: 1;
            transform: translateX(0);
        }

        /* Contenedor de imágenes */
        .images-container {
            position: relative;
            width: 100%;
            height: 650px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 650px;
        }

        /* COMPUTADORA */
        .mockup-image.computer {
            transform: translateX(-100px) scale(0.9);
            max-width: 860px !important;
            width: 100%;
            z-index: 1;
            filter: brightness(0.95);
            transition-delay: 0.1s;
            opacity: 0;
            transition: all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .mockup-image.computer.visible {
            opacity: 1;
            transform: translateX(200px) scale(1.1);
        }

        /* CELULAR */
        .mockup-image.phone {
            transform: translateX(100px) scale(0.8);
            max-width: 600px !important;
            width: 100%;
            z-index: 2;
            filter: drop-shadow(0 12px 30px rgba(0, 0, 0, 0.4));
            transition-delay: 0.3s;
            opacity: 0;
            transition: all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .mockup-image.phone.visible {
            opacity: 1;
            transform: translateX(-180px) translateY(100px) scale(0.95);
        }

        /* Efecto de profundidad al hover */
        .mockup-image.phone.visible:hover {
            transform: translateX(-180px) translateY(100px) scale(1.0);
            filter: drop-shadow(0 18px 40px rgba(0, 0, 0, 0.5));
        }

        /* Responsive */
        @media (max-width: 1400px) {
            .mockup-container {
                max-width: 1300px;
            }
            
            .images-container {
                height: 580px;
            }
            
            .mockup-image.computer {
                max-width: 750px !important;
            }
            
            .mockup-image.phone {
                max-width: 350px !important;
            }
            
            .mockup-image.phone.visible {
                transform: translateX(-160px) translateY(45px) scale(0.9);
            }
        }

        @media (max-width: 1279px) {
            .images-container {
                height: 520px;
            }
            
            .mockup-image.computer {
                max-width: 680px !important;
            }
            
            .mockup-image.phone {
                max-width: 320px !important;
            }
            
            .mockup-image.phone.visible {
                transform: translateX(-140px) translateY(40px) scale(0.85);
            }
        }

        @media (max-width: 1023px) {
            .images-container {
                height: 480px;
            }
            
            .mockup-image.computer {
                max-width: 600px !important;
            }
            
            .mockup-image.phone {
                max-width: 280px !important;
            }
            
            .mockup-image.phone.visible {
                transform: translateX(-120px) translateY(35px) scale(0.8);
            }
        }

        @media (max-width: 767px) {
            .overlay-mockup-component {
                padding: 3rem 0;
            }

            .mockup-container {
                gap: 3rem;
                padding: 0 1rem;
            }

            .mockup-title {
                font-size: 2rem;
            }

            .mockup-description {
                font-size: 1rem;
            }

            .images-container {
                height: 480px;
            }

            .mockup-image.computer {
                max-width: 480px !important;
                transform: translateX(-60px) scale(0.85);
            }

            .mockup-image.computer.visible {
                transform: translateX(10px) scale(1);
            }

            .mockup-image.phone {
                max-width: 260px !important;
                transform: translateX(80px) scale(0.75);
            }

            .mockup-image.phone.visible {
                transform: translateX(-110px) translateY(30px) scale(0.85);
            }
        }

        @media (max-width: 480px) {
            .images-container {
                height: 420px;
            }

            .mockup-image.computer {
                max-width: 380px !important;
            }

            .mockup-image.phone {
                max-width: 220px !important;
            }

            .mockup-image.phone.visible {
                transform: translateX(-90px) translateY(25px) scale(0.8);
            }
        }
    </style>

    <div class="mockup-container">
        <!-- Texto minimalista - Estilo Linear -->
        <div class="mockup-text" id="mockupText">
            <h2 class="mockup-title">
                Disponible donde estés
            </h2>
            
            <p class="mockup-description">
                Actualiza desde el celular después de cada reunión. 
                No esperes a volver a la oficina.
            </p>

            <div class="features-list">
                <div class="feature-item">
                    <span>Registra pedidos desde cualquier lugar</span>
                </div>
                <div class="feature-item">
                    <span>Consulta stock en tiempo real</span>
                </div>
                <div class="feature-item">
                    <span>Todo sincronizado al instante</span>
                </div>
            </div>
        </div>

        <!-- Imágenes (sin cambios de posición) -->
        <div class="images-container">
            <img 
                src="{{ asset('images/mockups/computer-mockup.png') }}" 
                alt="Gestión desde escritorio"
                class="mockup-image computer"
                id="computerImage"
                style="max-width: 850px"
            >

            <img 
                src="{{ asset('images/mockups/mobile-mockup.png') }}" 
                alt="Gestión desde móvil"
                class="mockup-image phone"
                id="phoneImage"
                style="max-width: 400px"
            >
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textElement = document.getElementById('mockupText');
            const computerImage = document.getElementById('computerImage');
            const phoneImage = document.getElementById('phoneImage');
            
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -80px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (entry.target === textElement) {
                            setTimeout(() => entry.target.classList.add('visible'), 200);
                        } else if (entry.target === computerImage) {
                            setTimeout(() => entry.target.classList.add('visible'), 400);
                        } else if (entry.target === phoneImage) {
                            setTimeout(() => entry.target.classList.add('visible'), 600);
                        }
                    }
                });
            }, observerOptions);

            if (textElement) observer.observe(textElement);
            if (computerImage) observer.observe(computerImage);
            if (phoneImage) observer.observe(phoneImage);
        });
    </script>
</div>