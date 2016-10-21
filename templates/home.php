<?php
/*
Template Name: Home
*/

if(!is_user_logged_in()){
  wp_redirect('connection-l8');
  exit;
}

get_header();

if($_GET['notif']){
  if($_GET['notif'] == "nok"){
    echo '<div class="alert alert-danger" role="alert">';
    echo __("Sorry, a problem appeared<br/>", 'l8');
    echo "</div>";
  }else{
    echo '<div class="alert alert-success" role="alert">';
    echo __("Your delay has been correctly registered<br/>", 'l8');
    echo "</div>";
  }
}
if($_GET['login']){
  echo '<div class="alert alert-success" role="alert">';
  echo __("Welcome<br/>", 'l8');
  echo "</div>";
}
?>

<a href="../delay"> <?php echo __('I\'m late !', 'l8') ?> </a>

<?php
get_footer();
?>
