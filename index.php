<?php

include 'autoloader.php';
spl_autoload_register('Autoloader::loader');

if(isset($_GET['controller'])){
$controller = $_GET['controller'];
$action = $_GET['action'];

if (!empty($controller) || empty($action)){
  $controller = new $controller();
  $function = $controller->$action();
}
}
else{
  echo 'hello';

}
