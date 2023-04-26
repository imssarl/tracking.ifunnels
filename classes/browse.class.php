<?php

class BrowseFTP

{

var $ftp_conn;



	function connect($ftpadd, $ftpusr, $ftppas)

	{

		$this->ftp_conn = @ftp_connect($ftpadd);

		

		if ($this->ftp_conn) {

			if (@ftp_login($this->ftp_conn, $ftpusr, $ftppas)) {
@ftp_pasv( $this->ftp_conn, true );

				return true;

			} else {

				return false;

			}
		} else {

			return false;

		}

	}

	function close()

	{

		if ($this->ftp_conn != "")

		{

			ftp_close($this->ftp_conn);

		}

	}

	

	function dirlist($dirname = ".")

	{

		$directories = array();

		$files = array();

		$dir_counter = 0;

		$file_counter = 0;

		$content = @ftp_nlist($this->ftp_conn, $dirname);

		if ($dirname==".")

		{

			$dirname = @ftp_pwd($this->ftp_conn);

			if (strcmp(trim($dirname),"/")==0) $_SESSION["stype"] = "L"; else $_SESSION["stype"] = "W";

		}

		

//		echo "list: ";

//		var_dump($content);

//		echo "\n\n";

		for($i = 0; $i < sizeof($content); $i++)

		{

			$content[$i] = $dirname."/".$this->getBaseName($content[$i]);

		}

		

		for($i = 0; $i < sizeof($content); $i++){ // If we can change into this then it's a directory, If not, it's a file

		if($content[$i] != "." && $content[$i] != ".."){

		$olddir = @ftp_pwd($this->ftp_conn);

		if(@ftp_chdir($this->ftp_conn, $content[$i])){ // We have a directory

		$directories[] = $content[$i];

		$dir_counter++;

		ftp_chdir($this->ftp_conn, $olddir);

//		@ftp_cdup($this->ftp_conn);

		}else{ // We have a file



		$files[] = $content[$i];

		$file_counter++;

		}//if

		}//if

		}//for

		echo "".$dirname."<br><hr/><br>";

		if ($_SESSION["rootdir"] == $_GET["dir"])

		{



			echo "<img border = '0' align='bottom' src='images/folder.gif' title='Click here to select the folder' onClick='setFolder(\"".$_SESSION["rootpath"]."\")'>&nbsp;..<br>";

		}

		else

		{

			echo "<a class = 'general' href='javascript:history.go(-1);' title='Click here to go back'><img border = '0' align='bottom' src='images/folder.gif' >&nbsp;..</a><br>";

		}

		for($j=0; $j<$dir_counter; $j++){

		$directories[$j] = str_replace(array("\\\\","\\"),"/",$directories[$j]);

		if ($_SESSION["stype"]=="L")

		{

			$dirpath = $_SESSION["rootpath"].substr($directories[$j],strpos($directories[$j],"/")+1,strlen($directories[$j])-strpos($directories[$j],"/"));

		}

		else

		{

			$dirpath = $directories[$j];

		}



		$dirnam =  basename($directories[$j]);

		echo "<img align='bottom' src='images/folder.gif' title='Click here to select the folder' onClick='setFolder(\"".$dirpath."\")'>&nbsp;<a title='Click here to view subfolder(s)' href='browse.php?dir=".($directories[$j])."&homebox=".$_GET['homebox']."' class = 'general'>".$dirnam."</a><br>";

		

		if($directories[$j] != "." OR $directories[$j] != ".."){

		$location = @ftp_pwd($this->ftp_conn);

		@ftp_cdup($this->ftp_conn);

		echo '

		<script language=javascript>

		document.getElementById("waitmess").style.display = "none";

		</script>';

		

		}//if

		}//for



	}

	function getBaseName($file)

	{

		$file = str_replace(array("\\\\","\\"),"/",$file);

		if (strpos($file,"/") !== false)

		{

		return strtolower(strrev(substr(strrev($file),0,strpos(strrev($file),"/"))));

		}

		else

		{

		return $file;

		}          

	}

}

?>