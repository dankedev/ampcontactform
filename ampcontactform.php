<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.dankedev.com
 * @since             1.0.0
 * @package           ampcontactform
 *
 * @wordpress-plugin
 * Plugin Name:       AMP Simple Contact Form
 * Plugin URI:        http://www.dankedev.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hadie Danker
 * Author URI:        http://www.dankedev.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       amp-contact-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
define('AMPCF_VERSION','1.0.0');
define('AMPCF_REQUIRED_WP_VERSION','4.6');
define('AMPCF_PLUGIN',__FILE__);
define( 'AMPCF__PLUGIN_BASENAME', plugin_basename( AMPCF_PLUGIN ) );
define( 'AMPCF_PLUGIN_NAME', trim( dirname( AMPCF__PLUGIN_BASENAME ), '/' ) );
define( 'AMPCF_PLUGIN_DIR', untrailingslashit( dirname( AMPCF_PLUGIN ) ) );
if (!defined('AMPCF_OPTIONS_KEY')) define('AMPCF_OPTIONS_KEY', 'ampcf_options');
if (!defined('AMPCF_PLUGIN_URL')) define('AMPCF_PLUGIN_URL', WP_PLUGIN_URL . '/' . AMPCF_PLUGIN_NAME);


require_once ('view/ampcf_contact_form.php');
require_once ('libs/ampcf_Contact.php');
require_once ('libs/ampcf_settings.php');
require_once ('libs/ampcf_Filters.php');
require_once ('ampcf_admin.php');
require_once ('class.ampcf.php');

$contact_form = new ampcf();