<?php
/*
Plugin Name: Product Isotope Filter for Elementor
Plugin URI: https://wpocean.com/wp/plugins/wco-isotope-filter
Description: Simple, clean WooCommerce Isotope Filter Plugin. An easy way to include animated, interactive graphs on your website.
Version: 1.0.2
Author: wpocean
Author URI: https://wpocean.com
License: GPLv2 or later
Text Domain: wco-isotop-filter
Domain Path: /languages/
*/

use \Elementor\Plugin as Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die( __( "Direct Access is not allowed", 'wco-isotop-filter' ) );
}

// Plugin URL
define( 'WCOISOTOPFILTER_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

// define Image size

add_image_size( 'wco-product-filter', 240, 220, true );

final class WooIsotopFilterExtension {

	const VERSION = "1.0.0";
	const MINIMUM_ELEMENTOR_VERSION = "3.0.0";
	const MINIMUM_PHP_VERSION = "7.4";

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;

	}

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init() {
		load_plugin_textdomain( 'wco-isotop-filter' );

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

			return;
		}

		add_action( 'elementor/widgets/register', [ $this, 'register' ] );

		add_action( "elementor/elements/categories_registered", [ $this, 'register_new_category' ] );

		add_action( "elementor/frontend/after_enqueue_styles", [ $this, 'wcoisotopfilter_assets_styles' ] );
		add_action( "elementor/frontend/after_enqueue_scripts", [ $this, 'wcoisotopfilter_assets_scripts' ] );

	}

	function wcoisotopfilter_assets_scripts(){
		wp_enqueue_script( 'imagesloaded');
		wp_enqueue_script("wco-isotop",plugins_url("/assets/js/isotope.min.js",__FILE__),array('jquery'),'3.0.6',true);
		wp_enqueue_script( 'masonry');
		wp_enqueue_script("wco-scripts",plugins_url("/assets/js/scripts.js",__FILE__),array('jquery','wco-isotop'),time(),true);
		wp_enqueue_script("wco-live-editor",plugins_url("/assets/js/live-editor.js",__FILE__), [ 'jquery' ], false, true );
	}


	function wcoisotopfilter_assets_styles() {
		wp_enqueue_style("bootstrap",plugins_url("/assets/css/bootstrap.min.css",__FILE__));
		wp_enqueue_style("wco-style-css",plugins_url("/assets/css/style.css",__FILE__));
	}

	public function register_new_category( $manager ) {
		$manager->add_category( 'WCoIsotopFilter', [
			'title' => esc_html__( 'Isotop Filter Category', 'wco-isotop-filter' ),
			'icon'  => ' eicon-products'
		] );
	}

	public function register() {
		require_once( __DIR__ . '/widgets/wco-isotop-widget.php' );

	}


	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wco-isotop-filter' ),
			'<strong>' . esc_html__( 'WooCommerce Isotop Filter Extension', 'wco-isotop-filter' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'wco-isotop-filter' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wco-isotop-filter' ),
			'<strong>' . esc_html__( 'WooCommerce Isotop Filter Extension', 'wco-isotop-filter' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wco-isotop-filter' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wco-isotop-filter' ),
			'<strong>' . esc_html__( 'WooCommerce Isotop Filter Extension', 'wco-isotop-filter' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wco-isotop-filter' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );


	}

		
	public function includes() {
	}

}

WooIsotopFilterExtension::instance();
