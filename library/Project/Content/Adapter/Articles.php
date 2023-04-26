<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Content_Monetized_Articles
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 10.03.2011
 * @version 1.0
 */


/**
 * ARticles контент функционал
 *
 * @category Project
 * @package Project_Content_Monetized_Articles
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */

class Project_Content_Adapter_Articles extends Project_Articles implements Project_Content_Interface {

	protected $_settings=array();
	protected $_counter=false;
	protected $_limit=false;
	private $_tags=array('body'=>'{body}');

	public function setFilter( $_arrFilter=array() ){
		$this->_settings=$_arrFilter;
		$this
			->withCategory($_arrFilter['category_id'])
			->withTags($_arrFilter['tags']);
		return $this;
	}

	public function setCounter( $_intCounter ){
		$this->_counter=$_intCounter;
		return $this;
	}

	public function setLimited( $_intLimited ){
		$this->_limit=$_intLimited;
		return $this;
	}

	public function getFilter( &$arrRes ){
		$arrRes = $this->_settings;
		return !empty( $arrRes );
	}

	public function getList( &$mixRes ){
		parent::getList( $mixRes );
		if( !empty( $this->_settings['template'] ) ){
			$this->prepareBody( $mixRes );
		}
		return $this;
	}

	private function prepareBody( &$mixRes ){
		foreach( $mixRes as &$_item ){
			if( !is_array($_item) ){
				return;
			}
			$_tmpTemplate=$this->_settings['template'];
			$_replace=array_intersect_key( $_item, $this->_tags );
			str_replace( $this->_tags, $_replace, $_tmpTemplate );
			$_item['body']=$_tmpTemplate;
		}
	}
}
?>