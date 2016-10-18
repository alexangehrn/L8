<?php
/*
Template Name: Connection
*/

  get_header();
?>

<form action="l8.php?controller=connectionController&action=checkLogs" method="post">
  Connection <input type="text" name="login">
  Password <input type="password" name="password">
  <input type="submit">
</form>

<?php
  get_footer();
?>
