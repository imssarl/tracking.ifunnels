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
 * Приложение для создания сайтов
 *
 * @category Project
 * @package Project_Ccs_Twilio_Apps
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Twilio_Apps_CreateSites extends Project_Ccs_Twilio_Apps_Abstract {

	/**
	 * Create site step 1
	 */
	public function createSitesStepOne(){
		$gather=$this->_response->gather( '', array( 'timeout'=>10, 'action'=>self::prepareUrl(array('app'=>'CreateSites','action'=>'createSocialMedia')) ) );
		$gather->say( 'If you would like to create a Zonterest website, press 1, for Niche Content website, press 2, for Niche Video website, press 3.', array('voice'=>$this->_voice) );
	}
	/**
	 * Create site step 2
	 */
	public function createSocialMedia(){
		$this->updateCall(array('type'=>$this->_settings['Digits']));
		$gather=$this->_response->gather( '', array( 'timeout'=>10, 'action'=>self::prepareUrl(array('app'=>'CreateSites','action'=>'createSitesStepTwo')) ) );
		$gather->say( 'If you would like to have a Social Media campaign created for your new website, press 1, if not, press 2.', array('voice'=>$this->_voice) );
	}

	/**
	 * Create site step 3
	 */
	public function createSitesStepTwo(){
		$this->updateCall(array('social'=>$this->_settings['Digits']));
		$this->_response->say( 'Provide a keyword that should be used to build your website at the beep. Press any key when finished.', array('voice'=>$this->_voice) );
		$this->_response->record('',array(
			'transcribe'=>true,
			'timeout'=>30,
			'transcribeCallback'=>self::prepareUrl(array('app'=>'CreateSites','action'=>'start')),
			'action'=>self::prepareUrl(array('app'=>'CreateSites','action'=>'complete'))
		));
	}

	/**
	 * Start create site
	 */
	public function start(){
		if(empty($this->_settings['TranscriptionStatus'])){
			return false;
		}
		$_client=new Project_Ccs_Twilio_Client();
		$arrCall=$this->updateCall(array('keyword'=>$this->_settings['TranscriptionText']));
		if( $this->_settings['TranscriptionStatus']=='failed' ){
			$_client->setSettings(array('body'=>'Keyword Transcription Status is failed'))->setCalled( Core_Users::$info['id'] )->sendSMS();
			throw new Project_Ccs_Exception('TranscriptionStatus is failed');
		}
		if( $this->_settings['TranscriptionText']=='(blank)' ){
			$_client->setSettings(array('body'=>'We can not your keyword transcript'))->setCalled( Core_Users::$info['id'] )->sendSMS();
			throw new Project_Ccs_Exception('Transcription is (blank)');
		}
		$_adapter=new Project_Ccs_Adapter();
		$_arrSettings=array();
		switch( $arrCall['commands']['type'] ){
			case 1:
				if( $arrCall['commands']['social']==1 ){
					$_arrSettings=array('promotion'=>1,'promoteCount'=>50,'promote_flg_type'=>0);
				}
				if( $_adapter->setEntered(array('keyword'=>$arrCall['commands']['keyword'])+$_arrSettings)->createZonterestSite() ){
					$_client->setSettings(array('body'=>'Zonterest site '.$_adapter->getSiteUrl().' was created successfully'));
				} else {
					$_strError=Core_Data_Errors::getInstance()->getErrorFlowShift();
					if( empty($_strError) ){
						$_strError='Sorry, we were not able to create a Zonterest site for <'.$arrCall['commands']['keyword'].'> keyword';
					}
					$_client->setSettings(array('body'=>$_strError));
				}
				break;
			case 2:
				if( $arrCall['commands']['social']==1 ){
					$_arrSettings=array('promotion'=>1,'promoteCount'=>50,'promote_flg_type'=>0);
				}
				if( $_adapter->setEntered(array('keyword'=>$arrCall['commands']['keyword'])+$_arrSettings)->createNcsbSite() ){
					$_client->setSettings(array('body'=>'NCSB site '.$_adapter->getSiteUrl().' was created successfully'));
				} else {
					$_strError=Core_Data_Errors::getInstance()->getErrorFlowShift();
					if( empty($_strError) ){
						$_strError='Sorry, we were not able to create a NCSB site for <'.$arrCall['commands']['keyword'].'> keyword';
					}
					$_client->setSettings(array('body'=>$_strError));
				}
				break;
			case 3:
				if( $arrCall['commands']['social']==1 ){
					$_arrSettings=array('promotion'=>1,'promoteCount'=>50,'promote_flg_type'=>0);
				}
				if( $_adapter->setEntered(array('keyword'=>$arrCall['commands']['keyword'])+$_arrSettings)->createNvsbSite() ){
					$_client->setSettings(array('body'=>'NVSB site '.$_adapter->getSiteUrl().' was created successfully'));
				} else {
					$_strError=Core_Data_Errors::getInstance()->getErrorFlowShift();
					if( empty($_strError) ){
						$_strError='Sorry, we were not able to create a NVSB site for <'.$arrCall['commands']['keyword'].'> keyword';
					}
					$_client->setSettings(array('body'=>$_strError));
				}
				break;
			default:
				$_client->setSettings(array('body'=>'Code <'.$arrCall['commands']['type'].'> is not recognized'));
				break;
		}
		$_client->setCalled( Core_Users::$info['id'] )->sendSMS();

	}

	/**
	 * Complete call
	 */
	public function complete(){
		$this->_response->say( 'Thank you! Goodbye!',array('voice'=>$this->_voice) );
	}
}
?>