<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Module
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.02.2010
 * @version 6.5
 */


/**
 * Class starter (module,src,shell querys)
 *
 * @category WorkHorse
 * @package Core_Module
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
final class WorkHorse {

	/**
	 * показывает в каком режиме старотовали
	 * true - запуск из коммандной строки; false - запуск по http
	 *
	 * @var boolean
	 */
	public static $_isShell=false;

	/**
	 * показывает в каком режиме отображаем view
	 * true - админка; false - пользовательская часть
	 *
	 * @var boolean
	 */
	public static $isBackend=false;

	/**
	 * запуск по url
	 *
	 * @return void
	 */
	public static function run() {
		self::preparation();
		self::enableSessionHandler();
		register_shutdown_function( 'Core_Sql::disconnect' ); // disconnect from db
		self::execute();
		//exit;
	}

	/**
	 * системные вызывы приходящие по http
	 *
	 * @param array $_arr ($_GET массив)
	 * @return void
	 */
	public static function src( $_arr=array() ) {
		if ( empty( $_arr['src'] )&&empty( $_arr['name'] )&&!isSet( $_arr['randtxtimg'] ) ) {
			header( 'HTTP/1.0 404 Not Found' );
			exit;
		}
		self::preparation();
		self::enableSessionHandler();
		register_shutdown_function( 'Core_Sql::disconnect' ); // disconnect from db
		// src="/fs.php?src=symply.jpg&w=50&h=50"
		if ( isSet( $_arr['src'] ) ) {
			$objF=new Core_Media_Driver;
			$objF->d_tumbonthefly( $_arr );
		}
		// href="/fs.php?get=123123"
		if ( !empty( $_arr['name'] ) ) {
			$objF=Core_Media;
			if ( !$objF->m_download_byname( $_arr['name'] ) ) {
				header( 'HTTP/1.0 404 Not Found' );
			}
		}
		// random text img href="/fs.php?randtxtimg"
		if ( isSet( $_arr['randtxtimg'] ) ) {
			$objF=new Core_Media_Driver;
			$objF->d_random_txtimg();
		}
		exit;
	}

	/**
	 * вызов скрипта из коммандной строки
	 *
	 * @return void
	 */
	public static function shell() {
		self::preparation();
		self::$_isShell=true; // для использования в местах где требуется user_id например
		register_shutdown_function( 'Core_Sql::disconnect' ); // disconnect from db
	}

	/**
	 * общая часть процесса инициализации движка для всех способов запуска
	 *
	 * @return void
	 */
	private static function preparation() {
		require_once './library/Zend/Config.php';
		$_config=new Zend_Config( require 'config.php' );
		self::enableXdebug( $_config );
		self::enableAutoloader( $_config );
		new Core_Emulate();
		Zend_Registry::set( 'config', $_config );
		Core_Datetime::getInstance()->set_default_timezone( Zend_Registry::get( 'config' )->date_time->dt_zone );
		new Core_Errors();
		self::initCacheObjects();
		//register_shutdown_function( 'sql_report' ); // надо приделать нормальный логгер к системе TODO !!! 24.02.2010
	}

	private static function initCacheObjects() {
		// т.к. у нас кодировка массива в json формат происходит самописными функциями (у стандартных криво реализована работа
		// c запрещёнными символами - разбивается результирующий json), это дело очень тормозит если встречается в коде несколько раз
		Zend_Registry::set( 'CachedCoreString', Zend_Cache::factory(
			'Class', 'File',
			array( 'cached_entity'=>'Core_String', 'cached_methods'=>array( 'php2json' ), 'lifetime'=>NULL ),
			array( 'cache_dir'=>Zend_Registry::get( 'config' )->path->relative->cache )
		) );
		// генерация дерева сайта - для шелл режима не кешируем
	}

	/**
	 * инициализация автолодера файлов движка.
	 * в данное время используется Zend_Loader_Autoloader
	 *
	 * @param object &$_config - Zend_Config
	 * @return void
	 */
	private static function enableAutoloader( &$_config ) {
		set_include_path( implode( PATH_SEPARATOR, array( $_config->path->relative->library, get_include_path() ) ) );
		require_once $_config->path->relative->zend.'Loader'.DIRECTORY_SEPARATOR.'Autoloader.php'; // zend loader
		$autoloader=Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace( 'Core' );
		$autoloader->registerNamespace( 'Project' );
	}

	/**
	 * инициализация Xdebug. подробности на http://www.xdebug.org
	 *
	 * @param object &$_config - Zend_Config
	 * @return void
	 */
	private static function enableXdebug( &$_config ) {
		if ( !function_exists( 'xdebug_enable' )||!$_config->debugging->xdebug_enable ) {
			return;
		}
		ini_set('xdebug.var_display_max_data', '5120');
		ini_set('xdebug.collect_includes', '0');
		ini_set('xdebug.collect_params', '2');
		ini_set('xdebug.show_mem_delta', '1');
		ini_set('xdebug.show_exception_trace', '1');
		ini_set('xdebug.trace_output_dir', 'e:/www/dev/engine5/xdebug/');
		ini_set('xdebug.trace_format', '9');
		ini_set('xdebug.auto_trace', '1');
	}

	/**
	 * инициализация сессии
	 *
	 * @return void
	 */
	private static function enableSessionHandler() {
		self::setIsBackend();
		Core_Session::getInstance( (self::$isBackend? 'adm':'') );
	}

	/**
	 * исполнение view
	 *
	 * @return void
	 */
	private static function execute() {
		if ( self::$isBackend==true ) {
			self::executeBackend();
		} else {
			self::executeFrontends();
		}
	}

	/**
	 * определяем режим view
	 *
	 * @return void
	 */
	private static function setIsBackend() {
		self::$isBackend=mb_substr( $_SERVER['REQUEST_URI'], 0,13 )=='/site-backend';
	}

	/**
	 * исполнение Frontends view - нужен рефактониг TODO!!! 24.02.2010
	 *
	 * @return void
	 */
	private static function executeFrontends() {
		include_once Zend_Registry::get( 'config' )->path->relative->source.'/site1/site1.class.php';
		Zend_Registry::set( 'current_view', new site1() );
		Zend_Registry::get( 'current_view' )->run();
	}

	/**
	 * исполнение Backend view - нужен рефактониг TODO!!! 24.02.2010
	 *
	 * @return void
	 */
	private static function executeBackend() {
		include_once Zend_Registry::get( 'config' )->path->relative->source.'/backend/backend.class.php';
		Zend_Registry::set( 'current_view', new backend() );
		Zend_Registry::get( 'current_view' )->run();
	}

	/**
	 * запуск модуля из кода - нужен рефактониг TODO!!! 24.02.2010
	 *
	 * @return void
	 */
	public static function executeFrontendOuter( $_arrPrm=array() ) {
		$_SERVER['REQUEST_URI']='/'; // это чтобы прочиталось дерево и права
		Zend_Registry::set( 'current_view', Core_Module::startModule( $_arrPrm ) ); // это чтобы работали подстановки ссылок
		Zend_Registry::get( 'current_view' )->run(); // непосредственно запуск
	}
}


/**
 * Мини дебаггер - просмотр любых данных
 *
 * @category WorkHorse
 * @package Core_Module
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
function p($mix) {
	while (@ob_end_clean());
	header( 'Content-Type: text/html; charset="'.Zend_Registry::get( 'config' )->database->codepage.'"');
	if ( function_exists( 'xdebug_var_dump' ) ) {
		xdebug_var_dump( $mix );
	} else {
		echo '<div align="left"><hr><pre>';
		if ( is_bool( $mix ) )
			var_dump( $mix );
		elseif ( is_array( $mix )||is_object( $mix ) )
			print_r( $mix );
		else
			echo $mix;
		echo '</pre><hr></div>';
	}
	@ob_flush();
	exit;
}
?>