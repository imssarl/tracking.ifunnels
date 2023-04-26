<?php require_once('Connections/db_connection.php'); ?>
<?php require_once('config.php');?>
<?php

//===================function used=============================
function get_url($url)
						{
								$ch = curl_init();
								curl_setopt ($ch, CURLOPT_URL, $url);
								curl_setopt ($ch, CURLOPT_HEADER, 0);
								ob_start();
								curl_exec ($ch);
								curl_close ($ch);
								$content = ob_get_contents();
								ob_end_clean();
							return $content;    
						}

//===============================================================
$debug=false;
if(isset($_GET['debug'])){
	$debug=true;
}
mysql_select_db($database_db_connection, $db_connection);
$query_rs_site = "SELECT * FROM lp_sites_tb WHERE status <>'Active'";
$rs_site = mysql_query($query_rs_site, $db_connection) or die($query_rs_site.mysql_error());
$row_rs_site = mysql_fetch_assoc($rs_site);
$totalRows_rs_site = mysql_num_rows($rs_site);
if($totalRows_rs_site>0){
	do{
			 //echo "<Li>satya";
			mysql_select_db($database_db_connection, $db_connection);
			$query_rs_user = "SELECT * FROM lp_user_tb WHERE id = ".$row_rs_site['user_id'];
			$rs_user = mysql_query($query_rs_user, $db_connection) or die($query_rs_user .mysql_error());
			$row_rs_user = mysql_fetch_assoc($rs_user);
			$totalRows_rs_user = mysql_num_rows($rs_user);
	
			$homepage = $row_rs_site['home_page_url'];
			$linkpage=$row_rs_site['link_page_url'];
			if($debug){
				echo "<br><li>==================== Processing for ".$homepage." ================================";
			}
       
	//checking for robot.txt file's content
$link_page_index="Yes";
$home_parts = pathinfo($homepage);
if($home_parts["dirname"]=="http:"){
 $replace_url="http://".$home_parts["basename"];
 $robot_url="http://".$home_parts["basename"]."/robot.txt";
}else{
      $replace_url=$home_parts["dirname"];
	  if(($home_parts["extension"]=="")&&($home_parts["basename"]!="")){
        $replace_url=$home_parts["dirname"]."/".$home_parts["basename"];
	  }
	  $robot_url=$replace_url."/robot.txt";
	  }
$rel_link_url=str_replace($replace_url,"",$linkpage);
$alt_link_url=str_replace($replace_url."/","",$linkpage);

	$search_str="Disallow:".linkpage; 
	 if($robot_content = @file($robot_url)){ 
			$robot_page_string =  @join('', $robot_content);
    			$robot_page_string=str_replace(" ","",$robot_page_string);
    			$robot_page_string=str_replace("\"","",$robot_page_string);
				if(strpos(strtolower($robot_page_string),strtolower($search_str))!==false){
			      $link_page_index="No";
				}else{
						$rel_link_arr=split("/",$rel_link_url);
						$rel_link_url="";
						foreach($rel_link_arr as $ilink){
						     if($ilink!=""){
							              $rel_link_url.=$ilink;
									$search_str="Disallow:".$rel_link_url; 
									$robot_page_string=str_replace("/","",$robot_page_string);
									if(strpos(strtolower($robot_page_string),strtolower($search_str))!==false){
									  $link_page_index="No";
									 break;
									}				
							  }
						}
				}
   	}//checkig for robots meta tag
    if(	$link_page_index=="Yes"){
			if($link_content = @file($linkpage)){
				$meta_array=get_meta_tags($linkpage); 
				$robot_tag_noindex="noindex";
				$robot_tag_nofollow="nofollow";
				if(isset($meta_array['robots'])&&($meta_array['robots']!=""))
				{
					if((strpos(strtolower($meta_array['robots']),strtolower($robot_tag_noindex))!==false)||(strpos(strtolower($meta_array['robots']),strtolower($robot_tag_nofollow))!==false)){
						  $link_page_index="No";
					}
				}
			}
     }?>
<?	if($link_page_index=="Yes" ){
			//------------- Checking Home URL EXIST OR NOT -----------------------
			$properwork="F";
			if(intval(ini_get('allow_url_fopen')) && function_exists('file')) {
				if(!($content = @file($homepage))) { 
					$properwork="F";
					$page_string="";
					if($debug){
						echo "<LI> Could not open Homepage URL.";
					}
				}
				else {
					$page_string =  @join('', $content);
					$properwork="T";
					if($debug){
						echo "<LI> Homepage URL success fully open.";
					}
				}
			}elseif(function_exists('curl_init')){
			              $content=get_url($homepage);
						if($content=="") { 
							$properwork="F";
							$page_string="";
							if($debug){
								echo "<LI> Could not open Homepage URL.";
							}
						}
						else {
							$page_string =$content;
							$properwork="T";
							if($debug){
								echo "<LI> Homepage URL success fully open.";
							  }
			               }
			 }
			//------------- Checking Link URL EXIST OR NOT -----------------------
				if(($linkpage!="")&&((strpos(strtolower($page_string),strtolower($linkpage))!==false)||(strpos(strtolower($page_string),strtolower($alt_link_url))!==false))){
					$properwork="T";
					if($debug){
						echo "<LI> Link Page URL Found.";
					}
				}else{ 
					$properwork="F";
					if($debug){
						echo "<LI> Could not found Link Page URL.";
					}
				} 
			//------------- Checking Link EXIST OR NOT on Home Page -----------------------
			$link_page_string="";
	if(intval(ini_get('allow_url_fopen')) && function_exists('file')) {
				if(!($content = @file($linkpage))) { 
					$properwork="F";
					if($debug){
						echo "<LI> Linkpage does not exist.";
					}
				}else{
					$link_page_string =  @join('', $content);
					if($debug){
					$properwork="T";
						echo "<LI> Linkpage exist.";
					}
				}
	}elseif(function_exists('curl_init')){
				              $content=get_url($linkpage);
						if($content=="") { 
							$properwork="F";
							if($debug){
						          echo "<LI> Linkpage does not exist.";
							}
						}
						else {
							$link_page_string =$content;
							$properwork="T";
							if($debug){
						    echo "<LI> Linkpage exist.";
							  }
			               }

	}
			if((strpos(strtolower($link_page_string),strtolower($row_rs_user['user_key']))!==false) and ($properwork=="T")){
				if($debug){
					echo "<LI> Linkpage is correct.";
						$properwork="T";

				}
			}else{ 
				$properwork="F";
				if($debug){
					echo "<LI> Linkpage is not correct.";
				}
			} 
			// -------------- Updating Sites Information ------------------------
			if($properwork=="T"){; 
				$updateSQL="UPDATE lp_sites_tb SET status='Active' where id=".$row_rs_site['id'];
				mysql_select_db($database_db_connection, $db_connection);
				$Result1 = mysql_query($updateSQL, $db_connection) or die($updateSQL.mysql_error());
				if($debug){
					echo "<br><li>Active update.";
				}
			}else{
				$updateSQL="UPDATE lp_sites_tb SET status='Pending' where id=".$row_rs_site['id'];
				mysql_select_db($database_db_connection, $db_connection);
				$Result1 = mysql_query($updateSQL, $db_connection) or die(mysql_error());
				if($debug){
					echo "<br><li>Pending Update.";
				}
			}
	}else{
		echo "<br><li> Your site does not allow indexing for your link page..";
	}//end of if for ($link_page_index=="Yes")


	}while($row_rs_site = mysql_fetch_assoc($rs_site));
}
?>