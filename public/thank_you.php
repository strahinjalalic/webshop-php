<?php 
require_once('../resources/config.php');
require_once(TEMPLATE_FRONT . DS . "header.php");
?>


<div class="container">
    <h1 class="text-center">THANK YOU FOR YOUR PURCHASE! </h1>
        <?php 
            report();
            foreach($_SESSION as $product => $value) {
                if($value > 0) {
                    unset($_SESSION['total_amount']);
                    unset($_SESSION['total_items']);
                    if(substr($product, 0, 8) == 'product_') {
                    $length = strlen($product); 
                    $id = substr($product, 8, $length); 
                    unset($_SESSION['product_' . $id]);
                    }
                }
            }
        ?>  
</div>

<?php require_once(TEMPLATE_FRONT . DS . 'footer.php'); ?>