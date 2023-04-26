<?php
session_start();
include_once("config/config.php");
$_SESSION[SESSION_PREFIX.'sessionusername'] = "";
$_SESSION[SESSION_PREFIX.'sessionuserid'] = "";
$_SESSION[SESSION_PREFIX.'sessionuseremail'] = "";

unset($_SESSION[SESSION_PREFIX.'sessionusername']);
unset($_SESSION[SESSION_PREFIX.'sessionuserid']);
unset($_SESSION[SESSION_PREFIX.'sessionuseremail']);
//session_destroy();
header("location: login.php");
?>