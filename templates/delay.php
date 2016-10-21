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
  echo '<div class="alert alert-danger" role="alert">';
  echo __('Your delay value is incorrect');
  echo '</div>';
}
if($_GET['cause']){
  echo '<div class="alert alert-danger" role="alert">';
  echo __('Your cause value is inccorect', 'l8');
  echo '</div>';
}
if($_GET['detail']){
  echo '<div class="alert alert-danger" role="alert">';
  echo __('Your detail value is inccorect', 'l8');
  echo '</div>';
}
if($_GET['type']){
  echo '<div class="alert alert-danger" role="alert">';
  echo __('Your type value is inccorect', 'l8');
  echo '</div>';
}
if($_GET['line']){
  echo '<div class="alert alert-danger" role="alert">';
  echo __('Your line value is inccorect', 'l8');
  echo '</div>';
}
?>

<form action="../l8" id="delay_form" method="post">
  <div class="input-group">
    <span class="input-group-addon" id="basic-addon1"><?php echo __('Delay', 'l8') ?> (min)</span>
    <input type="number" aria-describedby="basic-addon1" name="time">
  </div>
  Cause : <br/>
  <input type="radio" name="cause" value="RATP"> RATP
  <input type="radio" name="cause" value="<?php echo __('other', 'l8') ?>"> <?php echo __('Other', 'l8') ?>
  <br/>
  <div id="other">
    <div class="input-group">
      <span class="input-group-addon" id="basic-addon2"><?php echo __('Detail', 'l8') ?></span>
      <input type="text" aria-describedby="basic-addon2" name="detail">
    </div>
  </div>
  <div id="RATP">
    Type :<br/>
    <input type="radio" name="type" value="rers" checked="checked"> RER
    <input type="radio" name="type" value="metros"> METRO<br/>

    <div class="input-group">
      <span class="input-group-addon" id="basic-addon3"><?php echo __('Line', 'l8') ?></span>
      <input type="text" aria-describedby="basic-addon3" name="line">
    </div>

  </div>
  <?php wp_nonce_field( 'addDelay', 'nonce_delay' ); ?>
  <button type="submit" class="btn btn-primary btn-sm"> <?php echo __('Submit', 'l8') ?></button>
</form>

<?php
get_footer();
?>
