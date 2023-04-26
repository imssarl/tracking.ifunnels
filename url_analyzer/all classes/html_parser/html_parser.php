<?php
# 
# indrek päri
# indrek@indrek.ee
# 

class html_parser{

	var $content;
	var $linecount;
	
	function html_parser($file, $linecount=0){
		$this->content .= '<style>font, ol, li { font-family: "Courier New", Courier; font-size: 12px; } </style>';	
		$this->linecount = $linecount;
		if($this->linecount) $this->content .= '<ol type="1">';
		
		$id = @fopen($file,"r");
		
		while($data = fread($id, 4096)) $this->html_parse($data);
		
		fclose($id);
		
		if($this->linecount) $this->content .= '</ol>';
		
	}
	
	function html_parse($input){
		
		$end = 1;
		while($end>0){
			$start = strpos($input,"<");
			
			if((strpos($input,"!--")-1)==$start){
				$end = strpos($input,"-->");
				if($end>0){
					$comment = substr($input,$start+4,$end-$start-4);
					$this->content .= ($this->linecount ? '<li>'.$this->html_comment($comment).'</li>' : $this->html_comment($comment) );
					$input = substr($input,$end+3);
				}
			}elseif((strpos($input,"!")-1)==$start){
				$end = strpos($input,">");
				if($end>0){
					$doctype = substr($input,$start+2,$end-$start-2);
					$this->content .= ($this->linecount ? '<li>'.$this->html_doctype($doctype).'</li>' : $this->html_doctype($doctype) );
					$input = substr($input,$end+1);
				}
			}else{
				$end = strpos($input,">");
				if($end>0){
					$tag = trim(substr($input,$start+1,$end-$start-1));
					$this->content .= $this->html_text(substr($input,0,$start));
					$this->content .= ($this->linecount ? '<li>'.$this->htmlparse_parsetag($tag).'</li>' : $this->htmlparse_parsetag($tag) );
					$input = substr($input,$end+1);
				}
			}
		}
		$this->content .= $input;

	}
	
	function htmlparse_parsetag($tag){
		if(((strrpos($tag,"/")+1)==strlen($tag)) and (strlen($tag)>1)) $tag = substr($tag,0,strlen($tag)-1);
		
		if (strpos($tag," ")>0){
			$pos = strpos($tag," ");
			$element = trim(substr($tag,0,$pos));
			$attributes = $this->htmlparse_parseattributes(trim(substr($tag,$pos)));
			$tagasi .= $this->html_start($element,$attributes);
		}else{
			$element = trim($tag);
			if ((strpos($element,"/")===false)){
				$tagasi .= $this->html_start($element);
			}else{
				$tagasi .= $this->html_end(substr($element,1));
			}
		}
		return $tagasi;
	}
	
	function htmlparse_parseattributes($attributes){
		unset($output);
		$attribute = "";
		
		while(strpos($attributes,"=")>0){
			$pos = strpos($attributes,"=");
			$attribute = trim(substr($attributes,0,$pos));
			$attributes = trim(substr($attributes,$pos+1));
			$pos2 = strpos($attributes,"\"");
			$pos3 = strpos($attributes,"'");
			if(!($pos3===false) and !($pos2===false) and ($pos3<$pos2)) $pos2 = $pos3;
			if(!($pos3===false) and ($pos2===false) and (($pos3<$pos) or ($pos==0))) $pos2 = $pos3;
			
			if(!($pos2===false) and (($pos2<$pos) or ($pos==0))){
				if (substr($attributes,0,1) == "\""){
					$pos = strpos($attributes,"\"",1);
					$val = substr($attributes,1,$pos-1);
				}elseif (substr($attributes,0,1) == "'"){
					$pos = strpos($attributes,"'",1);
					$val = substr($attributes,1,$pos-1);
				}else{
					$pos1 = strpos($attributes,"=",1);
					$val = substr($attributes,0,$pos1);
					$pos1a = strrpos($val," ");
					$pos = $pos1-(strlen($val)-$pos1a);
					$val = substr($val,0,$pos1a);
				}
				
				while (strpos($attribute," ")>0){
					$pos1 = strpos($attribute," ");
					$attr1 = substr($attribute,0,$pos1);
					$output[$attr1] = null;
					$attribute = trim(substr($attribute,$pos1+1));
				}
				
				$output[$attribute] = $val;
				$attributes = trim(substr($attributes,$pos+1));
		    
			}elseif ($pos>0){
				if (strpos($attributes,"=")>0){
					$pos = strpos($attributes,"=");
					$val = substr($attributes,0,$pos);
				}else{
					$val = $attributes;
				}
				
				$pos2 = strrpos($val," ");
				if($pos2>0){
					$len = strlen($val);
					$val = substr($val,0,$pos2);
					$attributes = trim(substr($attributes,($pos-$len)+$pos2));
				}else{
					$len = strlen($val);
					$attributes = trim(substr($attributes,$len));
				}
				
				while (strpos($attribute," ")>0){
					$pos1 = strpos($attribute," ");
					$attr1 = substr($attribute,0,$pos1);
					$output[$attr1] = null;
					$attribute = trim(substr($attribute,$pos1+1));
				}
				
				$output[$attribute] = $val;
		    
			}else{
				while (strpos($attribute," ")>0){
					$pos1 = strpos($attribute," ");
					$attr1 = substr($attribute,0,$pos1);
					$output[$attr1] = null;
					$attribute = trim(substr($attribute,$pos1+1));
				}
				$output[$attribute] = $attributes;
			}
		}
	
		if(strlen(trim($attributes))>0){
			while (strpos($attribute," ")>0){
				$pos1 = strpos($attribute," ");
				$attr1 = substr($attribute,0,$pos1);
				$output[$attr1] = null;
				$attribute = trim(substr($attribute,$pos1+1));
			}
		
			$output[$attributes] = null;
			
		}
		
		if (isset($output)) return($output);
	}

		
	function html_start($element,$attributes=FALSE, $t=0){
		
		$tagasi .= '<font color="#0000ff">'.htmlentities('<'.$element);
	
		if(is_array($attributes)){
			while(list($k, $v) = each($attributes)){
				$k = strtolower($k);
				$tagasi .= '<font color="#ff0000"> '.strtolower($k).'</font>';
				if($v!=null){
					$tagasi .= '=';
					$tagasi .= '<font color="#FF00FF">"'.htmlentities($v).'"</font>';
				}
			}
		}
		$tagasi .= htmlentities('>').'</font>';
		return $tagasi;
	}
	
	
	function html_end($element, $t=0)
	{
		return '<font color="#0000ff">'.htmlentities('</').htmlentities($element).htmlentities('>').'</font>';
	}
	
	
	function html_text($data, $t=0)
	{
		return '<font color="#000000">'.nl2br(htmlentities($data)).'</font>';
	}
	
	
	function html_comment($data, $t=0)
	{
		return '<font color="#008000">'.nl2br(htmlentities('<!--'.$data.'-->')).'</font>';
	}
	
	
	function html_doctype($data, $t=0)
	{
		return '<font color="#999999">'.htmlentities('<!'.$data.'>').'</font>';
	}	
	
	
}
?>