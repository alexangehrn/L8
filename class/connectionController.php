<?php
class connectionController{

  public function initializeManager(){
    return new connectionManager();
  }

  function checkLogs(){
    if(isset($_POST["login"]) && isset($_POST["password"])){
      $creds= array();
      $creds['user_login'] = $_POST["login"];
      $creds['user_password'] = $_POST["password"];
      $creds['remember'] = true;
      $logon = $this->initializeManager()->checkLogs($creds);
    }
    return false;
  }

}
?>
