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
 * Typical first started frontend module
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class site1 extends Core_Module {

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Frontend', ),
			'actions'=>array(),
		);
	}

	public function before_run_parent() {
		$this->out['arrCurrentGroups']=Core_Acs::getInstance()->getGroupsBySys( Core_Users::$info['groups'] );
	}

	public function after_run_parent() {
		//$this->temporaryUnavailable();
	}

	private function temporaryUnavailable() {
		$this->out['temporaryUnavailable']=true;
		if ( !empty( $_GET['personnel'] )&&$_GET['personnel']=='Rdh4325dhUhfho23ejqfq2fHJEhd32' ) {
			$this->out['temporaryUnavailable']=false;
			$_SESSION['personnel']='Rdh4325dhUhfho23ejqfq2fHJEhd32';
		} elseif ( !empty( $_SESSION['personnel'] )&&$_SESSION['personnel']=='Rdh4325dhUhfho23ejqfq2fHJEhd32' ) {
			$this->out['temporaryUnavailable']=false;
		}
	}

	public function breadcrumb() {}

	public function head() {}
}
?>