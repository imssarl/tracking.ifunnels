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
 * Меню для входящих звонков, перенаправляет на нужные действия
 *
 * @category Project
 * @package Project_Ccs_Twilio_Apps
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Apps_Menu extends Project_Ccs_Twilio_Apps_Abstract {

	/**
	 * Say menu
	 */
	public function menu(){
		$gather=$this->_response->gather( '', array( 'timeout'=>10, 'action'=>self::prepareUrl(array('app'=>'Menu','action'=>'choice')) ) );
		$gather->say( 'If you want to create a site, press 1, if you would like to check your balance, press 2.', array('voice'=>$this->_voice) );
	}

	public function choice(){
		$this->updateCall(array('choice'=>$this->_settings['Digits']));
		switch( $this->_settings['Digits']){
			case 1:
				$_obj=new Project_Ccs_Twilio_Apps();
				$_obj->setSettings( array('app'=>'CreateSites','action'=>'createSitesStepOne') )->run();
				break;
			case 2:
				$_obj=new Project_Ccs_Twilio_Apps();
				$_obj->setSettings( array('app'=>'Balance','action'=>'get') )->run();
				break;
			default :
				$this->menu();
				break;
		}
		unset($this->_response);
		die();
	}

	public function  error(){
		$this->_response->say( 'It seems your phone number has not been verified yet. Please complete verification process and try again. Thank you! Goodbye!', array('voice'=>$this->_voice) );
	}

}
?>