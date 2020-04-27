<?php 

session_start();
session_destroy();
header('Location: /e-com-master/public');

?>