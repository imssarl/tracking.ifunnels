<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Ccs_Twilio
 * @copyright Copyright (c) 2013, Web2Innovation
 * @author Pavel Livinskiy <ikontakts@gmail.com>
 * @date 22.04.2013
 * @version 0.1
 */

/**
 * Адаптер для приложений
 *
 * @category Project
 * @package Project_Ccs_Twilio
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Apps {

	private $_settings=array();
	public static $appUrl='http://qjmpz.com/services/twilio.php?app=#app#&action=#action#';

	public function setSettings( $_arrSettings ){
		if( empty($_arrSettings) ){
			throw new Project_Ccs_Exception('Empty data');
		}
		$this->_settings=$_arrSettings;
		return $this;
	}

	public function run(){
		if( empty($this->_settings['action']) ){
			throw new Project_Ccs_Exception('Incorrect entered data');
		}
		$_class='Project_Ccs_Twilio_Apps_'.$this->_settings['app'];
		$_method=$this->_settings['action'];
		new $_class($_method,$this->_settings);
	}
}
?>