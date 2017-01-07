<?php
/**
 * Functions needed to run BvAsozial
 *
 * @package BvAsozial 1.5
 */

/**
 * Fix `$_SERVER` variables for various setups.
 *
 * @global string $PHP_SELF The filename of the currently executing script,
 *                          relative to the document root.
 */
function bva_fix_server_vars() {
    global $PHP_SELF;

    $default_server_values = array(
        'SERVER_SOFTWARE' => '',
        'REQUEST_URI' => '',
    );

    $_SERVER = array_merge( $default_server_values, $_SERVER );

    // Fix for IIS when running with PHP ISAPI
    if ( empty( $_SERVER['REQUEST_URI'] ) || ( PHP_SAPI != 'cgi-fcgi' && preg_match( '/^Microsoft-IIS\//', $_SERVER['SERVER_SOFTWARE'] ) ) ) {

        // IIS Mod-Rewrite
        if ( isset( $_SERVER['HTTP_X_ORIGINAL_URL'] ) ) {
            $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
        }
        // IIS Isapi_Rewrite
        elseif ( isset( $_SERVER['HTTP_X_REWRITE_URL'] ) ) {
            $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
        } else {
            // Use ORIG_PATH_INFO if there is no PATH_INFO
            if ( !isset( $_SERVER['PATH_INFO'] ) && isset( $_SERVER['ORIG_PATH_INFO'] ) )
                $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];

            // Some IIS + PHP configurations puts the script-name in the path-info (No need to append it twice)
            if ( isset( $_SERVER['PATH_INFO'] ) ) {
                if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
                    $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
                else
                    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
            }

            // Append the query string if it exists and isn't null
            if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
    }

    // Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
    if ( isset( $_SERVER['SCRIPT_FILENAME'] ) && ( strpos( $_SERVER['SCRIPT_FILENAME'], 'php.cgi' ) == strlen( $_SERVER['SCRIPT_FILENAME'] ) - 7 ) )
        $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];

    // Fix for Dreamhost and other PHP as CGI hosts
    if ( strpos( $_SERVER['SCRIPT_NAME'], 'php.cgi' ) !== false )
        unset( $_SERVER['PATH_INFO'] );

    // Fix empty PHP_SELF
    $PHP_SELF = $_SERVER['PHP_SELF'];
    if ( empty( $PHP_SELF ) )
        $_SERVER['PHP_SELF'] = $PHP_SELF = preg_replace( '/(\?.*)?$/', '', $_SERVER["REQUEST_URI"] );
}

/**
 * Die with a maintenance message when conditions are met.
 *
 * Checks for a file in the WordPress root directory named ".maintenance".
 * This file will contain the variable $upgrading, set to the time the file
 * was created. If the file was created less than 10 minutes ago, WordPress
 * enters maintenance mode and displays a message.
 *
 * The default message can be replaced by using a drop-in (maintenance.php in
 * the wp-content directory).
 *
 * @since 3.0.0
 * @access private
 *
 * @global int $upgrading the unix timestamp marking when upgrading WordPress began.
 */
function maintenance() {
    if ( ! file_exists( ABSPATH . '.maintenance' ) )
        return;
    require_once( WP_CONTENT_DIR . '/maintenance.php' );
    die();
}
/**
 * Load main class
 */
require_once('class-bva.php');
