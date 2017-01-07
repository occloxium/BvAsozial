<?php
/**
* Loads the BvAsozial environtment
*
* @package BvAsozial 1.5
*/
if( !isset($bvasozial_did_header) ) {

  $bvasozial_did_header = true;

  // load bvasozial library
  require_once( dirname(__FILE__) . '/bva_load.php');

  // Set up BvAsozial
  bvasozial();

  // Load page
  
}

 ?>
