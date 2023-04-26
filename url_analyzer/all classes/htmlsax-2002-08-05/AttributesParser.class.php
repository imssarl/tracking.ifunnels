<?
/*
	(c) Copyright by Alexander Zhukov alex@veresk.ru. Released under LGPL
*/	

class AttributesParser
{
	var $sm;
	var $str;
	var $atts = Array();
	var $_tmp_attname = "";
	var $_tmp_value = "";
	var $_last_att = "";
	
	function AttributesParser()
	{
		$this->sm = new StateMachine();
	}
	function parse($str)
	{
		$this->str = trim($str)." ";
		$this->sm->set("att_name");
		$this->_parse();
		return $this->atts;
	}
	function _parse()
	{
		for ($i=0; $i < strlen($this->str); $i++)
		{
			$chr  = $this->str[$i];

			if ($this->sm->state("att_val") and ($chr == "\""))
			{
				if($this->sm->state("qout_b"))
				{
					$this->sm->reset("qout_b");
				}
				else 
				{
					$this->sm->set("qout_b");
				}
			}
			
			if (($chr == "=") and ($this->sm->state("qout_b") == false))
			{ 
				$this->sm->reset("att_name"); 
				$this->sm->set("att_name_end");
			}
			if (($chr == " ") and ($this->sm->state("qout_b") == false))
			{
				$this->sm->reset("att_val"); 
				$this->sm->set("att_val_end");				
			}

			$this->_doChar($chr);
		}
	}
	
	function _doChar($chr)
	{
		if ($this->sm->state("att_name")) { $this->_tmp_attname .= $chr; }
		if ($this->sm->state("att_name_end")) 
		{ 
			$this->_last_att = trim($this->_tmp_attname);
			$this->atts[trim($this->_tmp_attname)] = $this->_last_att;		
			$this->_tmp_attname = "";
			$this->sm->reset("att_name_end");
			$this->sm->set("att_val");
		}
		if ($this->sm->state("att_val")) { $this->_tmp_value .= $chr; }
		if ($this->sm->state("att_val_end"))
		{
			if ($this->_tmp_value != "") 
			{ 
				$this->_tmp_value = substr($this->_tmp_value,1); 
				if (substr($this->_tmp_value,0,1) == "\"")
				{
					$this->_tmp_value = substr($this->_tmp_value,1); 
				}
				if (substr($this->_tmp_value,-1) == "\"")
				{
					$this->_tmp_value = substr($this->_tmp_value,0,-1); 
				}

				$this->atts[$this->_last_att] = $this->_tmp_value;
			}
			$this->_tmp_value = "";
			$this->sm->reset("att_val_end");
			$this->sm->set("att_name");
		}
	}
}
?>