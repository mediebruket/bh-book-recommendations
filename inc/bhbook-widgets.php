<?php

 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
  exit;
}

class BookRecommendationsFeed_Widget extends WP_Widget {

  protected $widget_slug = 'bhbook-feed';

  public function __construct() {

    parent::__construct(
      $this->get_widget_slug(),
      __( 'BH Book Recommendations', 'bh-book-recommendations' ),
      array(
        'classname'  => 'bh-book-recommendations-class',
        'description' => __( 'Displays book recommendations content from RSS.', 'bh-book-recommendations' )
        )
      );

    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );

    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

  } // end constructor


  public function get_widget_slug() {
    return $this->widget_slug;
  }


  public function widget( $args, $instance ) {

    // Check if there is a cached output
    $cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

    if ( !is_array( $cache ) ) {
      $cache = array();
    }

    if ( ! isset ( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }

    if ( isset ( $cache[ $args['widget_id'] ] ) ) {
      return print $cache[ $args['widget_id'] ];
    }

    extract( $args, EXTR_SKIP );

    $widget_string = $before_widget;

    $title = apply_filters( 'widget_title', $instance['title'] );
    $url = esc_url($instance['url']);
    $no_items = $instance['no_items'];
    $images = ! $instance['hide_images'];

    if ( ! empty( $title ) ) {
      $widget_string .= $args['before_title'] . $title . $args['after_title'];
    }

    $feed_args = array(
      'url' => $url,
      'number' => $no_items,
      'images' => $images
      );
    $widget_string .= bhbook_get_items($feed_args);
    $widget_string .= $after_widget;


    $cache[ $args['widget_id'] ] = $widget_string;

    wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

    print $widget_string;

  } // end widget


  public function flush_widget_cache() {
    wp_cache_delete( $this->get_widget_slug(), 'widget' );
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['url'] = ( ! empty( $new_instance['url'] ) ) ? esc_url_raw(strip_tags( $new_instance['url'] )) : '';
    $instance['no_items'] = ( ! empty( $new_instance['no_items'] ) ) ? intval( $new_instance['no_items'] ) : '';
    $instance['hide_images'] = ( ! empty( $new_instance['hide_images'] ) ) ? esc_attr( $new_instance['hide_images'] ) : '0';
    return $instance;
  }


  public function form( $instance ) {

    if ( isset( $instance['title'] ) ) {
      $title = $instance['title'];
    }
    else {
      $title = __( 'Book recommendations', 'bh-book-recommendations' );
    }
    if ( isset( $instance['url'] ) ) {
      $url = $instance['url'];
    }
    else {
      $url = BHBR_DEFAULT_URL;
    }
    if ( isset( $instance['no_items'] ) ) {
      $no_items = $instance['no_items'];
    }
    else {
      $no_items = BHBR_DEFAULT_NO_ITEMS;
    }
    if ( isset ($instance['hide_images'] ) ) {
      $hide_images = esc_attr($instance['hide_images']);
    }
    else {
      $hide_images = 0;
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bh-book-recommendations' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'URL:', 'bh-book-recommendations' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_url( $url ); ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'no_items' ); ?>"><?php _e( 'Number of items:', 'bh-book-recommendations' ); ?></label>
      <select id="<?php echo $this->get_field_id( 'no_items' ); ?>" name="<?php echo $this->get_field_name( 'no_items' ); ?>">
        <option value="1"  <?php selected( $no_items, 1 );  ?>>1</option>
        <option value="2"  <?php selected( $no_items, 2 );  ?>>2</option>
        <option value="3"  <?php selected( $no_items, 3 );  ?>>3</option>
        <option value="4"  <?php selected( $no_items, 4 );  ?>>4</option>
        <option value="5"  <?php selected( $no_items, 5 );  ?>>5</option>
        <option value="6"  <?php selected( $no_items, 6 );  ?>>6</option>
        <option value="7"  <?php selected( $no_items, 7 );  ?>>7</option>
        <option value="8"  <?php selected( $no_items, 8 );  ?>>8</option>
        <option value="9"  <?php selected( $no_items, 9 );  ?>>9</option>
        <option value="10" <?php selected( $no_items, 10 ); ?>>10</option>
      </select>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'hide_images' ); ?>"><?php _e( 'Hide images:', 'bh-book-recommendations' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'hide_images' ); ?>" name="<?php echo $this->get_field_name( 'hide_images' ); ?>" type="checkbox" value="1" <?php checked( $hide_images, '1' ); ?> />
    </p>
    <?php

  } // end form


  public function register_widget_styles() {

    wp_enqueue_style( 'bhbook' );

  } // end register_widget_styles


} // end class

add_action('widgets_init', function() {
    register_widget("BookRecommendationsFeed_Widget");
});
