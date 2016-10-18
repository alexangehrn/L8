<?php
date_default_timezone_set('Europe/Paris');
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
  function weekFilter(){
    global $wpdb;

    $d = date('Y-m-d h:m:s');
    $d7 = date('Y-m-d h:m:s', strtotime('-7 days'));
    var_dump($d);
    var_dump($d7);
    $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today
                                  FROM wp_delay
                                  LEFT JOIN wp_users
                                  ON wp_users.ID = wp_delay.user
                                  WHERE TRUE
                                  AND today BETWEEN '$d7' AND '$d'");

    return json_encode($delays);
  }

}
