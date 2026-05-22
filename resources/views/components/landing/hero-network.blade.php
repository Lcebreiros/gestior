<style>
  /* ===== Tokens ===== */
  .helipso-hero-root {
    --space:      #0B0820;
    --space-2:    #15102E;
    --violet:     #8A5CF5;
    --violet-soft:#B79CFA;
    --violet-pale:#E5DBFD;
    --fuchsia:    #D670F0;
    --ease-apple: cubic-bezier(0.32, 0.72, 0, 1);
  }

  .helipso-hero-root *,
  .helipso-hero-root *::before,
  .helipso-hero-root *::after { box-sizing: border-box; }

  /* ===== Hero stage ===== */
  .hero-stage {
    position: relative;
    height: 100vh;
    width: 100%;
    overflow: hidden;
    background:
      radial-gradient(ellipse at 50% 45%, #1a1240 0%, #0B0820 65%, #050313 100%);
  }

  /* ===== Far stars ===== */
  .far-stars {
    position: absolute;
    inset: 0;
    pointer-events: none;
  }
  .far-stars span {
    position: absolute;
    width: 2px; height: 2px;
    border-radius: 50%;
    background: #fff;
    opacity: 0;
    animation: helipso-starTwinkle var(--dur, 10s) ease-in-out infinite;
    animation-delay: var(--delay, 0s);
  }
  @keyframes helipso-starTwinkle {
    0%, 100% { opacity: 0.05; transform: scale(0.85); }
    50%      { opacity: var(--peak, 0.45); transform: scale(1); }
  }

  /* ===== Logo composition ===== */
  .logo-wrap {
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
  }
  .logo-inner {
    --fs: clamp(72px, 13vw, 200px);
    display: flex;
    align-items: flex-end;
    gap: calc(var(--fs) * 0.04);
  }

  /* ===== h-constellation SVG ===== */
  .helipso-glyph {
    width:  calc(var(--fs) * 1.05 * 52 / 80);
    height: calc(var(--fs) * 1.05);
    overflow: visible;
    transform: translateY(calc(var(--fs) * 0.025));
  }

  .helipso-node {
    --np: 0;
    transform-box: fill-box;
    transform-origin: center;
    transform: scale(calc(var(--np) * 1));
    opacity: calc(var(--np));
    transition: none;
  }
  .helipso-node-halo {
    --np: 0;
    transform-box: fill-box;
    transform-origin: center;
    transform: scale(calc(0.4 + var(--np) * 1.6));
    opacity: calc(var(--np) * (1 - var(--np)) * 1.6);
    filter: blur(2px);
  }

  .helipso-line {
    --lp: 0;
    stroke-dasharray: var(--len, 100);
    stroke-dashoffset: calc(var(--len, 100) * (1 - var(--lp)));
    fill: none;
    stroke: #B79CFA;
    stroke-width: 1.4;
    stroke-linecap: round;
    opacity: calc(0.55 + var(--lp) * 0.35);
  }

  /* ===== Wordmark ===== */
  .helipso-wordmark {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 350;
    font-size: var(--fs);
    letter-spacing: -0.04em;
    line-height: 1;
    display: inline-flex;
    color: #E5DBFD;
  }

  /* ===== Replay button ===== */
  .helipso-replay-btn {
    position: absolute;
    bottom: 5vh;
    right: 5vw;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.65);
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    font-weight: 500;
    padding: 10px 16px;
    border-radius: 999px;
    cursor: pointer;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    opacity: 0;
    transition: opacity 0.6s var(--ease-apple), background 0.2s ease, color 0.2s ease;
  }
  .helipso-replay-btn.visible { opacity: 1; }
  .helipso-replay-btn:hover {
    background: rgba(255,255,255,0.12);
    color: #fff;
  }

  /* ===== Reduced motion ===== */
  @media (prefers-reduced-motion: reduce) {
    .helipso-node, .helipso-node-halo { transform: none !important; opacity: 1 !important; }
    .helipso-line { stroke-dashoffset: 0 !important; opacity: 0.9 !important; }
    .far-stars span { animation: none !important; opacity: 0.3 !important; }
  }
</style>

<div class="helipso-hero-root">
  <div class="hero-stage" id="helipsoStage">

    <!-- Far stars -->
    <div class="far-stars" id="helipsoFarStars" aria-hidden="true"></div>

    <!-- Logo -->
    <div class="logo-wrap">
      <div class="logo-inner">

        <!-- h-constellation SVG -->
        <svg class="helipso-glyph" viewBox="26 16 52 80" aria-label="Helipso">
          <!-- Halos -->
          <circle class="helipso-node-halo" data-node="0" cx="32" cy="22" r="5"   fill="#D670F0" />
          <circle class="helipso-node-halo" data-node="1" cx="38" cy="54" r="5.5" fill="#B79CFA" />
          <circle class="helipso-node-halo" data-node="2" cx="34" cy="90" r="3.5" fill="#B79CFA" />
          <circle class="helipso-node-halo" data-node="3" cx="50" cy="46" r="5"   fill="#B79CFA" />
          <circle class="helipso-node-halo" data-node="4" cx="66" cy="56" r="3.5" fill="#B79CFA" />
          <circle class="helipso-node-halo" data-node="5" cx="72" cy="64" r="5.5" fill="#B79CFA" />
          <circle class="helipso-node-halo" data-node="6" cx="68" cy="90" r="3.5" fill="#B79CFA" />

          <!-- Constellation lines -->
          <line class="helipso-line" data-line="0" data-len="32.6" x1="32" y1="22" x2="38" y2="54" />
          <line class="helipso-line" data-line="1" data-len="36.4" x1="38" y1="54" x2="34" y2="90" />
          <line class="helipso-line" data-line="2" data-len="14.4" x1="38" y1="54" x2="50" y2="46" />
          <line class="helipso-line" data-line="3" data-len="18.9" x1="50" y1="46" x2="66" y2="56" />
          <line class="helipso-line" data-line="4" data-len="10.0" x1="66" y1="56" x2="72" y2="64" />
          <line class="helipso-line" data-line="5" data-len="26.3" x1="72" y1="64" x2="68" y2="90" />

          <!-- Nodes -->
          <g class="helipso-node" data-node="0">
            <circle cx="32" cy="22" r="2.8" fill="#D670F0" />
          </g>
          <g class="helipso-node" data-node="1"><circle cx="38" cy="54" r="4"   fill="#fff" /></g>
          <g class="helipso-node" data-node="2"><circle cx="34" cy="90" r="2.6" fill="#fff" /></g>
          <g class="helipso-node" data-node="3"><circle cx="50" cy="46" r="3.2" fill="#fff" /></g>
          <g class="helipso-node" data-node="4"><circle cx="66" cy="56" r="2.6" fill="#fff" /></g>
          <g class="helipso-node" data-node="5"><circle cx="72" cy="64" r="4"   fill="#fff" /></g>
          <g class="helipso-node" data-node="6"><circle cx="68" cy="90" r="2.6" fill="#fff" /></g>
        </svg>

        <!-- Wordmark -->
        <span class="helipso-wordmark" id="helipsoWordmark" aria-label="elipso">elipso</span>
      </div>
    </div>

    <button class="helipso-replay-btn" id="helipsoReplayBtn" aria-label="Repetir animación">↻ Replay</button>
  </div>
</div>

<script>
(function () {
  const ease     = (t) => 1 - Math.pow(1 - t, 4);
  const easeStar = (t) => 1 - Math.pow(1 - t, 3);
  const clamp01  = (v) => Math.max(0, Math.min(1, v));
  const remap    = (v, a, b) => clamp01((v - a) / (b - a));

  // Far stars
  const farStars = document.getElementById('helipsoFarStars');
  const STAR_COUNT = 60;
  const frag = document.createDocumentFragment();
  let seed = 42;
  const rnd = () => { seed = (seed * 9301 + 49297) % 233280; return seed / 233280; };
  for (let i = 0; i < STAR_COUNT; i++) {
    const s = document.createElement('span');
    const x = rnd() * 100, y = rnd() * 100;
    const size = 1 + rnd() * 2.2;
    const peak = 0.18 + rnd() * 0.55;
    const dur  = 6 + rnd() * 10;
    const delay = -rnd() * dur;
    s.style.left = x + '%';
    s.style.top  = y + '%';
    s.style.width = size + 'px';
    s.style.height = size + 'px';
    s.style.setProperty('--peak',  peak.toFixed(2));
    s.style.setProperty('--dur',   dur.toFixed(1) + 's');
    s.style.setProperty('--delay', delay.toFixed(1) + 's');
    frag.appendChild(s);
  }
  farStars.appendChild(frag);

  // Animation engine
  const stage     = document.getElementById('helipsoStage');
  const replayBtn = document.getElementById('helipsoReplayBtn');

  const DURATION_MS  = 4500;
  const STAR_ORDER   = [0, 2, 6, 1, 3, 4, 5];
  const STAR_PHASE   = { start: 0.04, end: 0.52, perStarDur: 0.22 };
  const LINE_PHASE   = { start: 0.34, end: 0.66, perLineDur: 0.14 };

  const nodes = [...document.querySelectorAll('.helipso-node[data-node]')];
  const halos = [...document.querySelectorAll('.helipso-node-halo[data-node]')];
  const lines = [...document.querySelectorAll('.helipso-line[data-line]')];

  lines.forEach(l => l.style.setProperty('--len', parseFloat(l.dataset.len)));

  function update(p) {
    STAR_ORDER.forEach((nodeIdx, i) => {
      const t0 = STAR_PHASE.start + i * ((STAR_PHASE.end - STAR_PHASE.start - STAR_PHASE.perStarDur) / (STAR_ORDER.length - 1));
      const np = easeStar(remap(p, t0, t0 + STAR_PHASE.perStarDur));
      nodes.find(n => +n.dataset.node === nodeIdx)?.style.setProperty('--np', np.toFixed(3));
      halos.find(n => +n.dataset.node === nodeIdx)?.style.setProperty('--np', np.toFixed(3));
    });

    lines.forEach((line, i) => {
      const t0 = LINE_PHASE.start + i * ((LINE_PHASE.end - LINE_PHASE.start - LINE_PHASE.perLineDur) / (lines.length - 1));
      line.style.setProperty('--lp', ease(remap(p, t0, t0 + LINE_PHASE.perLineDur)).toFixed(3));
    });

    if (p >= 0.98) {
      replayBtn.classList.add('visible');
    } else {
      replayBtn.classList.remove('visible');
    }
  }

  let startTime = null, rafId = null;
  function tick(now) {
    if (startTime === null) startTime = now;
    const p = clamp01((now - startTime) / DURATION_MS);
    update(p);
    if (p < 1) { rafId = requestAnimationFrame(tick); } else { rafId = null; }
  }

  function play() {
    if (rafId) cancelAnimationFrame(rafId);
    startTime = null;
    update(0);
    setTimeout(() => { rafId = requestAnimationFrame(tick); }, 200);
  }

  replayBtn.addEventListener('click', play);

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    update(1);
    replayBtn.classList.add('visible');
  } else {
    play();
  }
})();
</script>
