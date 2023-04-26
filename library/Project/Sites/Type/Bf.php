<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Sites
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 18.03.2010
 * @version 0.1
 */

 /**
 * Wpress (BlogFusion) сайты
 * объединить с Project_Wpress TODO!!! 13.10.2010
 *
 * @category Project
 * @package Project_Sites
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Sites_Type_Bf extends Project_Sites_Type_Abstract {
	protected $_table='bf_blogs';

	public function set( Project_Sites $object ) {}
	public function get( &$arrRes, $_arrSite=array() ) {}
	public function del( $_arrIds ) {}
	public function prepareSource() {}
	public function import( Project_Sites $object ) {}
}
?>