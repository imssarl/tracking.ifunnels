<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Ccs_Twilio_Apps
 * @copyright Copyright (c) 2013, Web2Innovation
 * @author Pavel Livinskiy <ikontakts@gmail.com>
 * @date 22.04.2013
 * @version 0.1
 */

/**
 * Приложение для подтверждения номера телефона пользователей
 *
 * @category Project
 * @package Project_Ccs_Twilio_Apps
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Apps_ConfirmPhone extends Project_Ccs_Twilio_Apps_Abstract {

	public function start(){
		$gather=$this->_response->gather( '', array( 'timeout'=>10, 'action'=>self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'choice')) ) );
		$gather->say( 'If you would like to type your PIN code, press 1, if you would like to say it in voice, press 2.', array('voice'=>$this->_voice) );
	}

	public function choice(){
		if( $this->_settings['Digits']==1 ){
			$this->_response->redirect(self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'confirmWithEnter')),array());
		} else {
			$this->_response->redirect(self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'confirmWithRecord')),array());
		}
	}

	/**
	 * Confirm phone number
	 */
	public function confirmWithRecord(){
		$_user=new Project_Users_Management();
		//сбрасываем флаг
		$_user->withIds( Core_Users::$info['id'] )->setFlgPhone(0);
		$this->updateCall(array('confirm'=>Core_Users::$info['code_confirm']));
		$this->_response->say( 'Provide your PIN code at the beep. Press any key when finished.', array('voice'=>$this->_voice) );
		$this->_response->record('',array(
			'transcribe'=>true,
			'timeout'=>30,
			'transcribeCallback'=>self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'transcribe')),
			'action'=>self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'confirmWithRecordTwo'))
		));
	}

	public function confirmWithRecordTwo(){
		$this->_response->say( 'Please wait.', array('voice'=>$this->_voice) );
		$this->_response->enqueue('confirm',array(
			'waitUrl'=>self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'queue'))
		));
	}

	/**
	 * Очередь ожидания результата
	 */
	public function queue(){
		$this->updateCall(array('QueueSid'=>$this->_settings['QueueSid']));
		$this->_response->play('http://com.twilio.sounds.music.s3.amazonaws.com/ClockworkWaltz.mp3');
		// добавить выход из звонка, после первого цикла.
		$this->_response->redirect(self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'queue')),array());
	}

	public function confirmWithRecordSuccess(){
		$this->_response->say( 'Your phone number was confirmed successfully. Thank you! Goodbye!', array('voice'=>$this->_voice) );
	}

	public function confirmWithRecordError(){
		$this->_response->say( 'We are sorry, but your phone number was not confirmed. Please try again later. Goodbye!', array('voice'=>$this->_voice) );
	}

	public function transcribe(){
		if( empty($this->_settings['TranscriptionStatus']) ){
			return false;
		}
		$arrCall=$this->updateCall(array('pin'=>intval($this->_settings['TranscriptionText'])));
		$_client=new Project_Ccs_Twilio_Client();
		$member=$_client->_client->account->queues->get($arrCall['commands']['QueueSid'])->members->get($arrCall['CallSid']);
		$_user=new Project_Users_Management();
		if( empty($arrCall['commands']['pin'])||!$_user->confirmPhone( $arrCall['commands']['pin'] ) ){
			$member->dequeue(self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'confirmWithRecordError')));
			return;
		}
		$member->dequeue(self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'confirmWithRecordSuccess')));
	}

	public function confirmWithEnter(){
		$gather=$this->_response->gather( '', array( 'timeout'=>10, 'action'=>self::prepareUrl(array('app'=>'ConfirmPhone','action'=>'confirmWithEnterTwo')) ) );
		$gather->say( 'Enter your PIN code. Press # when finished.', array('voice'=>$this->_voice) );
	}

	public function confirmWithEnterTwo(){
		$_user=new Project_Users_Management();
		$this->updateCall(array('pin'=>$this->_settings['Digits']));
		$this->updateCall(array('user_id'=>Core_Users::$info['id']));
		if( !empty($this->_settings['Digits'])&&$_user->confirmPhone( $this->_settings['Digits'] ) ){
			$this->_response->say( 'Your phone number was confirmed successfully.',array('voice'=>$this->_voice) );
		} else {
			$this->_response->say( 'We are sorry, but your phone number was not confirmed. Please try again later.', array('voice'=>$this->_voice) );
		}
		$this->_response->say( 'Thank you! Goodbye!',array('voice'=>$this->_voice) );
	}
}
?>