<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package Core_Module
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2011, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 18.03.2011
 * @version 6.1
 */


/**
 * Sorce module base class (through Project_Module)
 *
 * @category   WorkHorse
 * @package    Core_Module
 * @copyright Copyright (c) 2005-2011, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Core_Module extends Core_Services implements Core_Module_Interface {
	private $_uniqueId=''; // id модуля при рекурсивном запуске (знает только текущий модуль - но можно пройтись по всем TODO!!! 13.04.2009)
	private $_moduleName=''; // имя текущего модуля
	private $_forceNoTemplate=false; // принудитьельно отключает парсинг шаблона если true
	private $_redirectAfterRecursion=array(); // ccылка на которую нужно сделать редирект после отрисовки шаблона
	public $config;
	public $objMR; // статический объект Core_Module_Router
	public $objML; // статический объект Core_Module_Location
	public $objUser; // статический объект Core_Users
	public $out=array(); //то что попадает на шаблон
	public $out_js=array(); //то что конвертиться в json
	public $inst_script=array(); // описание модуля - задаётся в конечном классе
	public $params=array(); // параметры данного модуля

	public function __construct() {
		$this->config=Zend_Registry::get( 'config' );
		$this->initEnvironment();
	}

	private function initEnvironment() {
		$this->_moduleName=get_class( $this );
		$this->factory( array( 'objMR', 'objML', 'objSniff' ) );
		if ( empty( $this->objMR->curPathDirect ) ) {
			$objCMMS=new Core_Module_Management_Sites(); // проверка что сайты заинсталлированы иначе инсталл по конфигу сайта и рутовских страниц
			$this->objMR->initSite( $objCMMS ); // определяем по ссылке какой сайт запросили
		}
		$this->objML->initLocation( $this ); // хранилище уникальных ссылок - надо каждый раз перадавать актуальный модуль для формирования правильной ссылки при location()
		$objCMMM=new Core_Module_Management_Modules();
		$objCMMM->initModule( $this ); // проверяем заинталлирован-ли запрашиваемый модуль (по имени модуля)
		$this->factory( array( 'objUser' ) ); // инициализация пользователя, там-же проверка наличия root-пользователя
		if ( empty( $this->objMR->curPathDirect ) ) {
			$this->objMR->setGlobalParams( $this ); // берём дерево данного сайта,  находим в нём нужную ноду (определяем по ссылке), // пороверяем доступ пользователя к экшену привязанному к странице и устанавливаем в случае успеха глобальные парпметры
			$this->childInitModule();
		}
	}

	public function childInitModule() {}

	protected final function factory( $_arr=array() ) {
		if ( !$this->childFactory( $_arr ) ) {
			return false;
		}
		foreach( $_arr as $v ) {
			if ( Zend_Registry::isRegistered( $v ) ) {
				$this->$v=Zend_Registry::get( $v );
				continue;
			}
			switch( $v ) {
				case 'objMR': $this->$v=new Core_Module_Router(); break; // робота с сылками
				case 'objML': $this->$v=new Core_Module_Location(); break; // лог местоположений на сайте
				case 'objUser': $this->$v=(class_exists( 'Project_Users' )? new Project_Users():new Core_Users()); break; // пользователи и права
				case 'objSniff': $this->$v=new Core_Sniffer(); break; // инфа о клиенте
			}
			if ( !empty( $this->$v )&&is_object( $this->$v ) ) {
				Zend_Registry::set( $v, $this->$v );
			}
		}
		return true;
	}

	public function childFactory( $_arr=array() ) {return true;}

	public function setUniqueId( $_str='' ) {
		$this->_uniqueId=$_str;
	}

	public function getUniqueId() {
		return $this->_uniqueId;
	}

	public function getModuleName() {
		return $this->_moduleName;
	}

	public function setForceNoTemplate() {
		$this->_forceNoTemplate=true;
	}

	public function setRedirectAfterRecursion( $_mix=array() ) {
		$this->_redirectAfterRecursion=$_mix;
	}

	// тут стартуем сайт (см. WorkHorse - executeFrontends или executeBackend) TODO!!! 13.04.2009
	public static function startSite() {}

	public static function &startModule( $_arrPrm=array() ) {
		if ( !Core_Module_Management_Modules::includeModule( @$_arrPrm['name'] ) ) {
			return false;
		}
		$obj=$_arrPrm['module_unique_id']='obj'.Core_A::rand_uniqid();
		$$obj=new $_arrPrm['name']();
		// если в модуле есть метод имя которого такое-же как у класса то пхп считает его конструктором
		// соответственно конструктор Core_Module не срабатывает
		if ( in_array( get_class( $$obj ), get_class_methods( $$obj ) ) ) {
			trigger_error( ERR_PHP.'|constructor reassign' ); // TODO!!!
			return false;
		}
		$$obj->setUniqueId( $_arrPrm['module_unique_id'] );
		$$obj->objMR->setLocalParams( $_arrPrm );
		return $$obj;
	}

	// кроме прочего нужно для использования модулей внутри экшенов других модулей
	public static function getModuleObject( $_arrPrm=array() ) {
		$obj=self::startModule( $_arrPrm );
		$obj->beforeRunAspect();
		return $obj;
	}

	public static function runModule( $_arrPrm=array() ) {
		self::startModule( $_arrPrm )->run();
		return true;
	}

	// массивы в $GLOBALS всегда характерны для конкретной части сайта (backend или frontend)
	// так например если шаблон отображается на фронтэнде то будут массивы фронтэнда
	public static function getUrl( $_arrPrm=array() ) {
		if ( !Zend_Registry::get( 'current_view' )->objMR->generateCurrentUrl( $strUrl, $_arrPrm ) ) {
			return "#\" onclick=\"alert('url not found'); return false;\"";
		}
		return $strUrl;
	}

	private function checkActionType() {
		$_arrPrm=$this->objMR->getGlobalParams();
		if ( $_arrPrm['flg_tpl']==3 ) { // если экшн безшаблонный то вложенности модулей не будет заначит можно сразу старотовать нужный экшен
			self::startModule( $_arrPrm )->$_arrPrm['action']();
		}
	}

	public function run() {
		$this->checkActionType();
		ob_start();
		$this->beforeRunAspect();
		if ( !empty( $this->params['action'] ) ) {
			$_str=$this->params['action'];
			$this->$_str();
		}
		$this->afterRunAspect();
	}

	// public для того чтобы в getModuleObject можно было использовать
	public function beforeRunAspect() {
		$this->params=$this->objMR->getParams( $this );
		$this->objStore=new Core_Module_Store( $this ); // интерфейс для хранения переменных экшена
		$this->getOutHash();
		/* надо ли? TODO!!!
		$this->get_server_info( $this->out['arrServerInfo'] );
		if ( empty( $this->objCore->frontend ) ) {
			$this->out['admin_current_frontend']=$this->objCore->admin_current_frontend;
			$this->out['admin_current_frontend_url']=$this->objCore->admin_current_frontend_url;
		} else {
			$this->out['arrCurFrontend']=$this->objCore->frontend;
		}*/
		$this->before_run_parent();
	}

	public function getOutHash() {
		$this->out['arrPrm']=&$this->params; // во время исполнения экшена могут быть добавлены значения поэтому &
		$this->out['arrNest']=$this->objMR->getGlobalParams();
		$this->out['arrCurDirect']=$this->objMR->curPathDirect;
		$this->out['arrCurReverse']=$this->objMR->curPathReverse;
		$this->out['arrUser']=Core_Users::$info;
		$this->out['config']=&$this->config; // в каждом шаблоне доступен оъект с конфигом
		$this->out['strBackUrl']=$this->objML->get();
		$this->out['arrClientInfo']=&$this->objSniff->_browser_info;
	}

	private function afterRunAspect() {
		$this->after_run_parent();
		//if ( !$this->_forceNoTemplate ) { // тоже хрень какято TODO!!!
			if ( !isSet( $this->params['flg_tpl'] ) ) {
				// не во всех случаях flg_tpl выставлен - это потому что для модулей запускаемых 
				// из шаблона нету в params данных из inst_script TODO!!! вообще лучше эмулировать полный набор атрибутов экшена
				$this->params['flg_tpl']=0;
			}
			switch( $this->params['flg_tpl'] ) {
				case 2: Core_Parsers::viewAsXml( $this->out ); exit; break; // возможно тут надо что-то вроде $this->out_xml либо в getOutHash варьировать как-то TODO!!!
				case 3: Core_Parsers::viewAsJson( $this->out_js ); exit; break;
				default: Core_Parsers::viewAsHtml( $this->out, 
					$this->config->path->relative->source.$this->getModuleName().
					DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$this->getModuleName().'.tpl' );
				break;
			}
		//}
		if ( !empty( $this->_redirectAfterRecursion ) ) {
			$this->objML->initLocation( $this ); // восстановим в location текущий модуль т.к. возможно была модкульная рекурсия
			$this->objML->location( $this->_redirectAfterRecursion ); // редирект после модульной рекурсии
		}
	}

	public function getModuleAction( &$strRes ) {
		$_arrPrm=$this->objMR->getGlobalParams();
		if ( empty( $_arrPrm['action'] ) ) {
			return false;
		}
		$strRes=$_arrPrm['action'];
		return true;
	}

	public function getViewMode( &$strRes ) {
		$_arrPrm=$this->objMR->getGlobalParams();
		if ( empty( $_arrPrm['flg_tpl'] ) ) { // ссылочный экшен (обычный)
			return false;
		}
		$strRes=$_arrPrm['flg_tpl']; // попапы и прочая
		return true;
	}

	public function before_run_parent() {}

	public function after_run_parent() {}

	public function set_cfg() {}

	public function location( $_mix='', $_flgSkipBack=0 ) {
		$this->objML->location( $_mix, $_flgSkipBack );
	}

	public function moduleManagement( $_strName='', $_strMethod='' ) {
		if ( empty( $_strName )||empty( $_strMethod ) ) {
			return false;
		}
		if ( $_strName==$this->getModuleName() ) {
			$_obj=&$this;
		} else {
			$_obj=$this->startModule( array( 'name'=>$_strName ) );
		}
		$objCMMM=new Core_Module_Management_Modules();
		$objCMMM->setConfig( $_obj );
		$objCMMM->$_strMethod();
		$this->objUser->reloadUserSession();
		return true;
	}
}
?>