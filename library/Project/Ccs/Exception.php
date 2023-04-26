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
 * Исключения пакета
 *
 * @category Project
 * @package Project_Ccs
 * @copyright Copyright (c) 2013, Web2Innovation
 */
class Project_Ccs_Exception extends Exception {
	
	public function __construct( $_errorMessage='' ){
		parent::__construct();
		echo $_errorMessage;
		die();
	}
}
?>