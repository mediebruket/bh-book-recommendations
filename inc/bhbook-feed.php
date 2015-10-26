<?php

final class BookRecommendationsFeed {
  private $items;

  //const FEED_TEST_URL = 'http://sites/devsite/feed/';
  const FEED_TEST_URL = 'http://www.framtida.no/rss/articles/top';

  public function __construct() {
    require_once( ABSPATH . WPINC . '/feed.php' );
    $feed = fetch_feed(self::FEED_TEST_URL);
    $this->items = $feed->get_items();
  }

  public function getItems() {
    return $this->items;
  }
}
