<?php 
	require_once("psf_commonincludes.php"); 
	require_once("classes/config.class.php");
	require_once("classes/database.class.php");	
	require_once("config/config.php");	

	$prod_db = new Prod_Database();  
	$prod_db->openDB();	 // For Open Database connection		
	$prod_db->openDB();	 // For Open Database connection			
	$sql="SELECT * FROM ".TABLE_PREFIX."products_tb where id=".$prod_db->GetSQLValueString($_GET["id"],"int");	
	$totrecord=$prod_db->getDataSingleRow($sql,"no");
	$metakeyword = $totrecord['meta_keywords'];
	$metasummary = $totrecord['meta_summary'];		
	$con = new Configuration();
	$config = $con->getrecord();	
	$sitetitle = $config['site_title']." - Product Details - ".$totrecord['product_name'];
	$productdetail=getDataValue();

	$breadcrumb = "<a   class = 'bg' href = 'index.php'>Home</a> >> ".$totrecord['product_name'];

	require("inc.top.php"); 
	
	load_template();
	process_template("#SITETITLE#",$sitetitle);
	process_template("#KEYWORDS#",$metakeyword);
	process_template("#DESCRIPTION#",$metasummary);		
	process_template("#PAGECONTENTS#",$productdetail);
	process_template("#BREADCRUMB#",$breadcrumb);
	show_template();
?>

<?php
	function getDataValue() {
		global $totrecord,$prod_db;
		$sql2 = "select * from ".TABLE_PREFIX."config_tb";
		$config=$prod_db->getDataSingleRow($sql2);
		$strpagedata="<table width='95%' border='0' align='center' cellpadding='0' cellspacing='0'><tr><td width='11'></td><td width='100%' class='product_image_name'>";
		$strpagedata=$strpagedata."Product Details :- ".$totrecord['product_name']."&nbsp;&nbsp;&nbsp;";
			 if($totrecord["prod_rate"]==0) {
				$strpagedata=$strpagedata."<img src='image/star1.gif'>";
			 } else {
												 
		 		 for($i=1;$i<=$totrecord["prod_rate"];$i++) {
					$strpagedata=$strpagedata."<img src='image/star2.gif'>";
				}
				$rem=$i-$totrecord["prod_rate"];
				if($rem==.5) {
					$strpagedata=$strpagedata."<img src='image/star3.gif'>";
				}
			}	
		$strpagedata=$strpagedata." </td><td></td></tr><tr><td>&nbsp;</td> <td><table class='tableborder'>";
		if($totrecord!=false)  			 		
    	{ 
 							$strpagedata=$strpagedata."<tr><td  class = 'product_image' width = '".$config[thumb_width]."'  ><img  border='0'  title = 'Click here to view large image' style = 'cursor:pointer' src='".$totrecord['image_url']."' width = '".$config[detail_thumb_width]."' 	 onClick = \"javascript: window.open('$totrecord[image_url]','mywin','resizable=1')\"></td><td  class = 'product_image_summary' valign = 'top'> ".html_entity_decode($totrecord['product_summary'])."</td></tr><tr><td align='right' class='fromlable' colspan = '2'>";
////////////////////////////////////////////////////////////////////

				$strpagedata=$strpagedata.prodetail();

////////////////////////////////////////////////////////////////////		

				$strpagedata=$strpagedata."</td></tr><tr ><td align='center' class='fromlable' colspan='2'><br><a class='feature_value_url' href='".$totrecord['product_url']."' >More Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class='feature_value_url' href='tellafriend.php'>Tell a Friend</a></td></tr>";
		}	else {
				$strpagedata=$strpagedata."<tr align='right'><td colspan='2' align='center' class = 'product_image_name'>Product Details doesn't exists.</td></tr>";	
		}				
		$strpagedata=$strpagedata."<tr><td colspan='2'>&nbsp;</td></tr>";
        $strpagedata=$strpagedata."</table>";
		$strpagedata=$strpagedata."</td>";
        $strpagedata=$strpagedata."<td >&nbsp;</td></tr>";
        $strpagedata=$strpagedata."<tr><td></td><td background='image/middle-bottom-line.jpg'></td>";
        $strpagedata=$strpagedata."</tr></table>";
		return  $strpagedata;
}
///////////////////*/////////////*///////////////*//////////////////////////////////////////////////////

function prodetail()
{
			global $prod_db;
		        $str="<table class='tableborder'>";
			    $sql="select id,product_name,prod_rate,product_thumbnail from  ".TABLE_PREFIX."products_tb where id = ".$_GET['id'];
				$totalprod=$prod_db->getData($sql); 
			    $sql="select distinct g.id,group_name from  ".TABLE_PREFIX."feature_group_tb g, ".TABLE_PREFIX."feature_list_tb l where g.id = l.group_id";
				$totalgroup=$prod_db->getData($sql); 	
				$sql2 = "select * from ".TABLE_PREFIX."config_tb";
				$config=$prod_db->getDataSingleRow($sql2);	
		 		if($totalprod!=false)  			 		
    			{ 
						$prodid=array();						
							$prodid[0]=$_GET["id"];

							if($totalgroup!=false)  			 		
							{
								$y=0;		
								foreach($totalgroup as $row) 
								{		
									$str=$str."<tr >";								
									$sql="select a.id,a.list_name,a.symb_info1,a.symb_info2 from  ".TABLE_PREFIX."feature_list_tb a,".TABLE_PREFIX."feature_group_tb b where a.group_id=b.id and a.group_id=".$row["id"];
									$totallist=$prod_db->getData($sql);
									$str=$str."<td  class = 'group_title' colspan = '2' >".$row["group_name"]."</td></tr>";
									if($totallist!=false)  			 		
									{	
										foreach($totallist as $row1) 
										{
											//echo $row1["list_name"]."<br>";	
											$str=$str."<tr><td  class = 'feature_title'  width = '50%'>".$row1["list_name"]."</td>";												
											for($i=0;$i<count($prodid);$i++) 
											  {
												$sql="select * from  ".TABLE_PREFIX."product_compare_tb where product_id=".$prodid[$i]." and feature_id=".$row1["id"]."  order by product_id";									$totalprodcomp=$prod_db->getData($sql); 
												if($totalprodcomp!=false)  			 		
												{	
													foreach($totalprodcomp as $row2) 
													{
														if($row2["feature_value"]=="Yes") {
															$featureValue=$row1["symb_info1"];
														} elseif($row2["feature_value"]=="No") {
															$featureValue=$row1["symb_info2"];
														} else {
															$featureValue=$row2["feature_value"];
														}
														
														//$str=$str."<td bgcolor='#F4FAFD' class='mainlink' align='left'>"; 
														//echo "AAAA";
														if($row2["url"]!="")
														{
															$str=$str."<td  class = 'feature_value' >&nbsp;&nbsp;<a href='".$row2["url"]."' target='blank'  class='feature_value_url' title='view details of product feature'>".html_entity_decode($featureValue)."</a></td>"; 
														}	
														else
														{
															$str=$str."<td  class = 'feature_value'>&nbsp;&nbsp;".html_entity_decode($featureValue)."</td>";
														}
														//echo "BBBB";
													}
												} else {
														$str.="<td  class = 'feature_value'>&nbsp;</td>";
												}																	
											}											
										}										
									}

								}
							}							
						}
					
				$str=$str."</table>";
				
				;	
		
		return 	$str;			
		}

?>
