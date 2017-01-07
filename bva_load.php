<?php
/**
  * Bootstrap file for setting up ABSPATH and loading up the bva_config.php files
  * The bva_config.php file itself will then load up all the neccessary constants
  * and objects
  *
  * If the bva_config.php file is not found then an error_reporting
  * will be displayed asking the visitor to set up the configuration file.
  *
  * @package BvAsozial 1.5
  */

  /** Define ABSPATH as this file's directory */
  if( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname(__FILE__) . '/');
  }

  if( file_exists( ABSPATH . 'bva_config.php') ) {

    /** the config file was located in the root directory */
    require_once( ABSPATH . 'bva_config.php' );
  } else {
    define( 'INCPATH', 'includes' );
    define( 'BVA_CONTENT_DIR' , ABSPATH . 'bva_content');
    require_once( ABSPATH . INCPATH . '/load.php' );
    require_once( ABSPATH . INCPATH . '/functions.php' );

    $die  = sprintf(
  		"There doesn't seem to be a config file. I need this %s file to get started.",
  		'<code>bva_config.php</code>'
  	) . '</p>';
  	$die .= '<p>' . sprintf(
  	  'Need more help? We can: <a href="%s">Help me!</a>',
    	'http://occloxium.com/bvasozial/support/'
  	) . '</p>';
  	$die .= '<p>' . sprintf(
  		"The safest way is to manually create the %s file",
  		'<code>bva_config.php</code>'
  	) . '</p>';
  	die($die);
  }
 ?>
