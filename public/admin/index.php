<?php require_once('../../resources/config.php'); ?>
<?php include(TEMPLATE_BACK.DS.'header.php'); ?>

<?php 
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = null;
}

if(!isset($username) || !is_admin($username)) {
    redirect('../../public/');
} ?>


<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
               
            </div>
        </div>
        <?php  if($_SERVER['REQUEST_URI'] == '/e-com-master/public/admin/' || $_SERVER['REQUEST_URI'] == '/e-com-master/public/admin/index.php') {
                include(TEMPLATE_BACK.DS.'admin_content.php');
            }  

            if(isset($_GET['orders'])) {
                include(TEMPLATE_BACK.DS.'orders.php');
            }

            if(isset($_GET['add_product'])) {
                include(TEMPLATE_BACK.DS.'add_product.php');
            }

            if(isset($_GET['edit_user'])) {
                include(TEMPLATE_BACK.DS.'edit_user.php');
            }

            if(isset($_GET['edit_product'])) {
                include(TEMPLATE_BACK.DS.'edit_product.php');
            }

            if(isset($_GET['products'])) {
                include(TEMPLATE_BACK.DS.'products.php');
            }

            if(isset($_GET['categories'])) {
                include(TEMPLATE_BACK.DS.'categories.php');
            }

            if(isset($_GET['brands'])) {
                include(TEMPLATE_BACK.DS."brands.php");
            }

            if(isset($_GET['users'])) {
                include(TEMPLATE_BACK.DS.'users.php');
            }

            if(isset($_GET['add_user'])) {
                include(TEMPLATE_BACK.DS.'add_user.php');
            }

            if(isset($_GET['reports'])) {
                include(TEMPLATE_BACK.DS.'reports.php');
            }

            if(isset($_GET['slides'])) {
                include(TEMPLATE_BACK.DS.'slides.php');
            }
        ?>
    </div>
</div>
<?php include(TEMPLATE_BACK.DS.'footer.php'); ?> 
