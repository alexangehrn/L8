<?php
/*
Template Name: Home
*/

  get_header();
  if($_GET['notif']){
    if($_GET['notif'] == "nok"){
      echo "<div class='fail'>Sorry, a problem appeared</div>";
    }else{
      echo "<div class='success'>Your delay has been correctly registered</div>";
    }
  }
?>

<a href="../delay"> I'm late ! </a>

<?php
  get_footer();
?>
