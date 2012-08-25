<?php
/*
Plugin Name: Vimeo/Youtube to Media Library
Plugin URI: 
Description: Enhancing the Media Library by allowing Vimeo/Youtube content to be added as a piece of media.
Author: Garry Ing
Version: 1.0.0
Author URI: http://garrying.com
*/

/**
*  wp-content and plugin urls/paths
*/
// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

include_once("variables.php");

// Plugin Hooks
register_activation_hook( __FILE__, array('mlv','mlv_plugin_activate') );
register_uninstall_hook( __FILE__, array('mlv','mlv_plugin_uninstall') );
           
	
if (!class_exists('mlv')) {
    class mlv {
        //This is where the class variables go, don't forget to use @var to tell what they're for
        /**
        * @var string The options string name for this plugin
        */
        var $optionsName = 'mlv_options';
        
        /**
        * @var string $localizationDomain Domain used for localization
        */
        var $localizationDomain = "mlv";
        
        /**
        * @var string $pluginurl The path to this plugin
        */ 
        var $thispluginurl = '';
        /**
        * @var string $pluginurlpath The path to this plugin
        */
        var $thispluginpath = '';
            
        /**
        * @var array $options Stores the options for this plugin
        */
        var $options = array();
        
		
		
		var $adminmenuRole='administrator';
		
		static public $adminCapability = 'add_videos';
	
		
        //Class Functions
        /**
        * PHP 4 Compatible Constructor
        */
        function mlv(){$this->__construct();}
        
        /**
        * PHP 5 Constructor
        */        
        function __construct(){
            
            //"Constants" setup
            $this->thispluginurl = WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)).'/';
            $this->thispluginpath = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)).'/';  
            
            
            
            //Actions        
            add_action("admin_menu", array(&$this,"admin_menu_link"), 0);

            
        }
        
        /*
		 * Plugin activation
		 */
		static function mlv_plugin_activate()
		{
			// Initialize default capabilities
			
			$role = get_role( 'administrator' ); 
			$role->add_cap( self::$adminCapability );
			
			$role = get_role( 'editor' ); 
			$role->add_cap( self::$adminCapability );
			
		}
		
		/*
		 * Plugin deactivation
		 */
		static function mlv_plugin_uninstall()
		{
			global $wp_roles;
			// Initialize default capabilities
			
			$rolenames = $wp_roles->get_names() ;
			foreach ( $rolenames as $rolename => $displ ) {
				$role = get_role( $rolename );
				$role->remove_cap( self::$adminCapability );
				$role->remove_cap( self::$assignCapability );
			}

			
		} 
        
        /**
        * @desc Adds menu link to add video
        */
        function admin_menu_link() {
            global $mlv_dir, $mlv_base;
			add_submenu_page("upload.php", "Add Video", "Add Video", self::$adminCapability, $mlv_dir."/add.php");
			
        }
        
		
		/* ============================
		* MISC FUNCTIONS
		* ============================
		*/
		function cleanQuery($string)
		{
		  if(get_magic_quotes_gpc())  // prevents duplicate backslashes
		  {
			$string = stripslashes($string);
		  }
		  if (phpversion() >= '4.3.0')
		  {
			$string = mysql_real_escape_string($string);
		  }
		  else
		  {
			$string = mysql_escape_string($string);
		  }
		  return $string;
		}    
			
        
  } //End Class
} //End if class exists statement

//instantiate the class
if (class_exists('mlv')) {
    $mlv_var = new mlv();
} 

function video_preview($form_fields, $post) {

	$meta_keys = get_post_custom_keys($post->ID);
	$meta_values = get_post_meta($post->ID, $meta_keys[0], true);

	$form_fields["media_preview"]["label"] = __("Media Preview");
	$form_fields["media_preview"]["input"] = "html";
	$form_fields["media_preview"]["html"] = $meta_values;

	return $form_fields;
}
// attach our function to the correct hook
add_filter("attachment_fields_to_edit", "video_preview", null, 2);


?>