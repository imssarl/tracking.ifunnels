<?php
//PHP code will come here
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/amarticle.class.php");
require_once("classes/en_decode.class.php");

$endec=new encode_decode();
$article = new Article();
$database = new Database();
$database->openDB();
$option=isset($_POST['process'])?$_POST['process']:'';

if($option=='artcat')
{
	$artNum=$_POST['artNum'];
	$catid=$_POST['catid'];
	$catid=$endec->encode($catid);
	//echo "cat:".$catid;
	$code = '<?php
   		if(function_exists("curl_init"))
        {

		   $ch = @curl_init();

		   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'");

		   curl_setopt($ch, CURLOPT_HEADER, 0);

		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		   $resp = @curl_exec($ch);

		   $err = curl_errno($ch);



		   if($err === false || $resp == "")

		   {

				   $newsstr = "";

		   }

		   else

		   {

			   if (function_exists("curl_getinfo"))

				   {

					   $info = curl_getinfo($ch);

						   if ($info["http_code"]!=200)

								   $resp="";

				   }

				   $newsstr = $resp;

		   }

		   @curl_close ($ch);

		   echo $newsstr;

	  }

	   else

	   {

				@include("'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'");

	   }



?>';

}
elseif($option=='artsnip')
{
	$artNum=$_POST['artNum'];
	$catid=$_POST['catid'];
	$catid=$endec->encode($catid);
	$code = '<?php
	   		if(function_exists("curl_init"))

 	        {

 	        

		       $ch = @curl_init();

		       curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticlesnippets.php?category_id='.$catid.'&nb='.$artNum.'");

		       curl_setopt($ch, CURLOPT_HEADER, 0);

		       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		       $resp = @curl_exec($ch);

		       $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       }

                       else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.SERVER_PATH.'showarticlesnippets.php?category_id='.$catid.'&nb='.$artNum.'");

               }



?>';
}
elseif($option=='art')
{
	$catid=$_POST['catid'];
	$catid=$endec->encode($catid);
	$code = '<?php

   	if(function_exists("curl_init"))

               {

                               $ch = @curl_init();

                               curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?id='.$catid.'");

                               curl_setopt($ch, CURLOPT_HEADER, 0);

                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                               $resp = @curl_exec($ch);

                               $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       } else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.SERVER_PATH.'showarticles.php?id='.$catid.'");

               }



?>';
}
elseif($option=='randart')
{
	$artNum=$_POST['artNum'];
	$catid=$_POST['catid'];
	$catid=$endec->encode($catid);
	$code = '<?php

   	if(function_exists("curl_init"))

               {

                               $ch = @curl_init();

                               curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'&type=rand");

                               curl_setopt($ch, CURLOPT_HEADER, 0);

                               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                               $resp = @curl_exec($ch);

                               $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       } else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'&type=rand");

               }



?>';
}
elseif($option=='kwdart')
{
	$artNum=$_POST['artNum'];
	$catid=$_POST['catid'];
	$catid=$endec->encode($catid);
	$code = '<?php
		   		if(function_exists("curl_init"))
               {
				   $ch = @curl_init();
	
				   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?keyword='.$artNum.'&defcategory='.$catid.'");
	
				   curl_setopt($ch, CURLOPT_HEADER, 0);
	
				   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
				   $resp = @curl_exec($ch);
	
				   $err = curl_errno($ch);



                       if($err === false || $resp == "")

                       {

                               $newsstr = "";

                       } else

                       {

                               if (function_exists("curl_getinfo"))

                               {

                                   $info = curl_getinfo($ch);

                                       if ($info["http_code"]!=200)

                                               $resp="";

                               }

                               $newsstr = $resp;

                       }

                       @curl_close ($ch);

                       echo $newsstr;

               }

               else

               {

                        @include("'.SERVER_PATH.'showarticles.php?keyword='.$artNum.'&defcategory='.$catid.'");

               }



?>';
}
elseif($option=='save')
{
	$name=$_POST['name'];
	$descp=$_POST['descp'];
	$disp =$_POST['disp'];
	$codex =htmlentities(genCode($disp));
	$sql = "INSERT INTO `".TABLE_PREFIX."am_savedcode` ( `name` , `description` , `disp_option` , `code`,`user_id`)
		VALUES ("
		."'".$database->GetSQLValueString($name,"text")."',"
		."'".$database->GetSQLValueString($descp,"text")."',"
		."'".$database->GetSQLValueString($disp,"text")."',"
		."'".$database->GetSQLValueString($codex,"text")."',"
		."'".$database->GetSQLValueString($_SESSION[SESSION_PREFIX.'sessionuserid'],"int").
		"')";
		$id = $database->insert($sql);
		if($id > 0)
			$code="Saved Successfully";
		else
			$code ="Error occured while saving record";
}
echo $code;

function genCode($d)
{
	$endec=new encode_decode();
	if($d=='artcat')
	{
		$artNum=$_POST['artNum'];
		$catid=$_POST['catid'];
		$catid=$endec->encode($catid);
		//echo "cat:".$catid;
		$code = '<?php
			if(function_exists("curl_init"))
			{
	
			   $ch = @curl_init();
	
			   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'");
	
			   curl_setopt($ch, CURLOPT_HEADER, 0);
	
			   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
			   $resp = @curl_exec($ch);
	
			   $err = curl_errno($ch);
	
	
	
			   if($err === false || $resp == "")
	
			   {
	
					   $newsstr = "";
	
			   }
	
			   else
	
			   {
	
				   if (function_exists("curl_getinfo"))
	
					   {
	
						   $info = curl_getinfo($ch);
	
							   if ($info["http_code"]!=200)
	
									   $resp="";
	
					   }
	
					   $newsstr = $resp;
	
			   }
	
			   @curl_close ($ch);
	
			   echo $newsstr;
	
		  }
	
		   else
	
		   {
	
					@include("'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'");
	
		   }
	
	
	
	?>';
	
	}
	elseif($d=='artsnip')
	{
		$artNum=$_POST['artNum'];
		$catid=$_POST['catid'];
		$catid=$endec->encode($catid);
		$code = '<?php
				if(function_exists("curl_init"))
	
				{
	
				
	
				   $ch = @curl_init();
	
				   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticlesnippets.php?category_id='.$catid.'&nb='.$artNum.'");
	
				   curl_setopt($ch, CURLOPT_HEADER, 0);
	
				   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
				   $resp = @curl_exec($ch);
	
				   $err = curl_errno($ch);
	
	
	
						   if($err === false || $resp == "")
	
						   {
	
								   $newsstr = "";
	
						   }
	
						   else
	
						   {
	
								   if (function_exists("curl_getinfo"))
	
								   {
	
									   $info = curl_getinfo($ch);
	
										   if ($info["http_code"]!=200)
	
												   $resp="";
	
								   }
	
								   $newsstr = $resp;
	
						   }
	
						   @curl_close ($ch);
	
						   echo $newsstr;
	
				   }
	
				   else
	
				   {
	
							@include("'.SERVER_PATH.'showarticlesnippets.php?category_id='.$catid.'&nb='.$artNum.'");
	
				   }
	
	
	
	?>';
	}
	elseif($d=='art')
	{
		$catid=$_POST['catid'];
		$catid=$endec->encode($catid);
		$code = '<?php
	
		if(function_exists("curl_init"))
	
				   {
	
								   $ch = @curl_init();
	
								   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?id='.$catid.'");
	
								   curl_setopt($ch, CURLOPT_HEADER, 0);
	
								   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
								   $resp = @curl_exec($ch);
	
								   $err = curl_errno($ch);
	
	
	
						   if($err === false || $resp == "")
	
						   {
	
								   $newsstr = "";
	
						   } else
	
						   {
	
								   if (function_exists("curl_getinfo"))
	
								   {
	
									   $info = curl_getinfo($ch);
	
										   if ($info["http_code"]!=200)
	
												   $resp="";
	
								   }
	
								   $newsstr = $resp;
	
						   }
	
						   @curl_close ($ch);
	
						   echo $newsstr;
	
				   }
	
				   else
	
				   {
	
							@include("'.SERVER_PATH.'showarticles.php?id='.$catid.'");
	
				   }
	
	
	
	?>';
	}
	elseif($d=='randart')
	{
		$artNum=$_POST['artNum'];
		$catid=$_POST['catid'];
		$catid=$endec->encode($catid);
		$code = '<?php
	
		if(function_exists("curl_init"))
	
				   {
	
								   $ch = @curl_init();
	
								   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'&type=rand");
	
								   curl_setopt($ch, CURLOPT_HEADER, 0);
	
								   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
								   $resp = @curl_exec($ch);
	
								   $err = curl_errno($ch);
	
	
	
						   if($err === false || $resp == "")
	
						   {
	
								   $newsstr = "";
	
						   } else
	
						   {
	
								   if (function_exists("curl_getinfo"))
	
								   {
	
									   $info = curl_getinfo($ch);
	
										   if ($info["http_code"]!=200)
	
												   $resp="";
	
								   }
	
								   $newsstr = $resp;
	
						   }
	
						   @curl_close ($ch);
	
						   echo $newsstr;
	
				   }
	
				   else
	
				   {
	
							@include("'.SERVER_PATH.'showarticles.php?category_id='.$catid.'&nb='.$artNum.'&type=rand");
	
				   }
	
	
	
	?>';
	}
	elseif($d=='kwdart')
	{
		$artNum=$_POST['artNum'];
		$catid=$_POST['catid'];
		$catid=$endec->encode($catid);
		$code = '<?php
					if(function_exists("curl_init"))
				   {
					   $ch = @curl_init();
		
					   curl_setopt($ch, CURLOPT_URL,"'.SERVER_PATH.'showarticles.php?keyword='.$artNum.'&defcategory='.$catid.'");
		
					   curl_setopt($ch, CURLOPT_HEADER, 0);
		
					   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
					   $resp = @curl_exec($ch);
		
					   $err = curl_errno($ch);
	
	
	
						   if($err === false || $resp == "")
	
						   {
	
								   $newsstr = "";
	
						   } else
	
						   {
	
								   if (function_exists("curl_getinfo"))
	
								   {
	
									   $info = curl_getinfo($ch);
	
										   if ($info["http_code"]!=200)
	
												   $resp="";
	
								   }
	
								   $newsstr = $resp;
	
						   }
	
						   @curl_close ($ch);
	
						   echo $newsstr;
	
				   }
	
				   else
	
				   {
	
							@include("'.SERVER_PATH.'showarticles.php?keyword='.$artNum.'&defcategory='.$catid.'");
	
				   }
	
	
	
	?>';
	}
	
	return $code;
}

?>