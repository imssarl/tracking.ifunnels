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
 * Сообщает баланс пользователя(кредиты)
 *
 * @category Project
 * @package Project_Ccs_Twilio_Apps
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Apps_Balance extends Project_Ccs_Twilio_Apps_Abstract {

	/**
	 * Say balance
	 */
	public function get(){
		$this->_response->say( 'You have '.Core_Users::$info['amount'].' credits available in the Creative Niche Manager.', array('voice'=>$this->_voice) );
		$this->_response->say( 'Thank you! Goodbye!',array('voice'=>$this->_voice) );
	}
}
?>