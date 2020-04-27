<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php");
?>
    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header>
            <h1>Shop</h1>
        </header>

        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Latest Products</h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">
            <?php get_all_products(); ?>
        </div>
        <!-- /.row -->

        <hr>
<?php require_once(TEMPLATE_FRONT . DS . "footer.php"); ?>     
