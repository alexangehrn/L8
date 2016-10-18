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
    register_activation_hook( __FILE__, array('l8','activatePlugin') );
    register_deactivation_hook( __FILE__, array('l8','deactivatePlugin') );
    add_action('admin_menu', array($this, 'adminMenu'));
    add_filter('the_content',array($this,'my_content'));
    add_action( 'init', array($this, 'rewrite_rules'));

    require_once 'autoloader.php';
    spl_autoload_register('Autoloader::loader');
    require_once 'router.php';


  }

  function activatePlugin(){
    add_action('init', array($this, 'createDelaydb'));
    add_action('init', array($this, 'createItinerarydb'));
    add_action('init', array($this, 'newPage'));
  }

  function deactivatePlugin(){
    add_action('init', array($this, 'dropDb'));
    add_action('init', array($this, 'deletePage'));
  }


  function rewrite_rules() {
      global $wp_rewrite;

      add_rewrite_rule('l8/$', WP_PLUGIN_URL . '/l8/l8.php', 'top');
      $wp_rewrite->flush_rules(true);
  }

  function my_content($content) {

    if ( is_page('connection') )
        include 'connection.html';

  }

  function newPage(){

    $pages = array(
      'connection',
      'home',
      'delay'
    );

    foreach ($pages as $page){
      if( is_page($page.'-l8')){
        $post = array(
          'post_content'=> '',
          'post_name'=> '$page-l8',
          'post_title'=> '$page',
          'post_status'=> 'publish',
          'post_type'=> 'page',
          'comment_status'=> 'closed',
          'post_author'=> 1,
        );

        wp_insert_post($post, false);

      }else{
        add_action( 'admin_notices', array($this,'my_error_notice') );
      }
    }

  }


  function my_error_notice() {
      ?>
      <div class="error notice">
          <p><?php _e( 'You have already a page named connexion-l8, home-l8 or delay-l8 ! <br/> Please rename one of these pages and try again! ', 'my_plugin_textdomain' ); ?></p>
      </div>
      <?php
  }

  function deletePage($page1){
    $page = get_page_by_title('connection');
    $page_id = $page->ID;
    wp_delete_post($page_id, true);
  }

  function createDb() {
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

  function createItinerarydb() {
     global $wpdb;

     $table_name = $wpdb->prefix . "itinerary";

  	$sql = "CREATE TABLE $table_name (
  		id mediumint(9) NOT NULL AUTO_INCREMENT,
  		line mediumint(2) NOT NULL,
      user mediumint(9) NOT NULL,
  		PRIMARY KEY  (id)
  	);";

  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  	dbDelta( $sql );

  }

  function dropDelaydb() {
     global $wpdb;

     $table_name = $wpdb->prefix . "itinerary";
     $table_name1 = $wpdb->prefix . "delay";

  	 $sql = "DROP TABLE IF EXISTS $table_name, $table_name1";
     $wpdb->query($sql);
  }

  function adminMenu(){
          add_menu_page( 'Delays Page', 'Delays', 'administrator', 'delays', array($this,'adminPage') );
  }

  function adminPage(){
          echo "<h1>Hello World!</h1>";
  }
}

$l8 = new l8();
}
