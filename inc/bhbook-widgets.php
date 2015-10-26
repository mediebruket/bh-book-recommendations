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
      __( 'BH Bokomtaler', 'bh-bookrec' ),
      array(
        'classname'  => 'bh-bookrec-class',
        'description' => __( 'Hentar og viser RSS med bokomtaler frå definert URL.', 'bh-bookrec' )
        )
      );

    // Register site styles and scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );

    // Refreshing the widget's cached output with each new post
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

    if ( ! empty( $title ) ) {
      $widget_string .= $args['before_title'] . $title . $args['after_title'];
    }
    $widget_string .= bhbook_get_items();
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
    return $instance;
  }


  public function form( $instance ) {

    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'Bokomtaler', 'bh-bookrec' );
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Tittel:', 'bh-bookrec' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php

  } // end form


  public function register_widget_styles() {

    wp_enqueue_style( 'bhbook' );

  } // end register_widget_styles


} // end class

// TODO: Remember to change 'Widget_Name' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("BookRecommendationsFeed_Widget");' ) );