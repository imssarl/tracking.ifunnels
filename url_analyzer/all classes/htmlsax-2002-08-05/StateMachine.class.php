<?
/*
	(c) Copyright by Alexander Zhukov alex@veresk.ru. Released under LGPL
*/	

class StateMachine
{	
	
	function set($name)
	{	
		$this->_a_state[$name] = true;
	}
	function state($name)
	{
		return isset($this->_a_state[$name]);
	}
	
	function reset($name)
	{
		unset($this->_a_state[$name]);
	}
	function resetAll() 
	{
		$this->_a_state = Array();
	}
}	
?>