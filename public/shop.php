<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php"); ?>

<div class="container">
    <!-- <header>
        <h1>Shop</h1>
    </header> -->
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <h3>Latest Products</h3>
        </div>
    </div>
    <div class="row text-center">
        <?php get_all_products(); ?>
    </div>
    <hr>
<?php require_once(TEMPLATE_FRONT . DS . "footer.php"); ?>     
