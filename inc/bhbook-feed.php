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
      echo __('Det er noko gale med straumen. Sjekk parametrane.', 'bh-bookrec');
    }
  }

  public function getItems() {
    return $this->items;
  }
}
