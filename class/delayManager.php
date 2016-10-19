<?php
date_default_timezone_set('Europe/Paris');
class delayManager{

  function declareDelay( $user, $time, $cause, $detail, $type, $line ){

  global $wpdb;

  $wpdb->insert("wp_delay", array(
     "user" => $user,
     "time" => $time,
     "cause" => $cause,
     "detail" => $detail,
     "type" => $type,
     "line" => $line,
  ));

  return true;

  }

  function monthFilter(){
    global $wpdb;

    $d = date('Y-m-d h:m:s');
    $d7 = date('Y-m-d h:m:s', strtotime('-30 days'));

    $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                  FROM wp_delay
                                  LEFT JOIN wp_users
                                  ON wp_users.ID = wp_delay.user
                                  WHERE TRUE
                                  AND today BETWEEN '$d7' AND '$d'");

    return $delays;
  }


  function weekFilter(){
    global $wpdb;

    $d = date('Y-m-d h:m:s');
    $d7 = date('Y-m-d h:m:s', strtotime('-7 days'));

    $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                  FROM wp_delay
                                  LEFT JOIN wp_users
                                  ON wp_users.ID = wp_delay.user
                                  WHERE TRUE
                                  AND today BETWEEN '$d7' AND '$d'");

    return $delays;
  }

  function dayFilter(){
    global $wpdb;

      $d = date('Y-m-d');

    $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                  FROM wp_delay
                                  LEFT JOIN wp_users
                                  ON wp_users.ID = wp_delay.user
                                  WHERE TRUE
                                    AND today like '$d%'");

    return $delays;
  }

}
