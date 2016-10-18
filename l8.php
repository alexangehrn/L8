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
      add_action('init', array($this,'add_js_scripts'));
      add_action('admin_menu', array($this, 'adminMenu'));
      add_action( 'init', array($this, 'rewrite_rules'));
      add_action("template_redirect", array($this,'newTemplate'));


      require_once 'autoloader.php';
      spl_autoload_register('Autoloader::loader');
      require_once 'router.php';


    }

    function activatePlugin(){
      self::createDelaydb();
      self::createItinerarydb();
      self::newPage();
    }

    function deactivatePlugin(){
      self::dropDb();
      self::deletePage();

    }

    function newTemplate(){
      global $wp;
      $pages = array(
        'connection',
        'home',
        'delay'
      );
      $plugindir = dirname( __FILE__ );
      foreach ($pages as $page){
        if ($wp->query_vars["pagename"] == $page.'-l8') {
            $templatefilename = $page.'.php';
                $return_template = $plugindir . '/templates/' . $templatefilename;
            self::redirectTemplate($return_template);
        }
      }
    }

    function redirectTemplate($url) {
        global $post, $wp_query;
        if (have_posts()) {
            include($url);
            die();
        } else {
            $wp_query->is_404 = true;
        }
    }

    function rewrite_rules() {
      global $wp_rewrite;

      add_rewrite_rule('l8/$', WP_PLUGIN_URL . '/l8/l8.php', 'top');
      $wp_rewrite->flush_rules(true);
    }


    function newPage(){

      $pages = array(
        'connection-l8',
        'home-l8',
        'delay-l8'
      );

      foreach ($pages as $page){
        if( ! is_page($page)){
          $post = array(
            'post_content'=> '',
            'post_name'=> $page,
            'post_title'=> $page,
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

    function deletePage(){
      $pages = array(
        'connection-l8',
        'home-l8',
        'delay-l8'
      );

      foreach ($pages as $page){
        $sheet = get_page_by_title($page);
        $page_id = $sheet->ID;
        wp_delete_post($page_id, true);
      }
    }

    function createDelaydb() {
      global $wpdb;

      $table_name = $wpdb->prefix . "delay";

      $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time mediumint(3) NOT NULL,
        cause tinytext NOT NULL,
        user mediumint(9) NOT NULL,
        today timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
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

    function dropDb() {
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
      echo "<h1>Liste des retards employés </h1>";
      echo "<form id='filter'><select name='filter'>";
      echo "<option value='today'>Today</option>";
      echo "<option value='week'>This Week</option>";
      echo "</select></form>";
      $delays = self::selectDelays();

      echo '<table border=1>';
      echo '<tr><td>Name</td>';
      echo '<td>Delay (min)</td>';
      echo '<td>Cause</td>';
      echo '<td>Date and Time</td></tr>';

      foreach($delays as $delay){
        echo '<tr><td>'.$delay->user_nicename.'</td>';
        echo '<td>'.$delay->time.'</td>';
        echo '<td>'.$delay->cause.'</td>';
        echo '<td>'.$delay->today.'</td></tr>';
      }
      echo '</table>';

    }

    function selectDelays(){
      global $wpdb;

      $d = date('Y-m-d');

      $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today
                                    FROM wp_delay
                                    LEFT JOIN wp_users
                                    ON wp_users.ID = wp_delay.user
                                    WHERE TRUE
                                    AND today like '$d%'");

      return $delays;
    }

    function add_js_scripts(){
      wp_enqueue_script("jquery");
      wp_enqueue_script( 'script', WP_PLUGIN_URL .'/l8/js/script.js', array('jquery'), '1.0', true );
    }



  }

  $l8 = new l8();
}
