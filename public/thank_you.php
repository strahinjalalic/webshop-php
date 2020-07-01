<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php");
?>

<?php 
    report();
    if(isset($_GET['session_id'])) {
        echo '<div class="container">
                <h1 class="text-center">THANK YOU</h1>
              </div>';
    } else {
        echo 'Payment Failed! Try Again.';
    }
?>

<?php require_once(TEMPLATE_FRONT . DS . 'footer.php'); ?>