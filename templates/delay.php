<?php
/*
Template Name: Delay
*/

  get_header();
  $current_user_id = get_current_user_id();

?>

<form action="../l8?controller=delayController&action=declareDelay" method="post">
  Delay <input type="number" name="time">
  Cause <input type="text" name="cause">
  (& line if the cause is the mean of transport)<br/>
  <input type="hidden" name="user" value = <?php echo $current_user_id ?>>
  <input type="submit">
</form>

<?php
  get_footer();
?>
