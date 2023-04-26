<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Publisher_Adapter
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.03.2011
 * @version 1.0
 */


/**
 * Factory for site adapters
 *
 * @category Project
 * @package Project_Publisher_Adapter_Factory
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */

class Project_Publisher_Adapter_Factory {

	/**
	 * Get site adapter
	 * @static
	 * @param  $intType
	 * @return bool|object
	 */
	public static function get( $intType ){
		if( empty($intType) ){
			return false;
		}
		switch( $intType ){
			case Project_Sites::BF:		return Project_Publisher_Adapter_Blogfusion::getInstance(); break;
			case Project_Sites::CNB:	return Project_Publisher_Adapter_Cnb::getInstance(); break;
			case Project_Sites::PSB:	return Project_Publisher_Adapter_Psb::getInstance(); break;
			case Project_Sites::NCSB:	return Project_Publisher_Adapter_Ncsb::getInstance(); break;
			case Project_Sites::NVSB:	return Project_Publisher_Adapter_Nvsb::getInstance(); break;
			default:
				return false; break;
		}
	}
}
?>