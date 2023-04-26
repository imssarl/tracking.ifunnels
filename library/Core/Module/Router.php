<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Module
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 19.05.2010
 * @version 6.2
 */


/**
 * Route link for system through site tree
 *
 * @category   WorkHorse
 * @package    Core_Module
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Core_Module_Router extends Core_Services {

	public static $uriFull; // $_SERVER['REQUEST_URI']
	public static $uriVar; // $_SERVER['REQUEST_URI'] до знака '?'
	public static $curSiteName; // sys_site.sys_name
	public static $offset; // часть которая идёт вначале uri и не принедлежит дереву сайта (например языки или название бэкэнд)
	public static $domain; // $_SERVER['HTTP_HOST']

	private $_currentNode=array(); // данные текущего узла ссылки и парсинг ссылки
	private $_globalPrams=array(); // данные полученнце из дерева и ссылки (доступны всем запускаемым модулям)
	private $_localPrams=array(); // данные установленные для модулей запущенных ручками
	private $_objCMMS;

	public $backend=array();
	public $frontend=array();
	public $frontends=array();
	public $isBackend=false;

	private $_currentTree=array(); // данные дерева текущего сайта
	private $_currentUrls=array(); // прямые ссылки
	private $_currentUris=array(); // обратные ссылки
	private $_currentByIds=array(); // key массива является id

	public $curPathDirect=array(); // разбитый на надо путь от начала до конца
	public $curPathReverse=array(); // разбитый на надо путь от конца до начала

	public function __construct() {}

	public function initSite( &$objCMMS ) {
		if ( !empty( $this->_currentTree ) ) {
			return true;
		}
		$this->_objCMMS=&$objCMMS;
		$this->initCurrentSites();
		$this->setRegistryConstant();
	}

	private function initCurrentSites() {
		if ( !$this->_objCMMS->getSites( $_arrSites ) ) {
			trigger_error( 'sites not installed' );
		}
		foreach( $_arrSites as $v ) {
			if ( !empty( $v['flg_type'] ) ) {
				$this->backend=$v;
				continue;
			}
			$this->frontends[]=$v;
			if ( $this->isCurrentFrontend( $v ) ) {
				$this->frontend=$v;
			}
		}
	}

	private function isCurrentFrontend( &$_arrSite ) {
		if ( !empty( $this->_objCMMS->config->engine->current_domain )&&$_arrSite['sys_name']==$this->_objCMMS->config->engine->current_domain ) {
			return true;
		}
		// тут определенин текущего фронтэнда на основе поля domain TODO!!!
	}

	private function setRegistryConstant() {
		$_strUrl=urldecode( $_SERVER['REQUEST_URI'] );
		$_intPos=mb_strpos( $_strUrl, '?' );
		$_strUrlWV=( is_integer( $_intPos )? mb_substr( $_strUrl, 0, $_intPos):$_strUrl );
		if ( mb_substr( $_strUrlWV, -1 )!='/' ) { // если в конце чпу не поставили слэш - исправляем
			$_strUrlWV.='/';
			if ( is_integer( $_intPos ) ) { // тут надо прочекать
				$_strUrl=str_replace( '?', '/?', $_strUrl );
			}
		}
		self::$domain=$_SERVER['HTTP_HOST'];
		self::$uriFull=$_strUrl;
		self::$uriVar=$_strUrlWV;
		self::$curSiteName=($this->isBackend( $_strUrl )?$this->backend['sys_name']:$this->frontend['sys_name']);
		self::$offset=$this->getBaseOffset();
	}

	private function isBackend( $_str='' ) {
		$this->isBackend=mb_substr( $_str, 0, mb_strlen( $this->backend['domain'] ) )==$this->backend['domain'];
		return $this->isBackend;
	}

	// префиксы для ссылок. надо сделать по человечески + добавить опрделение языка TODO!!! 14.04.2009
	private function getBaseOffset() {
		if ( $this->isBackend ) {
			return '/'.$this->backend['sys_name'].'/';
		}
		return '/';
	}

	private function initPath( ) {
		$this->getTree( array(
			'node_id'=>($this->isBackend?$this->backend['root_id']:$this->frontend['root_id']),
			'with_root_node'=>true,
			'offset'=>self::$offset,
			'result'=>array( 
				'MOD_TREE'=>&$this->_currentTree, 
				'MOD_URLS'=>&$this->_currentUrls, 
				'MOD_URIS'=>&$this->_currentUris, 
				'MOD_BYIDS'=>&$this->_currentByIds, 
			),
		) );
		$this->parsePath( self::$uriVar );
		$this->getCurrentBackendsFrontend(); // TODO!!! 16.04.2009
	}

	private function parsePath( $_strPath='' ) {
		if ( empty( $this->_currentUris )||empty( $_strPath ) ) {
			return false;
		}
		if ( $_strPath=='/' ) {
			$this->curPathDirect=$this->curPathReverse=array( $this->_currentUris[$_strPath] );
			return true;
		}
		$this->curPathDirect=explode( '/', substr( substr( $_strPath, 1 ), 0, -1 ) );
		$_strUrl='/';
		foreach( $this->curPathDirect as $k=>$v ) {
			$_strUrl.=$v.'/';
			if ( !empty( $this->_currentUris[$_strUrl] ) ) {
				$this->curPathDirect[$k]=$this->_currentUris[$_strUrl];
			} else { // site_backend в дереве нет например TODO!!! 16.04.2009
				unSet( $this->curPathDirect[$k] );
			}
		}
		$this->curPathReverse=array_reverse( $this->curPathDirect );
		return !empty( $this->curPathReverse );
	}

	public function getCurrentTree() {
		return $this->_currentTree;
	}

	public function getTreeWithFilter( $_arrSetting ) {
		$this->getTree( $_arrSetting );
	}

	// раз такое дело ( $_arrSetting ) так может сделать его общедоступным в модулях ???
	private function getTree( $_arrSetting=array() ) {
		$_arrNodes=Core_Sql::getAssoc( '
			SELECT p.*, a.action, a.flg_tpl, m.name
			FROM '.$this->_objCMMS->getPageTable().' p
			LEFT JOIN sys_action a ON a.id=p.action_id
			LEFT JOIN sys_module m ON m.id=a.module_id
			WHERE p.root_id="'.$_arrSetting['node_id'].'"'.(empty( $_arrSetting['flg_onmap'] )? '':' AND p.flg_onmap=1').'
			ORDER BY p.level, p.sort
		' );
		$this->makeTree( $_arrNodes, $_arrSetting );
	}

	private function makeTree( &$arrNodes, $_arrSetting ) {
		$_arrRes=array();
		$k=0;
		foreach( $arrNodes as $v ) {
			if ( !empty( $_arrSetting['with_root_node'] )&&$v['id']==$_arrSetting['node_id'] ) { // корень дерева создаётся при инсталляции сайтов
				unSet( $_arrSetting['with_root_node'] );
				$_arrRes[$k]=array( 
					'sys_name'=>$_arrSetting['offset'], 
					'level'=>--$v['level'] )+$v;
				foreach( $_arrSetting['result'] as $need=>$tmp ) {
					switch( $need ) {
						case 'MOD_URIS': $_arrSetting['result']['MOD_URIS'][$_arrSetting['offset']]=$_arrRes[$k]; break; // backward
						case 'MOD_BYIDS': $_arrSetting['result']['MOD_BYIDS'][$v['id']]=$_arrRes[$k]; break; // by ids
					}
				}
				$this->makeTree( $arrNodes, $_arrSetting );
				if ( isSet( $_arrSetting['result']['MOD_TREE'] ) ) {
					$_arrRes[$k]['node']=$_arrSetting['result']['MOD_TREE'];
					$_arrSetting['result']['MOD_TREE']=$_arrRes;
				}
				return;
			}
			if ( $v['pid']!=$_arrSetting['node_id'] ) { // пропускаем ноды с другими pid
				continue;
			}
			$_arrRes[$k]=array( 
				'page'=>$v['sys_name'], 
				'sys_name'=>$_arrSetting['offset'].$v['sys_name'].'/', // наращиваем ссылку
				'level'=>--$v['level'] )+$v;
			foreach( $_arrSetting['result'] as $need=>$tmp ) {
				switch( $need ) {
					case 'MOD_URLS': $_arrSetting['result']['MOD_URLS'][$v['name']]['actions'][$v['action']]=$_arrRes[$k]; break; // direct
					case 'MOD_URIS': $_arrSetting['result']['MOD_URIS'][$_arrRes[$k]['sys_name']]=$_arrRes[$k]; break; // backward
					case 'MOD_BYIDS': $_arrSetting['result']['MOD_BYIDS'][$v['id']]=$_arrRes[$k]; break; // by ids
				}
			}
			$this->makeTree( $arrNodes, array( 'node_id'=>$v['id'], 'offset'=>$_arrRes[$k]['sys_name'] )+$_arrSetting );
			if ( isSet( $_arrSetting['result']['MOD_TREE'] ) ) {
				$_arrRes[$k]['node']=$_arrSetting['result']['MOD_TREE'];
			}
			$k++;
		}
		if ( isSet( $_arrSetting['result']['MOD_TREE'] ) ) {
			$_arrSetting['result']['MOD_TREE']=$_arrRes;
		}
	}

	// это делаем только раз - проверить initPath
	public function setGlobalParams( Core_Module_Interface &$module ) {
		$this->initPath();
		if ( !empty( $_REQUEST['new_frontend'] ) ) { // при переключении фронтэнда для редактирования в админке убираем переключающую переменную
			$module->objML->location( self::$uriVar, 'skip' );
		}
		if ( !$this->findNode() ) {
			$module->objML->location( self::$offset );
		}
		if ( self::$uriVar!=self::$offset&!$this->isBackend&empty( $this->_currentNode['flg_onmap'] ) ) { // страница скрыта с фронтэнда
			$module->objML->location( $module->objML->get() ); // возвращаем назад
		}
		$this->_globalPrams=$this->_currentNode; // _globalPrams понадобится в $module->objML->uniq()
		$module->objML->uniq(); // записываем уникальные линки в history
		// нету прав на экшен у текущего пользователя
		// self::$uriVar!=self::$offset можно к главному модулю привязывать экшн
		if ( self::$uriVar!=self::$offset&!empty( $this->_currentNode )&!Core_Users::haveActionAccess( $this->_currentNode ) ) {
			$this->_globalPrams=array(); // нет прав
			$module->objML->location( $module->objML->get() ); // возвращаем назад
		}
	}

	// поидее это надо делать где-то в $this->parsePath();
	private function findNode() {
		$_arrPart=explode( '/', trim( self::$uriVar, '/' ) );
		// если есть ссылка - ищем
		if ( !empty( $_arrPart ) ) {
			$_strUrl='/';
			$_strUrlFind='';
			foreach( $_arrPart as $v ) {
				$_strUrl.=$v.'/';
				if ( !empty( $this->_currentUris[$_strUrl] ) ) {
					$_strUrlFind=$_strUrl;
					$this->_currentNode=$this->_currentUris[$_strUrl];
					continue;
				}
				// это условие только из-за того что в админке в дереве нету страницы 
				// со ссылкой /site-backend/ - нужно как-то решить 03.11.2008 TODO!!!
				if ( !empty( $_strUrlFind ) ) {
					break;
				}
			}
		}
		if ( !empty( $this->_currentNode ) ) { // дополнительные переменные могут приходить только в экшен (если доступный не найден то всё игнорим)
			// переменные которые приходят через чпу но не участвуют в определении экшена страницы
			// т.е. дополнительные переменные
			$this->_currentNode['action_vars']=trim( str_replace( $_strUrlFind, '', self::$uriVar ), '/' );
		}
		// урл не нашёлся
		if ( empty( $this->_currentNode )&&self::$uriVar!=self::$offset ) {
			return false;
		}
		return true;
	}

	public function setLocalParams( $_arr=array() ) {
		if ( empty( $_arr['name'] ) ) {
			return false;
		}
		$this->_localPrams[$_arr['name']][$_arr['module_unique_id']]=$_arr;
	}

	public function getGlobalParams( $_strModuleName='' ) {
		if ( !empty( $_strModuleName ) ) { // в этом случае запрос идёт из getParams для текущего модуля
			if ( empty( $this->_globalPrams['name'] )||$this->_globalPrams['name']!=$_strModuleName ) {
				return array();
			}
		}
		return $this->_globalPrams;
	}

	public function getParams( Core_Module_Interface &$module ) {
		$_arrLocal=$_arrGlobal=array();
		if ( !empty( $this->_localPrams[$module->getModuleName()][$module->getUniqueId()] ) ) {
			$_arrLocal=$this->_localPrams[$module->getModuleName()][$module->getUniqueId()];
		}
		$_arrGlobal=$this->getGlobalParams( $module->getModuleName() );
		return ( empty( $_arrGlobal )? $_arrLocal:( $_arrLocal+$_arrGlobal ) );
	}

	private function getCurrentBackendsFrontend() {}

	// переключение текущего фронтэнда для бакэнда (для редактирования дерева например) TODO!!!
	/*private function check_admin_frontend_mode() {
		if ( !empty( $this->frontend ) ) {
			return false;
		}
		if ( !empty( $_REQUEST['new_frontend'] ) ) {
			$_SESSION['new_frontend']=$_REQUEST['new_frontend'];
		} elseif ( empty( $_SESSION['new_frontend'] ) ) {
			$this->get_sys_name_by_twoleveldomain( $currentSite );
			foreach( $this->frontends as $v ) {
				if ($v['sys_name']==$currentSite) {
					$_SESSION['new_frontend']=$v['sys_name'];
					break;
				}
			}
			if (empty($_SESSION['new_frontend'])) {
				$_SESSION['new_frontend']=$this->frontends[0]['sys_name'];
			}
		}
		$this->admin_current_frontend=$_SESSION['new_frontend'];
		$_arrHost=array_reverse( explode( '.', $_SERVER['HTTP_HOST'] ) );
		$_arrHost[1]=$this->admin_current_frontend;
		$this->admin_current_frontend_url=join( '.', array_reverse( $_arrHost ) );
	}*/

	// например для ссылок в нотификейшены для админа
	public function generateBackendUrl( &$strUrl, $_arrSetting=array() ) {
		$this->getTree( array(
			'node_id'=>$this->backend['root_id'],
			'with_root_node'=>true,
			'offset'=>'', // тут нужен оффет именно для backend поэтому видимо надо ручками ставить
			'result'=>array( 
				'MOD_URLS'=>&$_arrSetting['MOD_URLS'], 
				'MOD_BYIDS'=>&$_arrSetting['MOD_BYIDS'], 
			),
		) );
		return $this->generateUrl( $strUrl, $_arrSetting );
	}

	// например для ссылок в нотификейшены отсылаемые из админки пользователям
	public function generateFrontendUrl( &$strUrl, $_arrSetting=array() ) {
		$this->getTree( array(
			'node_id'=>$this->frontend['root_id'],
			'with_root_node'=>true,
			'offset'=>'', // тут нужен оффет именно для frontend поэтому видимо надо ручками ставить 
			'result'=>array( 
				'MOD_URLS'=>&$_arrSetting['MOD_URLS'], 
				'MOD_BYIDS'=>&$_arrSetting['MOD_BYIDS'], 
			),
		) );
		return $this->generateUrl( $strUrl, $_arrSetting );
	}

	// вызывается и из шаблонизатора
	public function generateCurrentUrl( &$strUrl, $_arrSetting=array() ) {
		$_arrSetting['MOD_URLS']=&$this->_currentUrls;
		$_arrSetting['MOD_BYIDS']=&$this->_currentByIds;
		return $this->generateUrl( $strUrl, $_arrSetting );
	}

	// сделать чтобы из шаблона запрашивался метод текущего модуля а не стартового
	// для этого видимо надо написать надстройку для смарти TODO!!! 16.04.2009
	private function generateUrl( &$strUrl, $_arrSetting=array() ) {
		// если передан id ноды (страницы) - нужно для генерации ссылки на страницу с неуникальным экшеном
		if ( !empty( $_arrSetting['id'] )&!empty( $_arrSetting['MOD_BYIDS'][$_arrSetting['id']] ) ) { 
			$strUrl=$_arrSetting['MOD_BYIDS'][$_arrSetting['id']];
		} elseif ( !empty( $_arrSetting['name'] )&!empty( $_arrSetting['action'] ) ) {
			$strUrl=$_arrSetting['MOD_URLS'][$_arrSetting['name']]['actions'][$_arrSetting['action']]['sys_name'];
		} elseif ( !empty( $_arrSetting['action'] ) ) {} // TODO!!! 16.04.2009
		if ( empty( $strUrl ) ) {
			if ( empty( $_arrSetting['f'] ) ) { // force_generate
				return false;
			}
			//если сгенерировать надо в любом случае, даже если в дереве ссылка не найдена (тут передаём например self::$offset или self::$uriVar)
			$strUrl=is_bool( $_arrSetting['f'] )?'':$_arrSetting['f'];
		}
		$strUrl.=$this->generateUrlVars( array_intersect_key( $_arrSetting, array( 'w'=>1, 'wg'=>1, 'wp'=>1, 'wr'=>1 ) ) );
		return $strUrl;
	}

	// если имеем что добавить к ссылке
	private function generateUrlVars( $_arrSetting=array() ) {
		foreach( $_arrSetting as $k=>$v ) {
			if ( is_bool( $v ) ) {
				$v=array();
			}
			if ( !is_array( $v ) ) {
				parse_str( $v, $v );
			}
			switch( $k ) {
				case 'w': return $this->makeUrl( $v ); break; // with_this - добавляет переданную строчку
				case 'wg': return $this->makeUrl( array_merge( $_GET, $v ) ); break; // with_current_get - добавляет $_GET + переданную строчку
				case 'wp': return $this->makeUrl( array_merge( $_POST, $v ) ); break;
				case 'wr': return $this->makeUrl( array_merge( $_REQUEST, $v ) ); break;
			}
		}
		return '';
	}

	private function makeUrl( $_arrDta=array() ) {
		if ( empty( $_arrDta ) ) {
			return '';
		}
		return '?'.http_build_query( $_arrDta );
	}

	public function generateLocationUrl( Core_Module_Interface &$module, $_arrSetting=array() ) {
		if ( empty( $_arrSetting ) ) {
			return self::$uriFull;
		}
		if ( is_string( $_arrSetting ) ) {
			return $_arrSetting;
		}
		// при отсутствии имени модуля берём имя текущего модуля
		if ( empty( $_arrSetting['name'] ) ) {
			$_arrSetting['name']=$module->getModuleName();
		}
		// при отсутствии названия экшена берём текущее название
		if ( empty( $_arrSetting['action'] ) ) {
			$module->getModuleAction( $_arrSetting['action'] );
		}
		$_arrSetting['f']=self::$offset;
		return $this->generateCurrentUrl( $_strUrl, $_arrSetting );
	}
}
?>