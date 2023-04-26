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
 * Абстактный класс для обработчиков входящих/исходящих SMS/звонков
 *
 * @category Project
 * @package Project_Ccs_Twilio
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Abstract {

	/**
	 * Адрес обработчика приложений
	 * @var string
	 */
	public $_urlCallsXML='http://qjmpz.com/services/twilio.php?method=';

	protected static $_tocken='235cbc28c40c70027db6fa49dfa97d97'; // real tocken
	protected static $_sid='ACa8c2b2d13b534902a9e840790371aa48'; // real sid
//	protected static $_tocken='2313f49855176a3094cb43dc0ed2df25'; // test tocken
//	protected static $_sid='AC68c8b4b283446d41cdce0148b1d80a84'; // test sid

	/**
	 * Телефон в привязанный к аккаунту в Twilio
	 * @var string
	 */
	protected static $_phone='+442033227476';
//	protected static $_phone='+12562024613';

	/**
	 * Объект API
	 * @var object|Core_Services_Twilio
	 */
	public  $_client=false;

	protected $_settings=array();

	public function __construct(){
		$this->_client=new Core_Services_Twilio( self::$_sid, self::$_tocken );
	}

	public function setSettings( $_arr ){
		$this->_settings=$_arr;
		return $this;
	}

	public function getCallStatus( &$call ){
		$_call=$this->_client->account->calls->get($call['CallSid']);
		$call['CallStatus']=$_call->status;
		$call['cost']=str_replace('-','',$_call->price);
	}

	public function getSmsStatus( &$sms ){
		$_massage=$this->_client->account->sms_messages->get($sms['SmsSid']);
		$sms['SmsStatus']=$_massage->status;
		$sms['cost']=str_replace('-','',$_massage->price);
	}
}
?>