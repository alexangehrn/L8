<?php

class delayManager{

  function declareDelay( $user, $time, $cause ){

  global $wpdb;

  $sql = "INSERT INTO wp_delay (user, time, cause)
          VALUES (:user, :time, :cause)";

  $sql->bindValue(':user', $user, \PDO::PARAM_INT);
  $sql->bindValue(':time', $time, \PDO::PARAM_INT);
  $sql->bindValue(':cause', $cause, \PDO::PARAM_STR);

  $select->execute();
  return $select;

  }

}
