<?php 
class Traffic
{
	function datacenter()
	{
		global $database;
			echo $sql="select * from `".TABLE_PREFIX."trafic_datacenters`";
			$cat_rs=$database->getRS($sql);
?>
			<!--$str .= "<select name='category' id='category'>";-->
			
			<?php
			if ($cat_rs)
			{
				while($data = $database->getNextRow($cat_rs))
				{
				
				
			?>
				<option value="<?php echo $data['url'];?>" <?php if($data['country']=="Google") echo "selected"; ?>><?php echo $data['country'];?></option>
	<?php
				}
			}
			//$str .= "</select>";
			//return $str;		
			
	}
	
}
?>