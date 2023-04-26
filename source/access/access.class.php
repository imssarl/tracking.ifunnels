<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.04.2009
 * @version 1.0
 */


/**
 * Typical grups&rights management module
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class access extends Core_Module {

	public function before_run_parent() {
		$this->objR=Core_Acs::getInstance();
	}

	function set_cfg() {
		$this->inst_script=array(
			'module'=>array(
				'title'=>'Access right',
			),
			'actions'=>array( 
				array( 'action'=>'groups', 'title'=>'Groups' ),
				array( 'action'=>'rights', 'title'=>'Rights' ),
				array( 'action'=>'rights2group', 'title'=>'Set Rigts2Group' ),
				array( 'action'=>'group2rights', 'title'=>'Set Group2Rights' ),
			),
		);
	}

	function groups() {
		if ( !empty( $_POST['arrR'] ) ) {
			$this->objR->r_setgroups( $this->out['arrR'], $this->out['arrErr'], $_POST['arrR'] );
			$this->location();
		}
		$this->objR->r_get_to_list( $this->out['arrG'] );
	}

	function rights() {
		if ( !empty( $_POST ) ) {
			if ( !empty( $_POST['arrI'] ) ) {
				$this->objR->r_delright( array_keys( $_POST['arrI'] ) );
			}
			if ( !empty( $_POST['arrR'] ) ) {
				$this->objR->r_setrightone( $_POST['arrR'] );
			}
			$this->location();
		}
		$this->objR->r_getrights_to_list_plain( $this->out['arrI'], $this->out['arrPg'], array( 'arrNav'=>array( 
			'url'=>$_GET,
			'numofdigits'=>5,
			'reconpage'=>25,
		) ) );
	}

	function rights2group() {
		if ( !empty( $_POST['change_group'] ) ) {
			$this->location( array( 'w'=>'group_id='.$_POST['arrR']['group_id'] ) );
		} elseif ( !empty( $_POST['arrR'] )&&$this->objR->r_setright2group( $_POST['arrR'] ) ) {
			$this->location();
		}
		$this->objR->r_get_to_select( $this->out['arrG'] );
		$this->objR->r_getrights_to_list( $this->out['arrR'] );
		$this->out['arrM']=Core_Sql::getKeyVal( 'SELECT id, title FROM sys_module' );
		if ( !empty( $_REQUEST['group_id'] ) ) {
			$this->objR->r_getright2group( $this->out['arrL'], $_REQUEST['group_id'] );
		}
	}

	function group2rights() {
		if ( !empty( $_POST['change_right'] ) ) {
			$this->location( array( 'w'=>'right_id='.$_POST['arrG']['right_id'] ) );
		} elseif ( !empty( $_POST['arrG'] )&&$this->objR->r_setgroup2right( $_POST['arrG'] ) ) {
			$this->location();
		}
		$this->objR->r_get_to_list( $this->out['arrG'] );
		$this->objR->r_getrights( $this->out['arrR'], array( 'mode'=>'to_select' ) );
		if ( !empty( $_REQUEST['right_id'] ) ) {
			$this->objR->r_getgroup2right( $this->out['arrL'], $_REQUEST['right_id'] );
		}
	}
}
?>