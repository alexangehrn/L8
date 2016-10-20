<?php
/*
Template Name: Home
*/

 if( ! is_user_logged_in()){
   wp_redirect('connection-l8');
   exit;
 }

  get_header();


  if($_GET['notif']){
    if($_GET['notif'] == "nok"){
      echo __("Sorry, a problem appeared<br/>", 'l8');
    }else{
      echo __("Your delay has been correctly registered<br/>", 'l8');
    }
  }
  if($_GET['login']){
    echo __("Welcome<br/>", 'l8');
  }
?>

<a href="../delay"> <?php echo __('I\'m late !', 'l8') ?> </a>

<?php
  get_footer();
?>
