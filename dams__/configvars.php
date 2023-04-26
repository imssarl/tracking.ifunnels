<?php
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/settings.class.php");
require_once("configsettings.php");
$ap_db = new Database();
$ap_db->openDB();

$cfg = new Settings();
$cfg->createConstants();
$ap_db->closeDB();

define("UPGRADE_VER","UPGRADE0001");

?>