<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Syndication
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 18.03.2010
 * @version 0.1
 */

 /**
 * размещение КТ на СА
 *
 * @category Project
 * @package Project_Syndication
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Syndication_Arrange {

	public $logger;
	private $_time=0;
	/**
	* конструктор
	* @return void
	*/
	public function __construct() {
		$this->_time=time();
		$this->setLogger();
	}

	public function run() {
		$this->logger->info( 'Start Project_Syndication_Arrange by crontab at '.date( 'r', $this->_time ) );
		$_intIds=Core_Sql::getField( '
			SELECT id 
			FROM '.Project_Syndication::$tables['project'].' 
			WHERE flg_status IN('.Project_Syndication::$stat['approved'].','.Project_Syndication::$stat['progress'].')
			LIMIT 10
		' ); // разрешённые и стартовавшие проекты
		if ( empty( $_intIds ) ) {
			$this->logger->info( 'Stop Project_Syndication_Arrange::run - no project exists' );
			return false;
		}
		foreach( $_intIds as $v ) {
			$this->_projectId=$v;
			$this->process();
		}
		$this->logger->info( 'Finish Project_Syndication_Arrange by crontab at '.date( 'r' ) );
		return true;
	}

	private function process() {
		$data=new Core_Data( Core_Sql::getRecord( 'SELECT * FROM '.Project_Syndication::$tables['project'].' WHERE id='.$this->_projectId ) );
		$data->setFilter();
		$this->logger->info( 'Process "'.$data->filtered['title'].'" ['.$data->filtered['id'].'] project start' );
		if ( $data->filtered['flg_status']==Project_Syndication::$stat['approved'] ) { // генерация плана текущего проекта
			Project_Users_Fake::setCashe();
			// тут нужно поставить владельца проекта напрмер для того чтобы
			// сайты на которые будем постить выбирались без сайтов владельца проекта
			Project_Users_Fake::byUserId( $data->filtered['user_id'] );
			$plan=new Project_Syndication_Content_Plan( $data );
			if ( !$plan->generate() ) {
				$this->logger->info( 'Can\'t generate project post plan for "'.$data->filtered['title'].'" ['.$data->filtered['id'].'] project' );
				Project_Syndication_Content::getInstance( $this->_projectId )->status( 'error' ); // весь KT - ошибки
				Project_Syndication::status( 'completed', $this->_projectId );
				return;
			}
			Project_Users_Fake::retrieveFromCashe();
			// проверка на то хватит ли пойнтов для постинга проекта
			Project_Syndication::status( 'progress', $this->_projectId );// выполнение проекта в процессе
		} else {
			// проверка на то хватит ли пойнтов для постинга проекта
		}
		$this->postContent($data);
		$this->logger->info( 'Process "'.$data->filtered['title'].'" ['.$data->filtered['id'].'] project finish' );
	}

	private function postContent($data) {
		Project_Syndication_Content_Plan::getProjectPlan( $_arrPlan, $arrContent, $this->_projectId );
		Project_Users_Fake::zero(); 
		foreach( $_arrPlan as $v ) {
			switch( $v[0]['site_type'] ) {
				case Project_Sites::BF: Project_Syndication_Sites_Blogfusion::getInstance()->setData( $v, $arrContent )->run(); break;
				case Project_Sites::PSB: Project_Syndication_Sites_Psb::getInstance()->setData( $v, $arrContent )->run(); break;
				case Project_Sites::NCSB: Project_Syndication_Sites_Ncsb::getInstance()->setData( $v, $arrContent )->run(); break;
				case Project_Sites::NVSB: Project_Syndication_Sites_Nvsb::getInstance()->setData( $v, $arrContent )->run(); break;
				case Project_Sites::CNB: Project_Syndication_Sites_Cnb::getInstance()->setData( $v, $arrContent )->run(); break;
			}
		}
		Project_Syndication_Checker::setUrls( Project_Sites::getLastUrls() );
		if ( Project_Syndication_Content_Plan::isCompleted( $this->_projectId ) ) {
			Project_Syndication_Content::getInstance( $this->_projectId )->status();
			Project_Syndication::status( 'completed', $this->_projectId );
			Project_Syndication_Counters::setPlacementPoint( $this->_projectId );
			$this->logger->info( 'Process "'.$data->filtered['title'].'" ['.$data->filtered['id'].'] project and content status updated' );
		}
	}

	private function setLogger() {
		$formatter = new Zend_Log_Formatter_Simple( Zend_Log_Formatter_Simple::DEFAULT_FORMAT.(php_sapi_name()=='cli'?PHP_EOL:'<br />'));
		$writer=new Zend_Log_Writer_Stream( 'php://output' );
		$writer->setFormatter( $formatter );
		$this->logger = new Zend_Log( $writer );
	}
}
?>