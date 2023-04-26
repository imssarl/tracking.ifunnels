<?php

$uploadsound = true;
$uploadflipped = true;
$uploadbackground = true;
		
if(isset($_POST["position"]) && $_POST["position"]=="C" && $_POST["play_sound"]=="Y" && $_POST["sound_option"]=="N") 
{
	$ext = strtolower(strrev(substr(strrev($_FILES['original_name']['name']),0,strpos(strrev($_FILES['original_name']['name']),"."))));
	
	if ($_FILES["original_name"]["type"]!="audio/mpeg" && $ext != "mp3")
	{
		$uploadsound = false;
	}
	elseif ($_FILES["original_name"]["size"]=="0")
	{
		$uploadsound = false;
	}	
	
	if(!$uploadsound)
	{
		$sound_error="Please upload valid mp3 file";
	}
}
if(isset($_POST["on_action"]) && $_POST["on_action"]=="F" && isset($_POST["flipped_default"]) && $_POST["flipped_default"]=="N")
{
	$ext = strtolower(strrev(substr(strrev($_FILES['small_corner_img']['name']),0,strpos(strrev($_FILES['small_corner_img']['name']),"."))));
	
	if($_FILES["small_corner_img"]["type"]!="image/jpeg")
	{
		$uploadflipped = false;
	}
	
	$image_ext_arr=array("jpg","jpeg","png","gif");
	
	$count=0;
	foreach($image_ext_arr as $arr)
	{
		if($ext!=$arr)
		{
			$count++;
		}
	}
	if($count==4)
	{
		$uploadflipped = false;		
	}
				
	if(!$uploadflipped)
	{
		$flipped_error="Please upload valid image";
	}
}
if(isset($_POST["position"]) && $_POST["position"]=="S")
{
	if($process!="edit" && $_FILES['background']['size']!="0")
	{
		$ext = strtolower(strrev(substr(strrev($_FILES['background']['name']),0,strpos(strrev($_FILES['background']['name']),"."))));
		
		if($_FILES["background"]["type"]!="image/jpeg")
		{
			$uploadbackground = false;
		}
		
		$image_ext_arr=array("jpg","jpeg","png","gif");
		
		$count=0;
		foreach($image_ext_arr as $arr)
		{
			if($ext!=$arr)
			{
				$count++;
			}
		}
		
		if($count=="4")
		{
			$uploadbackground = false;
		}
		
		if(!$uploadbackground)
		{
			$background_error="Please upload valid background image";
		}
	}
}
?>