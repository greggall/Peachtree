<?php
/**
Plugin Name: Live Population for Gravity Forms
Plugin URI: http://forgravity.com/plugins/live-population/
Description: Use merge tags to populate field values, labels and more without reloading the page.
Version: 1.3.11
Author: ForGravity
Author URI: http://forgravity.com
Text Domain: forgravity_livepopulation
Domain Path: /languages
 **/

if ( ! defined( 'FG_EDD_STORE_URL' ) ) {
	define( 'FG_EDD_STORE_URL', 'https://forgravity.com' );
}

define( 'FG_LIVEPOPULATION_VERSION', '1.3.11' );
define( 'FG_LIVEPOPULATION_EDD_ITEM_NAME', 'Live Population' );

// Initialize plugin updater.
add_action( 'init', array( 'LivePopulation_Bootstrap', 'updater' ), 0 );

// If Gravity Forms is loaded, bootstrap the Live Population Add-On.
add_action( 'gform_loaded', array( 'LivePopulation_Bootstrap', 'load' ), 5 );

/**
 * Class LivePopulation_Bootstrap
 *
 * Handles the loading of the Live Population Add-On and registers with the Add-On framework.
 */
class LivePopulation_Bootstrap {

	/**
	 * If the Feed Add-On Framework exists, Live Population Add-On is loaded.
	 *
	 * @access public
	 * @static
	 */
	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		if ( ! class_exists( '\ForGravity\LivePopulation\EDD_SL_Plugin_Updater' ) ) {
			require_once( 'includes/EDD_SL_Plugin_Updater.php' );
		}

		require_once( 'class-livepopulation.php' );

		GFAddOn::register( '\ForGravity\LivePopulation\Live_Population' );

	}

	/**
	 * Initialize plugin updater.
	 *
	 * @access public
	 * @static
	 */
	public static function updater() {

		// Get Live Population instance.
		$live_population = fg_livepopulation();

		// If Live Population could not be retrieved, exit.
		if ( ! $live_population ) {
			return;
		}

		// Get plugin settings.
		$settings = $live_population->get_plugin_settings();

		// Get license key.
		$license_key = trim( rgar( $settings, 'license_key' ) );

		new ForGravity\LivePopulation\EDD_SL_Plugin_Updater(
			FG_EDD_STORE_URL,
			__FILE__,
			array(
				'version'   => FG_LIVEPOPULATION_VERSION,
				'license'   => $license_key,
				'item_name' => FG_LIVEPOPULATION_EDD_ITEM_NAME,
				'author'    => 'ForGravity',
			)
		);

	}

}

/**
 * Returns an instance of the GF_Live_Population class
 *
 * @see    Live_Population::get_instance()
 *
 * @return object GF_Live_Population
 */
function fg_livepopulation() {
	if ( class_exists( '\ForGravity\LivePopulation\Live_Population' )  ) {
		return ForGravity\LivePopulation\Live_Population::get_instance();
	}
}
