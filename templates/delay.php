<?php
/*
Template Name: Delay
*/

if( ! is_user_logged_in()){
   wp_redirect('connection-l8');
   exit;
 }

  get_header();

  if($_GET['time']){
    echo __('Your delay value is inccorect');
  }
  if($_GET['cause']){
    echo __('Your cause value is inccorect', 'l8');
  }
  if($_GET['detail']){
    echo __('Your detail value is inccorect', 'l8');
  }
  if($_GET['type']){
    echo __('Your type value is inccorect', 'l8');
  }
  if($_GET['line']){
    echo __('Your line value is inccorect', 'l8');
  }
?>

<form action="../l8" id="delay_form" method="post">
  <?php echo __('Delay') ?> : <br/>
  <input type="number" name="time">
  Cause : <br/>
  <input type="radio" name="cause" value="RATP"> RATP
  <input type="radio" name="cause" value="<?php echo __('other', 'l8') ?>"> <?php echo __('Other', 'l8') ?>
  <br/>
  <div id="other">
    <input type="text" name="detail">
  </div>
  <div id="RATP">
    Type :<br/>
    <input type="radio" name="type" value="rers" checked="checked"> RER
    <input type="radio" name="type" value="metros"> METRO<br/>

     <?php echo __('Line', 'l8') ?> :<br/>
    <input type="text" name="line"><br/>
  </div>

  <input type="submit">
</form>

<?php
  get_footer();
?>
