<?
/*
	(c) Copyright by Alexander Zhukov alex@veresk.ru. Released under LGPL
*/	

class HTMLSax
{
	var $_html = "";
	
	var $_tmp_tag = "";
	var $_tmp_textNode = "";
	var $skipWhitespace = 0;
	var $trimDataNodes = 0;
	
	function HTMLSax()
	{
		$this->sm = new StateMachine();
		$this->sm->set("text_node_start");
	}	
	function parse($html)
	{
		
		$this->html = $html;
		$this->_parse();	
	}

	function _parse()
	{
		for ($i = 0; $i < strlen($this->html); $i++)
		{
			$chr = $this->html[$i];
			if ($chr == "<") 
			{ 	
				$this->sm->set("left_b"); 
				$this->sm->reset("text_node_start");
				$this->sm->set("text_node_end");
				
			}
			if ($chr == ">") 
			{ 
				$this->sm->set("right_b"); 
			}
			if ($chr == "\n") { $this->sm->set("text_node_end"); }
				$this->_doChar($chr);
		}
	}

	function _doChar($chr)
	{
		if ($this->sm->state("text_node_start"))
		{
				$this->_tmp_textNode .= $chr;
		}
		
		if ($this->sm->state("text_node_end"))
		{
			if ($this->trimDataNodes == 1) { $this->_tmp_textNode = trim($this->_tmp_textNode); }
			
			if ($this->skipWhitespace == 1)
			{
				if (trim($this->_tmp_textNode) != "") {
					$this->handle_data($this->_tmp_textNode);
				}
			}
			else
			{
				$this->handle_data($this->_tmp_textNode);
			}

			$this->_tmp_textNode = "";

			$this->sm->reset("text_node_end");
		}
		
		if ($this->sm->state("left_b"))  
		{ 
			$this->_tmp_tag .= $chr; 
		}

		if ($this->sm->state("right_b")) 
		{
			$this->_tagHandler(); 
			$this->sm->set("text_node_start");
		}
	}

	function _tagHandler()
	{
		$tag = substr(substr($this->_tmp_tag,1),0,-1);
		if (substr($tag,0,1) == "/") { $this->handle_end_tag(substr($tag,1)); }
		else 
		{
			$pos = strpos($tag," ");
			if ($pos === false) 
			{ 
				$this->handle_start_tag($tag,Array());
			}
			else 
			{
				$a = new AttributesParser();
				$attr = $a->parse(substr($tag,$pos));
				$this->handle_start_tag(substr($tag,0,$pos),$attr);
			}
		}
		
		$this->_tmp_tag = "";
		$this->sm->reset("left_b");		
		$this->sm->reset("right_b");
	}

	function handle_start_tag($txt,$attr)
	{
		die ("Method handle_start_tag in ".get_class($this)." not defined");
	}

	function handle_data($txt)
	{
		//die ("Method handle_data in ".get_class($this)." not defined");
	}
	
	function handle_end_tag($txt)
	{
		die ("Method handle_end_tag in ".get_class($this)." not defined");
	}
	
}
?>