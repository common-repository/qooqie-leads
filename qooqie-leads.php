<?php
/**
 * Plugin Name: Qooqie Leads
 * Plugin URI: https://qooqie.com/qooqie-leads
 * Description: Voeg de Qooqie Leads widget toe aan je website.
 * Version: 1.1
 * Author: Qooqie N.V.
 * Author URI: https://qooqie.com
 * License: GPL2
 */

// Hook for adding admin menu
add_action('admin_menu', 'QQLDS_add_admin_menu');

// Function to add the admin menu
function QQLDS_add_admin_menu() {
    // Add a submenu item under Settings
    add_options_page(
        'Qooqie Leads',  // The text to be displayed in the title tags of the page when the menu is selected
        'Qooqie Leads',           // The text to be used for the menu
        'manage_options',         // The capability required for this menu to be displayed to the user.
        'qooqie-leads',           // The slug name to refer to this menu by (should be unique for this menu).
        'QQLDS_admin_page'           // The function to be called to output the content for this page.
    );
}

// Admin page content
function QQLDS_admin_page() {
    ?>
    <h2>Qooqie Leads Settings</h2>
    <form action="options.php" method="post">
        <?php
        settings_fields('QQLDS_options_group');
        do_settings_sections('qooqie-leads');
        submit_button();
        ?>
    </form>
    <?php
}

// Register settings, sections, and fields
add_action('admin_init', 'QQLDS_settings_init');
function QQLDS_settings_init() {
    register_setting('QQLDS_options_group', 'QQLDS_options', 'QQLDS_options_validate');
    add_settings_section('QQLDS_main', 'Script toevoegen', 'QQLDS_section_text', 'qooqie-leads');
    add_settings_field('QQLDS_implementation', 'Widget ID (implementation)', 'QQLDS_implementation_setting_string', 'qooqie-leads', 'QQLDS_main');
    add_settings_field('QQLDS_sub', 'Organisatie ID (sub)', 'QQLDS_sub_setting_string', 'qooqie-leads', 'QQLDS_main');
}

// Validate user input
function QQLDS_options_validate($input) {
    // Validate and sanitize options, add validation code here
    return $input;
}

// Section text
function QQLDS_section_text() {
    echo '<p>Vul onderstaande velden in volgens het script op Qooqie Leads.<br /><br /><strong>Voorbeeld script:</strong></p>';
    // Example script in code editor style with specific example
    echo '<pre style="background-color: #f7f7f7; border: 1px solid #ccc; padding: 10px; border-radius: 5px; overflow: auto; font-family: monospace; margin-top: 10px; max-width: 100%; max-height: 200px;">&lt;script src="https://api-widget-callback.qooqie.com/widget/load_script/v1.1" id=\'{"implementation": "63039c852e8012002eddde29", "sub": "d22c14b0-038d-4533-b1af-0d52defc3ee6"}\'&gt;&lt;/script&gt;</pre>';
    echo '<p>Kopieer de implementation code: <code>63039c852e8012002eddde29</code> en de sub code: <code>d22c14b0-038d-4533-b1af-0d52defc3ee6</code> uit het script in jouw Qooqie Leads omgeving en plak deze in onderstaande formulier.</p>';
}

// Implementation value field
function QQLDS_implementation_setting_string() {
    $options = get_option('QQLDS_options');
    echo "<input id='QQLDS_implementation' name='QQLDS_options[implementation]' size='40' type='text' value='" . esc_attr($options['implementation']) . "' />";
}

// Sub value field
function QQLDS_sub_setting_string() {
    $options = get_option('QQLDS_options');
    echo "<input id='QQLDS_sub' name='QQLDS_options[sub]' size='40' type='text' value='" . esc_attr($options['sub']) . "' />";
}


// Hook to inject script into footer
add_action('wp_footer', 'QQLDS_inject_script');

function QQLDS_inject_script() {
    $options = get_option('QQLDS_options');
    $implementation = esc_js($options['implementation']);
    $sub = esc_js($options['sub']);

    if (!empty($implementation) && !empty($sub)) {
        echo "<script src='https://api-widget-callback.qooqie.com/widget/load_script/v1.1' id='" . esc_attr(wp_json_encode(array("implementation" => $implementation, "sub" => $sub))) . "'></script>";
    }
}