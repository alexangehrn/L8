<?php
class exportManager{

 function exportExcelDay(){

header("Content-type: application/vnd.ms-excel; charset=utf-8");

header("Content-disposition: attachment; filename=delays.csv");
echo utf8_decode('"Name";"Delay";"Time";"Cause";"Detail";"Type";"Line"'."\n");

  global $wpdb;

    $d = date('Y-m-d');

  $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                FROM wp_delay
                                LEFT JOIN wp_users
                                ON wp_users.ID = wp_delay.user
                                WHERE TRUE
                                  AND today like '$d%'");


    foreach ($delays as $delay) {
      $insertion =utf8_decode($delay->user_nicename.';'.$delay->time.';'.$delay->cause.';'.$delay->today.';'.$delay->detail.';'.$delay->line.';'.$delay->type.'');


      $insertion = $insertion."\n";
      echo $insertion;
    }
    exit;
  }


  function exportExcelWeek(){

 header("Content-type: application/vnd.ms-excel; charset=utf-8");

 header("Content-disposition: attachment; filename=delays.csv");
 echo utf8_decode('"Name";"Delay";"Time";"Cause";"Detail";"Type";"Line"'."\n");

 global $wpdb;

 $d = date('Y-m-d h:m:s');
 $d7 = date('Y-m-d h:m:s', strtotime('-7 days'));

 $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                               FROM wp_delay
                               LEFT JOIN wp_users
                               ON wp_users.ID = wp_delay.user
                               WHERE TRUE
                               AND today BETWEEN '$d7' AND '$d'");


     foreach ($delays as $delay) {
       $insertion =utf8_decode($delay->user_nicename.';'.$delay->time.';'.$delay->cause.';'.$delay->today.';'.$delay->detail.';'.$delay->line.';'.$delay->type.'');


       $insertion = $insertion."\n";
       echo $insertion;
     }
     exit;
   }


   function exportExcelMonth(){

  header("Content-type: application/vnd.ms-excel; charset=utf-8");

  header("Content-disposition: attachment; filename=delays.csv");
  echo utf8_decode('"Name";"Delay";"Time";"Cause";"Detail";"Type";"Line"'."\n");

  global $wpdb;

  $d = date('Y-m-d h:m:s');
  $d7 = date('Y-m-d h:m:s', strtotime('-30 days'));

  $delays = $wpdb->get_results("SELECT user_nicename, time, cause, today, detail, line, type
                                FROM wp_delay
                                LEFT JOIN wp_users
                                ON wp_users.ID = wp_delay.user
                                WHERE TRUE
                                AND today BETWEEN '$d7' AND '$d'");


      foreach ($delays as $delay) {
        $insertion =utf8_decode($delay->user_nicename.';'.$delay->time.';'.$delay->cause.';'.$delay->today.';'.$delay->detail.';'.$delay->line.';'.$delay->type.'');


        $insertion = $insertion."\n";
        echo $insertion;
      }
      exit;
    }
}
