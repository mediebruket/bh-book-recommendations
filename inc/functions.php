<?php

function bhbook_shortcode_handler($atts = array()) {
  $a = shortcode_atts( array(
    'url' => BHBR_DEFAULT_URL,
    'display' => '',
    'images' => true
    ), $atts );
    $a['images'] = filter_var($a['images'], FILTER_VALIDATE_BOOLEAN);
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
    $html .= bhbook_item_get_markup($review, $args['images']);
  }
  $html .= ob_get_clean();
  $html .= '</ul>';
  return $html;
}

function bhbook_item_get_markup($review, $show_images = true) {
  $html = '<li class="bhbook-item">';
  if ($show_images) {
    $html .= '<div class="bhbook-image">';
    $html .= '<a target="_blank" href="' . $review->link . '">';
    $html .= bhbook_get_image_tag($review);
    $html .= '</a>';
    $html .= '</div>';
  }
  $html .= '<div class="bhbook-content">';
  $html .= '<div class="bhbook-title"><a target="_blank" href="' . $review->link . '">' . $review->title . '</a></div>';
  if ( $review->description ) {
    $html .= '<div class="bhbook-description">' . $review->description . '</div>';
  }
  $html .= '</div>';
  $html .= '</li>' . "\n";
  return $html;
}

function bhbook_get_image_tag($review) {
  $url = $review->imageURL;
  $alt = __('Illustrative image for the review of ', 'bh-book-recommendations') . $review->title;
  if (strpos($url, 'krydder') !== false || strlen($url) == 0) {
    $url = BHBR_URL . 'assets/placeholder.png';
    $alt = __('The review does not have an image so this is used as a replacement', 'bh-book-recommendations');
  }
  elseif (strpos($review->imageURL, 'bokkilden') !== false) {
    $url = $review->imageURL . '&width=300';
  }
  str_replace('http:', '', $url);
  str_replace('https:', '', $url);
  return sprintf('<img src="%s" alt="%s">', $url, $alt);
}
