<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Sql
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.04.2009
 * @version 5.0
 */


/**
 * Abstract class for use Zend_Db connectors and make common operations
 *
 * @category WorkHorse
 * @package Core_Sql
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
abstract class Core_Sql_Abstract {

	public $db_config;
	public $db=null;
	public $sqlQuery=null;
	private $sqlResult=null;

	public function getDbConnect( &$db, Zend_Config $_db ) {
		$db=Zend_Db::factory( $_db->adapter, $_db->toArray() );
		$db->query( 'SET NAMES '.$this->db_config->codepage );
	}

	abstract public function prepareZendDbObject();

	private function setInit( $strQ='' ) {
		$this->sqlQuery=empty( $strQ )?null:$strQ;
		$this->sqlResult=null;
		$this->prepareZendDbObject();
	}

	public function getAssoc( $strQ='' ) {
		$this->setInit( $strQ );
		return $this->db->fetchAll( $this->sqlQuery );
	}

	public function getRecord( $strQ='' ) {
		$this->setInit( $strQ );
		return $this->db->fetchRow( $this->sqlQuery );
	}

	public function getField( $strQ='' ) {
		$this->setInit( $strQ );
		return $this->db->fetchCol( $this->sqlQuery );
	}

	public function getKeyVal( $strQ='' ) {
		$this->setInit( $strQ );
		return $this->db->fetchPairs( $this->sqlQuery );
	}

	public function getKeyRecord( $strQ='' ) {
		$this->setInit( $strQ );
		$_arrRes=$this->db->fetchAll( $strQ );
		if ( empty( $_arrRes ) ) {
			return array();
		}
		foreach( $_arrRes as $v ) {
			$_arrFirst=each( $v );
			$arrRes[$_arrFirst['value']]=$v;
		}
		return $arrRes;
	}

	public function getCell( $strQ='' ) {
		$this->setInit( $strQ );
		return $this->db->fetchOne( $this->sqlQuery );
	}

	public function setExec( $strQ='' ) {
		$this->setInit( $strQ );
		return $this->db->query( $this->sqlQuery );
	}

	public function setInsert( $strTbl, $arrDta ) {
		$this->setInit();
		$this->db->insert( $strTbl, $arrDta );
		return $this->db->lastInsertId();
	}

	public function setInsertUpdate( $strTbl, $arrDta, $strNdx ) {
		$this->setInit();
		if ( empty( $strNdx ) ) {
			$strNdx='id';
		}
		if ( isSet( $arrDta[$strNdx] ) ) {
			$this->db->update( $strTbl, $arrDta, $this->db->quoteIdentifier( $strNdx, true ).'='.$this->db->quote( $arrDta[$strNdx] ) );
			return $arrDta[$strNdx];
		} else {
			$this->db->insert( $strTbl, $arrDta );
			return $this->db->lastInsertId();
		}
	}

	public function setMassInsert( $_strTable, &$_arrDta ) {
		$this->setInit();
		foreach( $_arrDta as $k=>$v ) {
			if ( empty( $_arrFields ) ) {
				$_arrFields=array_keys( $v );
				foreach( $_arrFields as $k2=>$v2 ) {
					$_arrFields[$k2]=$this->db->quoteIdentifier( $v2, true );
				}
			}
			foreach( $v as $k3=>$v3 ) {
				$v[$k3]=$this->db->quote( $v3 );
			}
			$_arrParts[]='('.implode( ', ', $v ).')';
		}
		$sql='INSERT INTO '
			.$this->db->quoteIdentifier( $_strTable, true )
			.' ('.implode( ', ', $_arrFields ).') ' 
			.' VALUES '.implode( ', ', $_arrParts );
		$this->db->beginTransaction(); // Старт транзакции явным образом
		try {
			$this->db->query( 'LOCK TABLES '.$this->db->quoteIdentifier( $_strTable, true ).' WRITE' );
			$stmt = $this->db->query( $sql );
			$this->db->query( 'UNLOCK TABLES' );
			$this->db->commit(); // закрепить
		} catch (Exception $e) {
			$this->db->rollBack(); // откатить
			trigger_error( ERR_MYSQL.'|'.$e->getMessage().'<br>'.$sql );
		}
		$result = $stmt->rowCount();
		return $result;
	}

	public function fixInjection( $_mixVar='' ) {
		return $this->db->quote( $_mixVar );
	}
}
?>