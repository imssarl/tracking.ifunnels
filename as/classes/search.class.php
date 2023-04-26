<?php
	class Search
	{
		function showSearchPage()
		{
			$str = "<table width='100%'>
				<form method='get'>
				<tr>
					<td align='center'>
						Search Criteria: <input type='text' name='search' size='30' value = '".trim(stripslashes($_GET["search"]))."'>&nbsp;&nbsp;<input type='submit' value='Go'>
					</td>
				</tr>
				<input type='hidden' name='click'>
				</form>
			</table>
			";
			echo $str;
		}	
		
		function getSearchSql($search, $fields, $whereflag = "no")
		{
		$whereflag="no";
			global $dams_db;
			$sql = "";
			$search_values = explode(",",$search);
			//$this->checkForChar($search_values);
			if(is_array($fields) && count($fields) > 0 )
			{
				if($whereflag == "yes")
				{
					$sql .= " where ( ";
				}
				else
				{
					$sql .= " and ( ";
				}
				$flag = false;	
				foreach($fields as $field)				
				{
					foreach($search_values as $value)
					{
						if($flag === true)
						{
							$sql .= " or ";
						}
						$flag = true;
						if($field[1] == "any")
						{
							$sql .= " ".$field[0]." like '%".trim(addslashes(stripslashes($value)))."%'";
						}
						else
						{
							$char = $this->checkForChar(trim($value));
							$sql .= " ".$field[0]." like '".trim(addslashes(stripslashes($value)))."'";
							if($char != "")
							{
								$sql .= " or ".$field[0]." like '".$char."' ";
							}
						}
					}
				}
				$sql .= " ) ";
				
				
				
			}
			return $sql;
		}
		
		function checkForChar($value)
		{
			$return = "";
			$value = trim(strtolower($value));
			if(substr($value,0,6) == "active")
			{
				$return =  "A";
			}
			else if(substr($value,0,5) == "pause")
			{
				$return = "P";
			}
			else if(substr($value,0,8) == "complete")
			{
				$return = "C";
			}
			else
			{
				$return = $value;
			}
			return $return;
		}
		
		function getOrderSql($order_field_names, $default_order_field_name)
		{
			//global $sql;
			//echo "sql=".$sql;
			$order_type = "desc";
			if(isset($_GET["sort"]) && $_GET["sort"] != "")
			{
				$order_field_name = $_GET["sort"];
				/*
				if(in_array($order_field_name,$order_field_names) === false)
				{
					$order_field_name = $default_order_field_name;
				}
				*/
				if($order_field_name == $_SESSION[SESSION_PREFIX."sess_psf_order_field_name"])
				{

					if($_SESSION[SESSION_PREFIX."sess_order_field_type"] == "asc")
					{
						$order_type = "desc";
					}
					else
					{
						$order_type = "asc";
					}
				}
			}
			else if(isset($_SESSION[SESSION_PREFIX."sess_psf_order_field_name"]) && in_array($_SESSION[SESSION_PREFIX."sess_psf_order_field_name"],$order_field_names) !== false)
			{
				$order_field_name = $_SESSION[SESSION_PREFIX."sess_psf_order_field_name"];
				$order_type = $_SESSION[SESSION_PREFIX."sess_order_field_type"];
			}
			else
			{
				$order_field_name = $default_order_field_name;
			}
			
			
			$_SESSION[SESSION_PREFIX."sess_psf_order_field_name"] = $order_field_name;
			$_SESSION[SESSION_PREFIX."sess_order_field_type"] = $order_type;
			
			//echo $order_field_name;
			//unset($_SESSION[SESSION_PREFIX."sess_psf_order_field_name"]);

			$sql .= " order by ".$order_field_name." ".$order_type;
//die($sql."---".$_SESSION[SESSION_PREFIX."sess_order_field_type"]);
			return $sql;
		}
	}
?>