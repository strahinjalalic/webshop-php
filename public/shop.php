<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php"); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h3><?php if(isset($_GET['brand']) && isset($_GET['category'])) echo $_GET['brand'] . ", " . $_GET['category'] ?></h3>
        </div>
    </div>
    <div class="row text-center">
        <?php get_all_products(); ?>
    </div>
<?php require_once(TEMPLATE_FRONT . DS . "footer.php"); ?>     
