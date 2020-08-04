<?php

final class BookRecommendationsFeed
{
    private $items;

    public function __construct($url = BHBR_DEFAULT_URL, $max_no = BHBR_DEFAULT_NO_ITEMS)
    {
        require_once(ABSPATH . WPINC . '/feed.php');

        $feed = fetch_feed($url);
        if (! is_wp_error($feed)) {
            $this->items = $feed->get_items(0, $max_no);
        }
    }

    public function getItems()
    {
        return $this->items;
    }
}
