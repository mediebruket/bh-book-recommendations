<?php

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