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

    public function __construct(){
      register_activation_hook( __FILE__, array('l8','activatePlugin') );
      register_deactivation_hook( __FILE__, array('l8','deactivatePlugin') );
      add_action('init', array($this,'initiationParam'));
      add_action('admin_menu', array($this, 'adminMenu'));
      add_action('template_redirect', array($this,'newTemplate'));


      require_once 'autoloader.php';
      spl_autoload_register('Autoloader::loader');
      require_once 'router.php';


    }

    public function initiationParam(){
      self::add_js_scripts();
      self::add_css_style();
      self::rewrite_rules();
    }

    public function activatePlugin(){
      self::createDelaydb();
      self::createItinerarydb();
      self::newPage();
    }

    public function deactivatePlugin(){
      self::dropDb();
      self::deletePage();

    }

    public function newTemplate(){
      global $wp;
      $pages = array(
        'connection',
        'home',
        'delay'
      );

      foreach ($pages as $page){
        if ($wp->query_vars["pagename"] == $page.'-l8') {
            $templatefilename = $page.'.php';
                $return_template = WP_PLUGIN_DIR . '/l8/templates/' . $templatefilename;
            self::redirectTemplate($return_template);
        }
      }
    }

    public function redirectTemplate($url) {
        global $post, $wp_query;
        if (have_posts()) {
            include($url);
            die();
        } else {
            $wp_query->is_404 = true;
        }
    }

    public function rewrite_rules() {
      global $wp_rewrite;

      add_rewrite_rule('l8/$', WP_PLUGIN_URL . '/l8/l8.php', 'top');
      $wp_rewrite->flush_rules(true);
    }


    public function newPage(){

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


    public function my_error_notice() {
      ?>
      <div class="error notice">
        <p><?php _e( 'You have already a page named connexion-l8, home-l8 or delay-l8 ! <br/> Please rename one of these pages and try again! ', 'my_plugin_textdomain' ); ?></p>
      </div>
      <?php
    }

    public function deletePage(){
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

    public function createDelaydb() {
      global $wpdb;

      $table_name = $wpdb->prefix . "delay";

      $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time mediumint(3) NOT NULL,
        cause tinytext NOT NULL,
        detail tinytext NOT NULL,
        type tinytext NOT NULL,
        line tinytext NOT NULL,
        user mediumint(9) NOT NULL,
        today timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
      );";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
    }

    public function createItinerarydb() {
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

    public function dropDb() {
      global $wpdb;

      $table_name = $wpdb->prefix . "itinerary";
      $table_name1 = $wpdb->prefix . "delay";

      $sql = "DROP TABLE IF EXISTS $table_name, $table_name1";
      $wpdb->query($sql);
    }

    public function adminMenu(){
      add_menu_page( 'Delays Page', 'Delays', 'administrator', 'delays', array($this,'adminPage') );
    }

    public function adminPage(){
      echo "<h1>Liste des retards employés </h1>";
      echo "<form id='filter'><select name='filter'>";
      echo "<option value='today'>Today</option>";
      echo "<option value='week'>This Week</option>";
      echo "<option value='month'>This Month</option>";
      echo "</select></form>";
      $delays = self::selectDelays();

      echo '<div id="content_delays">';
      echo '<table border=1>';
      echo '<tr><td>Name</td>';
      echo '<td>Delay (min)</td>';
      echo '<td>Cause</td>';
      echo '<td>Detail</td>';
      echo '<td>Type</td>';
      echo '<td>Line</td>';
      echo '<td>Date and Time</td></tr>';

      foreach($delays as $delay){
        echo '<tr><td>'.$delay->user_nicename.'</td>';
        echo '<td>'.$delay->time.'</td>';
        echo '<td>'.$delay->cause.'</td>';
        echo '<td>'.$delay->detail.'</td>';
        echo '<td>'.$delay->type.'</td>';
        echo '<td>'.$delay->line.'</td>';
        echo '<td>'.$delay->today.'</td></tr>';
      }
      
      echo '</table>';
      echo '</div>';

      self::apiSNCF();
      self::exportExcel();

    }

    public function exportExcel(){
      echo "<a href='l8.php?controller=exportController&action=exportExcelDay'>Exporter le rapport d'aujourd'hui en Excel</a><br/>";
      echo "<a href='l8.php?controller=exportController&action=exportExcelWeek'>Exporter le rapport de cette semaine en Excel</a><br/>";
      echo "<a href='l8.php?controller=exportController&action=exportExcelMonth'>Exporter le rapport de ce mois en Excel</a><br/>";
    }

    public function apiSNCF(){
      echo '<div id="traffic">';
      echo 'Metros : <br/>';
      echo '<button class="metros" id="1">1</button>';
      echo '<button class="metros" id="2">2</button>';
      echo '<button class="metros" id="3">3</button>';
      echo '<button class="metros" id="4">4</button>';
      echo '<button class="metros" id="5">5</button>';
      echo '<button class="metros" id="6">6</button>';
      echo '<button class="metros" id="7">7</button>';
      echo '<button class="metros" id="8">8</button>';
      echo '<button class="metros" id="9">9</button>';
      echo '<button class="metros" id="10">10</button>';
      echo '<button class="metros" id="11">11</button>';
      echo '<button class="metros" id="12">12</button>';
      echo '<button class="metros" id="13">13</button>';
      echo '<button class="metros" id="14">14</button><br/>';

      echo 'Rers : <br/>';
      echo '<button class="rers" id="A">A</button>';
      echo '<button class="rers" id="B">B</button>';
      echo '<button class="rers" id="C">C</button>';
      echo '<button class="rers" id="D">D</button>';
      echo '<button class="rers" id="E">E</button>';
      echo '</div>';
      echo '<div id="result">';
      echo '</div><br/>';

    }
    public function selectDelays(){
      global $wpdb;

      $d = date('Y-m-d');

      $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, type, line
                                    FROM wp_delay
                                    LEFT JOIN wp_users
                                    ON wp_users.ID = wp_delay.user
                                    WHERE TRUE
                                    AND today like '$d%'");

      return $delays;
    }

    public function add_js_scripts(){
      wp_enqueue_script("jquery");
      wp_enqueue_script( 'script', WP_PLUGIN_URL .'/l8/js/script.js', array('jquery'), '1.0', true );
    }
    public function add_css_style(){
      wp_enqueue_style('style.css',WP_PLUGIN_URL .'/l8/css/style.css');
    }

  }

  $l8 = new l8();
}
