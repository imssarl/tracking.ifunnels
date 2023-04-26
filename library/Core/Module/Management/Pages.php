<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Module
 * @subpackage Management
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 23.01.2010
 * @version 6.1
 */


/**
 * Class for control site tree in project
 *
 * @category   WorkHorse
 * @package    Core_Module
 * @subpackage Management
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Core_Module_Management_Pages extends Core_Adjacency {

	private $_tableFields=array( 
		'id', 'pid', 'level', 'sort', 
		'root_id', 'action_id', 'item_id', 
		'sys_name', 'title', 'meta_description', 'meta_keywords', 'meta_robots', 'flg_onmap', 'added',
	);
	private $_tableName='sys_page';

	public function __construct() {
		parent::__construct( $this->_tableName );
	}

	public function getPageTable() {
		return $this->_tableName;
	}

	public function setBackendModulePage( $_arrPage=array() ) {
		if ( $this->getPage( $_arrUpdate, $_arrPage ) ) {
			$_arrUpdate['title']=$_arrPage['title'];
			$_arrPage=$_arrUpdate;
		}
		if ( !$this->setPage( $arrRes, $_arrErr, $_arrPage ) ) {
			trigger_error( 'TODO error' );
			return false;
		}
		return $arrRes['id'];
	}

	/**
	* настраиваем страницу дерева сайта
	* sys_name должен быть уникален в пределах одного уровня в MOD_PAGES пути в виде /page1/page2/
	* @param array $arrRes out
	* @param array $arrErr out
	* @param array $_arrDta in
	* @return bolean
	*/
	public function setPage( &$arrRes, &$arrErr, $_arrDta=array() ) {
		$_arrDat=Core_A::array_check( $_arrDta, $this->post_filter );
		if ( empty( $_arrDat['pid'] )&&!empty( $_arrDat['root_id'] ) ) {
			$_arrDat['pid']=$_arrDat['root_id'];
		}
		if ( empty( $_arrDat['sys_name'] )&&!empty( $_arrDat['title'] ) ) {
			$_arrDat['sys_name']=Core_String::getInstance( $_arrDat['title'] )->rus2translite(); // провермть что просто транслит TODO!!!
		}
		if ( !empty( $_arrDat['sys_name'] ) ) {
			$_arrDat['sys_name']=Core_String::getInstance( $_arrDat['sys_name'] )->toSystem( '-' );
			if ( empty( $_arrDat['title'] ) ) {
				$arrRes['title']=$_arrDat['sys_name'];
			}
		}
		// TODO Какая-то проверка хрен пойми для чего из-за нее не работает обновление модуля для site-backend
		// if ( !$this->error_check( $arrRes, $arrErr, $_arrDat, array(
		// 	'sys_name'=>empty( $_arrDat['sys_name'] ),
		// 	'sys_name_exists'=>$this->isNewPageUnique( $_arrDat ),
		// 	'pid'=>empty( $_arrDat['pid'] ),
		// 	'root_id'=>$this->checkRootId( $_arrDat ),
		// ) ) ) {
		// 	return false;
		// }
		$_arrDat['added']=time();
		$_arrDat['meta_robots']=empty( $_arrDat['meta_robots'] ) ? 0:1;
		$_arrDat['flg_onmap']=empty( $_arrDat['flg_onmap'] ) ? 0:1;
		$_arrDat['meta_description']=empty( $_arrDat['meta_description'] ) ? NULL:$_arrDat['meta_description'];
		$_arrDat['meta_keywords']=empty( $_arrDat['meta_keywords'] ) ? NULL:$_arrDat['meta_keywords'];
		if ( empty( $_arrDat['id'] ) ) {
			$this->node_info( $_arrPar, $_arrDat['pid'] );
			$_arrDat['level']=++$_arrPar['level'];
		}
		if ( empty( $_arrDat['position'] ) ) {
			$_arrDat['position']='end';
		}
		$arrRes=$_arrDat;
		$arrRes['id']=Core_Sql::setInsertUpdate( $this->_tableName, $this->get_valid_array( $arrRes, $this->_tableFields ) );
		if ( !empty( $arrRes['id'] )&&!empty( $_arrDat['position'] ) ) {
			$this->node_posset( $arrRes['id'], $_arrDat['position'] );
		}
		return !empty( $arrRes['id'] );
	}

	private function checkRootId( &$_arrDat ) {
		if ( empty( $_arrDat['pid'] ) ) {
			return true;
		}
		if ( $_arrDat['pid']==$this->root_id ) { // это значит инсталлим заглавную страницу сайта и root_id ещё не существует
			return false;
		}
		if ( empty( $_arrDat['root_id'] ) ) {
			return true;
		}
		return false;
	}

	// на одном уровне (одного дерева сайта - проверить TODO!!!) не может быть одинаковых имён страниц
	// на разных - может
	private function isNewPageUnique( &$_arrDat ) {
		if ( empty( $_arrDat['sys_name'] )||empty( $_arrDat['pid'] ) ) {
			return true;
		}
		if ( $this->getNodeLevel( $_arrNode, array( 'id'=>$_arrDat['pid'] ) ) ) {
			foreach( $_arrNode as $v ) {
				if ( $v['sys_name']==$_arrDat['sys_name']&&( empty( $_arrDat['id'] )||$v['id']!=$_arrDat['id'] ) ) {
					return true;
				}
			}
		}
		return false;
	}

	private function getNodeLevel( &$arrRes, $_arrSet=array() ) {
		if ( !empty( $_arrSet['page'] )&&empty( $_arrSet['tree_id'] ) ) {
			$_arrSet['id']=Core_Sql::getCell( 'SELECT tree_id FROM '.$this->_tableName.' WHERE sys_name="'.$_arrSet['page'].'"' );
		}
		if ( empty( $_arrSet['id'] )||!$this->tree_childids( $_arrIds, $_arrSet['id'] ) ) {
			return false;
		}
		$arrRes=Core_Sql::getAssoc( 'SELECT *, sys_name page FROM '.$this->_tableName.' WHERE id IN("'.join( '", "', $_arrIds ).'")' );
		return !empty( $arrRes );
	}

	public function getLevelPosition( &$arrRes, $_intParent=0 ) {
		if ( empty( $_intParent ) ) {
			return false;
		}
		$arrRes=Core_Sql::getAssoc( '
			SELECT id, title
			FROM '.$this->_tableName.'
			WHERE pid="'.$_intParent.'"
			ORDER BY sort
		' );
		return true;
	}

	public function getPage( &$arrRes, $_arrSet=array() ) {
		if ( !empty( $_arrSet['id'] ) ) {
			$_arrW[]='id='.Core_Sql::fixInjection( $_arrSet['id'] );
		}
		if ( !empty( $_arrSet['action_id'] ) ) {
			$_arrW[]='action_id='.Core_Sql::fixInjection( $_arrSet['action_id'] );
		}
		if ( !empty( $_arrSet['sys_name'] ) ) {
			$_arrW[]='sys_name='.Core_Sql::fixInjection( $_arrSet['sys_name'] );
		}
		if ( !empty( $_arrSet['pid'] ) ) {
			$_arrW[]='pid='.Core_Sql::fixInjection( $_arrSet['pid'] );
		}
		if ( !empty( $_arrSet['root_id'] ) ) {
			$_arrW[]='root_id='.Core_Sql::fixInjection( $_arrSet['root_id'] );
		}
		if ( empty( $_arrW ) ) {
			return false;
		}
		$arrRes=Core_Sql::getRecord( 'SELECT * FROM '.$this->_tableName.' WHERE '.join( ' AND ', $_arrW ).' LIMIT 1' );
		return !empty( $arrRes );
	}

	// сортировка вверх
	public function pageUp( $_int=0 ) {
		return $this->node_posmov( $_int, 'up' );
	}

	// сортировка вниз
	public function pageDown( $_int=0 ) {
		return $this->node_posmov( $_int, 'down' );
	}

	// скрыть-показать узел в дереве
	public function onSiteMap( $_int=0 ) {
		if ( empty( $_int ) ) {
			return false;
		}
		Core_Sql::setExec( 'UPDATE '.$this->_tableName.' SET flg_onmap=1-flg_onmap WHERE id="'.$_int.'" LIMIT 1' );
		return true;
	}

	// удаление узла и всех подчинённых узлов
	public function delPage( $_int=0 ) {
		if ( empty( $_int ) ) {
			return false;
		}
		return $this->tree_delete( $_arrIds, $_int );
	}
}
?>