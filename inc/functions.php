<?php

function bhbook_shortcode_handler($atts = array()) {
  $a = shortcode_atts( array(
    'url' => BHBR_DEFAULT_URL,
    'display' => ''
    ), $atts );
  return bhbook_get_items($a);
}
add_shortcode('bhbook', 'bhbook_shortcode_handler');

function bhbook_get_items($args) {
  if ( ! wp_style_is('bhbook') ) {
    wp_enqueue_style('bhbook');
  }
  $feed = new BookRecommendationsFeed($args['url']);
  $additional_classes = '';
  if ( array_key_exists('display', $args) && $args['display'] == 'grid' ) {
    $additional_classes = 'bhbook-items__grid';
  }
  $html = '<ul class="bhbook-items '. $additional_classes . '">';
  ob_start();
  foreach ($feed->getItems() as $item) {
    $review = new BookRecommendationsReview($item);
    $html .= bhbook_item_get_markup($review);
  }
  $html .= ob_get_clean();
  $html .= '</ul>';
  return $html;
}

function bhbook_item_get_markup($review) {
  $html = '<li class="bhbook-item">';
  $html .= '<div class="bhbook-image">';
  $html .= '<a target="_blank" href="' . $review->link . '">';
  if ( $review->imageURL ) {
    $html .= '<img src="' . $review->imageURL . '" alt="' . __('Illustrative image for the review of ', 'bh-bookrec') . $review->title . '">';
  }
  else {
    $html .= '<img alt="'. __('The review does not have an image so this is used as a replacement', 'bh-bookrec') .'" src="' . BHBR_URL . 'assets/placeholder.png">';
  }
  $html .= '</a>';
  $html .= '</div>';
  $html .= '<div class="bhbook-content">';
  $html .= '<div class="bhbook-title"><a target="_blank" href="' . $review->link . '">' . $review->title . '</a></div>';
  if ( $review->description ) {
    $html .= '<div class="bhbook-description">' . $review->description . '</div>';
  }
  $html .= '</div>';
  $html .= '</li>' . "\n";
  return $html;
}
