<?php
class Steps {
	var $conn_id = "";
	var $ftp_server = "";
	var $ftp_user = "";
	var $ftp_pass = "";
	var $ftp_home = "";
	var $siteurl = "";

	function ftp_details( $ftpaddress, $ftpusername, $ftppassword, $ftphomepage, $siteurl = "" ) {
		$this->conn_id = "";
		$this->ftp_server = $ftpaddress;
		$this->ftp_user = $ftpusername;
		$this->ftp_pass = $ftppassword;
		$this->ftp_home = $ftphomepage;
		$this->siteurl = $siteurl;
	}

	function check_ftp_details() {
		$this->conn_id = @ftp_connect( $this->ftp_server );
		if ( $this->conn_id ) {
			if ( @ftp_login( $this->conn_id, $this->ftp_user, $this->ftp_pass ) ) {
				@ftp_pasv( $this->conn_id, true );
				$cpscript = $this->unloadControlPanelScripts();
				if ( $cpscript ) {
					$URLisOK = $this->checkSiteURL( $this->siteurl );
					if ( $URLisOK ) {
						return "ok";
					}else {
						return "Please Check Site URL";
					}
				}else {
					return "Please check FTP homepage path : " . $this->ftp_home;
				}
			}else {
				return "Please check FTP login details";
			}
		}else {
			return "Please check FTP address : " . $this->ftp_server;
		}
	}

	function is_exist( $filename ) {
		$resp = $this->checkWritable( $filename );
		return ( int )$resp;

		/*$str = $_SESSION[SESSION_PREFIX.'ftp_homepage'];
$cut = $_SESSION[SESSION_PREFIX.'ftp_username'];
$homepage = substr($str, strpos($str,$cut) + strlen($cut)+1, strlen($str) - strpos ($str,$cut)-strlen($cut));
$myfilename = $homepage.$filename;

if ($filename=="feed.xml")
{
	$res = ftp_size($this->conn_id, $myfilename);
	if ($res==-1) $res= false; else $res = true;
}
else
{
		if(ftp_chdir($this->conn_id, $myfilename ))
		{ // We have a directory
			$res = true;
			ftp_cdup($this->conn_id);
		}
		else
		{
			$res = false;
		}
}	

if ($res == true) 
{		$resp = $this->checkWritable($filename);
		if(strcmp($resp,"true")==0) {
			return 1;
		} else {
			return 2;
		}
}
else
{
	return 3;
}		
*/
	}

	function change_permission( $filename, $mode ) {
		// lostarchives - 8:02 PM 1/24/2009
		// - fixed bug, ftp_login was using resource '$conn' instead of '$conn1'
		// - Changed ftp_site to ftp_chmod (more standardized across OS)
		// - Added additional return path for no connection & no login
		// - Added code to make sure $mode is an octal value
		// - Completed tested with CNB site, code is functional and bug free
		// Opens an FTP connection
		$conn1 = @ftp_connect( $_SESSION[SESSION_PREFIX . 'ftp_address'] ); 
		// Logs into the FTP connection
		$login_result1 = @ftp_login( $conn1, $_SESSION[SESSION_PREFIX . 'ftp_username'], $_SESSION[SESSION_PREFIX . "ftp_password"] );
		@ftp_pasv( $conn1, true );
		// Checks if connected and logged in
		if ( $conn1 && $login_result1 ) {
			// make sure mode is in octal
			if ( $mode == 666 ) $mode = 0666;
			if ( $mode == 777 ) $mode = 0777;
			if ( $mode == 757 ) $mode = 0757; 
			// Change file permissions
			if ( ftp_chmod( $conn1, $mode, $_SESSION[SESSION_PREFIX . 'ftp_homepage'] . $filename ) ) {
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}

		/*Commented By Mayank

//ftp_site($conn,"CHMOD $mode ".$_SESSION[SESSION_PREFIX.'ftp_homepage'].""))

print_r($_SESSION);
die();

$resp = $this->makeWritable($_SESSION[SESSION_PREFIX."url"], $filename, $mode);

if ($resp == "1")
return true;
else if ($resp == "2")
return false;
else
echo "Unknown response : ".$resp;

Commented By Mayank*/

		/*	$str = $_SESSION[SESSION_PREFIX.'ftp_homepage'];
	$cut = $_SESSION[SESSION_PREFIX.'ftp_username'];

	$homepage = substr($str, strpos($str,$cut) + strlen($cut)+1, strlen($str) - strpos ($str,$cut)-strlen($cut));
	$myfilename = $homepage.$filename;
if ($mode == 0666)
$chmo = ftp_chmod($this->conn_id, 0666, $myfilename);
if ($mode == 0777)
$chmo = ftp_chmod($this->conn_id, 0777, $myfilename);
if ($mode == 0757)
$chmo = ftp_chmod($this->conn_id, 0757, $myfilename);

	if ($chmo) {
		return true;
	} else {
		return false;
	}
*/
	}

	function close_conn() {
		if ( $this->conn_id != "" ) {
			@ftp_close( $this->conn_id );
		}
	}

	function FtpReConnect() {
		$this->conn_id = @ftp_connect( $_SESSION[SESSION_PREFIX . 'ftp_address'] );
		if ( $this->conn_id ) {
			if ( @ftp_login( $this->conn_id, $_SESSION[SESSION_PREFIX . 'ftp_username'], $_SESSION[SESSION_PREFIX . 'ftp_password'] ) ) {
				@ftp_pasv( $this->conn_id, true );
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}

	function checkWritable( $filename ) {
		if ( function_exists( "fopen" ) ) {
			$fp = @fopen( $_SESSION[SESSION_PREFIX . "url"] . "123_controlpanel_remote_methods.php?filename=" . $filename, "r" );

			if ( !$fp ) {
				echo "<br>&nbsp;Unable to check writable, 
			error in opening " . $_SESSION[SESSION_PREFIX . "url"] . "123_controlpanel_remote_methods.php";
				return false;
			}else {
				$resp = "";
				while( !feof( $fp ) ) {
					$resp .= fgets( $fp );
				}
				fclose( $fp );
			}
		}else if ( function_exists( "curl_init" ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $_SESSION[SESSION_PREFIX . "url"] . "123_controlpanel_remote_methods.php?filename=" . $filename );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			$resp = curl_exec( $ch );
			curl_close ( $ch );
		}
		return $resp;
	}

	function makeWritable( $url, $filename, $mode ) {
		if ( function_exists( "fopen" ) ) {
			$fp = @fopen( $url . "123_controlpanel_remote_methods.php?makewritable=" . $filename . "&mode=" . $mode, "r" );
			if ( !$fp ) {
				echo "<br>&nbsp;Unable to change permission, 
			<br>error in opening " . $_SESSION[SESSION_PREFIX . "url"] . "123_controlpanel_remote_methods.php";
				return false;
			}else {
				$resp = "";
				while( !feof( $fp ) ) {
					$resp .= fgets( $fp );
				}
				fclose( $fp );
			}
		}else if ( function_exists( "curl_init" ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url . "123_controlpanel_remote_methods.php?makewritable=" . $filename . "&mode=" . $mode );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			$resp = curl_exec( $ch );
			curl_close ( $ch );
		}
		return $resp;
	}

	function createFeedXML( $title, $description, $url, $feedurl, $elseupload = "" ) {
		global $feed, $common;

		$title = $feed->html2txt( $title );
		$title = $feed->strip_htmlentities( $title );
		$title = stripslashes( $title );
		$description = $feed->html2txt( $description );
		$description = $feed->strip_htmlentities( $description );
		$description = stripslashes( $description );

		$feedcontent = file_get_contents( "feedtemplate.txt" );
		$feedcontent = str_replace( "[title]", $title, $feedcontent );
		$feedcontent = str_replace( "[description]", $description, $feedcontent );
		$feedcontent = str_replace( "[url]", $url, $feedcontent );
		$feedcontent1 = ( $feedcontent );
		// $url = $_SESSION[SESSION_PREFIX."url"];
		if ( function_exists( "fopen" ) ) {
			$fp = @fopen( $feedurl . "123_controlpanel_remote_methods.php?createnewfeedxml=" . $feedcontent1, "r" );
			if ( !$fp ) {
				if ( $elseupload != "elseupload" ) {
					echo "<br>&nbsp;Unable to create feed.xml, 
				<br>error in opening " . $feedurl . "123_controlpanel_remote_methods.php";
					return false;
				}else {
					$resp = "false";
				}
			}else {
				$resp = "";
				while( !feof( $fp ) ) {
					$resp .= fgets( $fp );
				}
				fclose( $fp );
			}
		}else if ( function_exists( "curl_init" ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $feedurl . "123_controlpanel_remote_methods.php?createnewfeedxml=" . $feedcontent1 );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			$resp = curl_exec( $ch );
			curl_close ( $ch );
		}

		if ( $resp == "true" ) {
			return true;
		}else {
			if ( $elseupload != "elseupload" ) {
				return false;
			}else {
				$ftpaddr = $_SESSION[SESSION_PREFIX . "ftp_address"];
				$ftpuser = $_SESSION[SESSION_PREFIX . "ftp_username"];
				$ftppass = $_SESSION[SESSION_PREFIX . "ftp_password"];
				$ftphome = $_SESSION[SESSION_PREFIX . "ftp_homepage"];

				if ( $ftpaddr != "" && $ftpuser != "" && $ftppass != "" ) {
					$conn_id = @ftp_connect( $ftpaddr );
					$login_result = @ftp_login( $conn_id, $ftpuser, $ftppass );
					@ftp_pasv( $conn_id, true );
					$str = $ftphome;
					$cut = $ftpuser;
					$localfeedfile = $feed->createLocalFeed( $feedcontent );
					if ( $localfeedfile != "" ) {
						$ftphomepage = $common->getFTPhomePage( $str, $cut );
						if ( ( $conn_id ) && ( $login_result ) ) {
							$uploadfeed = @ftp_put( $conn_id, $ftphomepage . "feed.xml" , $localfeedfile, FTP_BINARY );
							ftp_close( $conn_id );
						}else {
							return false;
						}
						if ( !$uploadfeed ) {
							return false;
						}else {
							return true;
						}
					}else {
						return false;
					}
				}else {
					echo "<BR>ERROR[101]: Due to some technical problem If you get this message, , please inform us..<br>";
				}
			}
		}
	}

	function unloadControlPanelScripts() {
		global $common;
		// $str = $_POST['ftp_homepage'];
		// $cut = $_POST['ftp_username'];
		$str = $this->ftp_home;
		$cut = $this->ftp_user;
		// $ftphomepage = substr($str, strpos($str,$cut) + strlen($cut)+1 , strlen($str) - strpos ($str,$cut)-strlen($cut));
		$ftphomepage = $common->getFTPhomePage( $str, $cut );
		$destination_file = $ftphomepage . "123_controlpanel_remote_methods.php";

		$upload = @ftp_put( $this->conn_id, $destination_file, "123_controlpanel_remote_methods.php", FTP_BINARY );
		if ( !$upload ) {
			return false;
		}else {
			return true;
		}
	}

	function checkSiteURL( $url = "" ) {
		if ( $url == "" ) {
			$url = $_POST["url"];
		}
		$resp = "";
		if ( function_exists( "fopen" ) ) {
			$fp = @fopen( $url . "123_controlpanel_remote_methods.php?whoareyou=im", "r" );
			if ( !$fp ) {
				return false;
			}else {
				while( !feof( $fp ) ) {
					$resp .= @fgets( $fp );
				}
				fclose( $fp );
			}
		}else if ( function_exists( "curl_init" ) ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url . "123_controlpanel_remote_methods.php?whoareyou=im" );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			$resp = @curl_exec( $ch );
			curl_close ( $ch );
		}
		if ( trim( $resp ) == "thisiscontrolpanelremotemethod" ) {
			return true;
		}else {
			return false;
		}
	}
}

?>