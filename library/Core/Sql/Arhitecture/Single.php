<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Sql
 * @subpackage Arhitecture
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.04.2009
 * @version 5.0
 */


/**
 * Single database arhitecture
 *
 * @category WorkHorse
 * @package Core_Sql
 * @subpackage Arhitecture
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Core_Sql_Arhitecture_Single extends Core_Sql_Abstract {

	public function __construct( Zend_Config $conf ) {
		$this->db_config=$conf;
		$this->getDbConnect( $this->db, $this->db_config->master );
	}

	public function prepareZendDbObject() {}

	public function getLastInsertId() {
		return $this->db->lastInsertId();
	}

	public function setDisconnect() {
		$this->db->closeConnection();
	}
}
?>