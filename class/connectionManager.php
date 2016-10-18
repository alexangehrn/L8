<?php

class connectionManager{

  function checkLogs( $creds ){

    $user = wp_signon( $creds, false );
      if ( is_wp_error( $user ) )
        echo $user->get_error_message();
      return $user;
  }

}
