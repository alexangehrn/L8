<?php
/*
Plugin Name: L8
Description: L8 is a HR plugin, used to list all delays of each employee
Version:     20161017
Author:      Alexandra Angehrn
*/

//defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( ! class_exists( 'l8' ) ) {

class l8{

  function __construct(){
    register_activation_hook( this, 'createDelaydb' );
    register_activation_hook( this, 'createItinerarydb' );
    register_deactivation_hook( this, 'dropDelaydb' );

    require_once 'autoloader.php';
    spl_autoload_register('Autoloader::loader');
    //require_once 'router.php';
  }

  function createDelaydb () {
     global $wpdb;

    $table_name = $wpdb->prefix . "delay";

   $sql = "CREATE TABLE $table_name (
     id mediumint(9) NOT NULL AUTO_INCREMENT,
     time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
     cause tinytext NOT NULL,
     user mediumint(9) NOT NULL,
     PRIMARY KEY  (id)
   );";

  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }

  function createItinerarydb () {
     global $wpdb;

     $table_name = $wpdb->prefix . "itinerary";

  	$sql = "CREATE TABLE $table_name (
  		id mediumint(9) NOT NULL AUTO_INCREMENT,
  		lines mediumint(2) NOT NULL,
      user mediumint(9) NOT NULL,
  		PRIMARY KEY  (id)
  	);";

  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  	dbDelta( $sql );

  }

  function dropDelaydb () {
     global $wpdb;

     $table_name = $wpdb->prefix . "itinerary";
     $table_name1 = $wpdb->prefix . "delay";

  	 $sql = "DROP TABLE IF EXISTS $table_name, $table_name1";
     $wpdb->query($sql);
  }

}

$l8 = new l8();
}
