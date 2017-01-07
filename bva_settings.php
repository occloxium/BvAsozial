<?php
/**
 * Used for setting up common variables and loading up some dependencies
 *
 * @package BvAsozial 1.5
 */

/**
 * Stores the location of most of the scripts and classes of core content
 */
define('INCPATH', 'includes');

/**
 * Stores the location of true content
 */
define('BVA_CONTENT_DIR', ABSPATH . 'bva_content');

/**
 * Loads necessary scripts for runtime
 */
require( ABSPATH . INCPATH . '/load.php');
require( ABSPATH . INCPATH . '/functions.php');

bva_fix_server_vars();

maintenance();
