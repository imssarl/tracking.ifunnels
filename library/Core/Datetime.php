<?php
/**
 * Auxiliary classes
 * @category framework
 * @package Auxiliary
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 20.11.2008
 * @version 1.0
 */


/**
 * Date Time Timezone Interface
 * @internal работа с датами, конвертация дат только для php>5.2
 * @category framework
 * @package Auxiliary
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 26.05.2008
 * @version 1.5
 */


class Core_Datetime implements Core_Singleton_Interface {

	private static $_instance=NULL;

	private $_offset=0;
	private $_format='g:i a n/j/Y';
	private $_user_timezone='';
	private $_def_timezone='';

	public function __construct( $_strFormat='' ) {
		$this->set_default_format( $_strFormat );
	}

	public function set_default_format( $_strFormat='' ) {
		if ( empty( $_strFormat ) ) {
			$this->_format=Zend_Registry::get( 'config' )->date_time->dt_full_format;
		} else {
			$this->_format=$_strFormat;
		}
	}

	public function set_default_timezone( $_str='GMT' ) {
		if ( function_exists( 'date_default_timezone_set' ) ) {
			$_bool=date_default_timezone_set( $_str );
		} else {
			$_bool=putenv( 'TZ='.$_str );
		}
		if ( $_bool!==true ) {
			trigger_error( ERR_PHP.'|Timezone ('.$_str.') is not a known timezone' );
		}
		$this->_def_timezone=$_str;
	}

	public function get_default_timezone( &$_strRes ) {
		if ( function_exists( 'date_default_timezone_get' ) ) {
			$_strRes=date_default_timezone_get();
		} else {
			$_strRes=getenv( 'TZ' );
		}
	}

	public function get_gmt_offset() {
		if ( function_exists( 'date_offset_get' ) ) {
			$_objDtz=new DateTimeZone( $this->get_default_timezone() );
			$_objDt=new DateTime( 'now' );
			return $_objDtz->getOffset( $_objDt );
		} else {
			return ( mktime(0, 0, 0, 1, 2, 1970) - gmmktime(0, 0, 0, 1, 2, 1970) );
		}
	}

	public function set_user_byid( $_intId=0 ) {
		$this->_user_timezone='';
		$_obj=Zend_Registry::get( 'objUser' );
		if ( $_obj->get_user( $_arr, $_intId ) ) {
			$this->_user_timezone=$_arr['timezone'];
		}
		return !empty( $this->_user_timezone );
	}

	private function get_current_user_timezone() {
		if ( !empty( $this->_user_timezone ) ) {
			return $this->_user_timezone;
		}
		if ( Zend_Registry::isRegistered( 'objUser' ) ) {
			$obj=Zend_Registry::get( 'objUser' );
			return $obj->u_info['timezone'];
		}
		return $this->_def_timezone;
	}

	public function to_gmt( $_strDate='now', $_strFormat='' ) {
		return $this->to_timezone_from_timezone( $this->get_current_user_timezone(), 'GMT', $_strDate, $_strFormat );
	}

	public function to_current( $_strDate='now', $_strFormat='' ) {
		return $this->to_timezone_from_timezone( 'GMT', $this->get_current_user_timezone(), $_strDate, $_strFormat );
	}

	public function to_timezone_from_timezone( $_strFrom='GMT', $_strTo='GMT', $_strDate='now', $_strFormat='' ) {
		if ( is_numeric( $_strDate ) ) { // если дата в секундах от начала эпохи Unix
			$_strDate='@'.$_strDate;
		}
		$_objDt=new DateTime( $_strDate, new DateTimeZone( $_strFrom ) );
		$_objDt->setTimeZone( new DateTimeZone( $_strTo ) );
		$_strFormat=empty( $_strFormat )? $this->_format:$_strFormat;
		return $_objDt->format( $_strFormat );
	}

	public function get_timezone_to_select( &$arrRes ) {
		$_arr=timezone_identifiers_list();
		$arrRes=array_combine( $_arr, $_arr );
	}

	public static function getInstance() {
		if ( self::$_instance==NULL ) {
			self::$_instance=new Core_Datetime();
		}
		return self::$_instance;
	}
}
?>