<?php
/**
 * Plugin Name: Plugin Categories
 * Plugin URI:  http://tormorten.no
 * Description: The best WordPress extension ever made!
 * Version:     0.5
 * Author:      Tor Morten Jensen
 * Author URI:  http://tormorten.no
 * License:     GPLv2+
 * Text Domain: plugin_categories
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 Tor Morten Jensen (email : tormorten@tormorten.no)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

/**
 * Only allow the plugin files to be loaded while in admin
 *
 * @return bool
 */
if( !is_admin() )
	return;

// Useful global constants
define( 'PLUGIN_CATEGORIES_VERSION', '0.1.0' );
define( 'PLUGIN_CATEGORIES_URL',     plugin_dir_url( __FILE__ ) );
define( 'PLUGIN_CATEGORIES_PATH',    plugin_dir_path( __FILE__ ) );

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function plugin_categories_init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'plugin-categories' );
	load_textdomain( 'plugin-categories', WP_LANG_DIR . '/plugin-categories/plugin-categories-' . $locale . '.mo' );
	load_plugin_textdomain( 'plugin-categories', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// The Core Class
	require_once PLUGIN_CATEGORIES_PATH . '/includes/models/class.model.php';
	require_once PLUGIN_CATEGORIES_PATH . '/includes/models/class.category.php';
	require_once PLUGIN_CATEGORIES_PATH . '/includes/models/class.plugin.php';

	require_once PLUGIN_CATEGORIES_PATH . '/includes/class.controller.php';

	if( is_network_admin() ) {

		// The Class For Multisites
		require_once PLUGIN_CATEGORIES_PATH . '/includes/class.multisite.php';

		// The Instance
		$controller 	= new PluginCategory_Controller_MS;

	}
	else {

		$controller 	= new PluginCategory_Controller;

	}

}

/**
 * Activate the plugin
 */
function plugin_categories_activate() {
	
	plugin_categories_init();

	flush_rewrite_rules();

	require_once PLUGIN_CATEGORIES_PATH . '/includes/class.install.php';

	new PluginCategory_Install;

}
register_activation_hook( __FILE__, 'plugin_categories_activate' );

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function plugin_categories_deactivate() {

}
register_deactivation_hook( __FILE__, 'plugin-categories_deactivate' );

// Wireup actions
add_action( 'plugins_loaded', 'plugin_categories_init' );

// Wireup filters

// Wireup shortcodes
