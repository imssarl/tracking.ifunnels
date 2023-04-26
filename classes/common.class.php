<?php

class Common

{

		function getPostData()

		{

			$data = array();

			foreach($_POST as $key => $val)

			{

				$data[$key] = stripslashes($val);

			}

			$_SESSION["last_post_data"] = $data;

			return $data;

		}

		function getLastPostData()

		{

			$data = array();



			foreach($_SESSION["last_post_data"] as $key => $val)

			{

				$data[$key] = stripslashes($val);

			}

			return $data;

		}

		function getMyMessage()

		{

			if(isset($_SESSION[SESSION_PREFIX."mymessage"]) && $_SESSION[SESSION_PREFIX."mymessage"] != "")

			{

				$smsg = $_SESSION[SESSION_PREFIX."mymessage"];

				$_SESSION[SESSION_PREFIX."mymessage"] = "";

				return $smsg;

			}

			else

			{

				return "";

			}

		}		

		function getFTPhomePage($str, $cut)

		{

			$str = trim($str);

			if (substr($str,1,strlen($cut))==$cut)

			{

				return  substr($str, strpos($str,$cut) + strlen($cut)+1 , strlen($str) - strpos ($str,$cut)-strlen($cut));

			}

			else

			{

				if ($str{0} == "/")

				return substr($str,1,strlen($str)-1);

				else

				return $str;

			}

		}

		function getFTPhomePageAdv($str, $cut)

		{

			if(strpos($str,$cut) !== false)

				return  substr($str, strpos($str,$cut) + strlen($cut)+1);

			else 

				return $str;

		}

		function gethelp()

		{

			$name=$_SERVER['PHP_SELF'];

			$pos=strrpos($name,"/");

			$rev=strrev($name);

			$last=strrpos($rev,".");

			$name=substr($name,$pos+1,-$last);

			return $name;

		}



		function decodePhpTags($code, $mytphp, $tphp)

		{

			return str_replace($mytphp,$tphp,$code);

		}

		function getExt($file)

		{

			return strtolower(strrev(substr(strrev($file),0,strpos(strrev($file),"."))));

		}

		function fetchDataFromUrl($url)

		{

			if(function_exists("curl_init"))

			{

				$ch = @curl_init();

				@curl_setopt($ch, CURLOPT_URL, $url);

				@curl_setopt($ch, CURLOPT_HEADER, 0);

				@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

				$resp = @curl_exec($ch); 

				

				$curl_resp = curl_errno($ch);

		

				if ($curl_resp == 0)

				{

					$val = $resp;

				}

				else if($curl_resp != 0 && $resp == "") 

				{

					$val = "";

				} 

		

				@curl_close($ch);

				unset($ch);		

			}

			else if(function_exists("fopen"))

			{

					$fp = @fopen($url,"r");

					if($fp)

					{		

						while(!@feof($fp))

						{

							$val .= @fgets($fp);

						}

						@fclose($fp);

					}

					else 

					{

						$val = "";

					}

			}

			return $val;

		}



}



?>