<?php

if( !function_exists('osclassclsx_footer_scripts') ) {
    function osclassclsx_footer_scripts() {
        // Load scripts in the footer
        // osc_register_script('foundation-js', osc_current_web_theme_url('assets/js/foundation.js'));
        // // osc_enqueue_script('foundation-js');
        osc_load_scripts();
    }
}
osc_add_hook('footer', 'osclassclsx_footer_scripts', 10);

?>
