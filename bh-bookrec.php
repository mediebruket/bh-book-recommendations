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
  }

  public function register_assets() {
    wp_register_style( 'bhbook', plugin_dir_url( __FILE__ ) . 'assets/style.css' );
    wp_enqueue_style( 'bhbook' );
  }
}

new BookRecommendations();

final class BookRecommendationsFeed
{
  private $items;

  //const FEED_TEST_URL = 'http://sites/devsite/feed/';
  const FEED_TEST_URL = 'http://www.framtida.no/rss/articles/top';

  public function __construct()
  {
    require_once( ABSPATH . WPINC . '/feed.php' );
    $feed = fetch_feed(self::FEED_TEST_URL);
    $this->items = $feed->get_items();
  }

  public function getItems()
  {
    return $this->items;
  }
}

final class BookRecommendationsReview
{
  public $title;
  public $link;
  public $description;
  public $pubDate;
  public $author;
  public $imageURL;

  public function __construct($item)
  {
    $this->title = $item->get_title();
    $this->link = esc_url($item->get_link());
    $this->description = wp_strip_all_tags($item->get_description());
    $this->pubDate = $item->get_date(get_option('date_format'));
    $this->setAuthor($item);
    $this->setImageUrl($item);
  }

  public function __toString()
  {
    return $this->title;
  }
  private function setAuthor($item)
  {
    if ( $author = $item->get_author() ) {
      $this->author = $author->get_name();
    }
  }

  private function setImageUrl($item)
  {
    if ( $enclosure = $item->get_enclosure() ) {
      $image_type = explode('/', $enclosure->get_type());

      if ( $image_type && $image_type[0] == 'image' ) {
        $this->imageURL = $enclosure->get_link();
      }
    }
  }
}

function bhbook_shortcode_handler($atts = array()) {
  $a = shortcode_atts( array(
  ), $atts );
  return bhbook_get_items();
}
add_shortcode('bhbook', 'bhbook_shortcode_handler');

function bhbook_get_items($args = array()) {
  if ( ! wp_style_is('bhbook') ) {
    wp_enqueue_style('bhbook');
  }
  $feed = new BookRecommendationsFeed();
  $html = '<div class="bhbook-items">';
  foreach ($feed->getItems() as $item) {
    $review = new BookRecommendationsReview($item);
    $html .= bhbook_item_get_markup($review);
  }
  $html .= '</div>';
  return $html;
}

function bhbook_item_get_markup($review) {
  $html = '<div class="bhbook-item">';
  if ( $review->imageURL ) {
    $html .= '<div class="bhbook-image">';
    $html .= '<img src="' . $review->imageURL . '" alt="' . __('Illustrasjonsbilete til omtalen av ', 'bh-bookrec') . $review->title . '">';
    $html .= '</div>';
  }
  else {
    $html .= '<div class="bhbook-image">';
    $html .= "Dummybilde";
    $html .= '</div>';
  }
  $html .= '<div class="bhbook-content">';
  $html .= '<div class="bhbook-title"><a target="_blank" href="' . $review->link . '">' . $review->title . '</a></div>';
  if ( $review->description ) {
    $html .= '<div class="bhbook-description">' . $review->description . '</div>';
  }
  $html .= '</div>';
  $html .= '</div>';
  return $html;
}

