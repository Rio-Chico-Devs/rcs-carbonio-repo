<?php
/*
Template Name: Laboratorio 3D
Description: Configuratore 3D (tubi, piastre, treppiede, tubo telescopico) integrato nel layout del sito, con navbar e footer come le altre pagine.
*/
get_header();
?>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/js/three.min.js"></script>
<style>
/* Font Teko/Inter gia' locali (assets/fonts/fonts.css, caricati dal tema), nessuna dipendenza esterna.
   Variabili scoped a .rcs-lab3d-shell (non su :root) per non toccare il resto del sito, che ora
   condivide la stessa pagina con navbar e footer. */

.rcs-lab3d-shell {
  --bg:       #080809;
  --surface:  #0f0f12;
  --panel:    #141418;
  --border:   #222228;
  --border2:  #2e2e38;
  --accent:   #fdff00;
  --accent2:  #6ec8b4;
  --text:     #e0ddd8;
  --muted:    #55555f;
  --danger:   #c86e6e;
  --font:     'Teko', sans-serif;
  --mono:     'Inter', sans-serif;
}

body.light-mode .rcs-lab3d-shell {
  --bg:       #f8f9fa;
  --surface:  #ffffff;
  --panel:    #f1f1f3;
  --border:   #e2e2e5;
  --border2:  #d0d0d5;
  --accent:   #0099ff;
  --text:     #1a1a1a;
  --muted:    #58585d;
}

.rcs-lab3d-shell, .rcs-lab3d-shell * { box-sizing: border-box; }
.rcs-lab3d-shell {
  background: var(--bg);
  color: var(--text);
  font-family: var(--font);
  max-width: 1920px;
  margin: 0 auto;
  padding: 40px 40px 60px;
}
.rcs-lab3d-title {
  font-family: var(--font);
  font-size: clamp(2rem, 5vw, 3rem);
  text-transform: uppercase;
  letter-spacing: 2px;
  color: var(--text);
  margin-bottom: 20px;
}
.rcs-lab3d-title span { color: var(--accent); }

/* ═══ LAYOUT ═══ */
.layout {
  height: 78vh;
  min-height: 560px;
  max-height: 900px;
  display: flex;
  border: 1px solid var(--border2);
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
  border-radius: 12px;
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
  border-radius: 12px;
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
  border-radius: 50px;
  font-family: var(--font);
  font-size: 16px;
  font-weight: 600;
  letter-spacing: 3px;
  text-transform: uppercase;
  cursor: pointer;
  transition: all .15s;
}
.btn-gen:hover { filter: brightness(1.12); }
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
  border-radius: 12px;
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
  border-radius: 12px;
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
  border-radius: 8px;
  color: var(--text);
  font-size: 16px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .1s;
  backdrop-filter: blur(4px);
  font-family: var(--mono);
}
.ctrl-btn:hover { filter: brightness(1.3); border-color: var(--accent); color: var(--accent); }
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

/* ═══ PART INFO PANEL (click su un pezzo dell'assemblaggio) ═══ */
.part-info-panel {
  position: absolute;
  left: 20px; bottom: 20px;
  width: 300px;
  background: rgba(15,15,18,.95);
  border: 1px solid var(--accent);
  border-radius: 12px;
  padding: 16px 18px;
  backdrop-filter: blur(6px);
  display: none;
  flex-direction: column;
  gap: 8px;
  cursor: default;
}
.part-info-panel.visible { display: flex; }
.part-info-panel .pi-close {
  position: absolute;
  top: 6px; right: 8px;
  background: none;
  border: none;
  color: var(--muted);
  cursor: pointer;
  font-size: 13px;
  font-family: var(--mono);
}
.part-info-panel .pi-close:hover { color: var(--danger); }
.part-info-panel .pi-label {
  font-family: var(--font);
  font-size: 22px;
  font-weight: 600;
  letter-spacing: 1px;
  color: var(--accent);
  text-transform: uppercase;
  line-height: 1;
}
.part-info-panel .pi-text {
  font-family: var(--mono);
  font-size: 11px;
  line-height: 1.7;
  color: var(--text);
  opacity: .85;
}
.part-info-panel .pi-cta {
  margin-top: 4px;
  align-self: flex-start;
  padding: 9px 18px;
  background: var(--accent);
  color: #080809;
  border: none;
  border-radius: 50px;
  font-family: var(--font);
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  cursor: pointer;
  text-decoration: none;
  transition: filter .15s;
}
.part-info-panel .pi-cta:hover { filter: brightness(1.12); }
</style>

<div class="rcs-lab3d-shell">
<h1 class="rcs-lab3d-title">Configuratore <span>3D</span></h1>

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
    <div class="sec-head">// Esempi di Utilizzo</div>
    <div class="prod-list" id="usage-list">

      <button class="prod-card" data-shape="tripod" onclick="selectShape('tripod',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <line x1="18" y1="8" x2="6" y2="30"/>
            <line x1="18" y1="8" x2="18" y2="31"/>
            <line x1="18" y1="8" x2="30" y2="30"/>
            <line x1="18" y1="3" x2="18" y2="8"/>
            <circle cx="18" cy="8" r="2.5"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Treppiede</span>
          <span class="prod-desc">3 gambe · testa · colonna telescopica</span>
        </div>
      </button>

      <button class="prod-card" data-shape="telescopic" onclick="selectShape('telescopic',this)">
        <div class="prod-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2">
            <rect x="4" y="15" width="12" height="6"/>
            <rect x="14" y="16.5" width="10" height="3"/>
            <rect x="22" y="17.2" width="8" height="1.6"/>
          </svg>
        </div>
        <div class="prod-info">
          <span class="prod-name">Tubo Telescopico</span>
          <span class="prod-desc">4 sezioni · allungamento variabile</span>
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
    <div class="vp-corner bl" id="vp-hint">TRASCINA · RUOTA &nbsp;|&nbsp; SCROLL · ZOOM</div>

    <div class="part-info-panel" id="part-info-panel">
      <button class="pi-close" onclick="hideInfoPanel()">✕ CHIUDI</button>
      <div class="pi-label" id="part-info-label"></div>
      <div class="pi-text" id="part-info-text"></div>
      <a class="pi-cta" href="<?php echo esc_url(home_url('/contatti')); ?>">Richiedi Preventivo</a>
    </div>
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
</div><!-- /.rcs-lab3d-shell -->

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
  tripod: {
    label: 'TREPPIEDE',
    groups: [
      { label: 'GAMBE', params: [
        { k:'legOD', l:'Ø Esterno Gambe', u:'mm', v:25, min:5 },
        { k:'legID', l:'Ø Interno Gambe', u:'mm', v:20, min:0 },
        { k:'legLen', l:'Lunghezza Gambe', u:'mm', v:500, min:50 },
        { k:'spread', l:'Apertura dalla Verticale', u:'°', v:20, min:5, max:80 },
      ]},
      { label: 'TESTA', params: [
        { k:'headOD', l:'Ø Testa', u:'mm', v:60, min:20 },
        { k:'headLen', l:'Altezza Testa', u:'mm', v:40, min:10 },
      ]},
      { label: 'COLONNA TELESCOPICA', params: [
        { k:'colOD', l:'Ø Esterno Colonna', u:'mm', v:22, min:5 },
        { k:'colID', l:'Ø Interno Colonna', u:'mm', v:17, min:0 },
        { k:'colLen', l:'Lunghezza Colonna Fissa', u:'mm', v:150, min:20 },
        { k:'colExt', l:'Estensione Telescopica (0=chiusa)', u:'mm', v:100, min:0 },
      ]},
    ]
  },
  telescopic: {
    label: 'TUBO TELESCOPICO',
    groups: [
      { label: 'SEZIONE 1 · ESTERNA (fissa)', params: [
        { k:'od1', l:'Ø Esterno', u:'mm', v:32, min:5 },
        { k:'id1', l:'Ø Interno', u:'mm', v:27, min:0 },
        { k:'len1', l:'Lunghezza', u:'mm', v:350, min:20 },
      ]},
      { label: 'SEZIONE 2', params: [
        { k:'od2', l:'Ø Esterno', u:'mm', v:25, min:5 },
        { k:'id2', l:'Ø Interno', u:'mm', v:21, min:0 },
        { k:'len2', l:'Lunghezza', u:'mm', v:320, min:20 },
      ]},
      { label: 'SEZIONE 3', params: [
        { k:'od3', l:'Ø Esterno', u:'mm', v:19, min:5 },
        { k:'id3', l:'Ø Interno', u:'mm', v:15.5, min:0 },
        { k:'len3', l:'Lunghezza', u:'mm', v:300, min:20 },
      ]},
      { label: 'SEZIONE 4 · INTERNA', params: [
        { k:'od4', l:'Ø Esterno', u:'mm', v:13.5, min:3 },
        { k:'id4', l:'Ø Interno (0=pieno)', u:'mm', v:0, min:0 },
        { k:'len4', l:'Lunghezza', u:'mm', v:280, min:20 },
      ]},
      { label: 'ALLUNGAMENTO', params: [
        { k:'ext', l:'Estensione (0=chiuso, 100=aperto)', u:'%', v:60, min:0 },
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
// Colori viewport 3D coerenti col tema chiaro/scuro dell'interfaccia
const VIEWPORT_THEME = {
  dark:  { clear: 0x0d0d14, gridMain: 0x1e1e24, gridSub: 0x141418 },
  light: { clear: 0xeef0f2, gridMain: 0xd5d5da, gridSub: 0xe6e6ea },
};
function currentViewportTheme() {
  return document.body.classList.contains('light-mode') ? VIEWPORT_THEME.light : VIEWPORT_THEME.dark;
}
function applyViewportTheme() {
  if (!renderer) return;
  const t = currentViewportTheme();
  renderer.setClearColor(t.clear, 1);
  if (window._grid) {
    scene.remove(window._grid);
    window._grid.geometry.dispose();
    window._grid.material.dispose();
    const size = window._grid.userData.size || 1000;
    const divs = window._grid.userData.divs || 40;
    window._grid = new THREE.GridHelper(size, divs, t.gridMain, t.gridSub);
    window._grid.userData = {size, divs};
    scene.add(window._grid);
  }
}

function initThree() {
  const cv = document.getElementById('cv');
  renderer = new THREE.WebGLRenderer({canvas:cv, antialias:true, alpha:true});
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setClearColor(currentViewportTheme().clear, 1);

  // Perdita di contesto WebGL (tab in background a lungo, driver GPU
  // resettato, ecc.): senza gestirla il canvas resta bloccato/nero senza
  // nessun messaggio per l'utente.
  cv.addEventListener('webglcontextlost', e => {
    e.preventDefault();
    showError('Il rendering 3D si e\' interrotto (memoria video persa). Ricarica la pagina per continuare.');
  });
  cv.addEventListener('webglcontextrestored', () => {
    showError(null);
  });

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
  window._grid = new THREE.GridHelper(1000, 40, currentViewportTheme().gridMain, currentViewportTheme().gridSub);
  window._grid.userData = {size:1000, divs:40};
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

  // Click su un pezzo (distinto dal drag-rotate: conta solo se il puntatore
  // non si e' spostato molto tra down e up) -> mostra pannello info generico
  let clickStartPos = null;
  cv.addEventListener('pointerdown', e => { clickStartPos = {x:e.clientX, y:e.clientY}; });
  cv.addEventListener('pointerup', e => {
    if (!clickStartPos) return;
    const moved = Math.hypot(e.clientX-clickStartPos.x, e.clientY-clickStartPos.y);
    clickStartPos = null;
    if (moved > 6) return; // era un trascinamento, non un click
    handlePartClick(e);
  });

  (function loop(){ requestAnimationFrame(loop); renderer.render(scene,camera); })();
}

// ═══ PART INFO — click su un pezzo dell'assemblaggio (treppiede, telescopico) ═══
// Info generiche, non tecniche: i dati precisi dipendono dal preventivo.
const PART_INFO = {
  leg: {
    label: 'Gamba',
    text: 'Un tubo in fibra di carbonio come questo alleggerisce la struttura mantenendo rigidita\' e resistenza: a parita\' di prestazioni il peso e\' molto inferiore rispetto ad alluminio o acciaio.'
  },
  head: {
    label: 'Testa / Snodo',
    text: 'I punti di giunzione possono essere realizzati in fibra di carbonio o in lega leggera, a seconda delle sollecitazioni richieste dall\'applicazione specifica.'
  },
  column: {
    label: 'Colonna Centrale',
    text: 'La sezione centrale e\' spesso quella piu\' sollecitata: qui l\'uso della fibra di carbonio riduce il peso complessivo senza perdere rigidita\' strutturale.'
  },
  'column-ext': {
    label: 'Sezione Telescopica',
    text: 'Anche le parti mobili/estraibili possono essere realizzate in fibra di carbonio, mantenendo leggerezza anche sulle sezioni a scorrimento.'
  },
  'telescopic-section': {
    label: 'Sezione Telescopica',
    text: 'Ogni sezione che scorre dentro la precedente puo\' essere realizzata in fibra di carbonio: riduce il peso complessivo del tubo mantenendo la rigidita\' necessaria durante l\'estensione.'
  },
};

const raycaster = new THREE.Raycaster();
const mouseNDC = new THREE.Vector2();
let selectedMesh = null;
let selectedOrigEmissive = 0x000000;

function handlePartClick(e) {
  if (!mesh) { hideInfoPanel(); return; }
  const cv = document.getElementById('cv');
  const rect = cv.getBoundingClientRect();
  mouseNDC.x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
  mouseNDC.y = -((e.clientY - rect.top) / rect.height) * 2 + 1;
  raycaster.setFromCamera(mouseNDC, camera);
  const hits = raycaster.intersectObject(mesh, true);
  const hit = hits.find(h => h.object.userData && h.object.userData.partType);
  if (hit) selectPart(hit.object); else hideInfoPanel();
}

function selectPart(obj) {
  clearSelectionHighlight();
  selectedMesh = obj;
  if (obj.material && obj.material.emissive) {
    selectedOrigEmissive = obj.material.emissive.getHex();
    obj.material.emissive.setHex(0xc8a96e);
    obj.material.emissiveIntensity = 0.35;
  }
  const info = PART_INFO[obj.userData.partType];
  if (info) showInfoPanel(info);
}

function clearSelectionHighlight() {
  if (selectedMesh && selectedMesh.material && selectedMesh.material.emissive) {
    selectedMesh.material.emissive.setHex(selectedOrigEmissive);
    selectedMesh.material.emissiveIntensity = 1;
  }
  selectedMesh = null;
}

function showInfoPanel(info) {
  document.getElementById('part-info-label').textContent = info.label;
  document.getElementById('part-info-text').textContent = info.text;
  document.getElementById('part-info-panel').classList.add('visible');
}
function hideInfoPanel() {
  clearSelectionHighlight();
  document.getElementById('part-info-panel').classList.remove('visible');
}

// ═══ TEMA CHIARO/SCURO ═══
// Il pulsante di cambio tema e' quello del sito (navbar), non uno proprio
// di questa pagina. Il colore dell'interfaccia (sidebar, pannelli) segue
// gia' via CSS puro (body.light-mode .rcs-lab3d-shell {...}); qui serve
// solo aggiornare i pixel del canvas 3D (sfondo/griglia), che il CSS non
// puo' toccare. Un MutationObserver osserva la classe su <body> e reagisce
// a qualunque cosa la cambi, incluso il toggle del sito in header.php.
function watchThemeChanges() {
  const obs = new MutationObserver(() => applyViewportTheme());
  obs.observe(document.body, { attributes: true, attributeFilter: ['class'] });
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
  if (w === 0 || h === 0) return; // layout non ancora pronto, evita aspect=Infinity/NaN
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
  hideInfoPanel();
  const hint = document.getElementById('vp-hint');
  if (hint) hint.textContent = (s === 'tripod' || s === 'telescopic')
    ? 'TRASCINA · RUOTA | SCROLL · ZOOM | CLICK · INFO PEZZO'
    : 'TRASCINA · RUOTA | SCROLL · ZOOM';
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
        <input type="number" id="p_${p.k}" value="${p.v}" min="${p.min}"${p.max!==undefined?` max="${p.max}"`:''} step="0.1">
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

// Treppiede — assembla piu' istanze di hollowCyl() gia' usata per i tubi
// dritti: 3 gambe convergenti su una testa + colonna centrale telescopica.
// Riusa la stessa geometria dei tubi, cambia solo posizione/orientamento.
function buildTripod(p) {
  const group = new THREE.Group();
  const spreadRad = p.spread * Math.PI / 180;
  const UP = new THREE.Vector3(0,1,0);
  const Z_AXIS = new THREE.Vector3(0,0,1);

  // 3 gambe uguali, aperte di "spread" gradi dalla verticale, a 120° tra loro.
  // hollowCyl() genera il tubo lungo l'asse Z locale da 0 a legLen: ruotando
  // il mesh con setFromUnitVectors l'estremo a (0,0,0) resta ancorato
  // all'apice (origine del gruppo) e l'altro estremo punta verso "dir".
  for (let i = 0; i < 3; i++) {
    const yaw = i * (2*Math.PI/3);
    const dir = new THREE.Vector3(
      Math.sin(spreadRad)*Math.cos(yaw),
      -Math.cos(spreadRad),
      -Math.sin(spreadRad)*Math.sin(yaw)
    ).normalize();
    const legGeo = hollowCyl(p.legOD/2, p.legID/2, p.legLen);
    const legMesh = new THREE.Mesh(legGeo, makeMat());
    legMesh.quaternion.setFromUnitVectors(Z_AXIS, dir);
    legMesh.userData.partType = 'leg';
    group.add(legMesh);
    const legWire = new THREE.Mesh(legGeo, makeWire());
    legWire.quaternion.copy(legMesh.quaternion);
    group.add(legWire);
  }

  // Testa (hub): corpo pieno appena sotto il punto di convergenza delle gambe
  const headGeo = hollowCyl(p.headOD/2, 0, p.headLen);
  const headMesh = new THREE.Mesh(headGeo, makeMat());
  headMesh.quaternion.setFromUnitVectors(Z_AXIS, UP);
  headMesh.position.y = -p.headLen;
  headMesh.userData.partType = 'head';
  group.add(headMesh);

  // Colonna centrale fissa, sopra l'apice
  const colGeo = hollowCyl(p.colOD/2, p.colID/2, p.colLen);
  const colMesh = new THREE.Mesh(colGeo, makeMat());
  colMesh.quaternion.setFromUnitVectors(Z_AXIS, UP);
  colMesh.userData.partType = 'column';
  group.add(colMesh);
  const colWire = new THREE.Mesh(colGeo, makeWire());
  colWire.quaternion.copy(colMesh.quaternion);
  group.add(colWire);

  // Sezione telescopica: tubo piu' sottile che esce dalla colonna fissa
  if (p.colExt > 0) {
    const overlap = Math.min(60, p.colLen*0.3);
    const innerOD = Math.max(4, p.colID > 1 ? p.colID - 1.5 : p.colOD*0.7);
    const wallThick = Math.max(1, (p.colOD-p.colID)/2);
    const innerID = Math.max(0, innerOD - wallThick*2);
    const extGeo = hollowCyl(innerOD/2, innerID/2, p.colExt + overlap);
    const extMesh = new THREE.Mesh(extGeo, makeMat());
    extMesh.quaternion.setFromUnitVectors(Z_AXIS, UP);
    extMesh.position.y = Math.max(0, p.colLen - overlap);
    extMesh.userData.partType = 'column-ext';
    group.add(extMesh);
    const extWire = new THREE.Mesh(extGeo, makeWire());
    extWire.quaternion.copy(extMesh.quaternion);
    extWire.position.copy(extMesh.position);
    group.add(extWire);
  }

  return group;
}

// Rectangular plate with holes
function plateRect(w, h, t, nHoles, hd, margin) {
  if (!w||w<=0||!h||h<=0||!t||t<=0||isNaN(w)||isNaN(h)||isNaN(t)) throw new Error('Parametri piastra non validi');
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
  if (!od||od<=0||!t||t<=0||isNaN(od)||isNaN(t)) throw new Error('Parametri piastra non validi');
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

// Tubo telescopico — 4 sezioni via via piu' piccole, ognuna inserita nella
// precedente (Ø esterno < Ø interno di quella prima). "ext" (0-100%) controlla
// quanto ogni sezione sporge fuori da quella che la contiene: a 0% resta
// visibile solo il tratto minimo di sovrapposizione (aspetto chiuso), a 100%
// sporge per l'intera sua lunghezza (aspetto completamente aperto).
function buildTelescopic(p) {
  const group = new THREE.Group();
  const sections = [
    {od:p.od1, id:p.id1, len:p.len1},
    {od:p.od2, id:p.id2, len:p.len2},
    {od:p.od3, id:p.id3, len:p.len3},
    {od:p.od4, id:p.id4, len:p.len4},
  ];
  const extFrac = Math.max(0, Math.min(100, p.ext)) / 100;
  let prevEnd = 0;

  sections.forEach((s, i) => {
    const overlap = Math.min(50, s.len*0.15);
    let start, visLen;
    if (i === 0) {
      start = 0; visLen = s.len;
    } else {
      start = prevEnd - overlap;
      visLen = overlap + extFrac * (s.len - overlap);
    }
    const geo = hollowCyl(s.od/2, s.id/2, visLen);
    const posArr = geo.attributes.position.array;
    for (let j=2; j<posArr.length; j+=3) posArr[j] += start;
    geo.attributes.position.needsUpdate = true;

    const solidMesh = new THREE.Mesh(geo, makeMat());
    solidMesh.userData.partType = 'telescopic-section';
    group.add(solidMesh);
    group.add(new THREE.Mesh(geo, makeWire()));

    prevEnd = start + visLen;
  });

  return group;
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
  if (curShape === 'tripod') {
    pairs.push({od:p.legOD, id:p.legID, label:'gamba'});
    pairs.push({od:p.colOD, id:p.colID, label:'colonna'});
  }
  if (curShape === 'telescopic') {
    pairs.push({od:p.od1, id:p.id1, label:'sezione 1'});
    pairs.push({od:p.od2, id:p.id2, label:'sezione 2'});
    pairs.push({od:p.od3, id:p.id3, label:'sezione 3'});
    pairs.push({od:p.od4, id:p.id4, label:'sezione 4'});
  }
  for (const {od, id, label} of pairs) {
    if (id > 0 && id >= od) return `Ø interno (${id}mm) ≥ Ø esterno (${od}mm) — ${label}`;
    if (id > 0 && (od-id)/2 < 0.4) return `Parete troppo sottile (${((od-id)/2).toFixed(1)}mm) — ${label}`;
  }
  if (curShape === 'telescopic') {
    const nest = [[p.od2,p.id1,2],[p.od3,p.id2,3],[p.od4,p.id3,4]];
    for (const [odIn, idOut, n] of nest) {
      if (idOut > 0 && odIn >= idOut) return `Sezione ${n} non entra nella sezione precedente (Ø esterno ${odIn}mm ≥ Ø interno ${idOut}mm)`;
    }
  }
  if (curShape === 'tripod' && p.spread > 85) {
    return `Apertura dalla verticale troppo ampia (${p.spread}°) — sopra 85° le gambe puntano verso l'alto invece che verso il basso`;
  }
  if (curShape === 'plate-rect' && p.holes > 0) {
    if (p.hm*2 >= Math.min(p.w, p.h)) return `Margine dai bordi (${p.hm}mm) troppo grande per le dimensioni della piastra`;
  }
  if (curShape === 'plate-round' && p.ph > 0) {
    if (p.phr + p.phd/2 > p.od/2) return `I fori periferici (raggio cerchio ${p.phr}mm + Ø/2 ${(p.phd/2).toFixed(1)}mm) escono dal bordo della piastra (Ø esterno/2 = ${(p.od/2).toFixed(1)}mm)`;
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
  hideInfoPanel(); // il mesh vecchio sta per essere disposato, evita riferimenti stale
  const p = getP();
  let geo;
  let preBuiltGroup = null;
  let bb;

  if (curShape !== 'tapered2') {
    const err = validateInputs(p);
    if (err) { showError(err); return; }
  }

  try {
    // Tutta la generazione geometria e' dentro il try: qualunque builder
    // lanci un'eccezione (parametri assurdi digitati a mano, non sempre
    // coperti da validateInputs) viene gestito qui in modo uniforme invece
    // di propagare fino al listener globale window.onerror.
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
      case 'tripod':
        preBuiltGroup = buildTripod(p);
        break;
      case 'telescopic':
        preBuiltGroup = buildTelescopic(p);
        break;
      case 'plate-rect':
        geo = plateRect(p.w,p.h,p.t,p.holes,p.hd,p.hm);
        break;
      case 'plate-round':
        geo = plateRound(p.od,p.t,p.id,p.ph,p.phd,p.phr);
        break;
    }

    if (!geo && !preBuiltGroup) throw new Error('Geometria non generata — controlla i parametri.');

    // Remove old mesh (dispose geometria+materiali per evitare memory leak GPU)
    if(mesh){
      scene.remove(mesh);
      mesh.traverse(obj => {
        if (obj.geometry) obj.geometry.dispose();
        if (obj.material) obj.material.dispose();
      });
      mesh=null;
    }

    let group;
    if (preBuiltGroup) {
      // Assemblaggio multi-parte (es. treppiede): bounding box calcolata
      // sull'intero gruppo, non su una singola geometria.
      group = preBuiltGroup;
      bb = new THREE.Box3().setFromObject(group);
    } else {
      group = new THREE.Group();
      group.add(new THREE.Mesh(geo, makeMat()));
      group.add(new THREE.Mesh(geo, makeWire()));
      geo.computeBoundingBox();
      bb = geo.boundingBox;
    }

    // Center on scene
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
  const vt = currentViewportTheme();
  window._grid = new THREE.GridHelper(gridSize, gridDivs, vt.gridMain, vt.gridSub);
  window._grid.userData = {size:gridSize, divs:gridDivs};
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
  watchThemeChanges();
} catch(e) {
  document.querySelector('.vp-empty-text').textContent = 'Errore inizializzazione 3D: ' + e.message;
  console.error(e);
}
</script>
<?php get_footer(); ?>
