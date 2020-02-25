<?php
/**
  * UD Google Custom Search Engine (CSE) Widget
  *
  * A WordPress widget that allows administrator's to add their Google Custom Search Engine.
  *
  * @package     ud-cse
  * @author      Christopher Leonard - University of Delaware
  * @license     GPLv3
  * @link        https://github.com/UDelIT/ud-cse/
  * @copyright   Copyright (c) 2017-2020 University of Delaware
  * @version     2.0.0
*/

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

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
 * @var  string
 * @since    1.0.0
*/

if ( ! class_exists( 'UD_Google_CSE_Widget' ) ) :
	class UD_Google_CSE_Widget extends WP_Widget {
	/**
 		* REGISTER WIDGET
 		* Updated description and name.
 		* @version    2.0.0
 		* @since      1.0.0
	*/
    public function __construct(){
    $widget_ops = array( 'description' => 'Display your Google Custom Search Engine.' );
      parent::__construct(
        false,
        $name=' UD Google Custom Search (CSE) ',
        $widget_ops
      );
    }

	/**
		* DISPLAY WIDGET ON FRONT END
		* Google completely changed the API. Removed legacy code and replaced with latest rendition.
 		* @version    2.0.0
 		* @since      1.0.0
		* @see WP_Widget::widget()
		* @param array $args     Widget arguments.
		* @param array $instance Saved values from database.
	*/
    public function widget($args, $instance){
        extract($args);
        $cse_ID = empty($instance['cse_ID']) ? '' : $instance['cse_ID'];
        $title = empty($instance['title']) ? '' : $instance['title'];

        echo $before_widget;
        if ( $title ) echo $before_title . $title . $after_title;
?>

        <div id="search-form">
            <script>
              (function() {
                var cx = '<?php echo esc_html($cse_ID); ?>';
                var gcse = document.createElement('script');
                gcse.type = 'text/javascript';
                gcse.async = true;
                gcse.src = 'https://cse.google.com/cse?cx=' + cx;
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(gcse, s);
              })();
            </script>
            <gcse:search enableAutoComplete="true"></gcse:search>
        </div> <!-- end #search-form -->
<?php
    echo $after_widget;
  }

	/**
  	* DASHBOARD WIDGET
  	* Revised instructions to match latest Google UX
    * @version    2.0.0
    * @since      1.0.0
    * @see WP_Widget::form()
    * @param array $instance Previously saved values from database.
 	*/
  public function form($instance){
  	//Defaults
    $instance = wp_parse_args( (array) $instance, array('cse_ID'=>'', 'title'=>'') );

    $cse_ID = $instance['cse_ID'];
    $title = $instance['title'];

    # Description
    echo '<p>Your Search Engine ID is located in your <a href="https://cse.google.com/cse/all" target="_blank">Google CSE dashboard</a> under the <strong>Basics</strong> tab.</p>';

    # CSE Label
    echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_html($title) . '" /></p>';
    # Search Engine ID
    echo '<p><label for="' . $this->get_field_id('cse_ID') . '">' . 'Search engine ID:' . '</label><input class="widefat" id="' . $this->get_field_id('cse_ID') . '" name="' . $this->get_field_name('cse_ID') . '" type="text" value="' . esc_html($cse_ID) . '" /></p>';
    }
/**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
*/
    public function update($new_instance, $old_instance){
          $instance = $old_instance;
          $instance['cse_ID'] = sanitize_text_field($new_instance['cse_ID']);
          $instance['title'] = sanitize_text_field($new_instance['title']);

      return $instance;
    }

}// end UD_Google_CSE_Widget class

endif;

add_action('widgets_init', 'UD_Google_CSE_WidgetInit');
function UD_Google_CSE_WidgetInit() {
  register_widget('UD_Google_CSE_Widget');
}
?>
