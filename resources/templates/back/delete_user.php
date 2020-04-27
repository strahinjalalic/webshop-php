<?php
require_once("../../config.php");

if(isset($_GET['id'])) {
    $query = query("DELETE FROM users WHERE user_id = " . $_GET['id']);
    confirm($query);
    redirect("../../../public/admin/index.php?users");
}

?>