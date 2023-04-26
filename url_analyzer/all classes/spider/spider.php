<?php

/*
 *
 * Spider 1.0
 *
 * Written by Kurt Payne       
 * 
 * Released under the GNU public license:
 * http://www.gnu.org/copyleft/gpl.html
 *
 */

class Spider
	{
	/*
	 * Public variables
	 */
	var $link_array;
	var $email_array;
	var $title;
	var $description;
	var $keywords;
	var $words;
	var $times;
	var $time_info;
	
	/*
	 * Private variables
	 */
	
	var $wordfile = "words.txt";
	var $url;
	var $webpage;
	var $path;
	var $word_limit;
	var $strip_common;

	/*
	 * Constructor
	 *
	 * Accepts:
	 * $url = page to spider,
	 * $word_limit = how many words to nab, 0 = all,
	 * $strip_common = strip some common words?
	 *
	 */

	function Spider($url, $word_limit, $strip_common)
		{
		// This are the keys for the $times array
		$this->time_info[] = "URL Fetch";
		$this->time_info[] = "Link Extract";
		$this->time_info[] = "Link Clean";
		$this->time_info[] = "Email Extract";
		$this->time_info[] = "Title Extract";
		$this->time_info[] = "Keyword Extract";
		$this->time_info[] = "Description Extract";
		$this->time_info[] = "HTML Strip";
		$this->time_info[] = "Word Extract";
		$this->time_info[] = "Total Spider Process*";
		
		// Set the URL to spider		
		$this->url = $url;
		$this->word_limit = $word_limit;
		$this->strip_common = $strip_common;

		// No protocol?
		if (ereg("^www\.", $this->url))
			{
			$this->url = "http://" . $this->url;
			}

		// Add trailing slash
		if (ereg("^http://[[:alnum:].]+[:0-9]{0,5}$", $this->url))
			{
			$this->url = $this->url . "/";
			}

		// Is it a valid URL?
		if (!ereg("^http://[[:alnum:].-]+[:0-9]{0,5}[/]?.*", $this->url))
			{
			echo "Invalid URL: $this->url\n";
			exit;
			}

		// Get the document path
		$this->path = substr($this->url, 0, strrpos($this->url, "/") + 1);

		// Get the document root
		$this->domain = substr($this->url, 0, strpos($this->url, "/", 10));

		// Suck in the words to exclude		
		$fp = fopen($this->wordfile, "r");
		$common_words = fread($fp, filesize($this->wordfile));
		fclose($fp);

		// Start the clock
		$time_start = $this->getmicrotime();
		
		// Get the file
		$this->webpage = $this->get_url($this->url);

		// Record the time
		$time_end = $this->getmicrotime();
		$time = $time_end - $time_start;
		$this->times[] = $time;

		// Start timing on the processing
		$time_start = $this->getmicrotime();

		// Time the link extraction
		$time_start1 = $this->getmicrotime();

		// Script JavaScript
		$this->webpage = preg_replace("/<script[^>]*?>.*?<\/script>/si", "", $this->webpage);

		// Strip style
		$this->webpage = preg_replace("/<style[^>]*?>.*?<\/style>/si", "", $this->webpage);

		// Strip 'other'
		$this->webpage = preg_replace("/<link[^>]*?>/si", "", $this->webpage);

		// Get links
		$regex = "[hH][rR][eE][fF][[:space:]]*=[[:space:]]*[\"]*([^> \"'>]*)[\"]*";
		$str = $this->webpage;

		// While there's URL's left...
		while (ereg($regex, $str, $temp))
			{
			$str = str_replace($temp[0], "", $str);
			// If they're not file://, mailto:, or javascript calls...
			if (strtolower(substr($temp[1], 0, 11)) != "javascript:" && strtolower(substr($temp[1], 0, 7)) != "mailto:" && strtolower(substr($temp[1], 0, 7)) != "file://")
				{
				// Add them to the link array
				$this->link_array[] = $temp[1];
				}
			}

		// Record time
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Start time on link cleaning
		$time_start1 = $this->getmicrotime();

		// Make sure they're complete links
		for ($i = 0 ; $i < count($this->link_array) ; $i++)
			{
			if (!ereg("^(http://|https://|ftp://|gopher://)", $this->link_array[$i]))
				{
				if (substr($this->link_array[$i], 0, 1) == "/")
					{
					// Are the links relative to the domain?
					$this->link_array[$i] = $this->domain . $this->link_array[$i];
					}
				else
					{
					// Or the path?
					$this->link_array[$i] = $this->path . $this->link_array[$i];
					}
				}
			}

		// Record the time
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Start timing the e-mail extraction
		$time_start1 = $this->getmicrotime();

		// Get e-mails
		$regex = "([[:alnum:]_.-]+@[[:alnum:].-]+\.[a-zA-Z]{2,3})";
		$str = $this->webpage;

		// While they're are e-mails
		while(ereg($regex, $str, $temp))
			{
			$str = str_replace($temp[0], "", $str); 
			// Add them to our array
			$this->email_array[] = $temp[1];
			}
		while (ereg($regex, $str));

		// Record the time
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Start timing the title
		$time_start1 = $this->getmicrotime();

		// Get title
		if(eregi("<TITLE>([^<]*)</TITLE>", $this->webpage, $matches))
			{
			$this->title = $matches[1];
			}
		else
			{
			$this->title = "No Title";
			}
		if (strlen($this->title) <= 0)
			{
			$this->title = "No Title";
			}

		// Record the time
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Start timing on the keywords
		$time_start1 = $this->getmicrotime();

		// Get keywords
		if(eregi("META[[:space:]+]NAME[:space:]*=[:space:]*[\"]?KEYWORDS[\"]?[[:space:]+]CONTENT[:space:]*=[:space:]*[\"]?([^\">]+)[\"]?", $this->webpage, $matches))
			{
			$this->keywords = $matches[1];
			}
		else
			{
			$this->keywords = "No Keywords";
			}
		if (strlen($this->keywords) <= 0)
			{
			$this->keywords = "No Keywords";
			}

		// Record the time
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Start timing the description
		$time_start1 = $this->getmicrotime();

		// Get desciption
		if (eregi("META[[:space:]+]NAME[:space:]*=[:space:]*[\"]?DESCRIPTION[\"]?[[:space:]+]CONTENT[:space:]*=[:space:]*[\"]?([^\">]+)[\"]?", $this->webpage, $matches))
			{
			$this->description = $matches[1];
			}
		else
			{
			$this->description = "No Description";
			}

		if (strlen($this->description) <= 0)
			{
			$this->description = "No Description";
			}
		
		// Record the time
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Start timing the stripping
		$time_start1 = $this->getmicrotime();

		// Strip HTML
		$this->webpage = ereg_replace("<([^>]|\n)*>", "", $this->webpage); 

		// Strip HTML entities
		$this->webpage = ereg_replace("(&[[:alnum:]#]+;)", "", $this->webpage);

		// Fix whitepsaces
		$this->webpage = ereg_replace("[[:space:]]+", " ", $this->webpage);
		

		// Record the time
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Start timing the word extraction
		$time_start1 = $this->getmicrotime();

		// Strip common words, if we're supposed to
		if ($this->strip_common)
			{
			$common = split("[[:space:]]+", $common_words);
			for ($i = 0 ; $i < count($common) ; $i++)
				{
				if (strlen($common[$i]) > 0)
					{
					$this->webpage = eregi_replace(" " . $common[$i] .  " ", " ", $this->webpage);
					}
				}
			}

		$temp = split("[[:space:]]+", $this->webpage);
		
		// If there's less words than limit, or we're supposed to grab 'em all
		if ($this->word_limit > count($temp) || $this->word_limit < 0)
			{
			// If we have less words than limit, lower the limit
			$this->word_limit = count($temp);
			}
		
		// Get the words
		for ($i = 0 ; $i < $this->word_limit ; $i++)
			{
			// If it's not a single character
			if (strlen($temp[$i]) > 1)
				{
				// Add it
				$this->words .= $temp[$i] . " ";
				}
			elseif ($this->word_limit < count($temp))
				{
				// Otherwise extend the search, if we can
				$this->word_limit++;
				}
			}
		
		// Chop off trailing space
		$this->words = substr($this->words, 0, -1);

		// Record the tine
		$time_end1 = $this->getmicrotime();
		$time1 = $time_end1 - $time_start1;
		$this->times[] = $time1;

		// Record the whole process time
		$time_end = $this->getmicrotime();
		$time = $time_end - $time_start;
		$this->times[] = $time;
		}

	/*
	 * This function returns the time in microseconds
	 */

	function getmicrotime()
		{
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
		}

	/*
	 * This function handles HTTP redirects and returns the real URL
	 *
	 * I couldn't get it working.  Appearantly hotmail.com would talk
	 * to my telnet client, but not to PHP.  Also, I've had problems where
	 * the URL returned by this function mysteriously couldn't be opened.
	 *
	 * If you get it working, let me know.  Most of this code was swiped
	 * from examples on PHP.net
	 */

	function get_real_loc($file)
		{
		ereg("http://([a-zA-Z0-9.-]+)[/]+(.*)", $file, $matches);
		$host = $matches[1];
		if (!ereg("^/", $matches[2]) && strlen($matches[1]) <= 0)
			{
			$path = "/";
			}
		else
			{
			$path = "/" . $matches[2];
			}

		$connection = fsockopen ($host, 80, $errno, $errstr, 15); 

		if ($connection) 
			{
			//set non-blocking mode 
			set_socket_blocking($connection, false); 

			//tell server what document we want 
			fputs ($connection, "GET $path HTTP/1.0"); 
			fputs ($connection, "\r\n"); 
			fputs ($connection, "HOST: $host"); 
			fputs ($connection, "\r\n\r\n"); 

			$headerStart = 0; 

			while (!feof($connection)) 
				{ 
				$currentLine = fgets ($connection, 128);
				if(ereg("^HTTP", $currentLine)) 
					{ 
					if (ereg("200", $currentLine))
						{
						$realloc = $file;
						return $realloc;
						}
					else
						{
						$headerStart = 1; 
						}
					}
				elseif ($headerStart && eregi("^location:[ ](.*)", $currentLine, $matches)) 
					{
					$realloc = ereg_replace("[[:space:]]*", "", $matches[1]);
					return $realloc;
					}
				}
				fclose ($connection); 
			} 
		else 
			{
			echo "$errstr ($errno)\n"; 
			exit;
			}
		}

	/*
	 * Fetches the URL
	 */

	function get_url($url)
		{
		$url = $this->get_real_loc($url);
		$content;
		$fp = @fopen($url, "r");
		if (!$fp)
			{
			// If fopen() returns false, die
			echo "Cannot open url: $url";
			exit;
			}
		while (!feof($fp))
			{
			$content .= fread($fp, 1000);
			}
		fclose($fp);
		return $content;
		}
	}

?>