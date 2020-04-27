<?php
require_once('../../config.php');

if(isset($_GET['id'])) {
    global $connection;
    $query = query("DELETE FROM products WHERE product_id = " . $_GET['id']);
    confirm($query);
    fetch_array($query);
    if(mysqli_affected_rows($connection) == 1) {
        redirect('../../../public/admin/index.php?products');
        set_message('Product deleted!');
    } else {
        set_message('Product is not deleted...');
    }
}
?>