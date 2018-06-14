<?php
/**
 *
 * Plugin Name:       Add Paragraphs Option to Text Widget
 * Description:       Adds the pre 4.8 Paragraphs tick box and removes visual editor ( unless you tell it)
 * Version:           1.6
 * Author:            Alan Fuller
 * Author URI:        http://fullworks.net/wordpress-plugins/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       add-paragraphs-option
 * Domain Path:       /languages
 *
 * Acknowldgements
 *  @gitlost for shwoing me how to turn off the visual editor
 *  @herb_miller for pointing out a code fix
 *
 */

 class add_para_text_widget_settings
 {
     /**
      * Holds the values to be used in the fields callbacks
      */
     // private $options;

     /**
      * Start up
      */
     public function __construct()
     {
         add_action( 'admin_init', array( $this, 'page_init' ) );
     }


     /**
      * Register and add settings
      */
     public function page_init()
     {
       // Get the settings option array
       $this->options = get_option( 'add_paragraph_widget' );

         register_setting(
             'general', // Option group
             'add_paragraph_widget', // Option name
             array( $this, 'sanitize' ) // Sanitize
         );

         add_settings_section(
             'add_paragraph_widget_section', // ID
             __('Add Paragraph Widget Options','add-paragraphs-option'), // Title
             '__return_false', // Section callback (we don't want anything)
             'general' // Page
         );

         add_settings_field(
             'add_paragraph_widget_visual', // ID
             'Turn on visual editor in text widget', // Title
             array( $this, 've_callback' ), // Callback
             'general', // Page
             'add_paragraph_widget_section' // Section
         );

     }

     /**
      * Sanitize each setting field as needed
      *
      * @param array $input Contains all settings fields as array keys
      */
     public function sanitize( $input )
     {
         $new_input = array();
        $new_input = $input; // no validation yet

         return $new_input;
     }

     /**
      *  print one of its values
      */
     public function ve_callback()
     {
         printf(
             "<input type='checkbox' id='add_paragraph_widget_visual' name='add_paragraph_widget[add_paragraph_widget_visual]' value='ve' %s />",
             isset($this->options['add_paragraph_widget_visual'])?checked('ve', $this->options['add_paragraph_widget_visual'],false):''
         );
         printf('<p class="description">%s</p>',__("Tick this box to turn on visual editor - warning the visual ediotor may damage your HTML code so take care","add-paragraphs-option"));
     }
 }


 add_action('init', array( 'add_para_text_widget', 'init' )); // Main Hook

 if ( class_exists('add_para_text_widget', false) ) return; // if class is allready loaded return control to the main script

 class add_para_text_widget { // Plugin class

   public static function init() {

     global $wp_version;
     if ( $wp_version != "4.8" )   {
       return;  // do nothing if not to 4.8
     }

      if ( is_admin() ) {
         self::load_language();
         add_action('in_widget_form', array( __CLASS__, 'add_widget_option' ), 10, 3);
         add_filter('widget_update_callback', array( __CLASS__, 'update_widget_option' ), 10, 4);
    		 if (!defined('VISUAL_TEXT_WIDGET')) {
            $settings = new add_para_text_widget_settings();
          }
        $options = get_option( 'add_paragraph_widget' );
        $ve = false;
        if (isset($options['add_paragraph_widget_visual'])) {
          if ( $options['add_paragraph_widget_visual'] == 've' )
            $ve = true;
        }
        if (defined('VISUAL_TEXT_WIDGET')) {
            $ve = VISUAL_TEXT_WIDGET;
        }
        if ($ve===false) {
         add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
        }
       }


   }
   public static function add_widget_option($widget, $return, $instance) {

     if ($widget->id_base=='text'){
       $filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;
       $options = get_option( 'add_paragraph_widget' );
       $ve = false;
       if (isset($options['add_paragraph_widget_visual'])) {
         if ( $options['add_paragraph_widget_visual'] == 've' )
           $ve = true;
       }
       if (defined('VISUAL_TEXT_WIDGET')) {
           $ve = VISUAL_TEXT_WIDGET;
       }
       if ($ve===false) {
         $title = sanitize_text_field( $instance['title'] );
         ?>

         <p>
           <p><label for="<?php echo $widget->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
           <input class="widefat" id="<?php echo $widget->get_field_id('title'); ?>" name="<?php echo $widget->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
         </p>
         <p><label for="<?php echo $widget->get_field_id( 'text' ); ?>"><?php _e( 'Content:' ); ?></label>
    		<textarea class="widefat" rows="16" cols="20" id="<?php echo $widget->get_field_id('text'); ?>" name="<?php echo $widget->get_field_name('text'); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>
        <?php } ?>

       <p><input id="<?php echo $widget->get_field_id('filter'); ?>" name="<?php echo $widget->get_field_name('filter'); ?>" type="checkbox"<?php checked( $filter ); ?>
         />&nbsp;<label for="<?php echo $widget->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs',"add-paragraphs-option"); ?>
         </label></p>
     		<?php
    }
   }

   public static function update_widget_option($instance, $new_instance, $old_instance, $widget){

     if ($widget->id_base=='text'){
       $instance['filter'] = ! empty( $new_instance['filter'] );
     }
       return $instance;
   }

   public static function admin_init() {
		global $wp_scripts;
		if ( $wp_scripts ) {
			$wp_scripts->remove( 'text-widgets' );
		}
	}

   protected static function load_language() {
 		$languages_path = plugin_basename( dirname(__FILE__).'/lang' );
 		load_plugin_textdomain( 'add-paragraphs-option', false, $languages_path );
 	}
 }
