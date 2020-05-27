<?php
require_once("../../config.php");

if(isset($_GET['id'])) {
    $query = query("DELETE FROM warderobes WHERE id = " . $_GET['id']);
    confirm($query);
    redirect("../../../public/admin/index.php?categories");
}


?>