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
 * Arhitecture with database replication (master->slave)
 *
 * @category WorkHorse
 * @package Core_Sql
 * @subpackage Arhitecture
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Core_Sql_Arhitecture_Replication extends Core_Sql_Abstract {

	private $dbM, $dbS; // Zend_Db объекты для мастер и слэйв серверов

	public function __construct( Zend_Config $conf ) {
		$this->db_config=$conf;
		$this->getDbConnect( $this->dbM, $this->db_config->master );
		$this->getDbConnect( $this->dbS, $this->db_config->slave );
	}

	public function prepareZendDbObject() {
		// все select на слэйв всё остальное на мастер
		if ( is_null( $this->sqlQuery ) ) {
			$this->db=$this->dbM;
		} else {
			$this->db=preg_match( '/^\s*SELECT/i', $this->sqlQuery ) ? $this->dbS:$this->dbM;
		}
	}

	public function getLastInsertId() {
		return $this->dbM->lastInsertId();
	}

	public function setDisconnect() {
		$this->dbM->closeConnection();
		$this->dbS->closeConnection();
	}
}
?>