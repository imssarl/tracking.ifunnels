<?php

class FileSystem

{



	function getDirs($dir="")

	{

	

		$dirs = array();

		$dir_list = dir($dir);

		

		while($entry = $dir_list->read())

		{

			$path = $dir.$entry;

			if($entry != "." && $entry != "..")

			{

				if(is_dir($path))

				{

					$dirs[] = $entry;

				}

			}

		}

		return $dirs;

	}

	function deleteFolder($base="", $folder)
	{

		$msg = array();
		$directory = "";
		$folder = $base.$folder;
echo $folder;
		$scanned = $this->scan_directory_recursively($folder, $filter=FALSE);
		$flag = true;
		if ($scanned)
		{ 
			$flag = $this->removeFolder($scanned, $directory, $flag);
			if($flag) @rmdir($folder);
		}
		else
		{
			$flag = false;
		}
		die();
		return $flag;
	}


	function removeFolder($arr, $directory, $flag)

	{

		$msg = array();

		foreach($arr as $val)

		{

			$destination=$directory.$val["path"];

			if ($val["kind"]=="file")

			{

	

				if (!unlink($destination)) $flag = false;

			}

			else

			{	

				if (count($val["content"])>0)

				{	

					$this->removeFolder($val["content"],$directory, $flag);

				}

				if (!rmdir($destination)) $flag = false;

			}

		}

		return $flag;

	}



	function scan_directory_recursively($directory, $filter=FALSE)

	{  

		 if(substr($directory,-1) == '/')

		 {

			 $directory = substr($directory,0,-1);

		 }

		 if(!file_exists($directory) || !is_dir($directory))

		 {

			 return FALSE;

		 }elseif(is_readable($directory))

		 {

			 $directory_list = opendir($directory);

			 while (FALSE !== ($file = readdir($directory_list)))

			 {

				 if($file != '.' && $file != '..')

				 {

					 $path = $directory.'/'.$file;

					 if(is_readable($path))

					 {

						 $subdirectories = explode('/',$path);

						 if(is_dir($path))

						 {

							 $directory_tree[] = array(

								 'path'    => $path,

								 'name'    => end($subdirectories),

								 'kind'    => 'directory',

								 'content' => $this->scan_directory_recursively($path, $filter));

	  

						 }elseif(is_file($path))

						 {

							 $extension = end(explode('.',end($subdirectories)));

	  

							 if($filter === FALSE || $filter == $extension)

							 {

								 $directory_tree[] = array(

									 'path'      => $path,

									 'name'      => end($subdirectories),

									 'extension' => $extension,

									 'size'      => filesize($path),

									 'kind'      => 'file');

							 }

						 }

					 }

				 }

			 }

			 closedir($directory_list); 

	  

			 return $directory_tree;

	  

		}

		else

		{

			 return FALSE;    

		}

	}





}

?>