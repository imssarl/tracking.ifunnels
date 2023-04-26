<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Publisher
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 17.03.2011
 * @version 2.0
 */


/**
 * Data management for module (user interface)
 *
 * @category Project
 * @package Project_Publishing
 * @copyright Copyright (c) 2005-2011, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
 
class Project_Publisher extends Core_Storage {

	/**
	 * Статусы проекта
	 * @var array
	 */
	public static $stat=array(
		'notStarted'=>0,
		'inProgress'=>1,
		'crossLinking'=>2,
		'complete'=>3
	);
	public static $tables=array(
		'project'=>'pub_project',
		'automatic'=>'pub_autosites',
		'cache'=>'pub_cache',
		'schedule'=>'pub_schedule'
	);
	public $table='pub_project';

	public $fields=array('id','user_id','flg_mode','category_id', 'mastersite_id','flg_status','flg_type','flg_source','flg_posting','flg_mastersite',
	'flg_circular','start','end','time_between','random','title','settings','tags','edited','added');
	private $_userId=false;
	private static $_instance=NULL;
	private $_type=false;

	public function __construct( $_withoutUser=false ){
		if ( $_withoutUser ) {
			return;
		}
		if ( !Zend_Registry::get( 'objUser' )->getId( $_int ) ) {
			throw new Exception( Core_Errors::DEV.'|Zend_Registry::get( \'objUser\' )->getId( $_int ) is not return an User Id' );
			return;
		}
		$this->_userId=$_int;
	}

	public static function getInstance() {
		if ( self::$_instance==NULL ) {
			self::$_instance=new Project_Publisher( true );
		}
		return self::$_instance;
	}

	/**
	 * Set project type. Type from Project_Sites (BF,CNB,NVSB,)
	 * @param  $_intType
	 * @return Project_Publisher bool
	 */
	public function setType( $_intType ){
		$this->_type=$_intType; 
		return $this;
	}

	public function getType(){
		return $this->_type;
	}

	/**
	 * Создание проекта
	 *
	 * @return bool
	 */
	public function set(){
		$this->_data->setFilter();
		// проверим не запустился ли проект пока мы его редактировали
		if ( !empty( $this->_data->filtered['id'] )&& $this->_data->filtered['flg_status']==self::$stat['notStarted'] ) {
			$_arrPrj=array();
			$this->onlyOne()->withIds( $this->_data->filtered['id'] )->getList( $_arrPrj );
			$this->_data->setElement( 'flg_status', $_arrPrj['flg_status'] );
			// если таки запустился - показываем ошибку (т.к. пользователь отослал все данные а можно только добавлять конетнт)
			if ( !$this->_data->setChecker( array( 'flg_status'=>( $this->_data->filtered['flg_status']!=self::$stat['notStarted'] ) ) )->check() ) {
				return $this->setError('Can\'t save, project allready in progress');
			}
		}
		// проект в процессе
		if ( !empty( $this->_data->filtered['flg_status'] )&&$this->_data->filtered['flg_status']==self::$stat['inProgress'] ) {
			if ( empty( $_arrPrj ) ) {
				$this->withIds( $this->_data->filtered['id'] )->onlyOne()->get( $_arrPrj );
			}
			$_arrPrj['title'] = $this->_data->filtered['title'];
			$_arrPrj['end'] = $this->_data->filtered['end'];
			$this->_data->set( $_arrPrj )->setFilter(); // т.к. на форме все поля отключены берём их из бд
			if ( !Core_Sql::setInsertUpdate( $this->table, $this->_data->setMask( $this->fields )->getValid() )) {
				return $this->setError('Can\'t update project');
			}
			// manual проект
			if( $this->_data->filtered['flg_mode']==0 ){
				$obj=new Project_Publisher_Schedule($this->_data);
				if( !$obj->setContent( json_decode($this->_data->filtered['jsonContentIds'],true) )->addContent() ){
					return $this->setError('Can\'t add content to project');
				}
			}
			return true;
		}
		// рестарт проекта
		if ( !empty( $this->_data->filtered['flg_status'] )&&$this->_data->filtered['flg_status']==self::$stat['complete']&&$this->_data->filtered['restart'] ) {
			$this->_data->setElement( 'flg_status', self::$stat['inProgress'] );
		}
		// если проект ещё не запущен или уже завершён - можно менять все поля
		if( !$this->_type ){
			throw new Exception( Core_Errors::DEV.'| not set Project_Publiser::$_type. use setType( int )' );
			return;
		}
		$this->_data->setElement( 'flg_type', $this->_type );
		if ( !$this->_data->setChecker( array(
			'title'=>empty( $this->_data->filtered['title'] ),
			'flg_source'=>empty( $this->_data->filtered['flg_source'] ),
			'flg_posting'=>empty( $this->_data->filtered['flg_posting'] ),
		) )->check() ) {
			return $this->setError('Can\'t save project. Please fill all required fields.');
		}
		if ( empty( $this->_data->filtered['start'] ) ) {
			$this->_data->setElement( 'start', time() );
		}
		if ( empty( $this->_data->filtered['id'] ) ) {
			$this->_data
				->setElement( 'added', time() )
				->setElement( 'user_id', $this->_userId );
		} else {
			$this->_data->setElement( 'edited', time() );
		}
		$this->_data->filtered['settings']=serialize($this->_data->filtered['settings']);
		// сохраняем проект
		$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( $this->table, $this->_data->setMask( $this->fields )->getValid() ) );
		if ( empty( $this->_data->filtered['id'] ) ) {
			return $this->setError('Can\'t save project');
		}
		// Добавление контента и сайтов в проект.
		if( !empty($this->_data->filtered['flg_mode']) ){
			// Manual
			$obj=new Project_Publisher_Schedule( $this->_data );
			if( !$obj->setSites($this->_data->filtered['arrSiteIds'])->setContent( json_decode($this->_data->filtered['jsonContentIds'],true))->generate() ){
				return $this->setError('Can\'t create schedule for manual project');
			}
		} else {
			// Autosite
			$obj=new Project_Publisher_Autosites($this->_data->filtered['id']);
			if( !$obj->setData( $this->_data->filtered['arrSiteIds'] )->store()){
				return $this->setError('Can\'t add sites in auto project');
			}
		}
		return true;
	}

	/**
	 * Удаление проекта из всех таблиц.
	 * 
	 * @param mix $_mix
	 * @return bool
	 */
	public function del( $_mix=0 ) {
		if ( empty( $_mix ) ) {
			return $this->setError('id project can\'t by empty');
		}
		$_mix=is_array( $_mix ) ? $_mix:array( $_mix );
		Core_Sql::setExec( '
			DELETE p, s, a, c
			FROM '.$this->table.' p
			LEFT JOIN pub_schedule s ON s.project_id=p.id
			LEFT JOIN pub_autosites a ON a.project_id=p.id
			LEFT JOIN pub_cache c ON c.project_id=p.id
			WHERE p.id IN('.Core_Sql::fixInjection( $_mix ).')
		' );
		return true;
	}

	private $_withStatus=false; // cо статистикой
	private $_toShell=false; // данные для shell-скрипта
	
	public function getOwnerId(){
		return $this->_userId;
	}

	public function toShell(){
		$this->_toShell=true;
		return $this;
	}

	public function withStatus() {
		$this->_withStatus=true;
		return $this;
	}

	protected function init() {
		$this->_withStatus=false;
		$this->_toShell=false;
		parent::init();
	}

	protected function assemblyQuery() {
		if ( $this->_onlyIds ) {
			$this->_crawler->set_select( 'd.id' );
		} else {
			$this->_crawler->set_select( 'd.*' );
		}
		$this->_crawler->set_from( $this->table.' d' );
		if( $this->_toShell ){
			$this->_crawler->set_where( 'd.flg_status IN ( ' . self::$stat['notStarted'] . ','. self::$stat['inProgress'] . ' )' );
			$this->_crawler->set_where( 'd.start <= '. Core_Sql::fixInjection( time() ) );
		}
		if ( !empty( $this->_withIds ) ) {
			$this->_crawler->set_where( 'd.id IN ('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}
		if ( $this->_onlyOwner ) {
			$this->_crawler->set_where( 'd.user_id='.$this->getOwnerId() );
		}
		if( $this->_type ){
			$this->_crawler->set_where( 'd.flg_type='.$this->_type );
		}
		if( $this->_withStatus ) {
			$this->_crawler->set_select( '(SELECT COUNT(*) FROM pub_schedule as s WHERE s.project_id = d.id) as count_content' );
			$this->_crawler->set_select( '(SELECT COUNT(*) FROM pub_schedule as s WHERE s.project_id = d.id AND s.flg_status = 1) as count_posted_content' );
		}
	}

	public function getList( &$arrRes ){
		if ( self::$_instance==NULL ) {
			$this->onlyOwner();
		}
		$_onlyOne=$this->_onlyOne;
		parent::getList( $arrRes );
		if( $_onlyOne ){
			$this->prepare( $arrRes );
		}
		return !empty($arrRes);
	}

	private function prepare( &$arrRes ) {
		if ( empty($arrRes) ){
			return false;
		}
		$arrRes['settings']=unserialize($arrRes['settings']);
		return true;
	}

	public static function status( $_strKey='', $_arrIds=0 ){
		if( empty($_arrIds) || !in_array($_strKey, array_keys(self::$stat)) ){
			return false;
		}
		return Core_Sql::setExec('UPDATE '.self::$tables['project'] .' SET flg_status='. self::$stat[$_strKey] .' WHERE id IN ('.Core_Sql::fixInjection( $_arrIds ).')');
	}

	public static function update( $strField, $mixValue, $mixIds ){
		if( empty($mixIds) || empty($strField) || empty($mixValue) ){
			return false;
		}
		return Core_Sql::setExec('UPDATE '.self::$tables['project'] .' SET '.$strField .'='.Core_Sql::fixInjection($mixValue) .' WHERE id IN ('.Core_Sql::fixInjection( $mixIds ).')');
	}

}