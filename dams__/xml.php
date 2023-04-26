<?php
include_once('config/config.php');

echo "<?xml version='1.0' encoding='utf-8' standalone='yes'?>
<player showDisplay='yes' showPlaylist='no' autoStart='yes'>
 <song path='".BASEPATH."sound_files/sound_".$_GET['songid']."' title='dfd3' />
</player>";
?>
