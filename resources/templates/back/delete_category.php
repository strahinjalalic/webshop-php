<?php
//require_once("../../config.php");

if(isset($_GET['id'])) {
    $query = query("DELETE FROM warderobes WHERE id = " . $_GET['id']);
    confirm($query);
    redirect("/e-com-master/public/admin/index.php?categories");
    set_message('Category deleted!');
} else {
    set_message('Category is not deleted...');
}


?>