<?php
	class Database
	{
		var $link_idf;
		function closeDB()
		//This function is to close the database connection
		{
			mysql_close($this->link_idf);
		}
		
		function getData($sql, $sec = "yes")
		//This function returns all the records in a MD array
		{
			global $psf_db;
			$rs = mysql_query($sql,$this->link_idf) or die("Error Occured in executing Query: ".mysql_error()."<br>SQL: ".$sql);
			if($rs)
			{
				$total_rows = mysql_num_rows($rs);
				if($total_rows > 0)
				{
					$return_data = array();
					$count = 0;
					while(($data = mysql_fetch_array($rs)) !== false)	
					{
						if($sec == "yes")
						{
							$i = 0;
							foreach($data as $key => $val)
							{
								$data[$key] = htmlentities($val);
								$data[$i] = htmlentities($val);
								$i++;
							}
						}
						$return_data[$count] = $data;
						$count++;	 
					}
					return $return_data;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
		function getDataSingleRecord($sql, $sec = "yes")
		{
			$rs = mysql_query($sql,$this->link_idf) or die("Error Occured in executing Query: ".mysql_error()."<br>SQL: ".$sql);
			if($rs !== false && mysql_num_rows($rs) > 0)
			{
				$result = mysql_fetch_array($rs);
				if($result !== false)
				{
					if($sec == "no")
					{
						$data = $result[0];
					}
					else
					{
						$data = htmlentities($result[0]);
					}
					return $data;				
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
		function getDataSingleRow($sql, $sec = "yes")
		{	
			$rs = mysql_query($sql,$this->link_idf) or die("Error Occured in executing Query: ".mysql_error()."<br>SQL: ".$sql);
			if($rs !== false && mysql_num_rows($rs) > 0)
			{
				$result = mysql_fetch_array($rs);
				if($result !== false)
				{
					$data = array();
					if($sec == "yes")
					{
						$count = 0;
						foreach($result as $key=>$val)
						{
							$data[$count] = htmlentities($val);
							$data[$key] = htmlentities($val);
							$count++;
						}
					}
					else
					{
						$data = $result;
					}
					return $data;				
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
		function getNextRow($rs, $sec = "yes")
		{
			$result = mysql_fetch_array($rs);
			if($result !== false)
			{
				$data = array();
				if($sec == "yes")
				{
					$count = 0;
					foreach($result as $key=>$val)
					{
						$data[$count] = htmlentities($val);
						$data[$key] = htmlentities($val);
						$count++;
					}
				}
				else
				{
					$data = $result;
				}
				return $data;				
			}
			else
			{
				return false;
			}
		}
		
		function getRS($sql)
		{
			$rs = mysql_query($sql,$this->link_idf) or die("Error Occured in executing Query: ".mysql_error()."<br>SQL: ".$sql);
			if($rs !== false )
			{
				$rows = mysql_num_rows($rs);

				if($rows <=0)
				{
					return false;
				}
				else
				{
					return $rs;
				}
			}
			else
			{
				return false;
			}
		}
		
		function GetSQLValueString($theValue, $theType,  $theNull="yes", $theDefinedValue = "", $theNotDefinedValue = "") 
		{
		  $theNull = strtolower($theNull);
		  $theValue = addslashes(stripslashes($theValue));
		  switch ($theType) {
			case "text":
			  $theValue = ($theValue != "") ? $theValue : (($theNull == "yes") ? "NULL" : "");
			  break;    
			case "long":
			case "int":
			  $theValue = ($theValue != "") ? intval($theValue) : (($theNull == "yes") ? "NULL" : 0);
			  break;
			case "double":
			  $theValue = ($theValue != "") ? doubleval($theValue)  : (($theNull == "yes") ? "NULL" : 0);
			  break;
			case "date":
			  $theValue = ($theValue != "") ?   $theValue  : (($theNull == "yes") ? "NULL" : "");
			  break;
			case "defined":
			  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			  break;
		  }
		  return $theValue;
		}			
		
		function insert($sql)
		{
			$flag = mysql_query($sql,$this->link_idf) or die("Error Occured in executing Query: ".mysql_error()."<br>SQL: ".$sql);
			if($flag === false)
			{
				return false;
			}
			else
			{
				$id = mysql_insert_id();
				return $id;		
			}
		}
		
		function modify($sql)
		{
			$flag = mysql_query($sql,$this->link_idf) or die("Error Occured in executing Query: ".mysql_error()."<br>SQL: ".$sql);
			if($flag === false)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		function moveFirst(&$rs)
		//This function is to move the resource into first position
		{
			mysql_data_seek($rs,0);
		}
		
		function openDB()
		//This function is to open the database connection
		{
			$this->link_idf = mysql_pconnect(DB_SERVER_NAME,DB_USERNAME,DB_PASSWORD) or die("Error Occured in Connection: ".mysql_error());
			mysql_select_db(DB_NAME,$this->link_idf) or die("Error Occured in Selecting Database: ".mysql_error());;
		}
		function getRowsOfRS($rs)
		{
			if ($rs)
			{
				return mysql_num_rows($rs);
			}
			else
			{
				return 0;
			}
		}
				
	}	
?>