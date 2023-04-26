<?php
//Image Upload
//Note: You can customized this script it depends on your usage
include('php_func/ftp.inc.php');

if($_SERVER['REQUEST_METHOD'] == "POST")
{ 
        //FileName Field
	//$SFileName = magic_quotes($_POST['_fn']);
	$SFileName = $_POST['_fn'];
        //File Upload Field
	$file_upload = $_FILES['_f']['tmp_name'];
        //Document Root
	$root_dir = $_SERVER['DOCUMENT_ROOT'];
        //Get 2 Value for the temporary uploaded file from the remote server
	list($filenamedec,$filetypedec) = explode(".",basename($_FILES['_f']['name']));
        //Encrypt the filename using 2 method of encryption
	$filenameenc = sha1(md5($filenamedec));
	if(empty($file_upload))
	{
                //state your error here using session vars
		header("location: ".$_SERVER['HTTP_REFERER']);
		exit();		
	}
	else
	{
		if(!is_file($file_upload))
		{
                         //state your error here using session vars
			header("location: ".$_SERVER['HTTP_REFERER']);
			exit();				
		}	
		//procedure
		list($img_width,$img_height,$img_type,$img_attr) = getimagesize($file_upload);
		$img_size = filesize($file_upload);
		if($img_size > 5000000)
		{
                         //state your error here using session vars
			header("location: ".$_SERVER['HTTP_REFERER']);
			exit();						
		}

		switch($img_type) {
			case 1: {
				$file_ext = ".gif";
			} break;
			case 2: {
				$file_ext = ".jpg";	
			} break;
			case 3: {
				$file_ext = ".png";	
			} break;
			default: {
                         //state your error here using session vars
			 header("location: ".$_SERVER['HTTP_REFERER']);
		         exit; 	
			} break;
		}
		
         $myftp = new upload_file();	           
         $myftp->ftp_transaction($file_upload,$filenameenc,$img_type);
         $myftp->_close_connection();			
	 exit();		
	}	

}
else
{
	header("location: 404.shtml");
	exit();
}
?>