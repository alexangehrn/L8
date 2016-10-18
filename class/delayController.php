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
    $user = $_POST["user"];
    $time = $_POST["time"];
    $cause = $_POST["cause"];
    $declare = $this->initializeManager()->declareDelay( $user, $time, $cause );
    if($declare){
      wp_redirect('home-l8');
      exit;

    }else{
      wp_redirect('home-l8');
      exit;

    }
  }



}
?>
