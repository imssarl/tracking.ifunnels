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
 * Generate uniq code with expired datetime
 * @category framework
 * @package Auxiliary
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2008, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 13.09.2007
 * @version 1.0
 */


class Core_Uniqcodecash extends Core_Services {
	private $type;
	private $interval;

	public function __construct( $_intType=0 ) {
		$this->set_type( $_intType );
		$this->interval=24*60*60; //сутки
	}

	public function set_type( $_intType=0 ) {
		$this->type=0;
	}

	public function set_interval( $_intInterval=0 ) {
		if ( empty( $_intInterval ) ) {
			return;
		}
		$this->interval=$_intInterval;
	}

	public function clear_old_code() {
		Core_Sql::setExec( 'DELETE FROM d_uniqcodecash WHERE flg_type="'.$this->type.'" AND added<"'.(time()-$this->interval).'"' );
	}

	public function get_code( &$strRes ) {
		$i=0;
		$_flg=1;
		do {
			if ( $i>20 ) {
				trigger_error( ERR_PHP.'|can\'t generate uniq code. 20 time try. For d_uniqcodecash table and uniq_code field' );
			}
			$i++;
			$strRes=Core_A::rand_uniqid();
			$_flg=0;
			$_flg=Core_Sql::getCell( 'SELECT 1 FROM d_uniqcodecash WHERE flg_type="'.$this->type.'" AND uniq_code="'.$strRes.'"' );
		} while ( !empty( $_flg ) );
		$this->save_code( $strRes );
		return !empty( $strRes );
	}

	private function save_code( $_strCode ) {
		Core_Sql::setInsert( 'd_uniqcodecash', array(
			'flg_type'=>$this->type,
			'uniq_code'=>$_strCode,
			'added'=>time(),
		) );
	}
}
?>