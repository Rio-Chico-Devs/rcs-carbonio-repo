<?php
/**
 * Theme Name: RCS Carbonio Pro 2026
 * Version: 8.0.0
 * Description: Tema minimale - CSS pagine standalone
 */

function rcs_carbonio_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary'        => 'Menu Principale',
        'footer-quick'   => 'Footer - Link Rapidi',
        'footer-company' => 'Footer - Azienda',
        'footer-support' => 'Footer - Supporto',
    ));
}
add_action('after_setup_theme', 'rcs_carbonio_setup');

function rcs_carbonio_scripts() {
    // Font locali (GDPR compliant)
    wp_enqueue_style('rcs-fonts', 
        get_template_directory_uri() . '/assets/fonts/fonts.css', 
        array(), 
        '2.4.7'
    );
    
    // Main CSS - SOLO navbar + footer + utilities
    wp_enqueue_style('rcs-carbonio-style', 
        get_template_directory_uri() . '/assets/css/main.css', 
        array(), 
        '12.2.0'  // Icone FILLED come preview (non outline)
    );
    
    // Main JS
    wp_enqueue_script('rcs-carbonio-script', 
        get_template_directory_uri() . '/assets/js/main.js', 
        array(), 
        '12.0.0', 
        true
    );
}
add_action('wp_enqueue_scripts', 'rcs_carbonio_scripts');

// Dark mode body class
function rcs_add_dark_mode_class($classes) {
    if (isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'false') {
        $classes[] = 'light-mode';
    }
    return $classes;
}
add_filter('body_class', 'rcs_add_dark_mode_class');

// Abilita upload file 3D (GLB, GLTF)
function rcs_enable_3d_uploads($mimes) {
    $mimes['glb'] = 'model/gltf-binary';
    $mimes['gltf'] = 'model/gltf+json';
    return $mimes;
}
add_filter('upload_mimes', 'rcs_enable_3d_uploads');

// Fix MIME type check per GLB
function rcs_check_filetype_and_ext($data, $file, $filename, $mimes) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    if ($ext === 'glb') {
        $data['ext'] = 'glb';
        $data['type'] = 'model/gltf-binary';
    }
    
    if ($ext === 'gltf') {
        $data['ext'] = 'gltf';
        $data['type'] = 'model/gltf+json';
    }
    
    return $data;
}
add_filter('wp_check_filetype_and_ext', 'rcs_check_filetype_and_ext', 10, 4);
?>
