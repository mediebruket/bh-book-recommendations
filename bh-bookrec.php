<?php
/*
Plugin Name: BH Book Recommendations
Version: 0.1-alpha
Description: Provides shortcode and widget for displaying book recommendations from selected norwegian providers.
Author: Håvard Grimelid
Author URI: https://profiles.wordpress.org/hgmb
Plugin URI: http://mediebruket.no
Text Domain: bh-bookrec
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

if ( WP_DEBUG ) {
  add_filter('wp_feed_cache_transient_lifetime', function() { return 3;} );
}

final class BookRecommendations
{
  public function __construct() {
    add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
    add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
    add_action( 'plugins_loaded', array( $this, 'includes' ), 2 );
    add_action( 'plugins_loaded', array( $this, 'add_textdomain'), 3);
  }

  public function constants() {
    define( 'BHBR_DIR', plugin_dir_path( __FILE__ ) );
    define( 'BHBR_URL', plugin_dir_url( __FILE__ ) );
    define( 'BHBR_DEFAULT_URL', 'http://anbefalinger.deichman.no/feed' );
  }

  public static function includes() {
    require_once(  BHBR_DIR . 'inc/bhbook-feed.php'  );
    require_once(  BHBR_DIR . 'inc/bhbook-review.php'  );
    require_once(  BHBR_DIR . 'inc/bhbook-widgets.php'  );
    require_once(  BHBR_DIR . 'inc/functions.php'  );
  }

  public function register_assets() {
    wp_register_style( 'bhbook', plugin_dir_url( __FILE__ ) . 'assets/style.css' );
    wp_enqueue_style( 'bhbook' );
  }

  public function add_textdomain() {
   load_plugin_textdomain( 'bh-bookrec', false, BHBR_DIR . '/languages' );
  }
}

new BookRecommendations();
