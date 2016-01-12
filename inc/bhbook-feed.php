<?php

final class BookRecommendationsFeed {
  private $items;

  public function __construct($url = BHBR_DEFAULT_URL ) {
    require_once( ABSPATH . WPINC . '/feed.php' );
    $feed = fetch_feed($url);
    if ( ! is_wp_error($feed) ) {
      $this->items = $feed->get_items();
    }
    else {
      echo __('There is something wrong with the feed. Please check the arguments.', 'bh-book-recommendations');
    }
  }

  public function getItems() {
    return $this->items;
  }
}
