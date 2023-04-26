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
 * Services for others classes which use Core_Module class
 *
 * @category   WorkHorse
 * @package    Core_Module
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
interface Core_Language_Interface {

	public function getTable();

	public function getFieldsForTranslate();

	public function getDefaultLang();

	// должен возвращать настроенный объект Core_Language
	public function getLng();

	// взывается в setImplant для подмешивания в результат нужного языка
	public function &getResult();
}
?>