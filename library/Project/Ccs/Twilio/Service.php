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
 * Входящие сообщения звонки от Twilio
 *
 * @category Project
 * @package Project_Ccs_Twilio
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Service extends Project_Ccs_Twilio_Abstract {


	private function auth(){
		if( empty($this->_settings['From']) ){
			return false;
		}
		$_billings=new Project_Billing();
		$_billings->withPhone( $this->_settings['From'] )->getList( $arrUserBillings );
		if( count( $arrUserBillings )==0 ){
			return false;
		}
		return true;
	}

	/**
	 * Принимает входящие SMS, обрабатывает их и принимает решения что с ними дальше делать
	 * @throws Project_Ccs_Exception
	 */
	public function sms(){
		if(!$this->auth()){
			throw new Project_Ccs_Exception('Can not find user');
		}
		if( empty($this->_settings['SmsSid']) ){
			throw new Project_Ccs_Exception('SMS sid is empty');
		}
		$_message=$this->_client->account->sms_messages->get( $this->_settings['SmsSid'] );
		$_model=new Project_Ccs_Sms();
		$_model->setEntered(array(
			'SmsSid'=>$_message->sid,
			'To'=>$_message->to,
			'From'=>$_message->from,
			'SmsStatus'=>$_message->status,
			'Direction'=>$_message->direction,
			'Body'=>$_message->body,
		))->set();
		if( stripos($_message->body,':') ){
			$_tmp=explode( ':', strtolower($_message->body) );
			$_command=$_tmp[0];
			$_params=$_tmp[1];
		} else {
			$_command=strtolower($_message->body);
		}
		$_client=new Project_Ccs_Twilio_Client();
		$_adapter=new Project_Ccs_Adapter();
		$_arrSettings=array();
		switch( $_command ){
			case 'credits':
				$_client->setSettings(array('body'=>'Available CNM credits:'.Core_Users::$info['amount']))
						->setCalled( Core_Users::$info['id'] )
						->sendSMS();
				break;
			case 'social':
				if( $_adapter->setEntered(array('url'=>$_params))->createSocial() ){
					$_client->setSettings(array('body'=>'Social Media campaign for <'.$_params.'> was created successfully'));
				} else {
					$_client->setSettings(array('body'=>'Sorry, we were not able to create a Social Media campaign for  <'.$_params.'>'));
				}
				$_client->setCalled( Core_Users::$info['id'] )
						->sendSMS();
				break;
			case 'zonterest create+':
				$_arrSettings=array('promotion'=>1,'promoteCount'=>50,'promote_flg_type'=>0);
			case 'zonterest create':
				if( $_adapter->setEntered( array('keyword'=>$_params)+$_arrSettings )->createZonterestSite() ){
					$_client->setSettings(array('body'=>'Zonterest site '.$_adapter->getSiteUrl().' was created successfully'));
				} else {
					$_strError=Core_Data_Errors::getInstance()->getErrorFlowShift();
					if( empty($_strError) ){
						$_strError='Sorry, we were not able to create a Zonterest site for <'.$_params.'> keyword';
					}
					$_client->setSettings(array('body'=>$_strError));
				}
				$_client->setCalled( Core_Users::$info['id'] )
						->sendSMS();
				break;
			case 'nvsb create+':
				$_arrSettings=array('promotion'=>1,'promoteCount'=>50,'promote_flg_type'=>0);
			case 'nvsb create':
				if( $_adapter->setEntered( array('keyword'=>$_params)+$_arrSettings )->createNvsbSite() ){
					$_client->setSettings(array('body'=>'NVSB site '.$_adapter->getSiteUrl().' was created successfully'));
				} else {
					$_strError=Core_Data_Errors::getInstance()->getErrorFlowShift();
					if( empty($_strError) ){
						$_strError='Sorry, we were not able to create a NVSB site for <'.$_params.'> keyword';
					}
					$_client->setSettings(array('body'=>$_strError));
				}
				$_client->setCalled( Core_Users::$info['id'] )
						->sendSMS();
				break;
			case 'ncsb create+':
				$_arrSettings=array('promotion'=>1,'promoteCount'=>50,'promote_flg_type'=>0);
			case 'ncsb create':
				if( $_adapter->setEntered( array('keyword'=>$_params)+$_arrSettings )->createNcsbSite() ){
					$_client->setSettings(array('body'=>'NCSB site '.$_adapter->getSiteUrl().' was created successfully'));
				} else {
					$_strError=Core_Data_Errors::getInstance()->getErrorFlowShift();
					if( empty($_strError) ){
						$_strError='Sorry, we were not able to create a NCSB site for <'.$_params.'> keyword';
					}
					$_client->setSettings(array('body'=>$_strError));
				}
				$_client->setCalled( Core_Users::$info['id'] )
						->sendSMS();
				break;
			default :
				$_client->setSettings(array('body'=>'Code <'.$_command.'> is not recognized'))
						->setCalled( Core_Users::$info['id'] )
						->sendSMS();
				throw new Project_Ccs_Exception( 'Code <'.$_command.'>  is not recognized');
				break;
		}
	}

	public function voice(){
		if(!$this->auth()){
			$_obj=new Project_Ccs_Twilio_Apps();
			$_obj->setSettings( array('app'=>'Menu','action'=>'error') )->run();
			die();
		}
		if( empty($this->_settings['CallSid']) ){
			throw new Project_Ccs_Exception('Call sid is empty');
		}
		$_call=$this->_client->account->calls->get($this->_settings['CallSid']);
		$_model=new Project_Ccs_Voice();
		$_model->setEntered(array(
			'CallSid'=>$_call->sid,
			'To'=>$_call->to,
			'From'=>$_call->from,
			'CallStatus'=>$_call->status,
			'Direction'=>$_call->direction
		))->set();
		$_obj=new Project_Ccs_Twilio_Apps();
		$_obj->setSettings( array('app'=>'Menu','action'=>'menu') )->run();
	}
}
?>