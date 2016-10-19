<?php
class delayController{

  public function initializeManager(){
    return new delayManager();
  }

  function declareDelay(){
    if( isset ( $_POST["time"] ) && isset ( $_POST["cause"] ) ){

      add_action( 'init', array( $this, 'addDelay') );

    }
    return false;
  }

  function addDelay(){
    global $current_user;
    get_currentuserinfo();

    $user = $current_user->ID;
    $time = $_POST["time"];
    $cause = $_POST["cause"];
    if($_POST["detail"] != ""){
      $detail = $_POST["detail"];
      $type = "";
      $line = "";

    }else{
      $type = $_POST["type"];
      $line = $_POST["line"];
      $detail = "";

    }

    if($cause = 'RATP'){
      $validation= file_get_contents('http://api-ratp.pierre-grimaud.fr/v2/traffic/'.$type.'/'.$line);
      $message = json_decode($validation);
      $valid = $message->response->message;
    }

    $declare = $this->initializeManager()->declareDelay( $user, $time, $cause, $detail, $type, $line);

    if($declare){

      //instanciation (Nom de l'expediteur/adresse de l'expediteur/adresse de rÈponse)
      $test = $current_user->user_email;


      $email = new Mail($current_user->user_email, $current_user->user_nicename, $current_user->user_email);

      //adresses du/des destinataires
      $admin_email = get_option('admin_email', '');

      $email->ajouter_destinataire($admin_email);

      //adresses copie(s) cachÈe(s)
      $email->ajouter_bcc($current_user->user_email);

      //contenu(objet/contenu plain text/contenu text html)
      $siteurl = get_option('siteurl', '');

      $message1 = "Dear Admin, \n";
      $message1 .= $current_user->user_login." is going to be late today. His delay is of ".$time."min for the following cause: ".$cause." \n";
      $message1 .= "You can find all the reports <a href='".$siteurl."wp-admin/admin.php?page=delays'>here</a> \n";
      if($cause = 'RATP'){
        $message1 .= "Validation via RATP : ".$valid."\n";
      }
      $message = "<html>
                  <head>
                    <title>Delay</title>
                  </head>
                  <body>
                  Dear Admin, <br/>";
      $message .= $current_user->user_login." is going to be late today. His delay is of ".$time."min for the following cause: ".$cause." <br/>";
      $message .= "You can find all the reports <a href='".$siteurl."/wp-admin/admin.php?page=delays'>here</a>";
      if($cause = 'RATP'){
        $message1 .= "Validation via RATP : ".$valid."<br/>";
      }
      $message .= "</body></html>";
      
      $email->contenu("Delay of " . $current_user->user_login,$message1, $message);

      $email->envoyer();


      wp_redirect('home-l8');
      exit;

    }else{
      wp_redirect('home-l8');
      exit;

    }
  }

  function filterDelay(){
    if($_POST['id'] == "week"){
      add_action( 'init', array( $this, 'weekFilter') );
    }if($_POST['id'] == "month"){
      add_action( 'init', array( $this, 'monthFilter') );
    }else{
      add_action( 'init', array( $this, 'dayFilter') );
    }
  }

  function weekFilter(){
    $weeks = $this->initializeManager()->weekFilter();
    echo json_encode($weeks);
    exit;
  }

  function dayFilter(){
    $days = $this->initializeManager()->dayFilter();
    echo json_encode($days);
    exit;
  }

  function monthFilter(){
    $month = $this->initializeManager()->monthFilter();
    echo json_encode($month);
    exit;
  }


}
?>
