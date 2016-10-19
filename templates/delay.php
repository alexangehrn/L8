<?php
/*
Template Name: Delay
*/

  get_header();

?>

<form action="../l8?controller=delayController&action=declareDelay" id="delay_form" method="post">
  Delay : <br/>
  <input type="number" name="time">
  Cause : <br/>
  <input type="radio" name="cause" value="RATP"> RATP
  <input type="radio" name="cause" value="other"> Other
  <br/>
  <div id="other">
    <input type="text" name="detail">
  </div>
  <div id="RATP">
    Type :<br/>
    <input type="radio" name="type" value="rers" checked="checked"> RER
    <input type="radio" name="type" value="metros"> METRO<br/>

    Ligne :<br/>
    <input type="text" name="line"><br/>
  </div>

  <input type="submit">
</form>

<?php
  get_footer();
?>
