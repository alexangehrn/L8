<?php
/*
Template Name: Connection
*/
if($_GET['notif']){
  echo __("Sorry, a problem appeared", 'l8');
}
if($_GET['login']){
  echo __('Your login value is inccorect', 'l8');
}
if($_GET['pass']){
  echo __('Your pass value is inccorect', 'l8');
}
  get_header();
?>

<form action="l8" method="post">
  <?php echo __('Connection', 'l8') ?><input type="text" name="login">
  <?php echo __('Password', 'l8') ?> <input type="password" name="password">
  <input type="submit">
</form>

<?php
  get_footer();
?>
