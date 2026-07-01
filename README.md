# RCS Carbonio — Sito WordPress

Repository del **tema custom** e delle **pagine sorgente** del sito RCS Carbonio (produttore di tubi in fibra di carbonio su misura, Pordenone / FVG).

> Prima di lavorare, leggi **`docs/HANDOFF-COMPLETO-DETTAGLIATO.md`**: contiene contesto azienda, design system, i 9 problemi già risolti e le convenzioni. È la fonte di verità del progetto.

---

## Cosa c'è in questo repo

```
├── rcs-carbonpro-2026/     Il tema WordPress (questo è ciò che si deploya)
│   ├── functions.php        enqueue font/CSS/JS, dark mode cookie, upload GLB
│   ├── header.php           <head> + navbar
│   ├── footer.php           footer con social SVG inline
│   ├── index.php, style.css
│   └── assets/
│       ├── css/main.css     navbar + footer + utilities GLOBALI
│       ├── js/three.min.js  Three.js r128 LOCALE (GDPR) — 590KB
│       ├── js/main.js
│       └── fonts/           Inter + Teko .woff2 (GDPR, no Google CDN)
│
├── _pagine-source/          Contenuto delle pagine (HTML+CSS+JS inline)
│                            NON sono file del tema: si incollano nei blocchi
│                            "Custom HTML" dentro l'editor WordPress.
│
├── tools/                   Script di utilità (batch compressione WebP)
└── docs/                    Handoff completo del progetto
```

## Cosa NON c'è (per scelta)

- **Core WordPress** (`wp-admin/`, `wp-includes/`) — si reinstalla da solo.
- **Plugin di terzi** (Complianz, WPForms, Rank Math) — installati da WP.
- **Database** — vive in MySQL, non in file (menu, pagine pubblicate, impostazioni).
- **Immagini** (`wp-content/uploads/`) — stanno sull'hosting. Nomi e mappatura uso → in `docs/HANDOFF-COMPLETO-DETTAGLIATO.md`.

---

## Workflow

1. **Sviluppo** in locale (Claude Code) su `rcs-carbonpro-2026/` o `_pagine-source/`.
2. **Commit** su git = fonte di verità del codice.
3. **Deploy** del tema via FTP su Hostinger, in `wp-content/themes/rcs-carbonpro-2026/`.
4. Le pagine di `_pagine-source/` si aggiornano incollandole nei rispettivi blocchi Custom HTML in WordPress.

## Regole d'oro

- **GDPR assoluto:** nessun CDN esterno. Font, Three.js, tutto locale.
- **Soluzioni chirurgiche:** modificare solo il necessario, mai riscrivere pagine intere.
- **CSS scoped:** classi con prefisso `rcs-` per non collidere col tema.
- **Dark mode** default (`#fdff00`), light via `body.light-mode` (`#0099ff`), cookie `darkMode`.
- **Non fermarsi al primo errore:** se qualcosa non va, la causa può essere altrove (es. `overflow` del tema che rompe lo `sticky`).

## Ambiente

- **Hosting:** Hostinger — staging `https://darkgoldenrod-mole-448362.hostingersite.com`
- **Dominio finale:** rcscarbonio.it (in attivazione)
- **Tema attivo:** `rcs-carbonpro-2026` (v12.2.0)

## Pending

- [ ] Immagine reale fase **2014 Aeronautica** (ora = placeholder foto 2011)
- [ ] Immagine **Pagaie** card 3 Sport (placeholder)
- [ ] Clienti reali in **"Ci Hanno Già Scelto"** (ora fittizi)
- [ ] Migrazione a **rcscarbonio.it**
- [ ] **Google Analytics 4** + Consent Mode V2 (dopo dominio)
