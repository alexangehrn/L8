<h1><?php echo __('Delays', 'l8') ?></h1>
<br/>

<h2><?php echo __('List of delays', 'l8') ?></h2>
<br/>
<form id='filter'>
  <select name='filter'>
    <option value='today'><?php echo __('Today', 'l8') ?></option>
    <option value='week'><?php echo __('This Week', 'l8') ?></option>
    <option value='month'><?php echo __('This Month', 'l8') ?></option>
  </select>
</form>
<br/>

<div id="content_delays">
  <table class="table">
    <tr>
      <td><?php echo __('Name', 'l8') ?></td>
      <td><?php echo __('Delay', 'l8') ?>(min)</td>
      <td><?php echo __('Cause', 'l8')?></td>
      <td><?php echo __('Detail', 'l8') ?></td>
      <td><?php echo __('Type', 'l8') ?></td>
      <td><?php echo __('Line', 'l8') ?></td>
      <td><?php echo __('Date and time', 'l8') ?></td>
    </tr>

    <?php
    foreach($delays as $delay){
      ?>
      <tr>
        <td><?php echo $delay->user_nicename ?></td>
        <td><?php echo $delay->time ?></td>
        <td><?php echo $delay->cause ?></td>
        <td><?php echo $delay->detail ?></td>
        <td><?php echo $delay->type ?></td>
        <td><?php echo $delay->line ?></td>
        <td><?php echo $delay->today ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
</div>

<h2><?php echo __('Traffic', 'l8') ?></h2>
<br/>

<div id="traffic">
  <h5><?php echo __('Metros', 'l8') ?> :</h5>
  <div class="btn-group">
    <button type="button" class="btn btn-default" data="metros" id="1">1</button>
    <button type="button" class="btn btn-default" data="metros" id="2">2</button>
    <button type="button" class="btn btn-default" data="metros" id="3">3</button>
    <button type="button" class="btn btn-default" data="metros" id="4">4</button>
    <button type="button" class="btn btn-default" data="metros" id="5">5</button>
    <button type="button" class="btn btn-default" data="metros" id="6">6</button>
    <button type="button" class="btn btn-default" data="metros" id="7">7</button>
    <button type="button" class="btn btn-default" data="metros" id="8">8</button>
    <button type="button" class="btn btn-default" data="metros" id="9">9</button>
    <button type="button" class="btn btn-default" data="metros" id="10">10</button>
    <button type="button" class="btn btn-default" data="metros" id="11">11</button>
    <button type="button" class="btn btn-default" data="metros" id="12">12</button>
    <button type="button" class="btn btn-default" data="metros" id="13">13</button>
    <button type="button" class="btn btn-default" data="metros" id="14">14</button>
  </div>
  <br/>

  <h5><?php echo __('Rers', 'l8') ?> :</h5>
  <div class="btn-group">
    <button type="button" class="btn btn-default" data="rers" id="A">A</button>
    <button type="button" class="btn btn-default" data="rers" id="B">B</button>
    <button type="button" class="btn btn-default" data="rers" id="C">C</button>
    <button type="button" class="btn btn-default" data="rers" id="D">D</button>
    <button type="button" class="btn btn-default" data="rers" id="E">E</button>
  </div>
</div>
<br/>
<div id="result">
</div>
<br/>


<form action='<?php echo admin_url('admin-post.php') ?>' method='post'>
  <input type='radio' value='day' name='export'><?php echo __('Export today\'s reports in CSV', 'l8') ?><br/>
  <input type='radio' value='week' name='export'><?php echo __('Export this week\'s reports in CSV', 'l8') ?><br/>
  <input type='radio' value='month' name='export'><?php echo __('Export this month\'s reports in CSV', 'l8') ?><br/>
  <input type='hidden' name='action' value='exportCSV'><br/>
  <button type="submit" class="btn btn-primary btn-sm"> <?php echo __('Export', 'l8') ?></button>

</form>



<h2> <?php echo __('Configure email sent', 'l8') ?> </h2>

<?php
if($_GET["notif"]){
  if($_GET["notif"] == "ok"){
    echo __('Your adress has been added', 'l8');
  }else {
    echo __('Your adress has not been added', 'l8');
  }
}
if($_GET["del"]){
  if($_GET["del"] == "ok"){
    echo __('Your adress has been added', 'l8');
  }else {
    echo __('Your adress has not been added', 'l8');
  }
}
if($_GET["email"]){
  echo __("Your mail is invalid", 'l8');
}
?>

<form action='<?php echo admin_url('admin-post.php') ?>' method='post'>
  <div class="input-group">
    <span class="input-group-addon" id="basic-addon1">Email</span>
    <input type="text" aria-describedby="basic-addon1" name="dest">
  </div>
  <input type='hidden' name='action' value='addAdress'><br/>
  <?php wp_nonce_field( 'addEmail', 'nonce_email' ); ?>
  <button type="submit" class="btn btn-primary btn-sm"> <?php echo __('Add', 'l8') ?></button>
</form>
<br/>
Adresses already added :
<ul>

  <?php
  foreach($adresses as $adress){
    ?>
    <li>
      <?php echo $adress->email ?>
      <form action='<?php echo admin_url('admin-post.php') ?>' method='post'>
        <input type='hidden' name='email' value='<?php echo $adress->email ?>'>
        <input type='hidden' name='action' value='deleteAdress'>
        <button type="submit" class="btn btn-primary btn-sm"> <?php echo __('Delete', 'l8') ?></button>
      </form>
    </li><br/>
    <?php } ?>
  </ul>
