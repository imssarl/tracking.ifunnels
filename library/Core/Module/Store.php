<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Module
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.04.2009
 * @version 6.0
 */


/**
 * Store variables in session for each action
 *
 * @category   WorkHorse
 * @package    Core_Module
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Core_Module_Store extends Core_Services {

	private $module;
	private $_fromAction='';
	private $_toAction='';

	public function __construct( Core_Module_Interface &$obj ) {
		$this->module=&$obj;
	}

	// with toAction support
	public function set( $_arrDta=array(), $_intFlgClear=true ) {
		if ( empty( $_arrDta ) ) {
			return false;
		}
		if ( $_intFlgClear ) {
			$_strAction=empty( $this->_toAction )? $this->module->params['action']:$this->_toAction;
			$this->module->objUser->u_info['STORE'][$this->module->params['name']][$_strAction]=$_arrDta;
			$this->_toAction='';
			return true;
		} else {
			$this->get( $_arr ); // если есть уже данные то складываем с новыми
			return $this->set( ( $_arrDta+$_arr ) );
		}
	}

	// with toAction support
	public function setIsntClear( $_arrDta=array() ) {
		return $this->set( $_arrDta, false );
	}

	// with fromAction support
	public function get( &$arrRes, $_intFlgClear=false ) {
		if ( empty( $arrRes ) ) {
			$arrRes=array();
		}
		$_strAction=empty( $this->_fromAction )? $this->module->params['action']:$this->_fromAction;
		if ( empty( $this->module->objUser->u_info['STORE'][$this->module->params['name']][$_strAction] ) ) {
			return false;
		}
		$arrRes=$arrRes+$this->module->objUser->u_info['STORE'][$this->module->params['name']][$_strAction];
		if ( $_intFlgClear ) {
			$this->clear();
		}
		$this->_fromAction='';
		return true;
	}

	// with fromAction support
	public function getAndClear( &$arrRes ) {
		return $this->get( $arrRes, true );
	}

	// with fromAction support
	public function clear() {
		$_strAction=empty( $this->_fromAction )? $this->module->params['action']:$this->_fromAction;
		unSet( $this->module->objUser->u_info['STORE'][$this->module->params['name']][$_strAction] );
	}

	// with fromAction support
	public function clearThis( $_arrDta=array() ) {
		if ( empty( $arrRes ) ) {
			$arrRes=array();
		}
		$_strAction=empty( $this->_fromAction )? $this->module->params['action']:$this->_fromAction;
		if ( empty( $this->module->objUser->u_info['STORE'][$this->module->params['name']][$_strAction] ) ) {
			return false;
		}
		foreach( $_arrDta as $v ) {
			unSet( $this->objUser->u_info['STORE'][$this->name][$_strAction][$v] );
		}
		return true;
	}

	// как это выглядит в экшене модуля - $this->objStore->fromAction( <action of current module> )->get( <var> );
	public function fromAction( $_strAction='' ) {
		if ( empty( $_strAction ) ) {
			return $this;
		}
		$this->_fromAction=$_strAction;
		return $this;
	}

	// как это выглядит в экшене модуля - $this->objStore->toAction( <action of current module> )->set( <var> );
	public function toAction( $_strAction='' ) {
		if ( empty( $_strAction ) ) {
			return $this;
		}
		$this->_toAction=$_strAction;
		return $this;
	}
}
?>