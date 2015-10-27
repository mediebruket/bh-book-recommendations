<?php

function bhbook_shortcode_handler($atts = array()) {
  $a = shortcode_atts( array(
    'url' => BHBR_DEFAULT_URL
    ), $atts );
  return bhbook_get_items($a['url']);
}
add_shortcode('bhbook', 'bhbook_shortcode_handler');

function bhbook_get_items($url = BHBR_DEFAULT_URL) {
  if ( ! wp_style_is('bhbook') ) {
    wp_enqueue_style('bhbook');
  }
  $feed = new BookRecommendationsFeed($url);
  $html = '<div class="bhbook-items">';
  ob_start();
  foreach ($feed->getItems() as $item) {
    $review = new BookRecommendationsReview($item);
    $html .= bhbook_item_get_markup($review);
  }
  $html .= ob_get_clean();
  $html .= '</div>';
  return $html;
}

function bhbook_item_get_markup($review) {
  $html = '<div class="bhbook-item">';
  $html .= '<div class="bhbook-image">';
  $html .= '<a target="_blank" href="' . $review->link . '">';
  if ( $review->imageURL ) {
    $html .= '<img src="' . $review->imageURL . '" alt="' . __('Illustrasjonsbilete til omtalen av ', 'bh-bookrec') . $review->title . '">';
  }
  else {
    $html .= '<img alt="'. __('Artikkelen manglar bilete og dette vert sett inn i staden', 'bh-bookrec') .'" src="' . BHBR_URL . 'assets/placeholder.png">';
  }
  $html .= '</a>';
  $html .= '</div>';
  $html .= '<div class="bhbook-content">';
  $html .= '<div class="bhbook-title"><a target="_blank" href="' . $review->link . '">' . $review->title . '</a></div>';
  if ( $review->description ) {
    $html .= '<div class="bhbook-description">' . $review->description . '</div>';
  }
  $html .= '</div>';
  $html .= '</div>';
  return $html;
}