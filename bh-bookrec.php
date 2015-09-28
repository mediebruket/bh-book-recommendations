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

final class BookRecommendationsFeed
{
  private $items;

  const FEED_TEST_URL = 'http://sites/devsite/feed/';

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
    $author = $item->get_author();
    $this->author = $author->get_name();
  }

  private function setImageUrl($item)
  {
    $enclosure = $item->get_enclosure();
    $image_type = explode('/', $enclosure->get_type());

    if ( $image_type && $image_type[0] == 'image' ) {
      $this->imageURL = $enclosure->get_link();
    }
  }
}

function render_feed() {
  $feed = new BookRecommendationsFeed();
  foreach ($feed->getItems() as $item) {
    $review = new BookRecommendationsReview($item);
    if ( function_exists('_log') ) {
      _log($review);
    }
  }
}

if ( WP_DEBUG ) {
  add_filter('wp_feed_cache_transient_lifetime', function() { return 3;} );
}
