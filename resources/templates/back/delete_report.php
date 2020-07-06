<?php
//require_once('../../config.php');

if(isset($_GET['id'])){
    $query = query("DELETE FROM reports WHERE report_id = " . $_GET['id']);
    confirm($query);
    redirect("/e-com-master/public/admin/index.php?reports");
}
?>