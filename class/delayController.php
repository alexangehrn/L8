<?php
class delayController{

  public $user;
  public $time;
  public $cause;

  public function initializeManager(){
    return new delayManager();
  }

  function declareDelay(){
    
    if( isset ( $_POST["time"] ) && isset ( $_POST["cause"] ) ){
      $this->$user = wp_get_current_user();
      $this->$time = $_POST["time"];
      $this->$cause = $_POST["cause"];
      add_action( 'init', array( $this, 'addDelay') );

    }
    return false;
  }

  function addDelay(){
    $logon = $this->initializeManager()->declareDelay( $user, $time, $cause );
    var_dump($logon);
  }



}
?>
