<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Sql
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 23.01.2010
 * @version 5.1
 */


/**
 * Sql static interface
 *
 * @category WorkHorse
 * @package Core_Sql
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
final class Core_Sql {

	private static $_instance=NULL;

	//  implements Core_Singleton_Interface TODO!!!
	public static function getInstance( $_needNew=false ) {
		if ( self::$_instance==NULL||$_needNew ) {
			self::createConnect( Zend_Registry::get( 'config' )->database );
		}
		return self::$_instance;
	}

	public static function createConnect( Zend_Config $conf ) {
		switch( $conf->arhitecture ) {
			case 'single': self::$_instance=new Core_Sql_Arhitecture_Single( $conf ); break;
			case 'replication': self::$_instance=new Core_Sql_Arhitecture_Replication( $conf ); break;
		}
	}

	// кэшируем
	private static $_httpHost;
	private static $_config;
	private static $_connect;
	// сохраняем новый коннект в renewalConnectFromCashe
	private static $_prevHost;
	private static $_prevConnect;

	public static function setConnectToServer( $_strServerDomain='' ) {
		if ( $_strServerDomain==self::$_prevHost&&is_object( self::$_prevConnect ) ) { // чтобы не плодить кучу подключений
			self::$_instance=self::$_prevConnect;
			return;
		}
		if ( !empty( $_SERVER['HTTP_HOST'] ) ) {
			self::$_httpHost=$_SERVER['HTTP_HOST']; // кэшируем домен
		}
		$_SERVER['HTTP_HOST']=$_strServerDomain; // устанавливаем требуемый
		if ( Zend_Registry::isRegistered( 'config' ) ) {
			self::$_config=Zend_Registry::get( 'config' ); // кэшируем конфиг
		}
		Zend_Registry::set( 'config', new Zend_Config( require 'config.php' ) ); // устанавливаем новый
		if ( is_object( self::$_instance ) ) {
			self::$_connect=self::$_instance; // кэшируем соединение
		}
		self::getInstance( true ); // устанавливаем соединение
	}

	// метод для восстановления предыдущего значения _instance
	public static function renewalConnectFromCashe() {
		if ( !empty( self::$_httpHost ) ) {
			self::$_prevHost=$_SERVER['HTTP_HOST']; // если потребуется вернутся к новому соединению
			$_SERVER['HTTP_HOST']=self::$_httpHost;
		}
		if ( Zend_Registry::isRegistered( 'config' ) ) {
			Zend_Registry::set( 'config', self::$_config );
		}
		if ( is_object( self::$_connect ) ) {
			self::$_prevConnect=self::$_instance; // если потребуется вернутся к новому соединению
			self::$_instance=self::$_connect;
		}
	}

	// SQLSelect Key from 0 to n Value is Assoc Record
	public static function getAssoc( $strQ='' ) {
		return self::getInstance()->getAssoc( $strQ );
	}

	// SQLSelectOne - FirstRecord
	public static function getRecord( $strQ='' ) {
		return self::getInstance()->getRecord( $strQ );
	}

	// SQLSelectOneField - FirstField
	public static function getField( $strQ='' ) {
		return self::getInstance()->getField( $strQ );
	}

	// SQLSelectKeyValArray - FirstFieldAsKeySecondFieldAsValue
	public static function getKeyVal( $strQ='' ) {
		return self::getInstance()->getKeyVal( $strQ );
	}

	// SQLSelectKeyRec - FirstCellOfRecordAsKeyAssocRecordAsValue
	public static function getKeyRecord( $strQ='' ) {
		return self::getInstance()->getKeyRecord( $strQ );
	}

	// SQLSelectCell - FirstCellOfFirstRecord
	public static function getCell( $strQ='' ) {
		return self::getInstance()->getCell( $strQ );
	}

	// SQLExec
	public static function setExec( $strQ='' ) {
		return self::getInstance()->setExec( $strQ );
	}

	// SQLInsert
	public static function setInsert( $strTbl='', $arrDta=array() ) {
		return self::getInstance()->setInsert( $strTbl, $arrDta );
	}

	// SQLUpdate
	public static function setUpdate( $strTbl='', $arrDta=array(), $strNdx='' ) {
		return self::getInstance()->setInsertUpdate( $strTbl, $arrDta, $strNdx );
	}

	// SQLUpdateInsert
	public static function setUpdateInsert( $strTbl='', $arrDta=array(), $strNdx='' ) {
		return self::getInstance()->setInsertUpdate( $strTbl, $arrDta, $strNdx );
	}

	// SQLInsertUpdate - alias of SQLUpdateInsert
	public static function setInsertUpdate( $strTbl='', $arrDta=array(), $strNdx='' ) {
		return self::getInstance()->setInsertUpdate( $strTbl, $arrDta, $strNdx );
	}

	// SQLInsertMass
	public static function setMassInsert( $strTbl='', $arrDta=array() ) {
		return self::getInstance()->setMassInsert( $strTbl, $arrDta );
	}

	public static function fixInjection( $_mixVar='' ) {
		return self::getInstance()->fixInjection( $_mixVar );
	}

	// Core_Sql::disconnect
	public static function disconnect() {
		if ( self::$_instance!=NULL ) {
			return self::getInstance()->setDisconnect();
		}
	}
}
?>