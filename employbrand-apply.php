<?php
/**
 * Plugin Name: Employbrand Apply
 * Plugin URI: https://webbedrijf.nl
 * Description: Employbrand Apply integration
 * Version: 1.2.5
 * Author: Bart Fijneman
 * Author URI: https://employbrand.nl
 * License: GPL2
 */

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

new EmploybrandApply\Plugin();
