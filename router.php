<?php
    if(isset($_GET['controller'])){
    $controller = $_GET['controller'];
    $action = $_GET['action'];

    if (!empty($controller) || empty($action)){
      $controller = new $controller();
      $function = $controller->$action();
    }
    }
    else{
      include 'connection';
    }
?>
