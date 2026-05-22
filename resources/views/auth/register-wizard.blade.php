<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Crear cuenta · Helipso</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
<style>
  /* ====================================================
     TOKENS
     ==================================================== */
  :root {
    --space:        #0B0820;
    --space-2:      #15102E;
    --violet:       #8A5CF5;
    --violet-deep:  #5B2FCC;
    --violet-soft:  #B79CFA;
    --violet-pale:  #E5DBFD;
    --fuchsia:      #D670F0;
    --bg:           #FAFAF7;
    --bg-2:         #F4F2EE;
    --card:         #FFFFFF;
    --ink:          #1A1330;
    --ink-2:        #4A4360;
    --slate:        #8B8499;
    --slate-2:      #B5B0C2;
    --line:         #ECE9F2;
    --line-2:       #DCD7E5;
    --error:        #C2272D;
    --warning:      #C28A27;
    --ease:         cubic-bezier(0.32, 0.72, 0, 1);
  }

  *, *::before, *::after { box-sizing: border-box; }
  html, body { margin: 0; padding: 0; height: 100%; }
  [x-cloak] { display: none !important; }

  body {
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
  }

  /* ====================================================
     SHELL
     ==================================================== */
  .shell {
    height: 100vh;
    display: grid;
    grid-template-columns: 0.82fr 1fr;
    padding: 16px;
    gap: 16px;
    background: var(--bg);
  }

  /* ====================================================
     COSMOS PANE (left – dark)
     ==================================================== */
  .cosmos {
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    background:
      radial-gradient(ellipse 90% 60% at 30% 35%, rgba(138,92,245,0.22), transparent 60%),
      radial-gradient(ellipse 60% 50% at 80% 85%, rgba(214,112,240,0.14), transparent 55%),
      radial-gradient(ellipse 100% 100% at 50% 50%, #1a1240 0%, #0B0820 70%, #050313 100%);
    color: #fff;
    isolation: isolate;
  }

  .stars { position: absolute; inset: 0; pointer-events: none; }
  .stars span {
    position: absolute;
    border-radius: 50%;
    background: #fff;
    opacity: 0;
    animation: twinkle var(--dur, 8s) ease-in-out infinite;
    animation-delay: var(--delay, 0s);
  }
  @keyframes twinkle {
    0%, 100% { opacity: 0.05; transform: scale(0.8); }
    50%       { opacity: var(--peak, 0.5); transform: scale(1); }
  }

  .nebula {
    position: absolute;
    left: 50%; top: 48%;
    width: 70%; aspect-ratio: 1/1;
    transform: translate(-50%, -50%);
    background: radial-gradient(circle at center,
      rgba(183,156,250,0.16) 0%,
      rgba(138,92,245,0.08) 30%,
      transparent 65%);
    filter: blur(14px);
    pointer-events: none;
  }

  .cosmos-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 32px 38px;
  }

  /* Brand lockup (top) */
  .cosmos-brand {
    display: inline-flex;
    align-items: baseline;
    gap: 0;
    text-decoration: none;
    color: #fff;
    flex-shrink: 0;
  }
  .cosmos-brand .brand-glyph {
    width:  calc(22px * 1.05 * 52 / 80);
    height: calc(22px * 1.05);
    transform: translateY(calc(22px * 0.10));
    margin-right: 1px;
  }
  .cosmos-brand .brand-word {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 350;
    font-size: 22px;
    letter-spacing: -0.04em;
    line-height: 1;
  }

  /* Center: context text (no animated glyph) */
  .center {
    margin: auto 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-width: 460px;
  }

  .context-title {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 300;
    font-size: clamp(28px, 3vw, 40px);
    letter-spacing: -0.025em;
    color: #fff;
    margin: 0;
    line-height: 1.1;
  }
  .context-title em {
    font-style: normal;
    color: var(--violet-soft);
  }
  .context-sub {
    font-size: 14px;
    line-height: 1.55;
    color: rgba(255,255,255,0.58);
    margin: 0;
    max-width: 360px;
  }

  /* Step pills */
  .cosmos-progress {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding-top: 28px;
    border-top: 1px solid rgba(255,255,255,0.08);
    margin-top: 32px;
  }
  .cosmos-step-row {
    display: flex;
    align-items: center;
    gap: 14px;
    transition: opacity 0.3s var(--ease);
  }
  .cosmos-step-row .num {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.12);
    color: rgba(255,255,255,0.5);
    display: grid; place-items: center;
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px; font-weight: 500;
    flex-shrink: 0;
    transition: all 0.3s var(--ease);
  }
  .cosmos-step-row .num svg { width: 12px; height: 12px; display: none; }
  .cosmos-step-row .label { font-size: 13px; color: rgba(255,255,255,0.55); transition: color 0.3s var(--ease); }
  .cosmos-step-row .meta {
    margin-left: auto;
    font-family: 'JetBrains Mono', monospace;
    font-size: 10px; letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
    transition: color 0.3s var(--ease);
  }
  .cosmos-step-row.is-active .num    { background: #fff; border-color: #fff; color: var(--ink); }
  .cosmos-step-row.is-active .label  { color: #fff; font-weight: 500; }
  .cosmos-step-row.is-active .meta   { color: var(--violet-soft); }
  .cosmos-step-row.is-done .num      { background: var(--violet); border-color: var(--violet); color: #fff; }
  .cosmos-step-row.is-done .num span { display: none; }
  .cosmos-step-row.is-done .num svg  { display: block; }
  .cosmos-step-row.is-done .label    { color: rgba(255,255,255,0.45); }

  .cosmos-foot {
    margin-top: auto;
    padding-top: 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: 'JetBrains Mono', monospace;
    font-size: 10px; letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
  }

  /* ====================================================
     FORM PANE (right – light)
     ==================================================== */
  .form-pane {
    position: relative;
    display: grid;
    grid-template-rows: auto 1fr auto;
    padding: 24px 16px 16px;
    overflow-y: auto;
    background: var(--bg);
    border-radius: 20px;
  }

  .pane-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 24px;
    flex-shrink: 0;
  }
  .top-progress {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px; letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--slate);
  }
  .top-progress .bar { display: flex; gap: 4px; }
  .top-progress .bar i {
    display: block;
    width: 22px; height: 3px;
    border-radius: 999px;
    background: var(--line-2);
    transition: background 0.4s var(--ease);
  }
  .top-progress .bar i.bar-on   { background: var(--ink); }
  .top-progress .bar i.bar-done { background: var(--violet); }

  .pane-top .top-link { font-size: 13px; color: var(--slate); }
  .pane-top .top-link a {
    color: var(--ink);
    text-decoration: none;
    font-weight: 500;
    border-bottom: 1px solid var(--line-2);
    padding-bottom: 1px;
    margin-left: 4px;
    transition: border-color 0.2s var(--ease);
  }
  .pane-top .top-link a:hover { border-bottom-color: var(--ink); }

  .pane-mid {
    display: grid;
    place-items: center;
    padding: 16px 24px;
    overflow-y: auto;
  }
  .form-card { width: 100%; max-width: 560px; }

  /* Step eyebrow */
  .step-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 11px; letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--slate);
    margin-bottom: 10px;
  }
  .step-eyebrow .dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--violet);
  }
  .step-header h1 {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 400;
    font-size: 30px;
    letter-spacing: -0.025em;
    color: var(--ink);
    margin: 0 0 8px;
    line-height: 1.18;
  }
  .step-header p {
    font-size: 14px;
    color: var(--slate);
    margin: 0 0 32px;
    line-height: 1.55;
  }

  /* Step transition */
  @keyframes stepIn {
    from { opacity: 0; transform: translateX(8px); }
    to   { opacity: 1; transform: translateX(0); }
  }
  .step-enter { animation: stepIn 0.4s var(--ease); }

  /* ====================================================
     STEP 1 · Business cards
     ==================================================== */
  .biz-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  .biz-card {
    position: relative;
    background: var(--card);
    border: 1px solid var(--line-2);
    border-radius: 16px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.25s var(--ease);
    text-align: left;
    width: 100%;
  }
  .biz-card::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 16px;
    border: 1.5px solid transparent;
    pointer-events: none;
    transition: border-color 0.25s var(--ease);
  }
  .biz-card:hover { border-color: var(--violet-soft); transform: translateY(-1px); }
  .biz-card.selected {
    background: linear-gradient(180deg, #fff 0%, #FAF7FF 100%);
    border-color: var(--violet);
  }
  .biz-card.selected::before {
    border-color: var(--violet);
    box-shadow: 0 0 0 4px rgba(138,92,245,0.10);
  }
  .biz-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
  }
  .biz-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: var(--bg-2);
    display: grid; place-items: center;
    color: var(--ink-2);
    transition: all 0.25s var(--ease);
  }
  .biz-card.selected .biz-icon { background: var(--violet); color: #fff; }
  .check-circle {
    width: 22px; height: 22px;
    border-radius: 50%;
    border: 1.5px solid var(--line-2);
    background: #fff;
    display: grid; place-items: center;
    transition: all 0.25s var(--ease);
    flex-shrink: 0;
  }
  .check-circle svg { opacity: 0; transition: opacity 0.18s var(--ease); }
  .biz-card.selected .check-circle { background: var(--violet); border-color: var(--violet); }
  .biz-card.selected .check-circle svg { opacity: 1; }
  .biz-title {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 500;
    font-size: 18px;
    color: var(--ink);
    letter-spacing: -0.01em;
    margin: 0 0 4px;
  }
  .biz-desc { font-size: 13px; color: var(--slate); margin: 0 0 16px; line-height: 1.5; }
  .biz-features { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 7px; }
  .biz-features li { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--ink-2); }
  .biz-features li svg { color: var(--violet); flex-shrink: 0; }

  /* ====================================================
     STEP 2 · Plan cards
     ==================================================== */
  .plan-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
  }
  .plan-card {
    position: relative;
    background: var(--card);
    border: 1px solid var(--line-2);
    border-radius: 16px;
    padding: 20px 18px 18px;
    cursor: pointer;
    transition: all 0.25s var(--ease);
    text-align: left;
    display: flex;
    flex-direction: column;
    width: 100%;
  }
  .plan-card:hover { border-color: var(--violet-soft); transform: translateY(-1px); }
  .plan-card.selected {
    border-color: var(--violet);
    background: linear-gradient(180deg, #fff 0%, #FAF7FF 100%);
    box-shadow: 0 0 0 4px rgba(138,92,245,0.10);
  }
  .plan-card.featured { background: linear-gradient(180deg, #fff 0%, #FAF7FF 100%); }
  .plan-badge {
    position: absolute;
    top: -10px; left: 18px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 9px;
    border-radius: 999px;
    background: var(--ink);
    color: #fff;
    font-family: 'JetBrains Mono', monospace;
    font-size: 9.5px; font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
  }
  .plan-badge.violet { background: linear-gradient(110deg, var(--violet-deep), var(--violet)); }
  .plan-badge.preview { background: var(--bg-2); color: var(--ink-2); border: 1px solid var(--line-2); }
  .plan-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 14px; }
  .plan-name {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 500; font-size: 18px;
    color: var(--ink); letter-spacing: -0.01em;
    margin: 0; line-height: 1.2;
  }
  .plan-tag { font-size: 12px; color: var(--slate); margin: 2px 0 0; }
  .plan-price { margin-bottom: 16px; }
  .plan-price .amount {
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 500; font-size: 28px;
    letter-spacing: -0.02em;
    color: var(--ink); line-height: 1;
  }
  .plan-price .note { display: block; font-size: 11.5px; color: var(--slate); margin-top: 6px; }
  .plan-features { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 8px; flex: 1; }
  .plan-features li { display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: var(--ink-2); line-height: 1.4; }
  .plan-features li svg { color: var(--violet); flex-shrink: 0; margin-top: 3px; }

  /* ====================================================
     STEP 3 · Form fields
     ==================================================== */
  .fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
  }
  .fields .full { grid-column: 1 / -1; }

  .field {
    position: relative;
    border-bottom: 1px solid var(--line-2);
    padding-top: 18px;
    transition: border-color 0.2s var(--ease);
  }
  .field:focus-within { border-bottom-color: var(--ink); }
  .field.has-error { border-bottom-color: var(--error); }

  .field label {
    position: absolute;
    left: 0; top: 22px;
    font-size: 14px;
    color: var(--slate);
    pointer-events: none;
    transition: all 0.2s var(--ease);
    transform-origin: left center;
  }
  .field input:focus ~ label,
  .field input:not(:placeholder-shown) ~ label {
    top: 0; font-size: 11px;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--slate);
    font-weight: 500;
  }
  .field:focus-within label { color: var(--ink); }
  .field.has-error label { color: var(--error); }

  .field input {
    width: 100%;
    border: 0; outline: 0;
    background: transparent;
    padding: 0 50px 10px 0;
    font: inherit;
    font-size: 15px;
    color: var(--ink);
  }
  .field input:-webkit-autofill {
    -webkit-text-fill-color: var(--ink);
    box-shadow: 0 0 0 1000px var(--bg) inset;
  }

  .field-action { position: absolute; right: 0; top: 18px; }
  .toggle-pw {
    border: 0; background: transparent;
    color: var(--slate);
    cursor: pointer; padding: 4px;
    display: grid; place-items: center;
    transition: color 0.2s var(--ease);
  }
  .toggle-pw:hover { color: var(--ink); }

  /* Password strength */
  .pw-strength {
    grid-column: 1 / -1;
    display: flex; align-items: center; gap: 10px;
    font-size: 11.5px; color: var(--slate);
    margin-top: -10px;
  }
  .pw-strength .track { flex: 1; height: 3px; border-radius: 999px; background: var(--line-2); overflow: hidden; }
  .pw-strength .fill {
    height: 100%; width: 0%;
    background: var(--violet);
    transition: width 0.3s var(--ease), background 0.3s var(--ease);
    border-radius: 999px;
  }
  .pw-strength.weak   .fill { width: 25%; background: #E36800; }
  .pw-strength.medium .fill { width: 55%; background: var(--warning); }
  .pw-strength.strong .fill { width: 100%; background: #1d9d6c; }
  .pw-strength .label-text { width: 60px; text-align: right; font-weight: 500; }

  /* Info box */
  .info {
    margin-top: 24px;
    background: var(--bg-2);
    border: 1px solid var(--line-2);
    border-radius: 12px;
    padding: 14px 16px;
    display: flex; gap: 12px;
    font-size: 13px; color: var(--ink-2); line-height: 1.5;
  }
  .info svg { color: var(--violet); flex-shrink: 0; margin-top: 1px; }
  .info strong { color: var(--ink); font-weight: 600; }
  .info ul {
    margin: 6px 0 0; padding: 0;
    list-style: none;
    display: flex; flex-direction: column; gap: 4px;
    color: var(--slate); font-size: 12.5px;
  }
  .info ul li::before { content: "·"; margin-right: 6px; color: var(--violet); }

  /* Validation alert */
  .alert {
    display: none;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 0 10px 14px;
    border-left: 2px solid var(--error);
    margin-bottom: 22px;
    font-size: 13px; line-height: 1.5;
    color: var(--ink-2);
  }
  .alert.show { display: flex; }
  .alert strong { color: var(--ink); font-weight: 600; }
  .alert ul { margin: 4px 0 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 3px; color: var(--error); font-size: 12.5px; }

  /* ====================================================
     FOOTER NAV
     ==================================================== */
  .pane-foot {
    padding: 16px 24px 8px;
    display: flex; gap: 12px; align-items: center;
    border-top: 1px solid var(--line);
    background: var(--bg);
    flex-shrink: 0;
  }
  .pane-foot .footer-meta { flex: 1; font-size: 12px; color: var(--slate); }
  .pane-foot .footer-meta strong { color: var(--ink); font-weight: 600; }

  .btn {
    border: 0;
    padding: 12px 22px;
    border-radius: 999px;
    font: inherit; font-size: 14px; font-weight: 500;
    cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    transition: all 0.2s var(--ease);
    white-space: nowrap;
  }
  .btn-primary { background: var(--ink); color: #fff; }
  .btn-primary:hover:not(:disabled) { background: var(--violet-deep); box-shadow: 0 6px 20px rgba(91,47,204,0.25); }
  .btn-primary:disabled { background: var(--bg-2); color: var(--slate-2); cursor: not-allowed; }
  .btn-primary svg { transition: transform 0.2s var(--ease); }
  .btn-primary:hover:not(:disabled) svg { transform: translateX(2px); }
  .btn-ghost { background: transparent; color: var(--ink-2); padding: 12px 18px; }
  .btn-ghost:hover { color: var(--ink); }
  .btn-ghost svg { transition: transform 0.2s var(--ease); }
  .btn-ghost:hover svg { transform: translateX(-2px); }

  /* ====================================================
     RESPONSIVE
     ==================================================== */
  @media (max-width: 1024px) {
    body { overflow-y: auto; }
    .shell {
      grid-template-columns: 1fr;
      height: auto;
      min-height: 100vh;
      padding: 12px;
      gap: 12px;
    }
    .cosmos { min-height: 260px; }
    .cosmos-content { padding: 24px 24px 28px; }
    .context-title { font-size: 24px; }
    .cosmos-progress { display: none; }
    .plan-grid { grid-template-columns: 1fr; }
    .biz-grid { grid-template-columns: 1fr; }
    .fields { grid-template-columns: 1fr; }
    .form-pane { border-radius: 20px; }
  }
  @media (max-width: 640px) {
    .shell { padding: 8px; gap: 8px; }
    .cosmos { min-height: 200px; }
    .cosmos-content { padding: 20px; }
    .pane-top { flex-direction: column; gap: 10px; align-items: flex-start; padding: 8px 16px; }
    .pane-mid { padding: 12px 16px; }
    .pane-foot { flex-wrap: wrap; gap: 10px; padding: 12px 16px; }
    .pane-foot .footer-meta { width: 100%; order: 3; text-align: center; }
    .pane-foot .btn { flex: 1; }
    .biz-title { font-size: 16px; }
    .step-header h1 { font-size: 24px; }
  }

  /* Reduced motion */
  @media (prefers-reduced-motion: reduce) {
    .stars span { animation: none !important; opacity: 0.25 !important; }
    .step-enter { animation: none !important; }
  }
</style>
</head>

<body
  x-data="{
    step: {{ (request()->get('plan') && in_array(request()->get('plan'), ['basic','premium','enterprise'])) ? 2 : 1 }},
    businessType: '',
    plan: '{{ addslashes(request()->get('plan', '')) }}',

    contextTitles: {
      1: 'Cada negocio es <em>una constelación.</em>',
      2: 'Elegí cómo querés <em>orbitar.</em>',
      3: 'Bienvenido al <em>panel.</em>',
    },
    contextSubs: {
      1: 'Tres pasos para empezar a trazar la tuya.',
      2: 'Todos los planes están gratis durante la fase inicial.',
      3: 'Ingresá tus datos y empezá a usar Helipso.',
    },
    footerMetas: {
      1: () => this.businessType ? ('Negocio · ' + (this.businessType === 'comercio' ? 'Comercio / Tienda' : 'Alquiler / Clubes')) : 'Elegí tu tipo de negocio para continuar.',
      2: () => this.plan ? ('Plan · ' + { basic:'Básico', premium:'Premium', enterprise:'Enterprise' }[this.plan]) : 'Elegí un plan para continuar.',
      3: () => 'Vas a recibir tus credenciales por email.',
    },

    get footerMeta() {
      if (this.step === 1) return this.businessType ? 'Negocio · ' + (this.businessType === 'comercio' ? 'Comercio / Tienda' : 'Alquiler / Clubes') : 'Elegí tu tipo de negocio para continuar.';
      if (this.step === 2) return this.plan ? 'Plan · ' + { basic:'Básico', premium:'Premium', enterprise:'Enterprise' }[this.plan] : 'Elegí un plan para continuar.';
      return 'Vas a recibir tus credenciales por email.';
    },
    get canNext() {
      if (this.step === 1) return this.businessType !== '';
      if (this.step === 2) return this.plan !== '';
      return true;
    },
    get nextLabel() {
      if (this.step === 1) return 'Siguiente · Plan';
      if (this.step === 2) return 'Siguiente · Datos';
      return 'Crear cuenta';
    },

    nextStep() { if (this.step < 3) { this.step++; this.$nextTick(() => this.$el.scrollTop = 0); } },
    prevStep() { if (this.step > 1) this.step--; },
    selectBiz(type)  { this.businessType = type; },
    selectPlan(type) { this.plan = type; },
  }"
>

<div class="shell">

  <!-- ================================================
       LEFT · COSMOS
       ================================================ -->
  <aside class="cosmos">
    <div class="stars" id="stars" aria-hidden="true"></div>
    <div class="nebula" aria-hidden="true"></div>

    <div class="cosmos-content">
      <!-- Brand lockup (static, no animation) -->
      <a href="{{ route('home') }}" class="cosmos-brand">
        <svg class="brand-glyph" viewBox="26 16 52 80" aria-hidden="true">
          <g stroke="#fff" fill="none" stroke-linecap="round" stroke-width="2.4" opacity="0.85">
            <line x1="32" y1="22" x2="38" y2="54" />
            <line x1="38" y1="54" x2="34" y2="90" />
            <line x1="38" y1="54" x2="50" y2="46" />
            <line x1="50" y1="46" x2="66" y2="56" />
            <line x1="66" y1="56" x2="72" y2="64" />
            <line x1="72" y1="64" x2="68" y2="90" />
          </g>
          <circle cx="32" cy="22" r="3.6" fill="#D670F0" />
          <circle cx="38" cy="54" r="4.4" fill="#fff" />
          <circle cx="34" cy="90" r="3.2" fill="#fff" />
          <circle cx="50" cy="46" r="3.6" fill="#fff" />
          <circle cx="66" cy="56" r="3.2" fill="#fff" />
          <circle cx="72" cy="64" r="4.4" fill="#fff" />
          <circle cx="68" cy="90" r="3.2" fill="#fff" />
        </svg>
        <span class="brand-word">elipso</span>
      </a>

      <!-- Context text (changes per step) -->
      <div class="center">
        <h2 class="context-title" x-html="contextTitles[step]">Cada negocio es <em>una constelación.</em></h2>
        <p class="context-sub" x-text="contextSubs[step]">Tres pasos para empezar a trazar la tuya.</p>
      </div>

      <!-- Step list -->
      <div class="cosmos-progress">
        <div class="cosmos-step-row" :class="{ 'is-active': step === 1, 'is-done': step > 1 }">
          <div class="num">
            <span>1</span>
            <svg viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div class="label">Tipo de negocio</div>
          <div class="meta">Paso 1</div>
        </div>
        <div class="cosmos-step-row" :class="{ 'is-active': step === 2, 'is-done': step > 2 }">
          <div class="num">
            <span>2</span>
            <svg viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div class="label">Plan</div>
          <div class="meta">Paso 2</div>
        </div>
        <div class="cosmos-step-row" :class="{ 'is-active': step === 3, 'is-done': step > 3 }">
          <div class="num">
            <span>3</span>
            <svg viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div class="label">Tus datos</div>
          <div class="meta">Paso 3</div>
        </div>
      </div>

      <div class="cosmos-foot">
        <span>helipso · {{ date('Y') }}</span>
        <span>cluster ar-1</span>
      </div>
    </div>
  </aside>

  <!-- ================================================
       RIGHT · FORM PANE
       ================================================ -->
  <main class="form-pane">

    <!-- Top bar -->
    <div class="pane-top">
      <div class="top-progress">
        <span><span x-text="'0' + step">01</span> / 03</span>
        <div class="bar">
          <i :class="{ 'bar-on': step === 1, 'bar-done': step > 1 }"></i>
          <i :class="{ 'bar-on': step === 2, 'bar-done': step > 2 }"></i>
          <i :class="{ 'bar-on': step === 3, 'bar-done': step > 3 }"></i>
        </div>
      </div>
      <div class="top-link">
        ¿Ya tenés cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
      </div>
    </div>

    <!-- Form -->
    <div class="pane-mid">
      <form id="regForm" x-ref="form" method="POST" action="{{ route('register.wizard.store') }}"
            class="form-card" autocomplete="on" novalidate>
        @csrf

        {{-- Hidden fields (bound to Alpine state, always present for POST) --}}
        <input type="hidden" name="business_type" :value="businessType">
        <input type="hidden" name="plan" :value="plan">

        {{-- Server-side validation errors --}}
        @if ($errors->any())
          <div class="alert show">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="flex-shrink:0;margin-top:1px;color:var(--error)">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
              <strong>Revisá los campos.</strong>
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <!-- ==========================
             STEP 1 · Tipo de negocio
             ========================== -->
        <section x-show="step === 1" x-cloak x-transition:enter="step-enter">
          <div class="step-eyebrow"><span class="dot"></span><span>Paso 1 · Negocio</span></div>
          <div class="step-header">
            <h1>¿Qué tipo de negocio tenés?</h1>
            <p>Elegí el que mejor describe tu actividad. Helipso adapta los módulos según tu elección.</p>
          </div>

          <div class="biz-grid">
            {{-- Comercio --}}
            <button type="button" class="biz-card" :class="{ selected: businessType === 'comercio' }" @click="selectBiz('comercio')">
              <div class="biz-top">
                <div class="biz-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <div class="check-circle">
                  <svg width="10" height="10" viewBox="0 0 20 20" fill="none">
                    <path d="M4 10l4 4 8-8" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
              </div>
              <h3 class="biz-title">Comercio / Tienda</h3>
              <p class="biz-desc">Gestioná productos, ventas, stock y pedidos para tu negocio.</p>
              <ul class="biz-features">
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Control de inventario</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Gestión de ventas</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Reportes de productos</li>
              </ul>
            </button>

            {{-- Alquiler / Clubes --}}
            <button type="button" class="biz-card" :class="{ selected: businessType === 'alquiler' }" @click="selectBiz('alquiler')">
              <div class="biz-top">
                <div class="biz-icon">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <div class="check-circle">
                  <svg width="10" height="10" viewBox="0 0 20 20" fill="none">
                    <path d="M4 10l4 4 8-8" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
              </div>
              <h3 class="biz-title">Alquiler / Clubes</h3>
              <p class="biz-desc">Administrá espacios, servicios, turnos y cobros recurrentes.</p>
              <ul class="biz-features">
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Control de espacios</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Gestión de turnos</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Panel de operarios</li>
              </ul>
            </button>
          </div>
        </section>

        <!-- ==========================
             STEP 2 · Plan
             ========================== -->
        <section x-show="step === 2" x-cloak x-transition:enter="step-enter">
          <div class="step-eyebrow"><span class="dot"></span><span>Paso 2 · Plan</span></div>
          <div class="step-header">
            <h1>Elegí tu plan.</h1>
            <p>Todos los planes están disponibles gratis durante la fase inicial. Podés cambiar cuando quieras.</p>
          </div>

          <div class="plan-grid">
            {{-- Básico --}}
            <button type="button" class="plan-card" :class="{ selected: plan === 'basic' }" @click="selectPlan('basic')">
              <div class="plan-head">
                <div><h3 class="plan-name">Básico</h3><p class="plan-tag">Para comenzar</p></div>
                <div class="check-circle">
                  <svg width="10" height="10" viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
              </div>
              <div class="plan-price">
                <span class="amount">Gratis</span>
                <span class="note">Siempre, sin tarjeta</span>
              </div>
              <ul class="plan-features">
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Hasta 5 usuarios</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> 1 sucursal</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Soporte por email</li>
              </ul>
            </button>

            {{-- Premium --}}
            <button type="button" class="plan-card featured" :class="{ selected: plan === 'premium' }" @click="selectPlan('premium')">
              <div class="plan-badge violet">★ Early access</div>
              <div class="plan-head">
                <div><h3 class="plan-name">Premium</h3><p class="plan-tag">Funciones avanzadas</p></div>
                <div class="check-circle">
                  <svg width="10" height="10" viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
              </div>
              <div class="plan-price">
                <span class="amount">Gratis</span>
                <span class="note">Durante la fase inicial</span>
              </div>
              <ul class="plan-features">
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Todo lo de Básico</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Hasta 50 usuarios</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Hasta 5 sucursales</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Reportes avanzados</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Soporte prioritario</li>
              </ul>
            </button>

            {{-- Enterprise --}}
            <button type="button" class="plan-card" :class="{ selected: plan === 'enterprise' }" @click="selectPlan('enterprise')">
              <div class="plan-badge preview">Preview</div>
              <div class="plan-head">
                <div><h3 class="plan-name">Enterprise</h3><p class="plan-tag">Operaciones complejas</p></div>
                <div class="check-circle">
                  <svg width="10" height="10" viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
              </div>
              <div class="plan-price">
                <span class="amount">Gratis</span>
                <span class="note">Acceso de evaluación</span>
              </div>
              <ul class="plan-features">
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Todo lo de Premium</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Usuarios ilimitados</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Sucursales ilimitadas</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> API completa</li>
                <li><svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4A1 1 0 015.7 9.3L8 11.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg> Soporte 24/7</li>
              </ul>
            </button>
          </div>
        </section>

        <!-- ==========================
             STEP 3 · Tus datos
             ========================== -->
        <section x-show="step === 3" x-cloak x-transition:enter="step-enter" x-data="{ showPw: false, showPw2: false }">
          <div class="step-eyebrow"><span class="dot"></span><span>Paso 3 · Tus datos</span></div>
          <div class="step-header">
            <h1>Casi listos.</h1>
            <p>Ingresá tus datos y te enviamos las credenciales por email.</p>
          </div>

          <div class="fields">
            <div class="field full" id="nameField">
              <input id="name" name="name" type="text" required autocomplete="name" placeholder=" "
                     value="{{ old('name') }}" />
              <label for="name">Nombre completo</label>
            </div>

            <div class="field full" id="emailField">
              <input id="email" name="email" type="email" required autocomplete="username" placeholder=" "
                     value="{{ old('email') }}" />
              <label for="email">Email</label>
            </div>

            <div class="field" id="passwordField">
              <input id="password" name="password" :type="showPw ? 'text' : 'password'"
                     required autocomplete="new-password" placeholder=" " />
              <label for="password">Contraseña</label>
              <div class="field-action">
                <button type="button" class="toggle-pw" @click="showPw = !showPw" aria-label="Mostrar / ocultar">
                  <svg x-show="!showPw" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    <circle cx="12" cy="12" r="3" />
                  </svg>
                  <svg x-show="showPw" x-cloak width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.477 10.477A3 3 0 0012 15c1.657 0 3-1.343 3-3a3 3 0 00-3-3c-.525 0-1.02.135-1.45.373M9.88 9.88L6.343 6.343M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m3.32-2.91A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.186 2.592"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="field" id="password2Field">
              <input id="password_confirmation" name="password_confirmation" :type="showPw2 ? 'text' : 'password'"
                     required autocomplete="new-password" placeholder=" " />
              <label for="password_confirmation">Repetir contraseña</label>
              <div class="field-action">
                <button type="button" class="toggle-pw" @click="showPw2 = !showPw2" aria-label="Mostrar / ocultar">
                  <svg x-show="!showPw2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    <circle cx="12" cy="12" r="3" />
                  </svg>
                  <svg x-show="showPw2" x-cloak width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.477 10.477A3 3 0 0012 15c1.657 0 3-1.343 3-3a3 3 0 00-3-3c-.525 0-1.02.135-1.45.373M9.88 9.88L6.343 6.343M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m3.32-2.91A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.186 2.592"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="pw-strength" id="pwStrength">
              <div class="track"><div class="fill"></div></div>
              <span class="label-text">—</span>
            </div>
          </div>

          <div class="info">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
              <strong>¿Qué sigue?</strong>
              <ul>
                <li>Revisamos tu solicitud en minutos.</li>
                <li>Te enviamos las credenciales por email.</li>
                <li>Empezás a usar Helipso al instante.</li>
              </ul>
            </div>
          </div>
        </section>

      </form>
    </div>

    <!-- Footer nav -->
    <div class="pane-foot">
      <button type="button" class="btn btn-ghost"
              @click="prevStep()"
              :style="step === 1 ? 'visibility: hidden' : ''">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Atrás
      </button>

      <div class="footer-meta" x-text="footerMeta"></div>

      <button type="button" class="btn btn-primary"
              @click="step < 3 ? nextStep() : $refs.form.requestSubmit()"
              :disabled="!canNext">
        <span x-text="nextLabel">Siguiente</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 5l7 7-7 7"/>
        </svg>
      </button>
    </div>

  </main>
</div>

<script>
  // Stars
  (() => {
    const el = document.getElementById('stars');
    if (!el) return;
    let seed = 19;
    const rnd = () => { seed = (seed * 9301 + 49297) % 233280; return seed / 233280; };
    const frag = document.createDocumentFragment();
    for (let i = 0; i < 60; i++) {
      const s = document.createElement('span');
      const size = 1 + rnd() * 2.2;
      s.style.left  = (rnd() * 100).toFixed(2) + '%';
      s.style.top   = (rnd() * 100).toFixed(2) + '%';
      s.style.width = size.toFixed(2) + 'px';
      s.style.height = size.toFixed(2) + 'px';
      s.style.setProperty('--peak', (0.18 + rnd() * 0.55).toFixed(2));
      const dur = 5 + rnd() * 9;
      s.style.setProperty('--dur',   dur.toFixed(1) + 's');
      s.style.setProperty('--delay', (-rnd() * dur).toFixed(1) + 's');
      frag.appendChild(s);
    }
    el.appendChild(frag);
  })();

  // Password strength meter
  document.getElementById('password')?.addEventListener('input', function () {
    const v = this.value;
    const el = document.getElementById('pwStrength');
    const lbl = el?.querySelector('.label-text');
    if (!el || !lbl) return;
    el.classList.remove('weak', 'medium', 'strong');
    if (!v.length) { lbl.textContent = '—'; return; }
    let score = 0;
    if (v.length >= 8)  score++;
    if (v.length >= 12) score++;
    if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    if (score <= 2)      { el.classList.add('weak');   lbl.textContent = 'Débil'; }
    else if (score <= 3) { el.classList.add('medium'); lbl.textContent = 'Media'; }
    else                 { el.classList.add('strong'); lbl.textContent = 'Fuerte'; }
  });
</script>

@livewireScripts
</body>
</html>
