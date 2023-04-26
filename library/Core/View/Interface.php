<?php
/**
 * View Control System
 *
 * @category Framework
 * @package Core_View
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2012, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 06.12.2011
 * @version 1.0
 */


/**
 * View interface
 *
 * @category Framework
 * @package Core_View
 * @copyright Copyright (c) 2005-2012, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
interface Core_View_Interface {

	public function setTemplate();
	public function setHash();
	public function parse();
	public function header();
	public function show();
	public function getResult();
}
?>