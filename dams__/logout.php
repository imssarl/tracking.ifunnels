<?php
session_start();
$_SESSION[SESSION_PREFIX.'sessionadmin'] = "";
session_destroy();
header("location: home.php?nxp=idx");
?>