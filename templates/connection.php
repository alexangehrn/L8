<?php
/*
Template Name: Connection
*/

if($_GET['notif']){
  echo '<div class="alert alert-danger" role="alert">';
  echo __("Sorry, a problem appeared", 'l8');
  echo '</div>';
}
if($_GET['login']){
  echo '<div class="alert alert-danger" role="alert">';
  echo __('Your login value is incorrect', 'l8');
  echo '</div>';
}
if($_GET['pass']){
  echo '<div class="alert alert-danger" role="alert">';
  echo __('Your pass value is incorrect', 'l8');
  echo '</div>';
}
get_header();
?>

<form action="l8" method="post">
  <div class="input-group">
    <span class="input-group-addon" id="basic-addon1"><?php echo __('Login', 'l8') ?></span>
    <input type="text" aria-describedby="basic-addon1" name="login">
  </div>
  <div class="input-group">
    <span class="input-group-addon" id="basic-addon2"><?php echo __('Password', 'l8') ?></span>
    <input type="password" aria-describedby="basic-addon2" name="password">
  </div>
  <button type="submit" class="btn btn-primary btn-sm"> <?php echo __('Connect', 'l8') ?></button>
</form>

<?php
get_footer();
?>
