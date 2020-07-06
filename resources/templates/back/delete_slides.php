<?php
//require_once("../../config.php");

if(isset($_GET['id'])) {
    $unlink_query = query("SELECT slide_image FROM slides WHERE slide_id = " . $_GET['id']);
    confirm($unlink_query);

    $row = fetch_array($unlink_query);
    $path = UPLOAD_DIRECTORY.DS.$row['slide_image'];
    unlink($path);

    $query = query("DELETE FROM slides WHERE slide_id = " . $_GET['id']);
    confirm($query);
    set_message("Slide deleted");
    redirect("/e-com-master/public/admin/index.php?slides");
}
?>