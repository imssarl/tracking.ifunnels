<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Ccs
 * @copyright Copyright (c) 2013, Web2Innovation
 * @author Pavel Livinskiy <ikontakts@gmail.com>
 * @date 22.04.2013
 * @version 0.1
 */

/**
 * Связь пакета с функциями системы
 *
 * @category Project
 * @package Project_Ccs
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Adapter {

	/**
	 * @var Core_Data object
	 */
	private $_data=false;

	private $_adapter='';

	/**
	 * Set Data
	 * @param $_arr
	 * @return Project_Ccs_Adapter $this
	 */
	public function setEntered($_arr){
		$this->_data=new Core_Data($_arr);
		return $this;
	}

	public function getSiteUrl(){
		return $this->_adapter->getSiteUrl();
	}

	public function createSocial(){
		if ( !Core_Data_Errors::getInstance()->setData( $this->_data->setFilter('trim','clear') )->setValidators( array(
			'url'=>Core_Data_Errors::getInstance()->getValidator( 'Zend_Validate_NotEmpty' ),
		) )->isValid() ) {
			throw new Project_Ccs_Exception('Incorrect entered data <createSocial>');
		}
		$this->_adapter=new Project_Ccs_Adapter_Synnd();
		return $this->_adapter->setEntered( $this->_data )->run();
	}

	public function createZonterestSite(){
		if ( !Core_Data_Errors::getInstance()->setData( $this->_data->setFilter('trim','clear') )->setValidators( array(
			'keyword'=>Core_Data_Errors::getInstance()->getValidator( 'Zend_Validate_NotEmpty' ),
		) )->isValid() ) {
			throw new Project_Ccs_Exception('Incorrect entered data <createZonterestSite>');
		}
		$this->_adapter=new Project_Ccs_Adapter_Zonterest();
		return $this->_adapter->setEntered( $this->_data )->run();
	}

	public function createNcsbSite(){
		if ( !Core_Data_Errors::getInstance()->setData( $this->_data->setFilter('trim','clear') )->setValidators( array(
			'keyword'=>Core_Data_Errors::getInstance()->getValidator( 'Zend_Validate_NotEmpty' ),
		) )->isValid() ) {
			throw new Project_Ccs_Exception('Incorrect entered data <createNcsbSite>');
		}
		$this->_adapter=new Project_Ccs_Adapter_Ncsb();
		return $this->_adapter->setEntered( $this->_data )->run();
	}

	public function createNvsbSite(){
		if ( !Core_Data_Errors::getInstance()->setData( $this->_data->setFilter('trim','clear') )->setValidators( array(
			'keyword'=>Core_Data_Errors::getInstance()->getValidator( 'Zend_Validate_NotEmpty' ),
		) )->isValid() ) {
			throw new Project_Ccs_Exception('Incorrect entered data <createNvsbSite>');
		}
		$this->_adapter=new Project_Ccs_Adapter_Nvsb();
		return $this->_adapter->setEntered( $this->_data )->run();
	}
}
?>