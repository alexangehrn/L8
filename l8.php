<?php
/*
Plugin Name: L8
Description: L8 is a HR plugin, used to list all delays of each employee
Version:     20161017
Author:      Alexandra Angehrn
Domain Path: /languages
*/

if ( ! class_exists( 'l8' ) ){

  class l8
  {

    public function __construct()
    {

      register_activation_hook( __FILE__, array( 'l8', 'activatePlugin') );
      register_deactivation_hook( __FILE__, array( 'l8', 'deactivatePlugin') );
      add_action( 'init', array( $this, 'initiationParam'));
      add_action( 'admin_menu', array( $this, 'adminActions'));
      add_action( 'template_redirect', array( $this, 'newTemplate'));
      add_action( 'wp_ajax_filter', array( $this, 'adminFilter'));
      add_action( 'admin_post_exportCSV', array( $this, 'exportCSV'));
      add_action( 'admin_post_addAdress', array( $this, 'addAdress'));
      add_action( 'admin_post_deleteAdress', array( $this, 'deleteAdress'));

    }

    public function activatePlugin()
    {

      self::createDelaydb();
      self::createAdressdb();
      self::newPage();

    }

    public function deactivatePlugin()
    {

      self::dropDb();
      self::deletePage();

    }

    public function initiationParam()
    {

      self::add_js_scripts();
      self::add_css_style();

      load_plugin_textdomain( 'l8', false, plugin_basename(dirname(__FILE__)).'/languages' );

      if( isset( $_POST['time'] )){
        self::addDelay();
      }
      if( isset( $_POST['login'] )){
        self::checkLogs();
      }

    }

    public function adminActions()
    {

      self::adminMenu();

    }

    public function adminFilter()
    {

      if( isset( $_POST['id'] )){

        if( $_POST['id'] == 'month' ){
          self::monthFilter();
        }

        if( $_POST['id'] == 'week' ){
          self::weekFilter();
        }

        else{
          self::dayFilter();
        }

      }

    }

    public function exportCSV()
    {

      if( $_POST["export"] == "day" ){
        self::exportExcelDay();
      }

      if( $_POST["export"] == "week" ){
        self::exportExcelWeek();
      }

      if( $_POST["export"] == "month" ){
        self::exportExcelMonth();
      }

    }

    public function isArray( $data )
    {

      if( is_array( $data ) ){
        return $data;
      }else{
        return false;
      }

    }

    public function isNumeric( $data )
    {

      return is_numeric( $data );

    }

    public function isInt( $data )
    {

      return is_int( $data );

    }

    public function isString( $data )
    {

      return is_string( $data );

    }

    public function isEmail( $data )
    {

      return is_email( $data );

    }

    public function isMetro( $data )
    {

      $lines = array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14' );

      if ( in_array( $data, $lines ) ) {
        return $data;
      }else{
        return false;
      }

    }

    public function isRER( $data )
    {

      $lines =array( 'A', 'B', 'C', 'D', 'E' );

      if ( in_array( $data, $lines ) ) {
        return $data;
      }else{
        return false;
      }

    }

    //activate Plugin actions

    public function createDelaydb()
    {

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

    public function createAdressdb()
    {

      global $wpdb;

      $table_name = $wpdb->prefix . "mail_adress";

      $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                email tinytext NOT NULL,
                PRIMARY KEY  (id)
              );";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

    }

    public function newPage()
    {

      $pages = array(
        'connection-l8',
        'home-l8',
        'delay-l8'
      );

      foreach ( $pages as $page ){
        if( ! is_page( $page )){
          $post = array(
            'post_content'=> '',
            'post_name'=> $page,
            'post_title'=> $page,
            'post_status'=> 'publish',
            'post_type'=> 'page',
            'comment_status'=> 'closed',
            'post_author'=> 1,
          );

          wp_insert_post( $post, false );

        }else{
          add_action( 'admin_notices', array( $this, 'my_error_notice') );
        }
      }

    }

    public function my_error_notice()
    {

      ?>
      <div class="error notice">
        <p><?php echo __( 'You have already a page named connection-l8, home-l8 or delay-l8 ! <br/> Please rename one of these pages and try again! ', 'l8' ); ?></p>
      </div>
      <?php

    }

    //deactivate Plugin actions

    public function deletePage()
    {

      $pages = array(
        'connection-l8',
        'home-l8',
        'delay-l8',
      );

      foreach ( $pages as $page ){
        $sheet = get_page_by_title( $page );
        $page_id = $sheet->ID;
        wp_delete_post( $page_id, true );
      }

    }

    public function dropDb()
    {

      global $wpdb;

      $table_name1 = $wpdb->prefix . "delay";
      $table_name = $wpdb->prefix . "delay";

      $sql = "DROP TABLE IF EXISTS $table_name, $table_name1";
      $wpdb->query( $sql );

    }

    //Init params

    public function add_js_scripts()
    {

      wp_enqueue_script( "jquery" );
      wp_enqueue_script( 'script', WP_PLUGIN_URL .'/l8/js/script.js', array('jquery'), '1.0', true );

    }

    public function add_css_style()
    {

      wp_enqueue_style( 'style.css', WP_PLUGIN_URL .'/l8/css/style.css' );
      wp_register_style( 'bootstrap-css', WP_PLUGIN_URL . '/l8/css/bootstrap/css/bootstrap.min.css', array(), '3.0.1', 'all' );
      wp_enqueue_style( 'bootstrap-css' );

    }

    public function addDelay()
    {

      global $wpdb;
      global $current_user;
      get_currentuserinfo();

      if ( ! isset( $_POST['nonce_delay'] ) || ! wp_verify_nonce( $_POST['nonce_delay'], 'addDelay' ) ) {

        print __( 'Sorry, your nonce did not verify.', 'l8' );
        exit;

      } else {

        $user = $current_user->ID;

        $time = $_POST["time"];

        $checkTime = self::isNumeric( $time );
        if( ! $checkTime ){
          wp_redirect( 'delay-l8?time=inccorect' );
          exit;
        }

        $causeS = $_POST["cause"];
        $cause = sanitize_text_field( $causeS );
        $checkCause = self::isString( $cause );
        if( ! $checkCause ){
          wp_redirect( 'delay-l8?cause=inccorect' );
          exit;
        }

        if( $_POST["detail"] != "" ){
          $detailS = $_POST["detail"];
          $detail = sanitize_text_field( $detailS );
          $checkDetail = self::isString( $detail );
          if( ! $checkDetail ){
            wp_redirect( 'delay-l8?detail=inccorect' );
            exit;
          }

          $type = "";
          $line = "";
        }else{
          $type = $_POST["type"];
          $checkType = self::isString( $type );
          if( ! $checkType ){
            wp_redirect( 'delay-l8?type=inccorect' );
            exit;
          }

          $line = $_POST["line"];
          $checkLine = self::isString( $line );
          if( $type == "metros" ){
            $checkNumLine = self::isMetro( $line );
          }
          if( $type == "rers" ){
            $checkNumLine = self::isRER( $line );
          }
          if( ! $checkLine || ! $checkNumLine ){
            wp_redirect( 'delay-l8?line=inccorect' );
            exit;
          }

          $detail = "";
        }

        if( $cause == 'RATP' ){
          $validation= file_get_contents( 'http://api-ratp.pierre-grimaud.fr/v2/traffic/'.$type.'/'.$line );
          $message = json_decode( $validation );
          $valid = $message->response->message;
        }

        $declare = $wpdb->insert("wp_delay", array(
          "user" => $user,
          "time" => $time,
          "cause" => $cause,
          "detail" => $detail,
          "type" => $type,
          "line" => $line,
        ));

        if($declare){

          //instanciation (Nom de l'expediteur/adresse de l'expediteur/adresse de rÈponse)
          include WP_PLUGIN_DIR . '/l8/class/Mail.php';
          $email = new Mail( $current_user->user_email, $current_user->user_nicename, $current_user->user_email );

          //adresses du/des destinataires
          $adresses = self::selectAdress();
          foreach ($adresses as $adress ) {
            $admin_email = $adress->email;
            $email->ajouter_destinataire( $admin_email );
          }
          //adresses copie(s) cachÈe(s)
          $email->ajouter_bcc( $current_user->user_email );

          //contenu(objet/contenu plain text/contenu text html)
          $siteurl = get_option( 'siteurl', '' );

          $message1 = __( "Dear Admin,", 'l8' )." \n";
          $message1 .= $current_user->user_login.__( " is going to be late today. His delay is of ", 'l8' ).$time.__( "min for the following cause: ", 'l8' ).$cause." \n";
          $message1 .= __( "You can find all the reports", 'l8' )." <a href='".$siteurl."wp-admin/admin.php?page=delays'>".__( "here", 'l8' )."</a> \n";
          if($cause == 'RATP'){
            $message1 .= __( "Validation via RATP : ", 'l8').$valid."\n";
          }

          $message = "<html><head><title>".__( "Delay", 'l8' )."</title></head><body>";
          $message .= __( "Dear Admin,", 'l8' )." <br/>";
          $message .= $current_user->user_login.__( " is going to be late today. His delay is of ", 'l8' ).$time.__( "min for the following cause: ", 'l8' ).$cause." <br/>";
          $message .= __( "You can find all the reports", 'l8' ). "<a href='".$siteurl."/wp-admin/admin.php?page=delays'>".__( "here", 'l8' )."</a><br/>";
          if($cause == 'RATP'){
            $message .= __( "Validation via RATP : ", 'l8' ).$valid."<br/>";
          }
          $message .= "</body></html>";

          $email->contenu( __( "Delay of ", 'l8' ) . $current_user->user_login, $message1, $message);

          $email->envoyer();

          wp_redirect( 'home-l8?notif=ok' );
          exit;

        }else{

          wp_redirect( 'home-l8?notif=nok' );
          exit;

        }

      }

    }

    public function checkLogs()
    {

      if( isset ( $_POST["login"] ) && isset ( $_POST["password"] ) ){

        $creds = array();

        $login = $_POST["login"];
        $creds['user_login'] = sanitize_email( $login );
        $checkLogin = self::isEmail($creds['user_login']);
        if( ! $checkLogin ){
          wp_redirect( 'connection-l8?login=inccorect' );
          exit;
        }

        $pass = $_POST["password"];
        $creds['user_password'] = sanitize_text_field( $pass );
        $checkPassword = self::isString( $creds['user_password'] );
        if( ! $checkPassword ){
          wp_redirect( 'connection-l8?pass=inccorect' );
          exit;
        }

        $creds['remember'] = true;

        $user = wp_signon( $creds, false );
        if ( is_wp_error( $user ) )
        echo $user->get_error_message();

        if( $user ){
          wp_redirect( 'home-l8?login=ok' );
          exit;
        }
        else{
          wp_redirect( 'connection-l8?login=nok' );
          exit;
        }

      }

    }

    public function newTemplate()
    {

      global $wp;

      $pages = array(
        'connection',
        'home',
        'delay'
      );

      foreach ( $pages as $page ){
        if ($wp->query_vars["pagename"] == $page.'-l8') {
          $templatefilename = $page.'.php';
          $return_template = WP_PLUGIN_DIR . '/l8/templates/' . $templatefilename;
          self::redirectTemplate( $return_template );
        }
      }

    }

    public function redirectTemplate( $url )
    {

      global $post, $wp_query;

      if ( have_posts() ) {
        include( $url );
        die();
      } else {
        $wp_query->is_404 = true;
      }

    }

    //Admin delays

    public function adminMenu()
    {

      add_menu_page( 'Delays Page', 'Delays', 'administrator', 'delays', array( $this, 'adminPage' ) );

    }

    public function adminPage()
    {

      if( ! current_user_can( 'administrator' )){
        die(  __( 'You are not able to use this page', 'l8' ));
      }
      $delays = self::selectDelays();
      $adresses = self::selectAdress();

      include( WP_PLUGIN_DIR.'/l8/templates/admin.php' );

    }

    public function selectDelays()
    {

      global $wpdb;

      $d = date( 'Y-m-d' );

      $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, type, line
                                    FROM wp_delay
                                    LEFT JOIN wp_users
                                    ON wp_users.ID = wp_delay.user
                                    WHERE TRUE
                                    AND today like '$d%'");

        return $delays;

      }

    public function selectAdress()
    {

      global $wpdb;

      $adresses = $wpdb->get_results("SELECT email
                                      FROM wp_mail_adress");

      return $adresses;

    }


    public function monthFilter()
    {

      global $wpdb;

      $d = date( 'Y-m-d h:m:s' );
      $d7 = date( 'Y-m-d h:m:s', strtotime( '-30 days' ) );

      $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                    FROM wp_delay
                                    LEFT JOIN wp_users
                                    ON wp_users.ID = wp_delay.user
                                    WHERE TRUE
                                    AND today BETWEEN '$d7' AND '$d'");

        echo json_encode( $delays );
        exit;

    }

    public function weekFilter()
    {

      global $wpdb;

      $d = date( 'Y-m-d h:m:s' );
      $d7 = date( 'Y-m-d h:m:s', strtotime( '-7 days' ) );

      $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                    FROM wp_delay
                                    LEFT JOIN wp_users
                                    ON wp_users.ID = wp_delay.user
                                    WHERE TRUE
                                    AND today BETWEEN '$d7' AND '$d'");


      echo json_encode( $delays );
      exit;

    }

    public function dayFilter()
    {

      global $wpdb;

      $d = date( 'Y-m-d' );

      $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                    FROM wp_delay
                                    LEFT JOIN wp_users
                                    ON wp_users.ID = wp_delay.user
                                    WHERE TRUE
                                    AND today like '$d%'");

        echo json_encode( $delays );
        exit;

    }


    public function addAdress()
    {

      global $wpdb;

      if ( ! isset( $_POST['nonce_email'] ) || ! wp_verify_nonce( $_POST['nonce_email'], 'addEmail' ) ) {

        print __( 'Sorry, your nonce did not verify.', 'l8' );
            exit;

      } else {

        $emailS = $_POST["dest"];
        $email = sanitize_text_field( $emailS );
        $checkEmail = self::isEmail( $email );
        if( ! $checkEmail){
          wp_redirect( 'admin.php?page=delays&email=nok' );
          exit;
        }

        $declare = $wpdb->insert("wp_mail_adress", array(
          "email" => $email,
        ));

        if( $declare ){
              wp_redirect( 'admin.php?page=delays&notif=ok' );
          exit;
        }else{
          wp_redirect( 'admin.php?page=delays&notif=nok' );
          exit;
        }

      }

    }

    public function deleteAdress()
    {

      global $wpdb;

      $emailS = $_POST["email"];
      $email = sanitize_text_field( $emailS );

      $declare = $wpdb->delete("wp_mail_adress", array(
        "email" => $email,
      ));

      if( $declare ){
        wp_redirect( 'admin.php?page=delays&del=ok' );
        exit;
      }else{
        wp_redirect( 'admin.php?page=delays&del=nok' );
        exit;
      }

    }

    public function exportExcelDay()
    {

      header("Content-type: application/vnd.ms-excel; charset=utf-8");

      header("Content-disposition: attachment; filename=delays.csv");
      echo utf8_decode('"Name";"Delay";"Time";"Cause";"Detail";"Type";"Line"'."\n");

      global $wpdb;

      $d = date('Y-m-d');

      $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                    FROM wp_delay
                                    LEFT JOIN wp_users
                                    ON wp_users.ID = wp_delay.user
                                    WHERE TRUE
                                    AND today like '$d%'");


        foreach ( $delays as $delay ) {
          $insertion =utf8_decode( $delay->user_nicename.';'.$delay->time.';'.$delay->cause.';'.$delay->today.';'.$delay->detail.';'.$delay->line.';'.$delay->type.'' );


          $insertion = $insertion."\n";
                    echo $insertion;
        }
        exit;

      }


      public function exportExcelWeek()
      {

       header( "Content-type: application/vnd.ms-excel; charset=utf-8" );

       header( "Content-disposition: attachment; filename=delays.csv" );
       echo utf8_decode( '"Name";"Delay";"Time";"Cause";"Detail";"Type";"Line"'."\n" );

       global $wpdb;

       $d = date( 'Y-m-d h:m:s' );
       $d7 = date( 'Y-m-d h:m:s', strtotime( '-7 days' ) );

       $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                     FROM wp_delay
                                     LEFT JOIN wp_users
                                     ON wp_users.ID = wp_delay.user
                                     WHERE TRUE
                                     AND today BETWEEN '$d7' AND '$d'");


         foreach ( $delays as $delay ) {
           $insertion =utf8_decode( $delay->user_nicename.';'.$delay->time.';'.$delay->cause.';'.$delay->today.';'.$delay->detail.';'.$delay->line.';'.$delay->type.'');

           $insertion = $insertion."\n";
           echo $insertion;
         }

         exit;

       }


        function exportExcelMonth()
        {

          header( "Content-type: application/vnd.ms-excel; charset=utf-8" );

          header( "Content-disposition: attachment; filename=delays.csv" );
          echo utf8_decode( '"Name";"Delay";"Time";"Cause";"Detail";"Type";"Line"'."\n" );

          global $wpdb;

          $d = date( 'Y-m-d h:m:s' );
          $d7 = date( 'Y-m-d h:m:s', strtotime( '-30 days' ) );

          $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                        FROM wp_delay
                                        LEFT JOIN wp_users
                                        ON wp_users.ID = wp_delay.user
                                        WHERE TRUE
                                        AND today BETWEEN '$d7' AND '$d'");


            foreach ( $delays as $delay ) {
              $insertion =utf8_decode( $delay->user_nicename.';'.$delay->time.';'.$delay->cause.';'.$delay->today.';'.$delay->detail.';'.$delay->line.';'.$delay->type.'' );

              $insertion = $insertion."\n";
              echo $insertion;
            }

            exit;

          }

        }

  $l8 = new l8();
}
