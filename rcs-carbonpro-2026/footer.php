<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <div class="footer-logo">RCS CARBONIO</div>
            <p>Leader nella produzione di tubi e lastre in fibra di carbonio su misura dal 1990.</p>
            <p><strong>Sede Legale:</strong><br>[VIA SEDE LEGALE]<br>[CAP CITTÀ]</p>
            <p><strong>Sede Operativa:</strong><br>Via Industriale 42, Bergamo<br>Tel: +39 035 123 4567<br>Email: info@rcscarbonio.it</p>
            <p>P.IVA: [PARTITA IVA]<br>REA: [NUMERO REA]</p>
        </div>
        <div class="footer-section">
            <h3>Link Rapidi</h3>
            <?php if (has_nav_menu('footer-quick')) { wp_nav_menu(array('theme_location' => 'footer-quick', 'container' => false, 'fallback_cb' => false)); } else { echo '<ul><li><a href="' . home_url('/sport') . '">Sport</a></li><li><a href="' . home_url('/aerospace') . '">Aerospace</a></li><li><a href="' . home_url('/nautica') . '">Nautica</a></li><li><a href="' . home_url('/droni') . '">Droni</a></li><li><a href="' . home_url('/catalogo') . '">Catalogo</a></li></ul>'; } ?>
        </div>
        <div class="footer-section">
            <h3>Azienda</h3>
            <?php if (has_nav_menu('footer-company')) { wp_nav_menu(array('theme_location' => 'footer-company', 'container' => false, 'fallback_cb' => false)); } else { echo '<ul><li><a href="' . home_url('/chi-siamo') . '">Chi Siamo</a></li><li><a href="' . home_url('/processo') . '">Processo</a></li><li><a href="' . home_url('/sostenibilita') . '">Sostenibilità</a></li></ul>'; } ?>
        </div>
        <div class="footer-section">
            <h3>Supporto</h3>
            <?php if (has_nav_menu('footer-support')) { wp_nav_menu(array('theme_location' => 'footer-support', 'container' => false, 'fallback_cb' => false)); } else { echo '<ul><li><a href="' . home_url('/faq') . '">FAQ</a></li><li><a href="' . home_url('/assistenza') . '">Assistenza</a></li><li><a href="' . home_url('/contatti') . '">Contatti</a></li></ul>'; } ?>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> RCS Carbonio. Tutti i diritti riservati. | <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Privacy</a> | <a href="<?php echo home_url('/cookie-policy'); ?>">Cookie</a></p>
        <div class="footer-social-icons">
            <a href="https://facebook.com/rcscarbonio" class="footer-social-link" target="_blank" rel="noopener" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 3.667h-3.533v7.98H9.101z"/></svg>
            </a>
            <a href="https://instagram.com/rcscarbonio" class="footer-social-link" target="_blank" rel="noopener" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m9.65 1.5a1.25 1.25 0 0 1 1.25 1.25A1.25 1.25 0 0 1 17.25 8 1.25 1.25 0 0 1 16 6.75a1.25 1.25 0 0 1 1.25-1.25M12 7a5 5 0 0 1 5 5 5 5 0 0 1-5 5 5 5 0 0 1-5-5 5 5 0 0 1 5-5m0 2a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3z"/></svg>
            </a>
            <a href="https://linkedin.com/company/rcscarbonio" class="footer-social-link" target="_blank" rel="noopener" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>
            </a>
            <a href="https://x.com/rcscarbonio" class="footer-social-link" target="_blank" rel="noopener" aria-label="X">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
