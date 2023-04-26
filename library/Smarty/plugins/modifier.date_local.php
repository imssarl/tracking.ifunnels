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


function smarty_modifier_date_local( $_strDate, $_strFormat='g:i a n/j/Y' ) {
	if ( empty( $_strDate ) ) {
		return '';
	}
	require_once(SMARTY_PLUGINS_DIR . 'modifier.date_format.php');
	if ( Zend_Registry::get( 'config' )->date_time->dt_user_zone ) {
		return Core_Datetime::getInstance()->to_current( $_strDate, $_strFormat );
	}
	return smarty_modifier_date_format( $_strDate, $_strFormat );
}
?>