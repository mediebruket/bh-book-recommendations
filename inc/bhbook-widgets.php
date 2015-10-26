<?php

 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
  exit;
}

class BookRecommendationsFeed_Widget extends WP_Widget {

    /**
     * @TODO - Rename "widget-name" to the name your your widget
     *
     * Unique identifier for your widget.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $widget_slug = 'bhbook-feed';

    /*--------------------------------------------------*/
  /* Constructor
  /*--------------------------------------------------*/

  /**
   * Specifies the classname and description, instantiates the widget,
   * loads localization files, and includes necessary stylesheets and JavaScript.
   */
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


    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
      return $this->widget_slug;
    }

    /*--------------------------------------------------*/
  /* Widget API Functions
  /*--------------------------------------------------*/

  /**
   * Outputs the content of the widget.
   *
   * @param array args  The array of form elements
   * @param array instance The current instance of the widget
   */
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

    // go on with your widget logic, put everything into a string and …


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
  /**
   * Processes the widget's options to be saved.
   *
   * @param array new_instance The new instance of values to be generated via the update.
   * @param array old_instance The previous instance of values before the update.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }


  /**
   * Generates the administration form for the widget.
   *
   * @param array instance The array of keys and values for the widget.
   */
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


  /**
   * Registers and enqueues widget-specific styles.
   */
  public function register_widget_styles() {

    wp_enqueue_style( 'bhbook' );

  } // end register_widget_styles


} // end class

// TODO: Remember to change 'Widget_Name' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget("BookRecommendationsFeed_Widget");' ) );