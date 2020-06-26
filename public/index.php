<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php");  ?>
    <div class="container">
        <div class="row">
           <?php require_once(TEMPLATE_FRONT . DS . 'side_nav.php'); ?>
            <div class="col-md-9">
                    <?php get_products(); ?>
            </div>
        </div>
    </div>
<?php require_once(TEMPLATE_FRONT . DS . "footer.php"); ?>
