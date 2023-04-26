<?php
/**
 * User Management
 * @category framework
 * @package UserManagement
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.08.2010
 * @version 2.0
 */


/**
 * Core users methods
 * @category framework
 * @package UserManagement
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.08.2010
 * @version 3.0
 */


class Core_Users extends Core_Storage implements Core_Users_Sample {

	protected $_link=false; // тут линк нам не нужен

	/**
	* список полей таблицы
	* @var array
	*/
	public $fields=array( 'id', 'parent_id', 'cost_id', 'item_id', 'flg_status', 'email', 'passwd', 'nickname', 'timezone', 'reg_code', 'forgot_code', 'forgot_added', 'next_payment', 'added' );

	/**
	* название таблицы c сайтами на которые постим контент
	* @var string
	*/
	public $table='u_users';

	public static $info=array();
	public $u_info=array(); // DEPERCATED!!!! 23.08.2010 use Core_Users::$info instead текущий пользователь в данной сессии с которым работает класс

	private $_rootEmail='root@root.dev';
	private static $_cookieName=''; // префикс который используется для организации пользовательских кук

	public function __construct( $_intId=0 ) {
		$this->factory( array( 'objManageAccess' ) );
		$this->checkRootUser();
		$this->setCookieName();
		if ( !empty( $_intId ) ) {
			$this->getProfileById( self::$info, $_intId );
		}
		if ( empty( self::$info ) ) { // данный экземпляр класса не связан с сессией текущего пользователя
			if ( !$this->userInit() ) { // первая инициализация self::$info
				$this->objManageAccess->setMinimalUserRight( self::$info ); // пользователь получает права группы Visitor
				$this->setTimezone( self::$info ); // и дефолтный временной пояс
			}
		}
		$this->u_info=&self::$info; // DEPERCATED!!!! 23.08.2010 use Core_Users::$info instead
	}
/*

всё сделать статиком для получения и кэширования инстансов

// связывание текущего self::$info с сессией 
// (обычно требуется клиентской части проекта)
permanent

// текущй инстанс кэшируется и подменяется объектом инициализированным под другим пользователем 
// (обычно требуется при исполнении скриптов которые работают с данными многих пользователей и эти данные меняются)
temporary

// когда нужен пользовательский объект но пользователя инициализировать необязательно
// (например когда скрипт работает с данными многих пользовтаелей, но сохранять эти данные не нужно)
zero

	public function webUser() {}

	public function shellUser() {}
*/

	private function userInit() {
		if ( empty( $_SESSION[Zend_Registry::get( 'config' )->user->sesion_key] ) ) {
			$_SESSION[Zend_Registry::get( 'config' )->user->sesion_key]=array();
		}
		// если права в группах поменяются то у пользователя они не обновятся для этого надо делать u_reload_user_session
		// ссылку делаем даже если $_SESSION[$this->u_seskey] пустой. При этом, если он в последствии каким-то образом наполнится, 
		// во всех экземплярах user_core и extends'ах u_info тоже будут соответствовать $_SESSION[$this->u_seskey]
		self::$info=&$_SESSION[Zend_Registry::get( 'config' )->user->sesion_key];
		return !empty( self::$info );
	}

	// часовые пояса (зоны) ставится в self::$info
	private function setTimezone( &$arrRes ) {
		if ( empty( $arrRes['timezone'] ) ) {
			Core_Datetime::getInstance()->get_default_timezone( $arrRes['timezone'] );
		} else {
			// если у пользователя выбрана временная зона
			Core_Datetime::getInstance()->set_default_timezone( $arrRes['timezone'] );
		}
	}

	// u_getprofile
	public function getProfileById( &$arrRes, $_intId=0 ) {
		if ( empty( $_intId ) ) {
			return false;
		}
		if ( !$this->onlyOne()->withId( $_intId )->getList( $arrRes )->checkEmpty() ) { // основные данные
			return false;
		}
		if ( $this->objManageAccess->getGroups( $arrRes['groups'], $arrRes['id'] ) ) { // группы и права
			$this->objManageAccess->getRights( $arrRes['right'], $arrRes['right_parsed'], $arrRes['groups'] );
		}
		return true;
	}

	private function setCookieName() {
		if ( WorkHorse::$_isShell ) {
			return;
		}
		self::$_cookieName=str_replace( '.', '_', 
			Zend_Registry::get( 'config' )->user->cookies_name.
			Zend_Registry::get( 'config' )->engine->project_domain.'_'.
			Core_Module_Router::$curSiteName );
	}

	// чекаем root пользователя и если его нет в системе то создаём
	private function checkRootUser() {
		if ( empty( $GLOBALS['BACKUP_QUERY']['Core_Users@checkRootUser'] ) ) {
			if ( !$this->onlyCount()->withEmail( $this->_rootEmail )->getList( $_arrTmp )->checkEmpty() ) {
				$this->createRootUser();
			}
		}
		$GLOBALS['BACKUP_QUERY']['Core_Users@checkRootUser']=true;
	}

	private function createRootUser() {
		// registration
		$this->setData( array(
			'email'=>$this->_rootEmail,
			'passwd'=>md5( 'root' ),
			'nickname'=>'root',
			'flg_status'=>1,
		) )->_data->setFilter();
		if ( !$this->set() ) {
			throw new Exception( Core_Errors::DEV.'|Can\'t create root user' );
		}
		return $this->objManageAccess->setRootUserGroups( $this->_data->filtered['id'] );
	}

	// данные ранее добавлены через setData()
	public function createSysUser() {
		// делаем его child'ом рута
		if ( !$this->onlyCell()->onlyIds()->withEmail( $this->_rootEmail )->getList( $_intParentId )->checkEmpty() ) {
			return false;
		}
		$this->_data->setElement( 'parent_id', $_intParentId );
		$this->set();
		// всем системным для начала $this->objR->r_system_groups
		return $this->objManageAccess->setSysUserGroups( $this->_data->filtered['id'] );
	}

	// TODO!!! 24.08.2010 see accounts module, change_password
	public function changeOwnerPassword() {}

	public function set() {
		if ( empty( $this->_data->filtered['id'] ) ) {
			$this->_data->filtered['added']=time();
		}
		$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( $this->table, $this->_data->setMask( $this->fields )->getValid() ) );
		return true;
	}

	// активация аккаунтов
	public function switchStatus( $_arrIds=array() ) {
		if ( empty( $_arrIds ) ) {
			return false;
		}
		foreach( $_arrIds as $k=>$v ) {
			Core_Sql::setExec( 'UPDATE '.$this->table.' SET flg_status="'.(empty( $v )?0:1).'", reg_code="" WHERE id="'.$k.'" LIMIT 1' );
		}
		return true;
	}

	// активация пользовательского аккаунта по ссылке
	public function activation( $_strCode='' ) {
		if ( empty( $_strCode ) ) {
			return false;
		}
		$_intId=Core_Sql::getCell( 'SELECT id FROM '.$this->table.' WHERE reg_code='.Core_Sql::fixInjection( $_strCode ) );
		return $this->switchStatus( array( $_intId=>1 ) );
	}

	// удаление пользователей
	public function del( $_mixIds=array() ) {
		if ( empty( $_mixIds ) ) {
			return false;
		}
		$this->factory( 'objDel' );
		$this->objDel->set( $_mixIds );
		if ( !$this->objDel->get_ids( $arrIds ) ) {
			return false;
		}
		if ( !$this->profileDelete( $arrIds ) ) {
			return false;
		}
		return $this->objDel->initiate();
	}

	// метод который можно перегрузить добавив дополнительно удаление каких-то связанных с пользовтаелями вещей
	public function profileDelete( $_arrIds=array() ) {return true;}

	public function factory( $_mixIds=array() ) {
		if ( empty( $_mixIds ) ) {
			return false;
		}
		$_arrSet=!is_array( $_mixIds )?array( $_mixIds ):$_mixIds;
		foreach( $_arrSet as $v ) {
			if ( !empty( $this->$v )&&is_object( $this->$v )&&in_array( $v, array( 'objManageAccess' ) ) ) {
				continue;
			}
			switch( $v ) {
				case 'objDel': $this->$v=new Core_Users_Delete( $this ); break; // удаление пользователей
				case 'objSt': $this->$v=new Core_Items_Stencil_Extension(); break;
				case 'objManageAccess': $this->$v=new Core_Users_Manage_Access(); break; // взаимодействие пользователей и их прав
			}
		}
	}

	// настройки для getList
	private $_onlyParentIds=false; // массив с parent_id
	private $_withId=array(); // c данными id
	private $_withParentId=array(); // c данными parent_id
	private $_withGroups=array(); // только выбранные группы пользователей (по u_groups.sys_name)
	private $_withoutGroups=array(); // пользователи не в данных группах (по u_groups.sys_name)
	private $_withEmail=''; // c данными email
	private $_withNickname=''; // c данными nickname
	protected $_withOrder='u.id--up'; // c сортировкой

	protected function init() {
		$this->_onlyParentIds=false;
		$this->_withId=array();
		$this->_withParentId=array();
		$this->_withGroups=array();
		$this->_withoutGroups=array();
		$this->_withEmail='';
		$this->_withNickname='';
		parent::init();
		$this->_withOrder='u.id--up';
	}

	public function onlyParentIds() {
		$this->_onlyParentIds=true;
		return $this;
	}

	public function withId( $_arrIds=array() ) {
		$this->_withId=$_arrIds;
		return $this;
	}

	public function withParentId( $_arrIds=array() ) {
		$this->_withParentId=$_arrIds;
		return $this;
	}

	public function withGroups( $_mixIds=array() ) {
		if ( !empty( $_mixIds ) ) {
			$this->_withGroups=is_array( $_mixIds )? $_mixIds:array( $_mixIds );
		}
		$this->_cashe['with_groups']=$this->_withGroups;
		return $this;
	}

	public function withoutGroups( $_mixIds=array() ) {
		if ( !empty( $_mixIds ) ) {
			$this->_withoutGroups=is_array( $_mixIds )? $_mixIds:array( $_mixIds );
		}
		$this->_cashe['without_groups']=$this->_withoutGroups;
		return $this;
	}

	public function withEmail( $_str='' ) {
		if ( !empty( $_str ) ) {
			$this->_withEmail=$_str;
		}
		$this->_cashe['email']=$this->_withEmail;
		return $this;
	}

	public function withNickname( $_str='' ) {
		if ( !empty( $_str ) ) {
			$this->_withNickname=$_str;
		}
		$this->_cashe['nickname']=$this->_withNickname;
		return $this;
	}

	protected function assemblyQuery() {
		$_boolGroup=false;
		if ( $this->_onlyIds ) {
			$this->_crawler->set_select( 'u.id' );
		} elseif ( $this->_onlyParentIds ) {
			$this->_onlyIds=true; // чтобы выбрался только один столбец
			$this->_crawler->set_select( 'u.parent_id' );
			$this->_crawler->set_where( 'u.parent_id>0' );
		} else {
			$this->_crawler->set_select( 'u.*' );
		}
		$this->_crawler->set_from( $this->table.' u' );
		// возможно надо будет просто список пользовтелей с их группами TODO!!!17.04.2010
		if ( !empty( $this->_withGroups )&&Core_Acs_Groups::getIdsBySysName( $_arrIds, $this->_withGroups ) ) {
			$this->_crawler->set_where( 'ug.id IN('.Core_Sql::fixInjection( $_arrIds ).')' );
			$_boolGroup=true;
		}
		if ( !empty( $this->_withoutGroups )&&Core_Acs_Groups::getIdsBySysName( $_arrIds, $this->_withoutGroups ) ) {
			$this->_crawler->set_where( 'ug.id NOT IN('.Core_Sql::fixInjection( $_arrIds ).')' );
			$_boolGroup=true;
		}
		if ( !empty( $this->_withParentId ) ) {
			$this->_crawler->set_where( 'u.parent_id IN('.Core_Sql::fixInjection( $this->_withParentId ).')' );
		}
		if ( !empty( $this->_withId ) ) {
			$this->_crawler->set_where( 'u.id IN('.Core_Sql::fixInjection( $this->_withId ).')' );
		}
		if ( !empty( $this->_withEmail ) ) {
			$this->_crawler->set_where( 'u.email IN('.Core_Sql::fixInjection( $this->_withEmail ).')' );
		}
		if ( !empty( $this->_withNickname ) ) {
			$this->_crawler->set_where( 'u.nickname IN('.Core_Sql::fixInjection( $this->_withNickname ).')' );
		}
		if ( $_boolGroup ) {
			$this->_crawler->set_from( 'INNER JOIN u_link ul ON ul.user_id=u.id' );
			$this->_crawler->set_from( 'INNER JOIN u_groups ug ON ug.id=ul.group_id' );
			$this->_crawler->set_group( 'u.id' );
		}
		if ( !$this->_onlyOne ) {
			$this->_crawler->set_order_sort( $this->_withOrder );
		}
	}

	// выход пользователя из системы
	public static function logout() {
		if ( !empty( $_COOKIE[self::$_cookieName] ) ) {
			setcookie( self::$_cookieName."[nickname]", "", time()-42000, '/' );
			setcookie( self::$_cookieName."[email]", "", time()-42000, '/' );
			setcookie( self::$_cookieName."[passwd]", "", time()-42000, '/' );
			setcookie( self::$_cookieName."[rem]", "", time()-42000, '/' );
		}
		// kill session with id-in-cookie
		$_SESSION=array();
		if ( !empty( $_COOKIE[session_name()] ) ) {
			setcookie( session_name(), '', time()-42000, '/' );
		}
		session_destroy();
	}

	public function authorizeByCookie() {
		if ( empty( $_COOKIE[self::$_cookieName] ) ) {
			return false;
		}
		return $this->setData( $_COOKIE[self::$_cookieName] )->authorizeByEmail();
	}

	// авторизация по nickname&passwd
	// $obj->setData( array )->authorizeByNickname() /getEntered /getErrors
	public function authorizeByNickname() {
		if ( !$this->_data->setFilter()->setChecker( array(
			'passwd'=>empty( $this->_data->filtered['passwd'] ),
			'nickname'=>empty( $this->_data->filtered['nickname'] ),
		) )->check() ) {
			return false;
		}
		$_intId=Core_Sql::getCell( '
			SELECT id
			FROM '.$this->table.'
			WHERE
				reg_code="" AND flg_status=1 AND
				passwd=MD5('.Core_Sql::fixInjection( $this->_data->filtered['passwd'] ).') AND
				nickname='.Core_Sql::fixInjection( $this->_data->filtered['nickname'] )
		 );
		return $this->setUserSession( $_intId );
	}

	// авторизация по email&passwd
	// $obj->setData( array )->authorizeByEmail() /getEntered /getErrors
	public function authorizeByEmail() {
		if ( !$this->_data->setFilter()->setChecker( array(
			'passwd'=>empty( $this->_data->filtered['passwd'] ),
			'email'=>!Core_Common::checkEmail( $this->_data->filtered['email'] ),
		) )->check() ) {
			return false;
		}
		$_intId=Core_Sql::getCell( '
			SELECT id
			FROM '.$this->table.'
			WHERE
				reg_code="" AND flg_status=1 AND
				passwd=MD5('.Core_Sql::fixInjection( $this->_data->filtered['passwd'] ).') AND
				email='.Core_Sql::fixInjection( $this->_data->filtered['email'] )
		 );
		return $this->setUserSession( $_intId );
	}

	protected function setUserSession( $_intId=0 ) {
		if ( !$this->getProfileById( $_arrProfile, $_intId ) ) {
			$arrErr['no_user']=1; // выводить в массив ошибок TODO!!!
			return false;
		}
		// если есть ограничение по группам через данный логин
		if ( !empty( $this->_data->filtered['valid_groups'] ) ) {
			$_arr=array_intersect( $this->_data->filtered['valid_groups'], $_arrProfile['groups'] );
			if ( empty( $_arr ) ) {
				$arrErr['wrong_user_group']=1; // выводить в массив ошибок TODO!!!
				return false;
			}
		}
		$this->setTimezone( $_arrProfile );
		$this->_data->setElements( array( 
			'email'=>$_arrProfile['email'], 
			'nickname'=>$_arrProfile['nickname'] ) ); // дополнительные поля для установки кук
		$this->writeCookies();
		self::$info=$_arrProfile;
		return true;
	}

	private function writeCookies() {
		if ( !$this->_data->setFilter()->setChecker( array(
			'cook_email'=>empty( $this->_data->filtered['email'] ),
			'cook_nickname'=>empty( $this->_data->filtered['nickname'] ),
			'cook_passwd'=>empty( $this->_data->filtered['passwd'] ),
		) )->check() ) {
			return false;
		}
		// если нет флага "запомнить на сайте" и ранее он не был отмечен
		if ( empty( $this->_data->filtered['rem'] )&&empty( $_COOKIE[self::$_cookieName]['rem'] ) ) {
			return false;
		}
		setcookie( self::$_cookieName."[email]", $this->_data->filtered['email'], $_str, '/' );
		setcookie( self::$_cookieName."[nickname]", $this->_data->filtered['nickname'], $_str, '/' );
		setcookie( self::$_cookieName."[passwd]", $this->_data->filtered['passwd'], $_str, '/' );
		setcookie( self::$_cookieName."[rem]", 1, ( time()+Zend_Registry::get( 'config' )->user->interval ), '/' );
		return true;
	}

	// перегрузка сессии текущего пользовтаеля, либо смена пользовтеля
	public function reloadUserSession( $_intId=0 ) {
		$_intId=empty( $_intId )? self::$info['id']:$_intId;
		if ( empty( $_intId ) ) {
			return false;
		}
		if ( !empty( $_COOKIE[self::$_cookieName]['rem'] ) ) {
			self::$info['rem']=1;
		}
		$this->setData( self::$info );
		return $this->setUserSession( $_intId );
	}

	// состоит ли полоьзователь в переданных группах
	public static function haveAccess( $_mixGroups=array() ) {
		if ( empty( $_mixGroups ) ) {
			return false;
		}
		$_arrGroups=is_array( $_mixGroups )? $_mixGroups:array( $_mixGroups );
		$_arr=array_intersect( $_arrGroups, self::$info['groups'] );
		return !empty( $_arr );
	}

	// чекает имеет ли пользователь доступ к данному модулю-экшену
	public static function haveActionAccess( $_arrMs=array() ) {
		if ( empty( $_arrMs )||count( $_arrMs )<2 ) { // может быть только action_vars но без name и action
			return false;
		}
		return !empty( self::$info['right'][($_arrMs['name'].'_@_'.$_arrMs['action'])] );
	}

	// оставляет только те ссылки из дерева ссылок на которые есть права recursion TODO!!!
	// пока только для дерева которое выводит меню в дминке
	public static function haveUrlTreeAccess( $arrTree=array() ) {
		if ( empty( $arrTree )||empty( self::$info ) ) {
			return array();
		}
		$arrTree=$arrTree[0]['node'];
		foreach( $arrTree as $k=>$v ) {
			if ( empty( $v['node'] ) ) {
				continue;
			}
			$_arrA=array();
			foreach( $v['node'] as $i=>$j ) {
				// если нету прав на экшн и экшн попап или безтемплэйтный
				if ( empty( self::$info['right_parsed'][$j['name']][$j['action']] )||!empty( $j['flg_tpl'] ) ) {
					continue;
				}
				$_arrA[$i]=$j;
			}
			if ( empty( $_arrA ) ) {
				continue;
			}
			$arrRes[$k]=$v;
			$arrRes[$k]['node']=$_arrA;
		}
		usort( $arrRes, array( 'Core_Users', 'cmp' ) );
		return $arrRes;
	}

	static private function cmp( $a, $b ) {
		return strnatcmp( $a['title'], $b['title'] );
	}

	// id текущего пользователя
	public function getId( &$_intId ) {
		if ( WorkHorse::$_isShell ) { // если вызов из шелл то текущего пользователя нет
			$_intId=0;
			return true;
		}
		if ( WorkHorse::$isBackend&&empty( self::$info['id'] ) ) { // если бэкэнд то текущего тоже может не быть
			$_intId=0;
			return true;
		}
		if ( !empty( self::$info['id'] ) ) { // пользователь фронтэнда
			$_intId=self::$info['id'];
			return true;
		}
		return false; // должен генерить Error event?25.08.2010
	}

	// папка для временных папок текущего пользователя
	public function getTmpDirName() {
		return Zend_Registry::get( 'config' )->path->absolute->user_temp.($this->getId( $_intId )? $_intId:0).DIRECTORY_SEPARATOR;
	}

	// папка для папок текущего пользователя
	public function getDtaDirName() {
		return Zend_Registry::get( 'config' )->path->absolute->user_data.($this->getId( $_intId )? $_intId:0).DIRECTORY_SEPARATOR;
	}

	// папки пользователей для временных файлов
	public function checkTmpDir( &$strDir ) {
		$strDir=$this->getTmpDirName();
		if ( !is_dir( $strDir ) ) {
			mkdir( $strDir, 0755, true  );
		}
		return is_dir( $strDir );
	}

	// папки пользователей для файлов
	public function checkDtaDir( &$strDir ) {
		$strDir=$this->getDtaDirName();
		if ( !is_dir( $strDir ) ) {
			mkdir( $strDir, 0755, true  );
		}
		return is_dir( $strDir );
	}

	// создание верменной папки пользователя {config->path->absolute->user_temp/<parent_id>/<classname@methodname>/}
	public function prepareTmpDir( &$strDir ) {
		if ( !$this->checkTmpDir( $_tmpDir ) ) {
			return false;
		}
		$strDir=$_tmpDir.$strDir.DIRECTORY_SEPARATOR;
		Core_Files::rmDir( $strDir ); // удаляем папку с файлами (если что-то осталось c прошлого раза)
		if ( !is_dir( $strDir ) ) {
			mkdir( $strDir, 0755, true  );
		}
		return is_dir( $strDir );
	}

	// создание верменной папки пользователя {config->path->absolute->user_temp/<parent_id>/$strDir/}
	public function prepareDtaDir( &$strDir ) {
		if ( !$this->checkDtaDir( $_tmpDir ) ) {
			return false;
		}
		$strDir=$_tmpDir.$strDir.DIRECTORY_SEPARATOR;
		if ( !is_dir( $strDir ) ) {
			mkdir( $strDir, 0755, true );
		}
		return is_dir( $strDir );
	}
}
?>