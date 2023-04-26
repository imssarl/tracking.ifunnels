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
 * Клиент, отвечает за связь с Twilio
 *
 * @category Project
 * @package Project_Ccs_Twilio
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Client extends Project_Ccs_Twilio_Abstract {
	
	private static $_buyerPhone='';
	
	public function setCalled( $_userID ){
		Core_Users::getInstance()->setById( $_userID );
		if( empty(Core_Users::$info) ){
			throw new Project_Ccs_Exception('Can not find user');
		}
		self::$_buyerPhone=Core_Users::$info['buyer_phone'];
		return $this;
	}

	public function setBuyerPhone( $phone='' ){
		self::$_buyerPhone=$phone;
		return $this;
	}
	public function broadcast( $arrData, &$arrErrors ){
		set_time_limit(0);
		ignore_user_abort(true);
		if( $arrData['all']==1 ){
			$_users=new Project_Users_Management();
			$_users
				->withCallInfo()
				->withConfirmPhone()
				->onlyIds()
				->getList( $arrUsers );
		} else {
			$arrUsers=$arrData['users'];
		}
		$arrErrors=array();
		$count=0;
		Core_Users::getInstance()->withCashe();
		foreach( $arrUsers as $_id ){
			if( !$this->setSettings(array('body'=>$arrData['message']))->setCalled( $_id )->sendSMS() ){
				$arrErrors[]=array(
					'message'=>Core_Data_Errors::getInstance()->getErrorFlowShift(),
					'user_id'=>$_id,
					'email'=>Core_Users::$info['email']
				);
				continue;
			}
			$count++;
		}
		Core_Users::getInstance()->retrieveFromCashe();
		return $count;
	}

	/**
	 * Отправить СМС пользователю
	 * @throws Project_Ccs_Exception
	 */
	public function sendSMS(){
		if( empty($this->_settings['body']) ){
			throw new Project_Ccs_Exception('Empty body, can not send SMS');
		}
		try{
			if( self::$_phone != self::$_buyerPhone ){
				$_message = $this->_client->account->sms_messages->create(
					self::$_phone,
					self::$_buyerPhone,
					$this->_settings['body'],
					array()
				);
			}
		} catch( Exception $e ){
			throw new Project_Ccs_Exception( $e->getMessage() );
		}
		$_model=new Project_Ccs_Sms();
		return $_model->setEntered(array(
			'SmsSid'=>$_message->sid,
			'To'=>$_message->to,
			'From'=>$_message->from,
			'SmsStatus'=>$_message->status,
			'Direction'=>$_message->direction,
			'Body'=>$_message->body,
			'user_id'=>$_message->from,
		))->set();
	}

	/**
	 * Позвонить пользователю по поводу создания сайтов
	 *
	 */
	public function createSites(){
		$_call=$this->_client->account->calls->create(
			self::$_phone,
			self::$_buyerPhone,
			Project_Ccs_Twilio_Apps_CreateSites::prepareUrl(array('app'=>'CreateSites','action'=>'createSitesStepOne'))
		);
		$_model=new Project_Ccs_Voice();
		$_model->setEntered(array(
			'CallSid'=>$_call->sid,
			'To'=>$_call->to,
			'From'=>$_call->from,
			'CallStatus'=>$_call->status,
			'Direction'=>$_call->direction
		))->set();
	}

	/**
	 * Позвонить пользователю по поводу подтверждения телефона
	 *
	 */
	public function confirmPhone(){
		try{
			$_call=$this->_client->account->calls->create(
				self::$_phone,
				self::$_buyerPhone,
				Project_Ccs_Twilio_Apps_ConfirmPhone::prepareUrl(array('app'=>'ConfirmPhone','action'=>'start'))
			);
		} catch( Exception $e ){
			if( $e->getCode()==21215 ){
				Core_Data_Errors::getInstance()->setError('We are sorry, but our system does not work with the international phone number you provided.');
			} else {
				Core_Data_Errors::getInstance()->setError( $e->getMessage() );
			}
			return false;
		}
		$_call=$this->_client->account->calls->get($_call->sid);
		$_model=new Project_Ccs_Voice();
		return $_model->setEntered(array(
			'CallSid'=>$_call->sid,
			'To'=>$_call->to,
			'From'=>$_call->from,
			'CallStatus'=>$_call->status,
			'Direction'=>$_call->direction
		))->set();
	}

	public function balance(){
		$_call=$this->_client->account->calls->create(
			self::$_phone,
			self::$_buyerPhone,
			Project_Ccs_Twilio_Apps_ConfirmPhone::prepareUrl(array('app'=>'Balance','action'=>'get'))
		);
		$_call=$this->_client->account->calls->get($_call->sid);
		$_model=new Project_Ccs_Voice();
		return $_model->setEntered(array(
			'CallSid'=>$_call->sid,
			'To'=>$_call->to,
			'From'=>$_call->from,
			'CallStatus'=>$_call->status,
			'Direction'=>$_call->direction
		))->set();
	}
}
?>