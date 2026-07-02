<?php
/*
Template Name: Laboratorio 3D Fullscreen
Description: Configuratore 3D standalone (tubi + piastre). Bypassa header/footer del tema di proposito: e' un tool fullscreen con la sua UI, non una pagina di contenuto.
*/
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RCS Carbonio — Configuratore 3D</title>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/js/three.min.js"></script>
<link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/fonts/fonts.css">
<style>
/* Font Teko gia' locale (assets/fonts/fonts.css), nessuna dipendenza esterna */

:root {
  --bg:       #080809;
  --surface:  #0f0f12;
  --panel:    #141418;
  --border:   #222228;
  --border2:  #2e2e38;
  --accent:   #c8a96e;
  --accent2:  #6ec8b4;
  --text:     #e0ddd8;
  --muted:    #55555f;
  --danger:   #c86e6e;
  --font:     'Teko', sans-serif;
  --mono:     ui-monospace, 'SF Mono', 'Cascadia Code', Consolas, monospace;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; overflow: hidden; }

body {
  background: var(--bg);
  color: var(--text);
  font-family: var(--font);
  display: flex;
  flex-direction: column;
}

/* ═══ HEADER ═══ */
header {
  height: 56px;
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  padding: 0 28px;
  gap: 24px;
  flex-shrink: 0;
  position: relative;
}
.logo-mark {
  display: flex;
  align-items: center;
  gap: 10px;
}
.logo-diamond {
  width: 22px; height: 22px;
  background: var(--accent);
  transform: rotate(45deg);
  flex-shrink: 0;
}
.logo-text {
  font-size: 22px;
  font-weight: 600;
  letter-spacing: 4px;
  text-transform: uppercase;
  color: var(--text);
}
.logo-text span { color: var(--accent); }
.header-sep { width: 1px; height: 24px; background: var(--border2); }
.header-sub {
  font-family: var(--mono);
  font-size: 10px;
  letter-spacing: 2px;
  color: var(--muted);
  text-transform: uppercase;
}
.header-right {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: 16px;
}
.status-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--accent2);
  box-shadow: 0 0 8px var(--accent2);
  animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
.status-label {
  font-family: var(--mono);
  font-size: 10px;
  color: var(--muted);
  letter-spacing: 1.5px;
}
.header-home {
  color: var(--muted);
  text-decoration: none;
  font-family: var(--mono);
  font-size: 10px;
  letter-spacing: 1.5px;
  border: 1px solid var(--border2);
  padding: 6px 12px;
  transition: all .15s;
}
.header-home:hover { color: var(--accent); border-color: var(--accent); }

/* ═══ LAYOUT ═══ */
.layout {
  flex: 1;
  display: flex;
  overflow: hidden;
}

/* ═══ SIDEBAR ═══ */
.sidebar {
  width: 280px;
  background: var(--surface);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  overflow-y: auto;
  flex-shrink: 0;
}
.sidebar::-webkit-scrollbar { width: 3px; }
.sidebar::-webkit-scrollbar-thumb { background: var(--border2); }

.sec-head {
  padding: 16px 20px 6px;
  font-family: var(--mono);
  font-size: 9px;
  letter-spacing: 2.5px;
  color: var(--muted);
  text-transform: uppercase;
}

/* Product cards */
.prod-list {
  display: flex;
  flex-direction: column;
  gap: 2px;
  padding: 4px 10px 12px;
}
.prod-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  cursor: pointer;
  border: 1px solid transparent;
  transition: all .15s;
  background: transparent;
  color: var(--text);
  font-family: var(--font);
  text-align: left;
}
.prod-card:hover { background: var(--panel); border-color: var(--border2); }
.prod-card.active {
  background: rgba(200,169,110,.07);
  border-color: var(--accent);
}
.prod-icon {
  width: 36px; height: 36px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  opacity: .5;
  transition: opacity .15s;
}
.prod-card.active .prod-icon,
.prod-card:hover .prod-icon { opacity: 1; }
.prod-info { flex: 1; }
.prod-name {
  font-size: 17px;
  font-weight: 500;
  letter-spacing: 1px;
  line-height: 1;
  display: block;
}
.prod-desc {
  font-family: var(--mono);
  font-size: 9px;
  color: var(--muted);
  letter-spacing: 1px;
  margin-top: 2px;
  display: block;
}
.prod-card.active .prod-desc { color: var(--accent); }

.divider { border: none; border-top: 1px solid var(--border); margin: 2px 10px 8px; }

/* Params */
.params-wrap { padding: 0 10px 10px; display: flex; flex-direction: column; gap: 7px; }

.param-group-label {
  font-family: var(--mono);
  font-size: 9px;
  letter-spacing: 2px;
  color: var(--accent);
  text-transform: uppercase;
  padding: 6px 2px 2px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 2px;
}

.param-row { display: flex; flex-direction: column; gap: 3px; }
.param-row label {
  font-family: var(--mono);
  font-size: 9px;
  color: var(--muted);
  letter-spacing: 1.5px;
  display: flex;
  justify-content: space-between;
}
.param-row label .unit { color: var(--accent2); }
.param-row input[type=number], .param-row input[type=range] {
  width: 100%;
  background: var(--panel);
  border: 1px solid var(--border2);
  color: var(--text);
  padding: 7px 10px;
  font-family: var(--mono);
  font-size: 13px;
  outline: none;
  transition: border-color .15s;
  -moz-appearance: textfield;
}
.param-row input[type=number]::-webkit-inner-spin-button { display: none; }
.param-row input[type=number]:focus { border-color: var(--accent); }
.param-row input[type=range] {
  padding: 4px 0;
  border: none;
  accent-color: var(--accent);
  cursor: pointer;
}

/* Generate */
.btn-gen {
  margin: 8px 10px 4px;
  padding: 14px;
  background: var(--accent);
  color: #080809;
  border: none;
  font-family: var(--font);
  font-size: 16px;
  font-weight: 600;
  letter-spacing: 3px;
  text-transform: uppercase;
  cursor: pointer;
  transition: all .15s;
}
.btn-gen:hover { background: #d9be85; }
.btn-gen:active { transform: scale(.98); }

/* Stats bar */
.stats-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 2px;
  padding: 2px 10px 12px;
}
.stat-item {
  background: var(--panel);
  border: 1px solid var(--border);
  padding: 7px 8px;
  text-align: center;
}
.stat-item .sv {
  font-family: var(--mono);
  font-size: 13px;
  color: var(--accent);
  display: block;
}
.stat-item .sl {
  font-family: var(--mono);
  font-size: 8px;
  color: var(--muted);
  letter-spacing: 1px;
  text-transform: uppercase;
}

/* ═══ VIEWPORT ═══ */
.viewport {
  flex: 1;
  position: relative;
  overflow: hidden;
}
#cv { width: 100%; height: 100%; display: block; }

/* Carbon fiber grid background */
.viewport::before {
  content: '';
  position: absolute; inset: 0;
  background-image:
    repeating-linear-gradient(45deg, rgba(200,169,110,.025) 0, rgba(200,169,110,.025) 1px, transparent 0, transparent 50%),
    repeating-linear-gradient(-45deg, rgba(200,169,110,.025) 0, rgba(200,169,110,.025) 1px, transparent 0, transparent 50%);
  background-size: 12px 12px;
  pointer-events: none;
}

.vp-empty {
  position: absolute; inset: 0;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 16px; pointer-events: none;
}
.vp-empty-icon { opacity: .12; }
.vp-empty-text {
  font-family: var(--mono);
  font-size: 11px;
  letter-spacing: 3px;
  color: var(--muted);
  opacity: .5;
  text-transform: uppercase;
}

/* Corner info */
.vp-corner {
  position: absolute;
  font-family: var(--mono);
  font-size: 10px;
  color: var(--muted);
  letter-spacing: 1px;
  pointer-events: none;
}
.vp-corner.tl { top: 16px; left: 16px; }
.vp-corner.tr { top: 16px; right: 16px; display: flex; gap: 12px; }
.vp-corner.bl { bottom: 16px; left: 16px; }

/* Dynamic sections */
.section-block {
  background: var(--panel);
  border: 1px solid var(--border2);
  padding: 8px 10px;
  margin-bottom: 6px;
  position: relative;
}
.section-block-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 6px;
}
.section-block-label {
  font-family: var(--mono);
  font-size: 9px;
  color: var(--accent);
  letter-spacing: 2px;
}
.btn-remove-sec {
  background: none;
  border: 1px solid var(--border2);
  color: var(--danger);
  cursor: pointer;
  font-size: 11px;
  padding: 1px 6px;
  font-family: var(--mono);
  transition: all .1s;
}
.btn-remove-sec:hover { background: rgba(200,110,110,.1); border-color: var(--danger); }
.btn-add-sec {
  margin: 4px 0 8px;
  padding: 8px;
  background: transparent;
  border: 1px dashed var(--border2);
  color: var(--muted);
  cursor: pointer;
  font-family: var(--mono);
  font-size: 10px;
  letter-spacing: 1px;
  width: 100%;
  transition: all .15s;
}
.btn-add-sec:hover { border-color: var(--accent2); color: var(--accent2); }
.error-msg {
  background: rgba(200,110,110,.1);
  border: 1px solid var(--danger);
  color: var(--danger);
  font-family: var(--mono);
  font-size: 9px;
  letter-spacing: 1px;
  padding: 6px 10px;
  margin: 4px 0;
  display: none;
}
.error-msg.show { display: block; }


/* ═══ CONTROL PAD ═══ */
.ctrl-pad {
  position: absolute;
  bottom: 20px;
  right: 20px;
  display: none;
  flex-direction: column;
  align-items: center;
  gap: 3px;
  user-select: none;
}
.ctrl-pad.visible { display: flex; }
.ctrl-row { display: flex; gap: 3px; }
.ctrl-btn {
  width: 36px; height: 36px;
  background: rgba(15,15,18,.85);
  border: 1px solid var(--border2);
  color: var(--text);
  font-size: 16px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .1s;
  backdrop-filter: blur(4px);
  font-family: var(--mono);
}
.ctrl-btn:hover { background: rgba(200,169,110,.15); border-color: var(--accent); color: var(--accent); }
.ctrl-btn:active { transform: scale(.9); }
.ctrl-sep { width: 36px; height: 1px; background: var(--border2); margin: 2px 0; }
.ctrl-label {
  font-family: var(--mono);
  font-size: 8px;
  color: var(--muted);
  letter-spacing: 1px;
  text-align: center;
  margin-top: 2px;
}
.ctrl-reset {
  width: 78px;
  font-size: 9px;
  letter-spacing: 1px;
  color: var(--muted);
}

.ax { font-weight: 500; }
.ax-x { color: #c86e6e; }
.ax-y { color: #6ec870; }
.ax-z { color: #6e9ec8; }

/* Model name tag */
.model-tag {
  position: absolute;
  top: 16px; left: 50%; transform: translateX(-50%);
  background: var(--surface);
  border: 1px solid var(--border2);
  padding: 5px 16px;
  font-size: 18px;
  font-weight: 600;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--accent);
  pointer-events: none;
  opacity: 0;
  transition: opacity .3s;
}
.model-tag.show { opacity: 1; }
</style>
</head>
<body>

<header>
  <div class="logo-mark">
    <div class="logo-diamond"></div>
    <div class="logo-text">RCS <span>Carbonio</span></div>
  </div>
  <div class="header-sep"></div>
  <div class="header-sub">Configuratore Prodotti — Fase I</div>
  <div class="header-right">
    <div class="status-dot"></div>
    <div class="status-label">Rendering Engine Attivo</div>
    <a class="header-home" href="<?php echo esc_url(home_url('/')); ?>">← TORNA AL SITO</a>
  </div>
</header>

<div class="layout">

  <!-- SIDEBAR -->
  <div class="sidebar">

    <div class="sec-head">// Prodotti</div>
    <div class="prod-list" id="prod-list">

      <button class="prod-card active" data-shape="tube" onclick="selectShape('tube',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <ellipse cx="18" cy="9" rx="11" ry="4"/>
            <ellipse cx="18" cy="27" rx="11" ry="4"/>
            <ellipse cx="18" cy="9" rx="5" ry="2" stroke-dasharray="2 2"/>
            <line x1="7" y1="9" x2="7" y2="27"/>
            <line x1="29" y1="9" x2="29" y2="27"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Tubo Dritto</span>
          <span class="prod-desc">Cilindrico · cavo · sezione costante</span>
        </div>
      </button>

      <button class="prod-card" data-shape="tapered" onclick="selectShape('tapered',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <ellipse cx="18" cy="9" rx="7" ry="2.5"/>
            <ellipse cx="18" cy="27" rx="13" ry="4"/>
            <ellipse cx="18" cy="9" rx="3" ry="1.2" stroke-dasharray="2 2"/>
            <line x1="5" y1="27" x2="11" y2="9"/>
            <line x1="31" y1="27" x2="25" y2="9"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Tubo Conico</span>
          <span class="prod-desc">Frustum · conicità singola</span>
        </div>
      </button>

      <button class="prod-card" data-shape="tapered2" onclick="selectShape('tapered2',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <ellipse cx="18" cy="7" rx="5" ry="2"/>
            <ellipse cx="18" cy="19" rx="10" ry="3"/>
            <ellipse cx="18" cy="30" rx="6" ry="2"/>
            <line x1="8" y1="19" x2="13" y2="7"/>
            <line x1="28" y1="19" x2="23" y2="7"/>
            <line x1="8" y1="19" x2="12" y2="30"/>
            <line x1="28" y1="19" x2="24" y2="30"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Tubo Multi-Conico</span>
          <span class="prod-desc">N sezioni · conicità libere</span>
        </div>
      </button>

      <button class="prod-card" data-shape="bent" onclick="selectShape('bent',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <path d="M8 28 Q8 8 28 8" stroke-width="5" stroke-linecap="round"/>
            <path d="M8 28 Q8 8 28 8" stroke="var(--bg)" stroke-width="2.5" stroke-linecap="round"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Tubo Curvo</span>
          <span class="prod-desc">Sweep toroidale · angolo libero</span>
        </div>
      </button>

      <button class="prod-card" data-shape="plate-rect" onclick="selectShape('plate-rect',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <rect x="6" y="12" width="24" height="16"/>
            <rect x="6" y="8" width="24" height="4" fill="currentColor" fill-opacity=".15"/>
            <circle cx="11" cy="20" r="2"/>
            <circle cx="25" cy="20" r="2"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Piastra Rettangolare</span>
          <span class="prod-desc">Piana · fori configurabili</span>
        </div>
      </button>

      <button class="prod-card" data-shape="plate-round" onclick="selectShape('plate-round',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <ellipse cx="18" cy="20" rx="12" ry="4"/>
            <ellipse cx="18" cy="16" rx="12" ry="4"/>
            <line x1="6" y1="16" x2="6" y2="20"/>
            <line x1="30" y1="16" x2="30" y2="20"/>
            <circle cx="18" cy="16" r="3"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Piastra Rotonda</span>
          <span class="prod-desc">Disco · foro centrale + periferici</span>
        </div>
      </button>

    </div>

    <div class="divider"></div>
    <div class="sec-head">// Parametri</div>
    <div class="params-wrap" id="params-wrap"></div>

    <button class="btn-gen" onclick="generate()">▶ VISUALIZZA</button>

    <div class="sec-head">// Info Modello</div>
    <div class="stats-row">
      <div class="stat-item"><span class="sv" id="s-x">—</span><span class="sl">Larg.</span></div>
      <div class="stat-item"><span class="sv" id="s-y">—</span><span class="sl">Alt.</span></div>
      <div class="stat-item"><span class="sv" id="s-z">—</span><span class="sl">Prof.</span></div>
    </div>

  </div>

  <!-- VIEWPORT -->
  <div class="viewport">
    <canvas id="cv"></canvas>

    <div class="vp-empty" id="vp-empty">
      <svg class="vp-empty-icon" width="80" height="80" viewBox="0 0 80 80" fill="none" stroke="white" stroke-width="1">
        <path d="M40 10 L70 27 L70 53 L40 70 L10 53 L10 27 Z"/>
        <path d="M40 10 L40 70M10 27 L70 53M70 27 L10 53"/>
      </svg>
      <div class="vp-empty-text">Seleziona un prodotto e premi Visualizza</div>
    </div>

    <div class="vp-corner tl" id="vp-name-tl" style="display:none">
      <span id="vp-shape-label" style="color:var(--accent);font-size:11px;letter-spacing:2px;"></span>
    </div>
    <div class="vp-corner tr">
      <span class="ax ax-x">X</span>
      <span class="ax ax-y">Y</span>
      <span class="ax ax-z">Z</span>
    </div>
    <div class="vp-corner bl">TRASCINA · RUOTA &nbsp;|&nbsp; SCROLL · ZOOM</div>
  </div>

    <!-- SCALE BAR -->
    <div id="scale-bar" style="display:none;position:absolute;bottom:20px;left:50%;transform:translateX(-50%);align-items:center;gap:8px;pointer-events:none;">
      <div style="width:60px;height:2px;background:var(--accent);opacity:.6;"></div>
      <span id="scale-val" style="font-family:var(--mono);font-size:9px;color:var(--accent);letter-spacing:1.5px;opacity:.7;"></span>
      <div style="width:60px;height:2px;background:var(--accent);opacity:.6;"></div>
    </div>

    <!-- CONTROL PAD -->
    <div class="ctrl-pad" id="ctrl-pad">
      <div class="ctrl-label">SPOSTA</div>
      <div class="ctrl-row">
        <div style="width:36px"></div>
        <button class="ctrl-btn" onclick="moveObj(0,1)" title="Avanti">↑</button>
        <div style="width:36px"></div>
      </div>
      <div class="ctrl-row">
        <button class="ctrl-btn" onclick="moveObj(-1,0)" title="Sinistra">←</button>
        <button class="ctrl-btn ctrl-reset" onclick="resetPos()" title="Reset">⌖</button>
        <button class="ctrl-btn" onclick="moveObj(1,0)" title="Destra">→</button>
      </div>
      <div class="ctrl-row">
        <div style="width:36px"></div>
        <button class="ctrl-btn" onclick="moveObj(0,-1)" title="Indietro">↓</button>
        <div style="width:36px"></div>
      </div>
      <div class="ctrl-sep"></div>
      <div class="ctrl-label">RUOTA</div>
      <div class="ctrl-row">
        <button class="ctrl-btn" onclick="rotObj(-1)" title="Ruota -15°">↺</button>
        <button class="ctrl-btn" onclick="rotObj(1)" title="Ruota +15°">↻</button>
      </div>
    </div>

</div>

<script>
// ═══ CONFIGS ═══
const SHAPES = {
  tube: {
    label: 'TUBO DRITTO',
    groups: [
      { label: 'SEZIONE', params: [
        { k:'od', l:'Diametro Esterno', u:'mm', v:30, min:1 },
        { k:'id', l:'Diametro Interno (0=pieno)', u:'mm', v:24, min:0 },
      ]},
      { label: 'LUNGHEZZA', params: [
        { k:'len', l:'Lunghezza', u:'mm', v:500, min:1 },
      ]},
    ]
  },
  tapered: {
    label: 'TUBO CONICO',
    groups: [
      { label: 'SEZIONE BASE', params: [
        { k:'od1', l:'Ø Esterno Base', u:'mm', v:40, min:1 },
        { k:'id1', l:'Ø Interno Base', u:'mm', v:34, min:0 },
      ]},
      { label: 'SEZIONE PUNTA', params: [
        { k:'od2', l:'Ø Esterno Punta', u:'mm', v:25, min:1 },
        { k:'id2', l:'Ø Interno Punta', u:'mm', v:21, min:0 },
      ]},
      { label: 'LUNGHEZZA', params: [
        { k:'len', l:'Lunghezza', u:'mm', v:600, min:1 },
      ]},
    ]
  },
  tapered2: {
    label: 'TUBO MULTI-CONICO',
    dynamic: true,
  },
  bent: {
    label: 'TUBO CURVO',
    groups: [
      { label: 'SEZIONE', params: [
        { k:'od', l:'Diametro Esterno', u:'mm', v:30, min:1 },
        { k:'id', l:'Diametro Interno', u:'mm', v:24, min:0 },
      ]},
      { label: 'CURVA', params: [
        { k:'br', l:'Raggio di Curvatura', u:'mm', v:120, min:10 },
        { k:'ang', l:'Angolo Curva', u:'°', v:90, min:5 },
      ]},
    ]
  },
  'plate-rect': {
    label: 'PIASTRA RETTANGOLARE',
    groups: [
      { label: 'DIMENSIONI', params: [
        { k:'w', l:'Larghezza', u:'mm', v:200, min:1 },
        { k:'h', l:'Altezza', u:'mm', v:150, min:1 },
        { k:'t', l:'Spessore', u:'mm', v:3, min:0.5 },
      ]},
      { label: 'FORI', params: [
        { k:'holes', l:'Numero Fori (0=nessuno)', u:'', v:4, min:0 },
        { k:'hd', l:'Diametro Fori', u:'mm', v:8, min:1 },
        { k:'hm', l:'Margine dai bordi', u:'mm', v:15, min:1 },
      ]},
    ]
  },
  'plate-round': {
    label: 'PIASTRA ROTONDA',
    groups: [
      { label: 'DIMENSIONI', params: [
        { k:'od', l:'Diametro Esterno', u:'mm', v:150, min:1 },
        { k:'t', l:'Spessore', u:'mm', v:3, min:0.5 },
      ]},
      { label: 'FORO CENTRALE', params: [
        { k:'id', l:'Ø Foro Centrale (0=pieno)', u:'mm', v:20, min:0 },
      ]},
      { label: 'FORI PERIFERICI', params: [
        { k:'ph', l:'N° Fori Periferici (0=nessuno)', u:'', v:6, min:0 },
        { k:'phd', l:'Ø Fori Periferici', u:'mm', v:8, min:1 },
        { k:'phr', l:'Raggio Cerchio Fori', u:'mm', v:55, min:5 },
      ]},
    ]
  },
};

let curShape = 'tube';
let mesh = null;
let renderer, scene, camera;
let drag = false, prevM = {x:0,y:0};
let sph = {t:0.5, p:1.1, r:200};
const SEG = 96;

// ═══ THREE INIT ═══
function initThree() {
  const cv = document.getElementById('cv');
  renderer = new THREE.WebGLRenderer({canvas:cv, antialias:true, alpha:true});
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setClearColor(0x0d0d14, 1);

  scene = new THREE.Scene();
  camera = new THREE.PerspectiveCamera(40, 1, 0.1, 20000);
  camPos();

  // Lights
  scene.add(new THREE.AmbientLight(0xffffff, 0.55));
  const d1 = new THREE.DirectionalLight(0xffffff, 2.5); d1.position.set(200,300,300); scene.add(d1);
  const d2 = new THREE.DirectionalLight(0xc8a96e, 1.4); d2.position.set(-300,-100,200); scene.add(d2);
  const d3 = new THREE.DirectionalLight(0x8ab4ff, 0.7); d3.position.set(0,400,-200); scene.add(d3);
  const d4 = new THREE.DirectionalLight(0xffffff, 0.8); d4.position.set(-200,100,300); scene.add(d4);

  // Grid
  window._grid = new THREE.GridHelper(1000, 40, 0x1e1e24, 0x141418);
  scene.add(window._grid);

  new ResizeObserver(onResize).observe(cv.parentElement);
  onResize();

  cv.addEventListener('mousedown', e => { drag=true; prevM={x:e.clientX,y:e.clientY}; });
  window.addEventListener('mouseup', () => drag=false);
  window.addEventListener('mousemove', e => {
    if (!drag) return;
    sph.t -= (e.clientX-prevM.x)*0.008;
    sph.p = Math.max(0.15, Math.min(Math.PI-0.15, sph.p-(e.clientY-prevM.y)*0.008));
    prevM = {x:e.clientX, y:e.clientY};
    camPos();
  });
  cv.addEventListener('wheel', e => {
    sph.r = Math.max(10, sph.r + e.deltaY * 0.5);
    camPos();
  }, {passive:true});

  let lTouch = null;
  cv.addEventListener('touchstart', e => { lTouch=e.touches[0]; });
  cv.addEventListener('touchmove', e => {
    if (!lTouch) return;
    const t=e.touches[0];
    sph.t -= (t.clientX-lTouch.clientX)*0.01;
    sph.p = Math.max(0.15, Math.min(Math.PI-0.15, sph.p-(t.clientY-lTouch.clientY)*0.01));
    lTouch=t; camPos(); e.preventDefault();
  }, {passive:false});

  (function loop(){ requestAnimationFrame(loop); renderer.render(scene,camera); })();
}

function camPos() {
  camera.position.set(
    sph.r*Math.sin(sph.p)*Math.sin(sph.t),
    sph.r*Math.cos(sph.p),
    sph.r*Math.sin(sph.p)*Math.cos(sph.t)
  );
  camera.lookAt(0,0,0);
}

function onResize() {
  const vp = document.querySelector('.viewport');
  const w=vp.clientWidth, h=vp.clientHeight;
  renderer.setSize(w,h);
  camera.aspect=w/h;
  camera.updateProjectionMatrix();
}

// ═══ UI ═══
function selectShape(s, btn) {
  curShape = s;
  document.querySelectorAll('.prod-card').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  renderParams();
}

// Dynamic sections state
let dynSections = [
  {od:40, id:34, len:200},
  {od:25, id:21, len:300},
];

function renderParams() {
  const cfg = SHAPES[curShape];
  const wrap = document.getElementById('params-wrap');

  if (cfg.dynamic) {
    renderDynamic(wrap);
    return;
  }

  wrap.innerHTML = '<div id="error-msg" class="error-msg"></div>' + cfg.groups.map(g => `
    <div class="param-group-label">${g.label}</div>
    ${g.params.map(p => `
      <div class="param-row">
        <label>${p.l.toUpperCase()}<span class="unit">${p.u}</span></label>
        <input type="number" id="p_${p.k}" value="${p.v}" min="${p.min}" step="0.1">
      </div>
    `).join('')}
  `).join('');
}

function renderDynamic(wrap) {
  const maxSec = 15;
  wrap.innerHTML = '<div id="error-msg" class="error-msg"></div>' + dynSections.map((s, i) => {
    const isLast = i === dynSections.length - 1;
    const label = i === 0 ? 'NODO 1 · INIZIO' : isLast ? `NODO ${i+1} · FINE` : `NODO ${i+1}`;
    return `
    <div class="section-block">
      <div class="section-block-head">
        <span class="section-block-label">${label}</span>
        ${dynSections.length > 2 ? `<button class="btn-remove-sec" onclick="removeSection(${i})">✕</button>` : ''}
      </div>
      <div class="param-row">
        <label>Ø ESTERNO<span class="unit">mm</span></label>
        <input type="number" id="ds_od_${i}" value="${s.od}" min="1" step="0.1">
      </div>
      <div class="param-row">
        <label>Ø INTERNO<span class="unit">mm</span></label>
        <input type="number" id="ds_id_${i}" value="${s.id}" min="0" step="0.1">
      </div>
      ${!isLast ? `
      <div class="param-row" style="margin-top:4px; border-top:1px solid var(--border); padding-top:6px;">
        <label>↕ LUNGHEZZA TRATTO VERSO NODO ${i+2}<span class="unit">mm</span></label>
        <input type="number" id="ds_len_${i}" value="${s.len}" min="1" step="1">
      </div>` : `
      <div style="font-family:var(--mono);font-size:8px;color:var(--muted);margin-top:4px;letter-spacing:1px;">
        ← nodo finale: definisce solo il Ø
      </div>`}
    </div>`;
  }).join('') + `
    ${dynSections.length < maxSec ? `<button class="btn-add-sec" onclick="addSection()">+ AGGIUNGI NODO (${dynSections.length}/${maxSec})</button>` : `<div style="font-family:var(--mono);font-size:9px;color:var(--muted);text-align:center;padding:6px;">LIMITE ${maxSec} NODI RAGGIUNTO</div>`}
  `;
}

function addSection() {
  const last = dynSections[dynSections.length-1];
  saveDynState();
  dynSections.push({od: last.od, id: last.id, len: 200});
  renderDynamic(document.getElementById('params-wrap'));
}

function removeSection(i) {
  saveDynState();
  dynSections.splice(i, 1);
  renderDynamic(document.getElementById('params-wrap'));
}

function saveDynState() {
  dynSections.forEach((s, i) => {
    const od = document.getElementById('ds_od_'+i);
    const id = document.getElementById('ds_id_'+i);
    const len = document.getElementById('ds_len_'+i);
    if (od) s.od = parseFloat(od.value)||s.od;
    if (id) s.id = parseFloat(id.value)||0;
    if (len) s.len = parseFloat(len.value)||s.len;
  });
}

function getDynSections() {
  return dynSections.map((s,i) => ({
    od: parseFloat(document.getElementById('ds_od_'+i)?.value)||s.od,
    id: parseFloat(document.getElementById('ds_id_'+i)?.value)||0,
    len: parseFloat(document.getElementById('ds_len_'+i)?.value)||s.len,
  }));
}

function getP() {
  const cfg = SHAPES[curShape];
  const out = {};
  if (!cfg.groups) return out;
  cfg.groups.forEach(g => g.params.forEach(p => {
    out[p.k] = parseFloat(document.getElementById('p_'+p.k)?.value ?? p.v) || 0;
  }));
  return out;
}

// ═══ MATERIAL ═══
function makeMat() {
  return new THREE.MeshPhongMaterial({
    color: 0x2a2a38,
    specular: 0xe8d080,
    shininess: 200,
    side: THREE.DoubleSide,
  });
}
function makeWire() {
  return new THREE.MeshBasicMaterial({
    color: 0xc8a96e,
    wireframe: true,
    opacity: 0.06,
    transparent: true,
  });
}

// ═══ GEOMETRY BUILDERS ═══

// Hollow cylinder from z=0 to z=len, axis Z
function hollowCyl(rOut, rIn, len) {
  if (!rOut || rOut <= 0 || len <= 0 || isNaN(rOut) || isNaN(len)) throw new Error('Parametri tubo non validi');
  const pos=[], nor=[];
  function pushTri(v0,v1,v2){
    const ax=v1[0]-v0[0],ay=v1[1]-v0[1],az=v1[2]-v0[2];
    const bx=v2[0]-v0[0],by=v2[1]-v0[1],bz=v2[2]-v0[2];
    let nx=ay*bz-az*by,ny=az*bx-ax*bz,nz=ax*by-ay*bx;
    const l=Math.sqrt(nx*nx+ny*ny+nz*nz)||1;
    nx/=l;ny/=l;nz/=l;
    for(const v of [v0,v1,v2]){pos.push(...v);nor.push(nx,ny,nz);}
  }
  const solid = rIn < 0.5 || rIn >= rOut;
  for(let i=0;i<SEG;i++){
    const a0=2*Math.PI*i/SEG, a1=2*Math.PI*(i+1)/SEG;
    const co0=rOut*Math.cos(a0),so0=rOut*Math.sin(a0);
    const co1=rOut*Math.cos(a1),so1=rOut*Math.sin(a1);
    // outer wall
    pushTri([co0,so0,0],[co1,so1,0],[co1,so1,len]);
    pushTri([co0,so0,0],[co1,so1,len],[co0,so0,len]);
    if(!solid){
      const ci0=rIn*Math.cos(a0),si0=rIn*Math.sin(a0);
      const ci1=rIn*Math.cos(a1),si1=rIn*Math.sin(a1);
      // inner wall
      pushTri([ci0,si0,0],[ci1,si1,len],[ci1,si1,0]);
      pushTri([ci0,si0,0],[ci0,si0,len],[ci1,si1,len]);
      // caps (annulus)
      pushTri([ci0,si0,0],[co0,so0,0],[co1,so1,0]);
      pushTri([ci0,si0,0],[co1,so1,0],[ci1,si1,0]);
      pushTri([ci0,si0,len],[co1,so1,len],[co0,so0,len]);
      pushTri([ci0,si0,len],[ci1,si1,len],[co1,so1,len]);
    } else {
      // solid caps — fan
      pushTri([0,0,0],[co1,so1,0],[co0,so0,0]);
      pushTri([0,0,len],[co0,so0,len],[co1,so1,len]);
    }
  }
  const g=new THREE.BufferGeometry();
  g.setAttribute('position',new THREE.Float32BufferAttribute(pos,3));
  g.setAttribute('normal',new THREE.Float32BufferAttribute(nor,3));
  return g;
}

// Frustum (tapered hollow tube) z=0 to z=len
function frustumTube(rOut1, rIn1, rOut2, rIn2, len) {
  if (!rOut1||!rOut2||len<=0||isNaN(rOut1)||isNaN(rOut2)||isNaN(len)) throw new Error('Parametri frustum non validi');
  const pos=[], nor=[];
  function pushTri(v0,v1,v2){
    const ax=v1[0]-v0[0],ay=v1[1]-v0[1],az=v1[2]-v0[2];
    const bx=v2[0]-v0[0],by=v2[1]-v0[1],bz=v2[2]-v0[2];
    let nx=ay*bz-az*by,ny=az*bx-ax*bz,nz=ax*by-ay*bx;
    const l=Math.sqrt(nx*nx+ny*ny+nz*nz)||1;
    nx/=l;ny/=l;nz/=l;
    for(const v of [v0,v1,v2]){pos.push(...v);nor.push(nx,ny,nz);}
  }
  const hollow = rIn1>0.5 && rIn2>0.5;
  for(let i=0;i<SEG;i++){
    const a0=2*Math.PI*i/SEG, a1=2*Math.PI*(i+1)/SEG;
    const o0b=[rOut1*Math.cos(a0),rOut1*Math.sin(a0),0];
    const o1b=[rOut1*Math.cos(a1),rOut1*Math.sin(a1),0];
    const o0t=[rOut2*Math.cos(a0),rOut2*Math.sin(a0),len];
    const o1t=[rOut2*Math.cos(a1),rOut2*Math.sin(a1),len];
    pushTri(o0b,o1b,o1t); pushTri(o0b,o1t,o0t);
    if(hollow){
      const i0b=[rIn1*Math.cos(a0),rIn1*Math.sin(a0),0];
      const i1b=[rIn1*Math.cos(a1),rIn1*Math.sin(a1),0];
      const i0t=[rIn2*Math.cos(a0),rIn2*Math.sin(a0),len];
      const i1t=[rIn2*Math.cos(a1),rIn2*Math.sin(a1),len];
      pushTri(i0b,i1t,i1b); pushTri(i0b,i0t,i1t);
      // bottom cap
      pushTri(i0b,o0b,o1b); pushTri(i0b,o1b,i1b);
      // top cap
      pushTri(i0t,o1t,o0t); pushTri(i0t,i1t,o1t);
    } else {
      pushTri([0,0,0],o1b,o0b);
      pushTri([0,0,len],o0t,o1t);
    }
  }
  const g=new THREE.BufferGeometry();
  g.setAttribute('position',new THREE.Float32BufferAttribute(pos,3));
  g.setAttribute('normal',new THREE.Float32BufferAttribute(nor,3));
  return g;
}

// Bent tube — toroidal sweep
function bentTube(rTube, rHole, bendR, angleDeg) {
  if (!rTube||rTube<=0||bendR<=0||angleDeg<=0||angleDeg>360) throw new Error('Parametri tubo curvo non validi');
  if (rHole > 0 && rHole >= rTube) throw new Error('Ø interno ≥ Ø esterno nel tubo curvo');
  const ang = angleDeg*Math.PI/180;
  const seg1=Math.max(32, Math.round(angleDeg/2));
  const seg2=24;
  const pos=[],nor=[];
  function pushTri(v0,v1,v2){
    const ax=v1[0]-v0[0],ay=v1[1]-v0[1],az=v1[2]-v0[2];
    const bx=v2[0]-v0[0],by=v2[1]-v0[1],bz=v2[2]-v0[2];
    let nx=ay*bz-az*by,ny=az*bx-ax*bz,nz=ax*by-ay*bx;
    const l=Math.sqrt(nx*nx+ny*ny+nz*nz)||1;
    nx/=l;ny/=l;nz/=l;
    for(const v of [v0,v1,v2]){pos.push(...v);nor.push(nx,ny,nz);}
  }
  // ring radii
  const rings = rHole>0.5 ? [rTube/2, rHole/2] : [rTube/2, 0];
  // build section circles along the sweep
  function sectionPts(phi, r) {
    const pts=[];
    const cx=(bendR)*Math.cos(phi), cz=(bendR)*Math.sin(phi);
    const tx=-Math.sin(phi), tz=Math.cos(phi); // tangent
    const nx2=Math.cos(phi), nz2=Math.sin(phi); // radial (outward)
    for(let j=0;j<seg2;j++){
      const a=2*Math.PI*j/seg2;
      const dr=r*Math.cos(a), dy=r*Math.sin(a);
      pts.push([cx+dr*nx2, dy, cz+dr*nz2]);
    }
    return pts;
  }
  for(let ri=0;ri<rings.length;ri++){
    const r=rings[ri];
    if(r<0.1) continue;
    const reverse=ri===1; // inner wall reversed
    for(let i=0;i<seg1;i++){
      const phi0=ang*i/seg1, phi1=ang*(i+1)/seg1;
      const p0=sectionPts(phi0,r), p1=sectionPts(phi1,r);
      for(let j=0;j<seg2;j++){
        const j1=(j+1)%seg2;
        const v00=p0[j],v01=p0[j1],v10=p1[j],v11=p1[j1];
        if(!reverse){
          pushTri(v00,v01,v11); pushTri(v00,v11,v10);
        } else {
          pushTri(v00,v11,v01); pushTri(v00,v10,v11);
        }
      }
    }
  }
  // End caps (annular rings)
  if(rHole>0.5){
    function annulusCap(phi, flip){
      const cx=bendR*Math.cos(phi), cz=bendR*Math.sin(phi);
      const nr=Math.cos(phi), nz2=Math.sin(phi);
      for(let j=0;j<seg2;j++){
        const j1=(j+1)%seg2;
        const a0=2*Math.PI*j/seg2, a1=2*Math.PI*j1/seg2;
        const io0=[cx+rHole/2*Math.cos(a0)*nr, rHole/2*Math.sin(a0), cz+rHole/2*Math.cos(a0)*nz2];
        const io1=[cx+rHole/2*Math.cos(a1)*nr, rHole/2*Math.sin(a1), cz+rHole/2*Math.cos(a1)*nz2];
        const oo0=[cx+rTube/2*Math.cos(a0)*nr, rTube/2*Math.sin(a0), cz+rTube/2*Math.cos(a0)*nz2];
        const oo1=[cx+rTube/2*Math.cos(a1)*nr, rTube/2*Math.sin(a1), cz+rTube/2*Math.cos(a1)*nz2];
        if(!flip){pushTri(io0,oo0,oo1);pushTri(io0,oo1,io1);}
        else {pushTri(io0,oo1,oo0);pushTri(io0,io1,oo1);}
      }
    }
    annulusCap(0, false); annulusCap(ang, true);
  }
  const g=new THREE.BufferGeometry();
  g.setAttribute('position',new THREE.Float32BufferAttribute(pos,3));
  g.setAttribute('normal',new THREE.Float32BufferAttribute(nor,3));
  return g;
}

// Rectangular plate with holes
function plateRect(w, h, t, nHoles, hd, margin) {
  const pos=[],nor=[];
  function pushTri(v0,v1,v2){
    const ax=v1[0]-v0[0],ay=v1[1]-v0[1],az=v1[2]-v0[2];
    const bx=v2[0]-v0[0],by=v2[1]-v0[1],bz=v2[2]-v0[2];
    let nx=ay*bz-az*by,ny=az*bx-ax*bz,nz=ax*by-ay*bx;
    const l=Math.sqrt(nx*nx+ny*ny+nz*nz)||1;
    nx/=l;ny/=l;nz/=l;
    for(const v of [v0,v1,v2]){pos.push(...v);nor.push(nx,ny,nz);}
  }
  function box(x0,x1,y0,y1,z0,z1){
    pushTri([x1,y0,z0],[x1,y1,z0],[x1,y1,z1]);pushTri([x1,y0,z0],[x1,y1,z1],[x1,y0,z1]);
    pushTri([x0,y0,z0],[x0,y1,z1],[x0,y1,z0]);pushTri([x0,y0,z0],[x0,y0,z1],[x0,y1,z1]);
    pushTri([x0,y1,z0],[x1,y1,z0],[x1,y1,z1]);pushTri([x0,y1,z0],[x1,y1,z1],[x0,y1,z1]);
    pushTri([x0,y0,z0],[x1,y0,z1],[x1,y0,z0]);pushTri([x0,y0,z0],[x0,y0,z1],[x1,y0,z1]);
    pushTri([x0,y0,z1],[x1,y0,z1],[x1,y1,z1]);pushTri([x0,y0,z1],[x1,y1,z1],[x0,y1,z1]);
    pushTri([x0,y0,z0],[x1,y1,z0],[x1,y0,z0]);pushTri([x0,y0,z0],[x0,y1,z0],[x1,y1,z0]);
  }
  box(-w/2,w/2,-h/2,h/2,0,t);
  if(nHoles>0 && hd>0){
    const hs=16;
    const positions=[];
    if(nHoles===4){
      positions.push([-(w/2-margin),-(h/2-margin)]);
      positions.push([ (w/2-margin),-(h/2-margin)]);
      positions.push([-(w/2-margin), (h/2-margin)]);
      positions.push([ (w/2-margin), (h/2-margin)]);
    } else if(nHoles===2){
      positions.push([-(w/2-margin),0]);
      positions.push([ (w/2-margin),0]);
    } else {
      for(let i=0;i<nHoles;i++){
        const a=2*Math.PI*i/nHoles;
        const rx=Math.min(w/2-margin, h/2-margin);
        positions.push([rx*Math.cos(a), rx*Math.sin(a)]);
      }
    }
    positions.forEach(([cx,cy])=>{
      const r=hd/2;
      for(let j=0;j<hs;j++){
        const a0=2*Math.PI*j/hs, a1=2*Math.PI*(j+1)/hs;
        const x0=cx+r*Math.cos(a0),y0=cy+r*Math.sin(a0);
        const x1=cx+r*Math.cos(a1),y1=cy+r*Math.sin(a1);
        const x0i=cx+(r-1)*Math.cos(a0),y0i=cy+(r-1)*Math.sin(a0);
        const x1i=cx+(r-1)*Math.cos(a1),y1i=cy+(r-1)*Math.sin(a1);
        pushTri([x0i,y0i,t+0.01],[x0,y0,t+0.01],[x1,y1,t+0.01]);
        pushTri([x0i,y0i,t+0.01],[x1,y1,t+0.01],[x1i,y1i,t+0.01]);
      }
    });
  }
  const g=new THREE.BufferGeometry();
  g.setAttribute('position',new THREE.Float32BufferAttribute(pos,3));
  g.setAttribute('normal',new THREE.Float32BufferAttribute(nor,3));
  return g;
}

// Round plate
function plateRound(od, t, id, nPH, phd, phr) {
  const ro=od/2, ri=id/2, hollow=ri>0.5&&ri<ro;
  const pos=[],nor=[];
  function pushTri(v0,v1,v2){
    const ax=v1[0]-v0[0],ay=v1[1]-v0[1],az=v1[2]-v0[2];
    const bx=v2[0]-v0[0],by=v2[1]-v0[1],bz=v2[2]-v0[2];
    let nx=ay*bz-az*by,ny=az*bx-ax*bz,nz=ax*by-ay*bx;
    const l=Math.sqrt(nx*nx+ny*ny+nz*nz)||1;
    nx/=l;ny/=l;nz/=l;
    for(const v of [v0,v1,v2]){pos.push(...v);nor.push(nx,ny,nz);}
  }
  for(let i=0;i<SEG;i++){
    const a0=2*Math.PI*i/SEG,a1=2*Math.PI*(i+1)/SEG;
    const co0=ro*Math.cos(a0),so0=ro*Math.sin(a0);
    const co1=ro*Math.cos(a1),so1=ro*Math.sin(a1);
    // outer wall
    pushTri([co0,so0,0],[co1,so1,0],[co1,so1,t]);
    pushTri([co0,so0,0],[co1,so1,t],[co0,so0,t]);
    if(hollow){
      const ci0=ri*Math.cos(a0),si0=ri*Math.sin(a0);
      const ci1=ri*Math.cos(a1),si1=ri*Math.sin(a1);
      pushTri([ci0,si0,0],[ci1,si1,t],[ci1,si1,0]);
      pushTri([ci0,si0,0],[ci0,si0,t],[ci1,si1,t]);
      pushTri([ci0,si0,0],[co0,so0,0],[co1,so1,0]);pushTri([ci0,si0,0],[co1,so1,0],[ci1,si1,0]);
      pushTri([ci0,si0,t],[co1,so1,t],[co0,so0,t]);pushTri([ci0,si0,t],[ci1,si1,t],[co1,so1,t]);
    } else {
      pushTri([0,0,0],[co1,so1,0],[co0,so0,0]);
      pushTri([0,0,t],[co0,so0,t],[co1,so1,t]);
    }
  }
  // Peripheral holes markers
  if(nPH>0&&phd>0){
    const hs=16, hr=phd/2;
    for(let i=0;i<nPH;i++){
      const a=2*Math.PI*i/nPH;
      const cx=phr*Math.cos(a),cy=phr*Math.sin(a);
      for(let j=0;j<hs;j++){
        const b0=2*Math.PI*j/hs,b1=2*Math.PI*(j+1)/hs;
        const x0=cx+hr*Math.cos(b0),y0=cy+hr*Math.sin(b0);
        const x1=cx+hr*Math.cos(b1),y1=cy+hr*Math.sin(b1);
        const x0i=cx+(hr-0.8)*Math.cos(b0),y0i=cy+(hr-0.8)*Math.sin(b0);
        const x1i=cx+(hr-0.8)*Math.cos(b1),y1i=cy+(hr-0.8)*Math.sin(b1);
        pushTri([x0i,y0i,t+0.01],[x0,y0,t+0.01],[x1,y1,t+0.01]);
        pushTri([x0i,y0i,t+0.01],[x1,y1,t+0.01],[x1i,y1i,t+0.01]);
      }
    }
  }
  const g=new THREE.BufferGeometry();
  g.setAttribute('position',new THREE.Float32BufferAttribute(pos,3));
  g.setAttribute('normal',new THREE.Float32BufferAttribute(nor,3));
  return g;
}


function showError(msg) {
  const el = document.getElementById('error-msg');
  if (!el) return;
  if (msg) { el.textContent = '⚠ ' + msg; el.classList.add('show'); }
  else { el.textContent=''; el.classList.remove('show'); }
}

function validateInputs(p) {
  const pairs = [];
  if (curShape === 'tube')   pairs.push({od:p.od, id:p.id, label:'tubo'});
  if (curShape === 'tapered') {
    pairs.push({od:p.od1, id:p.id1, label:'base'});
    pairs.push({od:p.od2, id:p.id2, label:'punta'});
  }
  if (curShape === 'bent')   pairs.push({od:p.od, id:p.id, label:'tubo'});
  if (curShape === 'plate-round') pairs.push({od:p.od, id:p.id, label:'piastra'});
  for (const {od, id, label} of pairs) {
    if (id > 0 && id >= od) return `Ø interno (${id}mm) ≥ Ø esterno (${od}mm) — ${label}`;
    if (id > 0 && (od-id)/2 < 0.4) return `Parete troppo sottile (${((od-id)/2).toFixed(1)}mm) — ${label}`;
  }
  return null;
}

function validateDynSections(sections) {
  for (let i=0; i<sections.length; i++) {
    const s = sections[i];
    if (s.id > 0 && s.id >= s.od) return `Sezione ${i+1}: Ø interno (${s.id}mm) ≥ Ø esterno (${s.od}mm)`;
    if (s.id > 0 && (s.od-s.id)/2 < 0.4) return `Sezione ${i+1}: parete troppo sottile (${((s.od-s.id)/2).toFixed(1)}mm)`;
  }
  return null;
}

// ═══ GENERATE ═══
function generate() {
  showError(null);
  const p = getP();
  let geo;

  if (curShape !== 'tapered2') {
    const err = validateInputs(p);
    if (err) { showError(err); return; }
  }

  switch(curShape) {
    case 'tube':
      geo = hollowCyl(p.od/2, p.id/2, p.len);
      break;
    case 'tapered':
      geo = frustumTube(p.od1/2, p.id1/2, p.od2/2, p.id2/2, p.len);
      break;
    case 'tapered2': {
      saveDynState();
      const secs = getDynSections();
      const dynErr = validateDynSections(secs);
      if (dynErr) { showError(dynErr); return; }
      const geos = [];
      let zOff = 0;
      for (let i=0; i<secs.length-1; i++) {
        const g = frustumTube(secs[i].od/2,secs[i].id/2,secs[i+1].od/2,secs[i+1].id/2,secs[i].len);
        const arr = g.attributes.position.array;
        for(let j=2;j<arr.length;j+=3) arr[j]+=zOff;
        g.attributes.position.needsUpdate=true;
        geos.push(g);
        zOff += secs[i].len;
      }
      geo = mergeGeos(geos);
      break;
    }
    case 'bent':
      geo = bentTube(p.od, p.id, p.br, p.ang);
      break;
    case 'plate-rect':
      geo = plateRect(p.w,p.h,p.t,p.holes,p.hd,p.hm);
      break;
    case 'plate-round':
      geo = plateRound(p.od,p.t,p.id,p.ph,p.phd,p.phr);
      break;
  }

  if (!geo) { showError('Geometria non generata — controlla i parametri.'); return; }

  try {
    // Remove old mesh (dispose geometria+materiali per evitare memory leak GPU)
    if(mesh){
      scene.remove(mesh);
      mesh.traverse(obj => {
        if (obj.geometry) obj.geometry.dispose();
        if (obj.material) obj.material.dispose();
      });
      mesh=null;
    }

    const group = new THREE.Group();
    group.add(new THREE.Mesh(geo, makeMat()));
    group.add(new THREE.Mesh(geo, makeWire()));

    // Center on scene
    geo.computeBoundingBox();
    const bb=geo.boundingBox;
    if (!bb || isNaN(bb.min.x)) throw new Error('Bounding box non valida');
    const cx=(bb.max.x+bb.min.x)/2, cz=(bb.max.z+bb.min.z)/2;
    group.position.set(-cx, -bb.min.y, -cz);
    scene.add(group);
    mesh=group;
  } catch(e) {
    showError('Errore rendering: ' + e.message);
    return;
  }

  // Camera fit
  const sz=new THREE.Vector3(); bb.getSize(sz);
  const maxDim = Math.max(sz.x, sz.y, sz.z);
  sph.r = maxDim * 2.2;
  camPos();

  // Update grid to match model size (dispose geometria+materiale precedenti)
  if (window._grid) { scene.remove(window._grid); window._grid.geometry.dispose(); window._grid.material.dispose(); }
  const gridSize = Math.pow(10, Math.ceil(Math.log10(maxDim * 1.5)));
  const gridDivs = 40;
  window._grid = new THREE.GridHelper(gridSize, gridDivs, 0x1e1e24, 0x141418);
  scene.add(window._grid);

  // Scale indicator
  const scaleUnit = gridSize / gridDivs;
  const scaleLabel = scaleUnit >= 1000 ? (scaleUnit/1000).toFixed(1)+'m' : scaleUnit.toFixed(0)+'mm';
  const scaleEl = document.getElementById('scale-bar');
  if (scaleEl) {
    scaleEl.style.display = 'flex';
    document.getElementById('scale-val').textContent = scaleLabel + ' / cella';
  }

  // Stats
  try {
    document.getElementById('s-x').textContent = sz.x.toFixed(1)+'mm';
    document.getElementById('s-y').textContent = sz.y.toFixed(1)+'mm';
    document.getElementById('s-z').textContent = sz.z.toFixed(1)+'mm';
  } catch(e) { console.warn('Stats update failed:', e); }

  // Show name
  document.getElementById('vp-empty').style.display='none';
  document.getElementById('vp-name-tl').style.display='block';
  document.getElementById('vp-shape-label').textContent = SHAPES[curShape].label;
  document.getElementById('ctrl-pad').classList.add('visible');
}

function mergeGeos(geos){
  if (!geos || geos.length === 0) throw new Error('Nessuna geometria da unire');
  const pos=[], nor=[];
  geos.forEach(g=>{
    const ni=g.toNonIndexed();
    ni.computeVertexNormals();
    const p=ni.attributes.position, n=ni.attributes.normal;
    for(let i=0;i<p.count;i++){
      pos.push(p.getX(i),p.getY(i),p.getZ(i));
      nor.push(n.getX(i),n.getY(i),n.getZ(i));
    }
    // dispose geometrie intermedie usate solo per il merge
    g.dispose();
    ni.dispose();
  });
  const g=new THREE.BufferGeometry();
  g.setAttribute('position',new THREE.Float32BufferAttribute(pos,3));
  g.setAttribute('normal',new THREE.Float32BufferAttribute(nor,3));
  return g;
}


// ═══ OBJECT CONTROLS ═══
const STEP = 10; // mm per click

function moveObj(dx, dz) {
  if (!mesh) return;
  mesh.position.x += dx * STEP;
  mesh.position.z += dz * STEP;
}

function rotObj(dir) {
  if (!mesh) return;
  mesh.rotation.y += dir * (15 * Math.PI / 180);
}

function resetPos() {
  if (!mesh) return;
  mesh.position.x = 0;
  mesh.position.z = 0;
  mesh.rotation.y = 0;
}

// Keyboard support
window.addEventListener('keydown', e => {
  if (!mesh) return;
  switch(e.key) {
    case 'ArrowUp':    moveObj(0, 1);  e.preventDefault(); break;
    case 'ArrowDown':  moveObj(0, -1); e.preventDefault(); break;
    case 'ArrowLeft':  moveObj(-1, 0); e.preventDefault(); break;
    case 'ArrowRight': moveObj(1, 0);  e.preventDefault(); break;
    case 'q': rotObj(-1); break;
    case 'e': rotObj(1);  break;
    case 'r': resetPos(); break;
  }
});

// ═══ GLOBAL ERROR HANDLER ═══
window.addEventListener('error', e => {
  console.error('Uncaught error:', e.message);
  showError('Errore imprevisto: ' + (e.message || 'sconosciuto'));
});

// ═══ BOOT ═══
renderParams();
try {
  initThree();
} catch(e) {
  document.querySelector('.vp-empty-text').textContent = 'Errore inizializzazione 3D: ' + e.message;
  console.error(e);
}
</script>
</body>
</html>
