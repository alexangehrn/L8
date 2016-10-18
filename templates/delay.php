<?php
/*
Template Name: Delay
*/

  get_header();

?>

<form action="l8.php?controller=delayController&action=declareDelay" method="post">
  Delay <input type="number" name="time">
  Cause <input type="text" name="cause">
  (& line if the cause is the mean of transport)<br/>
  <input type="hidden" value = <?php  ?>>
  <input type="submit">
</form>

<?php
  get_footer();
?>
