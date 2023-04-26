<?php
class Project_Publisher_Arrange_Automatic {

	// мессив с проектом
	private $_project;
	// объекты
	private $_logger, $_autosites;

	public static function run( Zend_Log $logger, Core_Data $data ) {
		$_obj=new Project_Publisher_Arrange_Automatic( $logger, $data );
		return $_obj->process();
	}

	public function __construct( Zend_Log $logger, Core_Data $data ) {
		$this->_logger=$logger;
		$this->_project=&$data->filtered;
	}

	public function process() {
		if ( !$this->prepare() ) {
			$this->updateProject();
			return;
		}
		$this->publicate();
		$this->updateProject();
	}

	private function updateProject() {
		Project_Publisher::update( 'counter', $this->_project['counter'], $this->_project['id'] );
		$_intNextStart=$this->_project['start']+( 86400*$this->_project['post_every'] );
		// если дата окончания не указана или следующий старт будет раньше чем $this->_project['end']
		if ( empty( $this->_project['end'] )||$_intNextStart<$this->_project['end'] ) {
			Project_Publisher::update( 'start', ( $this->_project['start']+( 86400*$this->_project['post_every'] ) ), $this->_project['id'] );
			$this->_logger->info( 'updateed project start time' );
			return;
		}
		Project_Publisher::status( 'complete', $this->_project['id'] );
		$this->_logger->info( 'project completed' );
	}

	private function prepare() {
		$this->_autosites=new Project_Publisher_Autosites( $this->_project['id'] );
		if ( !$this->_autosites->getList( $this->_sites )||
			!Project_Content::factory( $this->_project['flg_content'] )
				->setFilter( $this->_project['settings'] )
				->setLimited( $this->_project['post_num'] )
				->setCounter( $this->_project['counter'] )
				->getList( $_arrContent ) ) {
			return false;
		}
		$this->_cashe=new Project_Publisher_Cache( $this->_project['id'] );
		$this->_cashe->setSiteList( $this->_sites );
		foreach( $_arrContent as $v ) {
			if ( !empty( $this->_project['tags'] ) ) { // тэги нужны только для блогфьюжн пока
				$v['tags']=$this->_project['tags'];
			}
			$this->_sites[$this->_cashe->getUniqId( $v )]['posts'][]=$v;
		}
		return true;
	}

	private function publicate() {
		$_adapter=Project_Publisher_Adapter_Factory::get( $this->_project['flg_type'] );
		foreach( $this->_sites as $v ) {
			if ( empty( $v['posts'] ) ) {
				continue;
			}
			$this->_logger->info( 'start to publicate post content to ['.$v['site_id'].']');
			if ( !$_adapter->setSite( $v['site_id'] )->setContent( $v['posts'] )->post() ) {
				$this->_logger->info( 'fail publicated '.count( $v['posts'] ).' of posts' );
				continue;
			}
			$this->_cashe->setSiteId( $v['site_id'] );
			foreach( $v['posts'] as $post ) {
				$this->_cashe->set( $post['body'] );
			}
			$_intCount=count( $v['posts'] );
			$this->_project['counter']+=$_intCount;
			$this->_logger->info( 'success publicated '.$_intCount.' of posts' );
		}
	}
}
?>