<?php
class Project_Publisher_Arrange {

	public $logger, $project;

	public function __construct() {
		$this->setLogger();
	}

	public function run() {
		$this->logger->info( 'Start Project_Publisher_Arrange by crontab at '.date( 'r' ) );
		Project_Publisher::getInstance()->toShell()->onlyIds()->getList( $_intIds );
		if ( empty( $_intIds ) ) {
			$this->logger->info( 'Stop Project_Publisher_Arrange::run - no project exists' );
			return false;
		}
		Project_Publisher::status( 'inProgress', $_intIds );
		foreach( $_intIds as $v ) {
			if ( !$this->getProject( $v ) ) {
				continue;
			}
			$this->process();
		}
		$this->logger->info( 'Finish Project_Publisher_Arrange by crontab at '.date( 'r' ) );
		return true;
	}

	private function process() {
		$this->logger->info( 'Process "'.$this->project->filtered['title'].'" ['.$this->project->filtered['id'].'] project start' );
		if ( empty( $this->project->filtered['flg_mode'] ) ) {
			Project_Publisher_Arrange_Automatic::run( $this->logger, $this->project );
		} else {
			Project_Publisher_Arrange_Manual::run( $this->logger, $this->project );
		}
		$this->logger->info( 'Process "'.$this->project->filtered['title'].'" ['.$this->project->filtered['id'].'] project finish' );
	}

	private function getProject() {
		if ( !Project_Publisher::getInstance()->onlyOne()->withIds( $_intId )->getList( $arrRes ) ) {
			return false;
		}
		// Zend_Registry::set('objUser', new Project_Users()); // тут надо установить айди пользователя - владельца проекта TODO!!! 24.03.2011
		$this->project=new Core_Data( $arrRes );
		$this->project->setFilter();
		return true;
	}

	private function setLogger() {
		$formatter = new Zend_Log_Formatter_Simple( Zend_Log_Formatter_Simple::DEFAULT_FORMAT.(php_sapi_name()=='cli'?PHP_EOL:'<br />'));
		$writer=new Zend_Log_Writer_Stream( 'php://output' );
		$writer->setFormatter( $formatter );
		$this->logger=new Zend_Log( $writer );
	}
}
?>