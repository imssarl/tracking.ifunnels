<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse Project
 * @package Project_Users
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 04.11.2009
 * @version 2.0
 */


/**
 * Core user library extension
 *
 * @category WorkHorse Project
 * @package Project_Users
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Users extends Core_Users {

	/**
	 * служит для включения/отключения feedbackhq сайта
	 *
	 * @var boolean
	 */
	private $_feedbackServiceOn=false;

	/**
	 * группы по убыванию доступных возможностей (уже не поубыванию давно 21.07.2010)
	 *
	 * gate_id=44&secret=letsbesmart - Unlimited
	 * gate_id=2232&secret=bfenginefor98743vip - Blog Fusion
	 * gate_id=2227&secret=sbppro2009vip - Site Profit Bot Pro
	 * gate_id=2228&secret=sbpadvancedfor98743vip - Campaign Optimizer
	 * gate_id=2235&secret=cnm2010ad - Advertiser
	 * gate_id=2234&secret=spb2010hosted - Site Profit Bot Hosted
	 * gate_id=2236&secret=nvsbhosted2y - NVSB Hosted
	 * gate_id=2237&secret=nvsbhostedpro3z - NVSB Hosted Pro
	 *
	 * @var array
	 */
	private $_ethiccashGates=array(
		'Unlimited'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=44&secret=letsbesmart',
		'Blog Fusion'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2232&secret=bfenginefor98743vip',
		'Site Profit Bot Pro'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2227&secret=sbppro2009vip',
		'Campaign Optimizer'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2228&secret=sbpadvancedfor98743vip',
		'Advertiser'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2235&secret=cnm2010ad',
		'Site Profit Bot Hosted'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2234&secret=spb2010hosted',
		'NVSB Hosted'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2236&secret=nvsbhosted2y',
		'NVSB Hosted Pro'=>'http://sales.ethiccash.com/2/action/Jin/APIUser/authorize.txt?gate_id=2237&secret=nvsbhostedpro3z',
	);

	private $_mediatrafficmeltdownGate='http://mediatrafficmeltdown.com/cnm_api.php?f=uc';

	/**
	 * т.к. для пользователей mediatrafficmeltdown нет емэйлов используем вместо них
	 *
	 * @var string
	 */
	private $_mediatrafficmeltdownDefEmail='noemail';

	/**
	 * ошибки в процессе логина
	 *
	 * @var integer
	 */
	private $_loginError=0;

	/**
	 * ответ полученный при опросе гейта
	 *
	 * @var array
	 */
	private $_result=array();

	/**
	 * группы текущего пользовтаеля - проверенные по гейтам
	 *
	 * @var array
	 */
	private $_currentUserGroups=array();

	public function __construct( $_intId=0 ) {
		parent::__construct( $_intId );
	}

	public function getSettings( &$arrRes ) {
		$arrRes=Core_Sql::getRecord( 'SELECT * FROM hct_admin_settings_tb WHERE user_id="'.Core_Users::$info['parent_id'].'"' );
		return !empty( $arrRes );
	}

	public function setSettings() {
		if ( !$this->_data->setFilter()->setChecker( array(
			'page_links'=>empty( $this->_data->filtered['page_links'] ),
			'rows_per_page'=>empty( $this->_data->filtered['rows_per_page'] ),
		) )->check() ) {
			return false;
		}
		Core_Sql::setInsertUpdate( 'hct_admin_settings_tb', $this->_data
			->setElement( 'id', Core_Users::$info['arrSettings']['id'] )
			->setMask( array( 'id', 'page_links', 'rows_per_page' ) )
			->getValid() );
		$this->getSettings( Core_Users::$info['arrSettings'] ); // обновим данные, может это делать прямо в getSettings? TODO!!! 03.11.2010
		return true;
	}

	// в данном проекте всё базируется на parent_id т.к. в нём хранится id из внешней системы профайлинга
	public function getId( &$_intId ) {
		if ( WorkHorse::$_isShell ) { // если вызов из шелл то текущего пользователя нет
			$_intId=0;
			return true;
		}
		if ( WorkHorse::$isBackend&&empty( Core_Users::$info['parent_id'] ) ) { // если бэкэнд то текущего тоже может не быть
			$_intId=0;
			return true;
		}
		if ( !empty( Core_Users::$info['parent_id'] ) ) { // пользователь фронтэнда
			$_intId=Core_Users::$info['parent_id'];
			return true;
		}
		return false; // должен генерить Error event
	}

	public function getError() {
		return $this->_loginError;
	}

	private function checkLoginData() {
		if ( !$this->_data->setFilter()->setChecker( array(
			'password'=>empty( $this->_data->filtered['password'] )||$this->_data->filtered['password']=='********',
			'username_empty'=>empty( $this->_data->filtered['username'] )||$this->_data->filtered['username']=='Email / Username',
			'username_noemail'=>!Core_Common::checkEmail( $this->_data->filtered['username'] ),
		) )->check() ) {
			$this->_data->getErrors( $_arrErr );
			if ( count( $_arrErr )>1||empty( $_arrErr['username_noemail'] ) ) {
				$this->_loginError=1; // введены невалидные данные
				return false; 
			}
			return $this->checkMediatrafficmeltdownGate(); // возможно пользователь из mediatrafficmeltdown.com
		}
		return $this->checkEthiccashGates();
	}

	private function getEncryptedPassword() {
		$_str=md5( 'ph4zanaspaqeqAtuphenuge*u6raS7awr7rUrecredrutucheTHut9dufecudr' ).
			md5( $this->_data->filtered['password'] ).md5( $this->_data->filtered['username'] );
		return substr( $_str, 0, 80 );
	}

	// т.к. сервис не возвращает id будем назначать вручную начиная от 100 000 000-ого id (хранится в int unsigned - до 4294967295)
	// но сначала проверим пользователя по никнэйму и паролю
	private function getParentIdToMediatrafficmeltdown() {
		// в этой системе пароли могут менятся, поэтому ищем только по никнэйму но из пользователей больше 100 000 000
		$_intRes=Core_Sql::getCell( '
			SELECT parent_id 
			FROM u_users 
			WHERE 
				nickname='.Core_Sql::fixInjection( $this->_data->filtered['username'] ).' AND
				parent_id>=100000000'
		 );
		if ( !empty( $_intRes ) ) {
			return $_intRes;
		}
		$_intRes=Core_Sql::getCell( 'SELECT parent_id FROM u_users ORDER BY parent_id DESC LIMIT 1' );
		if ( $_intRes<100000000 ) {
			$_intRes=100000000;
		} else {
			$_intRes++;
		}
		return $_intRes;
	}

	private function checkMediatrafficmeltdownGate() {
		// Instantiate a client object
		$client=new Zend_Http_Client( 
			$this->_mediatrafficmeltdownGate.'&un='.$this->_data->filtered['username'].'&pw='.$this->getEncryptedPassword(), 
			array(
				/*'adapter'=>'Zend_Http_Client_Adapter_Proxy',
				'proxy_host'=>'211.138.124.196',
				'proxy_port'=>80,*/
				'timeout'=>30
			)
		);
		$response=$client->request();
		if ( $response->getStatus()!=200 ) {
			$this->_loginError=6; // ошибка при получении ответа от Mediatrafficmeltdown
			return false;
		}
		$_arrStat=json_decode( $response->getBody(), true );
		if ( empty( $_arrStat['Status'] )||$_arrStat['Status']!='Yes' ) {
			$this->_loginError=7; // not correct
			return false;
		}
		$this->_result=array(
			'id'=>$this->getParentIdToMediatrafficmeltdown(),
			'email'=>$this->_mediatrafficmeltdownDefEmail,
			'password'=>$this->_data->filtered['password'],
			'name'=>$this->_data->filtered['username'],
		);
		$this->_currentUserGroups=array( 'Blog Fusion' ); // доступ только в Blog Fusion группу
		return true;
	}

	// проверка гейтов и вычисление активных/оплаченных групп пользователя
	private function checkEthiccashGates() {
		$this->_currentUserGroups=array();
		foreach( $this->_ethiccashGates as $k=>$v ) {
			if ( $this->checkGate( $k ) ) {
				$this->_currentUserGroups[]=$k;
				if ( $k=='Unlimited' ) { // этой группе доступен весь функционал, поэтому проверять дальше смысла не имеет
					break;
				}
			}
		}
		return !empty( $this->_currentUserGroups );
	}

	// проверка гейта и парсинг результатов в случае если гейт разрешён пользователю
	private function checkGate( $_strGroup='' ) {
		if ( empty( $_strGroup )||empty( $this->_ethiccashGates[$_strGroup] ) ) {
			return false;
		}
		// Instantiate a client object
		$client=new Zend_Http_Client( 
			$this->_ethiccashGates[$_strGroup].
			'&email='.urlencode( $this->_data->filtered['username'] ).
			'&password='.urlencode( $this->_data->filtered['password'] ), 
			array(
				/*'adapter'=>'Zend_Http_Client_Adapter_Proxy',
				'proxy_host'=>'211.138.124.196',
				'proxy_port'=>80,*/
				'timeout'=>30
			)
		);
		$response=$client->request();
		if ( $response->getStatus()!=200 ) {
			$this->_loginError=6; // ошибка при получении ответа от ethiccash
			return false;
		}
		$_arrRes=preg_split( '/[\n\r]+/i', $response->getBody(), -1, PREG_SPLIT_NO_EMPTY );
		if ( empty( $_arrRes )||$_arrRes[0]!='SUCCESS' ) {
			$this->_loginError=7; // not correct
			return false;
		}
		array_shift( $_arrRes );
		parse_str( join( '&', $_arrRes ), $this->_result ); // эти данные возможно надо сохранять в чистом виде в отдельной таблице 15.10.2009
		$this->_result['password']=$this->_data->filtered['password'];
		return true;
	}

	public function getIdByParent( $_intId=0 ) {
		return Core_Sql::getCell( 'SELECT id FROM u_users WHERE parent_id='.$_intId );
	}

	public function authorise() {
		if ( !$this->checkLoginData() ) {
			return false;
		}
		// проверка пользователя в wh
		$intRes=$this->getIdByParent( $this->_result['id'] );
		$_intId=Core_Sql::setInsertUpdate( 'u_users', (empty( $intRes )?
			array(
				'passwd'=>md5( $this->_result['password'] ),
				'email'=>$this->_result['email'],
				'parent_id'=>$this->_result['id'], // id в системе ethiccash.com
				'nickname'=>$this->_result['name'], // это всётаки не никнэйм, возможно надо хранить всё в айтемах TODO!!!
				'flg_status'=>1,
				'added'=>time(),
			)
		:
			array( // эти данные могут изменится на ethiccash.com
				'id'=>$intRes,
				'passwd'=>md5( $this->_result['password'] ),
				'email'=>$this->_result['email'],
				'nickname'=>$this->_result['name'],
			)
		) );
		$this->objManageAccess->setGroups( $_intId, $this->_currentUserGroups ); // обновляем группы пользователя
		if ( !$this->setUserSession( $_intId ) ) {
			return false;
		}
		// это всё относится к поддержке старого кода
		$this->oldCodeActions(); // внесение пользователей
		$this->getSettings( Core_Users::$info['arrSettings'] ); // получение данных
		Core_Users::$info['feedbackServiceOn']=$this->_feedbackServiceOn;
		// old code vars depercated!!!
		$_SESSION['feedbackServiceOn']=$this->_feedbackServiceOn;
		$_SESSION['CP_SESS_sessionuserid'] = $this->_result['id']; // id в системе ethiccash.com
		$_SESSION['CP_SESS_sessionusername'] = $this->_result['name'];
		$_SESSION['CP_SESS_sessionuseremail'] = $this->_result['email'];
		$_SESSION['CP_SESS_sessionuserpassword'] = $this->_result['password']; // in md5 not recoverable но как я понял это нигде не используется
		// As all customer is paid customer,allways updated to '1'
		// $_SESSION[$this->_sessPrefix.'fusionstatus'] = $fusion_user_type;
		$_SESSION['CP_SESS_fusionstatus'] = '1'; // ??
		$_SESSION['user_type'] = "admin"; // ??
		$_SESSION['sessionGen'] = $this->_result['email'];
		$_SESSION['paymenturl'] = $this->_paymentUrl; // ??
		return true;
	}

	// for old code
	private function oldCodeActions() {
		$intRes=Core_Sql::getCell( 'SELECT id FROM hct_admin_settings_tb WHERE user_id='.$this->_result['id'] );
		/*
		snippet_part_1-2-3
		это параметр, который отвечает за то, чтобы campaign part, которая получает больше кликов, чаще показывалась на сайте
		параметр snippet show-ration (4:2:1) как раз с этим и связан - то есть скрипт автоматически меняет 
		число показов для каждой части сниппета в зависимости от conversion rate данной части
		чем выше conversion rate, тем чаще эта часть будет показываться
		см. /snippets.php
		вощем раньше пользователи могли задавать сами
		теперь у всех одинаково
		*/
		Core_Sql::setInsertUpdate( 'hct_admin_settings_tb', (empty( $intRes )?
				array(
					'username'=>$this->_result['name'],
					'password'=>$this->_result['password'],
					'email_address'=>$this->_result['email'],
					'user_id'=>$this->_result['id'],
					'rows_per_page'=>15,
					'snippet_part_1'=>4,
					'snippet_part_2'=>2,
					'snippet_part_3'=>1,
				)
			:
				array( // эти данные могут изменится на ethiccash.com
					'id'=>$intRes,
					'password'=>$this->_result['password'],
					'email_address'=>$this->_result['email'],
					'username'=>$this->_result['name'],
				)
			)
		);
	}
}
?>