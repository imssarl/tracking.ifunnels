<?php
/**
 * Smarty additional
 * @category framework
 * @package SmartyAdditional
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 11.10.2010
 * @version 2.0
 */


function smarty_function_img( $_arrPrm, &$objS ) {
	$_obj=new Core_Media_Driver();
	return $_obj->getThumbnail( @$_arrPrm['src'], @$_arrPrm['w'], @$_arrPrm['h'] );
}
?>