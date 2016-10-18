<?php
class connectionController{

  public $creds = array();

  public function initializeManager(){
    return new connectionManager();
  }

  function checkLogs(){
    if( isset ( $_POST["login"] ) && isset ( $_POST["password"] ) ){
      $this->creds['user_login'] = $_POST["login"];
      $this->creds['user_password'] = $_POST["password"];
      $this->creds['remember'] = true;

      add_action( 'init', array( $this, 'validateLogs') );
    }
    return false;
  }

  function validateLogs() {
    $logon = $this->initializeManager()->checkLogs( $this->creds );
    if($logon){
      if(! is_admin()){

        header( 'location:home.php' );
      }else{
        header( 'location:delay.php' );
      }
    }
    else{
      header( 'location:connection.php?log=nok' );
    }
  }

}
?>
