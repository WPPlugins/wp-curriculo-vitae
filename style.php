<?php

global $wpdb, $wpcvf, $wls_curriculo, $wls_areas, $wls_curriculo_options;

wp_enqueue_style("wpcvf_normalize", plugins_url('css/normalize.css', __FILE__) );
wp_enqueue_style("wpcvf_lightboxcssa", plugins_url('css/wpcvf_lightbox.css', __FILE__) );
wp_enqueue_style("wpcvf_style", plugins_url('css/wp_curriculo_style.css', __FILE__));
wp_enqueue_style('wpcvf_bootstrap', plugins_url('css/bootstrap.css', __FILE__));

wp_enqueue_style('wpcvf_bugs', plugins_url('bugs.php', __FILE__));

wp_enqueue_script('jquery');
wp_enqueue_script('wpcvf_script', plugins_url('js/script.js', __FILE__));
wp_enqueue_script('wpcvf_bootstrapJSa', plugins_url('js/bootstrap.min.js', __FILE__));
wp_enqueue_script('wpcvf_scriptMask', plugins_url('js/jquery.maskedinput-1.1.4.pack.js', __FILE__));
#wp_enqueue_script('scriptAreaJS', plugins_url('js/scriptArea.js', __FILE__));


?>