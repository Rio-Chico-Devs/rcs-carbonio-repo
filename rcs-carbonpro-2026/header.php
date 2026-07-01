<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font locali (GDPR compliant) -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/fonts.css">
    
    <!-- CSS principale -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/main.css?ver=7.6.4">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav class="navbar">
    <div class="nav-container">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">RCS CARBONIO</a>
        
        <?php
        // Provo a caricare il menu WordPress
        $menu_exists = wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'nav-links',
            'container' => false,
            'fallback_cb' => false,
            'echo' => false
        ));
        
        // Se il menu non esiste, uso HTML statico
        if (!$menu_exists) {
            echo '<ul class="nav-links">
                <li class="current-menu-item"><a href="' . esc_url(home_url('/')) . '">HOME</a></li>
                <li><a href="' . esc_url(home_url('/sport')) . '">SPORT</a></li>
                <li><a href="' . esc_url(home_url('/droni')) . '">DRONI</a></li>
                <li><a href="' . esc_url(home_url('/nautica')) . '">NAUTICA</a></li>
                <li><a href="' . esc_url(home_url('/aerospace')) . '">AEROSPACE</a></li>
                <li><a href="' . esc_url(home_url('/contatti')) . '">CONTATTI</a></li>
            </ul>';
        } else {
            echo $menu_exists;
        }
        ?>
        
        <div class="nav-actions">
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="Cambia tema">
                <!-- Sole - visibile in light mode -->
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" style="display: none;">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3" stroke-width="2" stroke-linecap="round"/>
                    <line x1="12" y1="21" x2="12" y2="23" stroke-width="2" stroke-linecap="round"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke-width="2" stroke-linecap="round"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke-width="2" stroke-linecap="round"/>
                    <line x1="1" y1="12" x2="3" y2="12" stroke-width="2" stroke-linecap="round"/>
                    <line x1="21" y1="12" x2="23" y2="12" stroke-width="2" stroke-linecap="round"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke-width="2" stroke-linecap="round"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <!-- Luna - visibile in dark mode -->
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</nav>

<div class="mobile-menu">
    <?php
    if ($menu_exists) {
        echo $menu_exists;
    } else {
        echo '<ul>
            <li><a href="' . esc_url(home_url('/')) . '">HOME</a></li>
            <li><a href="' . esc_url(home_url('/sport')) . '">SPORT</a></li>
            <li><a href="' . esc_url(home_url('/droni')) . '">DRONI</a></li>
            <li><a href="' . esc_url(home_url('/nautica')) . '">NAUTICA</a></li>
            <li><a href="' . esc_url(home_url('/aerospace')) . '">AEROSPACE</a></li>
            <li><a href="' . esc_url(home_url('/contatti')) . '">CONTATTI</a></li>
        </ul>';
    }
    ?>
</div>
