<?php

class delayManager{

  function declareDelay( $user, $time, $cause ){
  global $wpdb;
  $wpdb->insert("wp_delay", array(
     "user" => $user,
     "time" => $time,
     "cause" => $cause,
  ));

  return true;

  }

}
