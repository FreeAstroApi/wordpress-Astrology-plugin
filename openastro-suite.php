<?php
/**
 * Plugin Name: Astrology Suite for FreeAstroAPI
 * Description: All-in-one astrology plugin for FreeAstroAPI with shared settings, API proxy, and Gutenberg blocks.
 * Version: 0.2.2
 * Author: omegab
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.6
 * Requires PHP: 8.1
 * Text Domain: astrology-suite-freeastroapi
 */

if (!defined('ABSPATH')) {
    exit;
}

define('OPENASTRO_SUITE_VERSION', '0.2.2');
define('OPENASTRO_SUITE_FILE', __FILE__);
define('OPENASTRO_SUITE_DIR', plugin_dir_path(__FILE__));
define('OPENASTRO_SUITE_URL', plugin_dir_url(__FILE__));

require_once OPENASTRO_SUITE_DIR . 'includes/class-openastro-suite-plugin.php';

AstrologySuiteFreeAstroAPI\Plugin::boot();
