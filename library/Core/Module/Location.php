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
 * Locate & accumulate links history for each project visitor
 *
 * @category   WorkHorse
 * @package    Core_Module
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Core_Module_Location extends Core_Stack {

	private $_module; // текущий модуль

	public function __construct() {
		parent::__construct( 'location' );
	}

	public function initLocation( Core_Module_Interface &$module ) {
		$this->setMaxNest( $module->config->engine->max_back_urls );
		$this->_module=&$module;
	}

	// уникальные переходы
	public function uniq() {
		if ( $this->_module->getViewMode( $_int ) ) { // попапы и сервисы (xml,json) пропускаем
			return false;
		}
		if ( !empty( $this->stack[1] )&&Core_Module_Router::$uriFull==$this->stack[1] ) { // если воспользовались кнопой back, урл откуда пришли затираем
			$this->shift();
			return false;
		}
		if ( !empty( $this->stack[0] )&&Core_Module_Router::$uriFull==$this->stack[0] ) { // f5 неучитываем
			return false;
		}
		$this->push( Core_Module_Router::$uriFull );
		return true;
	}

	// история всех переходов
	public function hist() {
		if ( $this->_module->getViewMode( $_int ) ) { // попапы и сервисы (xml,json) пропускаем
			return false;
		}
		if ( !empty( $this->stack[0] )&&Core_Module_Router::$uriFull==$this->stack[0] ) { // f5 неучитываем
			return false;
		}
		$this->push( Core_Module_Router::$uriFull );
		return true;
	}

	public function get( $_intDepth=1 ) {
		if ( isSet( $this->stack[$_intDepth] ) ) {
			return $this->stack[$_intDepth];
		}
		return Core_Module_Router::$offset;
	}

	public function location( $_mix='', $_flgSkipBack=0 ) {
		if ( !empty( $_flgSkipBack ) ) { // если текущий урл запоминать ненадо
			$this->shift();
		}
		header( 'Location: '.$this->_module->objMR->generateLocationUrl( $this->_module, $_mix ) );
		exit;
	}
}
?>