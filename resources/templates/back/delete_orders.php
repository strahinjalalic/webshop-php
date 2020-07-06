<?php
//require_once('../../config.php');


if(isset($_GET['id'])) {
    global $connection;
    $query = query("DELETE FROM orders WHERE order_id = " . $_GET['id']);
    confirm($query);
    fetch_array($query);
    if(mysqli_affected_rows($connection) == 1) {
        redirect('../../../public/admin/index.php?orders');
        set_message('Order deleted!');
    } else {
        set_message('Orders is not deleted...');
    }
}

?>