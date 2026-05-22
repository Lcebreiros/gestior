<x-guest-layout>

<style>
  /* ===== Global landing canvas ===== */
  .helipso-landing {
    position: relative;
    background: #0B0820;
    overflow-x: hidden;
  }

  /* Page-wide star field — fixed, visible while scrolling */
  .page-stars {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
  }
  .page-stars span {
    position: absolute;
    border-radius: 50%;
    background: #fff;
    opacity: 0;
    animation: pgStarTwinkle var(--dur, 8s) ease-in-out var(--delay, 0s) infinite;
  }
  @keyframes pgStarTwinkle {
    0%, 100% { opacity: 0.03; transform: scale(0.9); }
    50%       { opacity: var(--peak, 0.18); transform: scale(1); }
  }

  /* Constellation trail SVG — absolute over the full landing */
  .page-trail-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 2;
    overflow: visible;
  }
  .pt-halo {
    transform-box: fill-box;
    transform-origin: center;
    transform: scale(0.3);
    opacity: 0;
    filter: blur(4px);
    transition: transform 1.4s cubic-bezier(0.32, 0.72, 0, 1), opacity 1.2s ease;
  }
  .pt-halo.lit { transform: scale(1.9); opacity: 0.4; }
  .pt-node {
    transform-box: fill-box;
    transform-origin: center;
    transform: scale(0);
    opacity: 0;
    transition: transform 0.9s cubic-bezier(0.32, 0.72, 0, 1), opacity 0.9s ease;
  }
  .pt-node.lit { transform: scale(1); opacity: 1; }
  .pt-line {
    stroke: #B79CFA;
    stroke-width: 1.2;
    stroke-linecap: round;
    fill: none;
    opacity: 0;
    transition: stroke-dashoffset 1.8s cubic-bezier(0.32, 0.72, 0, 1), opacity 0.7s ease;
    vector-effect: non-scaling-stroke;
  }
  .pt-line.drawn { opacity: 0.4; stroke-dashoffset: 0 !important; }

  @media (prefers-reduced-motion: reduce) {
    .pt-halo.lit, .pt-node.lit { transform: none; opacity: 1; }
    .pt-line.drawn { opacity: 0.4; }
    .page-stars span { animation: none; opacity: 0.08; }
  }
</style>

<div id="helipsoLanding" class="helipso-landing">

  <!-- Global star field (behind everything) -->
  <div class="page-stars" id="pageStars" aria-hidden="true"></div>

  <!-- Constellation trail SVG (overlays full page) -->
  <svg class="page-trail-svg" id="pageTrailSvg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"></svg>

    <!-- PRIMERA SECCIÓN: Hero Animada -->
    @include('components.landing.hero-network')

    <!-- SEGUNDA SECCIÓN: Feature Sections -->
        @foreach(config('features.features') as $feature)
        <x-feature-section
            :badge="$feature['badge']"
            :title="$feature['title']"
            :description="$feature['description']"
            :features="$feature['features']"
            :image="$feature['image']"
            :inverted="$feature['inverted']"
            :background="$feature['background']"
        />
    @endforeach

    <!-- SEGUNDA SECCIÓN: Screenshots 3D -->
    @include('components.landing.screenshot-section')


    <!-- TERCERA SECCIÓN: Mockups de Dispositivos -->
    <section class="py-20 screenshots-section" style="background: #0B0820; color: white;">
        @livewire('mockup-view')
    </section>

<!-- CTA Final Section -->
<section id="cta-final" class="relative text-white py-20 md:py-32 overflow-hidden" style="background: #0B0820;">
    <div class="cst-anchor" aria-hidden="true" style="display:block;width:1px;height:1px;visibility:hidden;pointer-events:none;position:absolute;top:3rem;left:6vw;"></div>
    <style>
        /* Apple-style premium background */
        .cta-apple-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(138, 92, 245, 0.2), transparent),
                radial-gradient(ellipse 60% 50% at 50% 120%, rgba(138, 92, 245, 0.15), transparent);
            pointer-events: none;
        }

        /* Elegant container - Apple style */
        .cta-apple-container {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .cta-apple-card {
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.06) 0%,
                rgba(255, 255, 255, 0.02) 50%,
                rgba(255, 255, 255, 0.04) 100%
            );
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 32px;
            padding: 3.5rem 3rem;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cta-apple-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.3) 50%,
                transparent
            );
        }

        .cta-apple-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-4px);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.3),
                0 0 60px rgba(138, 92, 245, 0.1);
        }

        /* Inner glow effect */
        .cta-apple-inner-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            background: radial-gradient(
                circle,
                rgba(138, 92, 245, 0.08) 0%,
                transparent 70%
            );
            pointer-events: none;
            animation: glow-pulse 4s ease-in-out infinite;
        }

        @keyframes glow-pulse {
            0%, 100% { opacity: 0.6; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
        }

        /* Typography - Apple style */
        .cta-apple-pretitle {
            font-size: 1.125rem;
            font-weight: 600;
            color: #8A5CF5;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .cta-apple-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.04em;
            text-align: center;
            margin-bottom: 1.5rem;
            background: linear-gradient(
                180deg,
                #ffffff 0%,
                rgba(255, 255, 255, 0.8) 100%
            );
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-apple-subtitle {
            font-size: clamp(1.125rem, 2vw, 1.5rem);
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            max-width: 650px;
            margin: 0 auto 2.5rem;
            font-weight: 400;
            letter-spacing: -0.02em;
        }

        /* Features grid - Apple style */
        .cta-apple-features {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2.5rem;
            margin: 2.5rem 0;
            flex-wrap: wrap;
        }

        .cta-apple-feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.625rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: -0.01em;
        }

        .cta-apple-feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(138, 92, 245, 0.12);
            border: 1px solid rgba(138, 92, 245, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .cta-apple-feature:hover .cta-apple-feature-icon {
            background: rgba(138, 92, 245, 0.18);
            border-color: rgba(138, 92, 245, 0.3);
            transform: translateY(-2px);
        }

        .cta-apple-feature-icon svg {
            width: 22px;
            height: 22px;
            color: #8A5CF5;
        }

        /* CTA Button - Apple style */
        .cta-apple-button-wrapper {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }

        .cta-apple-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.25rem 3.5rem;
            background: #8A5CF5;
            color: #ffffff;
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 14px;
            letter-spacing: -0.02em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 
                0 4px 16px rgba(138, 92, 245, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .cta-apple-button:hover {
            background: #7A4FE0;
            transform: translateY(-2px);
            box-shadow: 
                0 8px 24px rgba(138, 92, 245, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .cta-apple-button:active {
            transform: translateY(0);
            box-shadow: 
                0 2px 8px rgba(138, 92, 245, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .cta-apple-button svg {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .cta-apple-button:hover svg {
            transform: translateX(3px);
        }

        /* Support text - Apple style */
        .cta-apple-support {
            text-align: center;
            margin-top: 2.5rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.45);
            font-weight: 400;
            letter-spacing: -0.01em;
        }

        .cta-apple-support-highlight {
            color: rgba(255, 255, 255, 0.65);
            font-weight: 500;
        }

        /* Divider */
        .cta-apple-divider {
            height: 1px;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.1) 50%,
                transparent
            );
            margin: 3rem 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cta-apple-card {
                padding: 4rem 3rem;
                border-radius: 28px;
            }

            .cta-apple-features {
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .cta-apple-card {
                padding: 3rem 2rem;
                border-radius: 24px;
            }

            .cta-apple-pretitle {
                font-size: 0.95rem;
            }

            .cta-apple-features {
                flex-direction: column;
                gap: 1.5rem;
            }

            .cta-apple-button-wrapper {
                flex-direction: column;
                width: 100%;
            }

            .cta-apple-button {
                width: 100%;
                padding: 1.125rem 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .cta-apple-card {
                padding: 2.5rem 1.5rem;
            }

            .cta-apple-pretitle {
                font-size: 0.875rem;
                letter-spacing: 0.08em;
            }
        }
    </style>

    <!-- Background -->
    <div class="cta-apple-bg"></div>

    <!-- Content -->
    <div class="cta-apple-container">
        <div class="cta-apple-card">
            
            <!-- Inner glow -->
            <div class="cta-apple-inner-glow"></div>

            <!-- Content wrapper -->
            <div style="position: relative; z-index: 1;">
                
                <!-- Pre-title -->
                <div class="cta-apple-pretitle">
                    Comienza hoy
                </div>

                <!-- Main title -->
                <h2 class="cta-apple-title">
                    Probá Helipso ahora
                </h2>

                <!-- Subtitle -->
                <p class="cta-apple-subtitle">
                    Gestiona pedidos, stock y equipo desde una plataforma diseñada para que tu negocio crezca sin límites.
                </p>

                <!-- CTA Button -->
                <div class="cta-apple-button-wrapper">
                    <a href="{{ route('register.wizard') }}" class="cta-apple-button">
                        <span>Comienza gratis</span>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <!-- Support text -->
                <div class="cta-apple-support">
                    <span class="cta-apple-support-highlight">primer mes gratis</span>
                    <span> • </span>
                    <span>Sin compromisos</span>
                    <span> • </span>
                    <span>Cancela cuando quieras</span>
                </div>

            </div>
        </div>
    </div>
 </section>

    {{-- Footer --}}
    @include('components.landing.footer')

</div>{{-- /helipsoLanding --}}

<script>
(function () {
  'use strict';

  /* ─── Page star field ─── */
  const starsEl = document.getElementById('pageStars');
  if (starsEl) {
    let seed = 1984;
    const rnd = () => { seed = (seed * 9301 + 49297) % 233280; return seed / 233280; };
    const frag = document.createDocumentFragment();
    for (let i = 0; i < 110; i++) {
      const s = document.createElement('span');
      const size = 1 + rnd() * 1.8;
      s.style.left   = rnd() * 100 + '%';
      s.style.top    = rnd() * 100 + '%';
      s.style.width  = size + 'px';
      s.style.height = size + 'px';
      s.style.setProperty('--peak',  (0.06 + rnd() * 0.22).toFixed(2));
      s.style.setProperty('--dur',   (6 + rnd() * 12).toFixed(1) + 's');
      s.style.setProperty('--delay', (-rnd() * 14).toFixed(1) + 's');
      frag.appendChild(s);
    }
    starsEl.appendChild(frag);
  }

  /* ─── Constellation trail ─── */
  const svgEl   = document.getElementById('pageTrailSvg');
  const landing = document.getElementById('helipsoLanding');
  if (!svgEl || !landing) return;

  const svgNS = 'http://www.w3.org/2000/svg';
  let svgHalos = [], svgNodes = [], svgLines = [];

  function buildTrail() {
    const anchors = [...landing.querySelectorAll('.cst-anchor')];
    if (anchors.length < 2) return anchors;

    svgEl.innerHTML = '';
    svgHalos = []; svgNodes = []; svgLines = [];

    const lRect  = landing.getBoundingClientRect();
    const lTop   = lRect.top + window.scrollY;
    const W      = landing.offsetWidth;
    const H      = landing.scrollHeight;
    svgEl.setAttribute('viewBox', `0 0 ${W} ${H}`);
    svgEl.setAttribute('height', H);

    const pts = anchors.map(a => {
      const r = a.getBoundingClientRect();
      return {
        x: r.left + r.width / 2,
        y: r.top + window.scrollY - lTop + r.height / 2,
      };
    });

    pts.forEach((p, i) => {
      const accent = i % 2 === 0 ? '#D670F0' : '#8A5CF5';

      const halo = document.createElementNS(svgNS, 'circle');
      halo.setAttribute('cx', p.x); halo.setAttribute('cy', p.y); halo.setAttribute('r', '14');
      halo.setAttribute('fill', accent); halo.classList.add('pt-halo');
      svgEl.appendChild(halo); svgHalos.push(halo);

      const node = document.createElementNS(svgNS, 'circle');
      node.setAttribute('cx', p.x); node.setAttribute('cy', p.y); node.setAttribute('r', '4');
      node.setAttribute('fill', accent); node.classList.add('pt-node');
      svgEl.appendChild(node); svgNodes.push(node);

      if (i < pts.length - 1) {
        const next = pts[i + 1];
        const len  = Math.hypot(next.x - p.x, next.y - p.y).toFixed(1);
        const line = document.createElementNS(svgNS, 'line');
        line.setAttribute('x1', p.x); line.setAttribute('y1', p.y);
        line.setAttribute('x2', next.x); line.setAttribute('y2', next.y);
        line.style.strokeDasharray  = len;
        line.style.strokeDashoffset = len;
        line.classList.add('pt-line');
        svgEl.appendChild(line); svgLines.push(line);
      } else {
        svgLines.push(null);
      }
    });

    return anchors;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting || entry.target.dataset.lit) return;
      entry.target.dataset.lit = '1';
      const i = parseInt(entry.target.dataset.trailIdx, 10);
      setTimeout(() => {
        svgHalos[i]?.classList.add('lit');
        svgNodes[i]?.classList.add('lit');
      }, 80);
      if (i > 0) setTimeout(() => svgLines[i - 1]?.classList.add('drawn'), 220);
    });
  }, { threshold: 0.2, rootMargin: '0px 0px -6% 0px' });

  function init() {
    const anchors = buildTrail();
    if (!anchors) return;
    anchors.forEach((a, i) => { a.dataset.trailIdx = i; observer.observe(a); });
  }

  if (document.readyState === 'complete') {
    init();
  } else {
    window.addEventListener('load', init);
  }

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => { buildTrail(); }, 200);
  });
})();
</script>

 </x-guest-layout>
