<?php
class Project_Publisher_Arrange_Manual {

	// мессив с проектом
	private $_project;
	// объекты
	private $_logger, $_schedule;

	public static function run( Zend_Log $logger, Core_Data $data ) {
		$_obj=new Project_Publisher_Arrange_Manual( $logger, $data );
		return $_obj->process();
	}

	public function __construct( Zend_Log $logger, Core_Data $data ) {
		$this->_logger=$logger;
		$this->_project=&$data->filtered;
		$this->_adapter=Project_Publisher_Adapter_Factory::get( $this->_project['flg_type'] );
		$this->_schedule=new Project_Publisher_Schedule( $data );
	}

	// стартуем выполнение проекта
	public function process() {
		if ( !$this->_schedule->onlyNonPosted()->withOrder( 'd.site_id--dn' )->withTime( time() )->getList( $arrSchedule ) ) {
			$this->updateProject();
			return false;
		}
		$this->setSites( $arrSchedule );
		$this->publicate();
		$this->updateProject();
		return true;
	}

	// обновляем данные о проекте
	private function updateProject() {
		Project_Publisher::update( 'counter', $this->_project['counter'], $this->_project['id'] );
		if ( empty( $this->_project['end'] )&&$this->_schedule->onlyNonPosted()->getList( $_arrSchedule ) ) { // проект ещё не закончен - не всё опубликовали
			return;
		} elseif ( $this->_project['end']>time() ) { // проект ещё не закончен - указана дата окончания и она в будущем
			return;
		}
		$this->applyNetworking(); // по окончанию публикации добавляем ссылки
		Project_Publisher::status( 'complete', $this->_project['id'] );
		$this->_logger->info( 'project completed' );
	}

	// публикуем контент заново с добавлением сетевых ссылок
	private function applyNetworking() {
		if ( !$this->_schedule->generateNetworking( $_arrSchedule ) ) {
			return;
		}
		$this->_logger->info( 'update project content for Network linking' );
		Project_Publisher::status( 'crossLinking', $this->_project['id'] );
		foreach( $_arrSchedule as $k=>$from ) {
			foreach( $_arrSchedule as $to ) {
				if ( $from['link_to']==$to['id'] ) { // закольцованая сеть (circular)
					$_arrSchedule[$k]['body'].=$this->_adapter->getLink( $to );
				}
				if ( $from['link_to_master']==$to['id'] ) { // сеть с ведущим блогом (master blog)
					$_arrSchedule[$k]['body'].=$this->_adapter->getLink( $to );
				}
			}
		}
		$this->setSites( $_arrSchedule );
		$this->publicate();
	}

	// распределяем контент по сайтам
	private function setSites( &$_arrSchedule ) {
		$this->_sites=array();
		foreach( $_arrSchedule as $v ) {
			if ( !empty( $this->_project['tags'] ) ) { // тэги нужны только для блогфьюжн пока
				$v['tags']=$this->_project['tags'];
			}
			$this->_sites[$v['site_id']]['posts'][]=$v;
		}
	}

	// публикуем на сайтах
	private function publicate() {
		foreach( $this->_sites as $siteId=>$v ) {
			$this->_logger->info( 'start to publicate post content to ['.$siteId.']');
			$_bool=$this->_schedule->setHistory(
				$this->_adapter->setSite( $siteId )->setContent( $v['posts'] )->post(),
				$this->_adapter->getPublicateResult()
			);
			$_intCount=count( $v['posts'] );
			if ( !$_bool ) {
				$this->_logger->info( 'fail publicated '.$_intCount.' of posts' );
				continue;
			}
			$this->_project['counter']+=$_intCount; // при вызове из applyNetworking в БД не сохраняется
			$this->_logger->info( 'success publicated '.$_intCount.' of posts' );
		}
	}
}
?>