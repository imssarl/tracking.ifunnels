<?php
/**
 * CNM
 *
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 26.06.2009
 * @version 1.0
 */


/**
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class site1_sbookmarking extends Core_Module {

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Social Bookmarking', ),
			'actions'=>array(
				array( 'action'=>'gadget', 'title'=>'Web gadget', 'flg_tree'=>1 ),
			),
		);
	}

	public function gadget() {}
}
?>