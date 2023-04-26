<?php
class site1_content extends Core_Module {
	
	public function set_cfg(){		
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Content Projects', ),
			'actions'=>array(
				array( 'action'=>'blog', 'title'=>'Blog Content Projects', 'flg_tree'=>1 ),
				array( 'action'=>'blog_create', 'title'=>'Blog create', 'flg_tree'=>1 ),
				array( 'action'=>'cnb', 'title'=>'CNB Content Projects', 'flg_tree'=>1 ),
				array( 'action'=>'cnb_create', 'title'=>'CNB create', 'flg_tree'=>1 ),
				array( 'action'=>'ncsb', 'title'=>'NCSB Content Projects', 'flg_tree'=>1 ),
				array( 'action'=>'ncsb_create', 'title'=>'NCSB create', 'flg_tree'=>1 ),
				array( 'action'=>'statistic', 'title'=>'Statistic', 'flg_tree'=>1 ),
				array( 'action'=>'selectcontent', 'title'=>'Select content', 'flg_tree'=>1, 'flg_tpl'=>1 ),
			),
		);
	}

	private $_model=null;
	private $_arrList=array();

	public function before_run_parent(){
		$this->_model=new Project_Publisher();
	}

	public function statistic(){
		/*
		$_project = new Project_Publishing( new Project_Publishing_Cnb() );
		$_project->get( $this->out['arrPrj'], $_GET['id'] );
		$_project->setData( $this->out['arrPrj'] );
		$_project->data->setFilter();
		$_shedule = new Project_Publishing_Cnb_Content( $_project->data );
		$_shedule->withSite()->getList( $this->out['arrList'] );
		if ( $this->out['arrPrj']['flg_source']==1 ){ // если статьи
			$_article = new Project_Articles();
			foreach ( $this->out['arrList'] as $_key=>&$_item ){
				$_article->withIds( $_item['content_id'] )->onlyOne()->getContent( $_item['content'] );
				$_item['content']['url'] = Core_String::getInstance( strtolower( strip_tags( $_item['content']['title'] ) ) )->toSystem( '-' ).'.txt';
			}
		}*/
	}
	
	public function blog() {
		$this->objStore->getAndClear( $this->out );
		$this->_model->setType( Project_Sites::BF );
		$this->prepare_manage();
	}
	
	public function blog_create() {
		$this->_model->setType( Project_Sites::BF );
		$sites=new Project_Wpress();
		$sites->toJs()->getList( $this->_arrList );
		$this->prepare_create();
	}
	
	public function cnb() {
		$this->objStore->getAndClear( $this->out );
		$this->_model->setType( Project_Sites::CNB );
		$sites=new Project_Sites( Project_Sites::CNB );
		$sites->getList( $this->_arrList );
		$this->prepare_manage();
	}

	public function cnb_create() {
		$this->_model->setType( Project_Sites::CNB );
		$this->prepare_create();
	}

	private function prepare_manage(){
		if( !empty( $_POST['del'] ) ) {
			if ( $this->_model->del( $_POST['del'] ) ) {
				$this->objStore->set( array( 'msg'=>'delete' ) );
				$this->location( array( 'action'=>'blog' ) );
			} else {
				$this->objStore->set( array( 'msg'=>'error' ) );
				$this->location( array( 'action'=>'blog' ) );
			}
		}
		$this->_model
		->withPaging(array(
			'url'=>@$_GET,
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		))
		->withOrder( @$_GET['order'] )
		->withStatus()
		->onlyOwner()
		->getList( $this->out['arrList'] );
		$this->_model->getPaging( $this->out['arrPg'] );
	}
	
	private function prepare_create(){
		if ( !empty( $_POST['arrPrj'] ) ) {
			$_POST['arrPrj']['start'] = strtotime($_POST['arrPrj']['start']);
			$_POST['arrPrj']['settings']=$_POST['arrCnt'][$_POST['arrPrj']['flg_source']]['settings'];
			if ( $this->_model->setData( $_POST['arrPrj'] )->set() ) {
				$this->location( array( 'action'=>'blog' ) );
			}
			$this->_model
				->getEntered( $this->out['arrPrj'] )
				->getErrors( $this->out['arrErr'] );
		}
		// edit
		if ( !empty( $_GET['id'] ) ) {
			$this->_model->onlyOne()->withIds($_GET['id'])->getList( $this->out['arrPrj'] );
			$data=new Core_Data($this->out['arrPrj']);
			$data->setFilter();
			$obj = new Project_Publisher_Schedule( $data );
			$obj->getList( $this->out['arrPrj']['shedule'] );
			$this->out['jsonShedule']=Zend_Registry::get( 'CachedCoreString' )->php2json($this->out['arrPrj']['shedule']);
		}
		$arrSitesCategory = array();
		foreach ( $this->_arrList as $value ) {
				$arrSitesCategory[] = $value['category_id'];
		}
		$category = new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrContentCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		foreach ( $arrTree as $firstkey => $value ) {
			foreach ( $arrTree[$firstkey]['node'] as $secondkey => $value){
				if ( !in_array( $value['id'], $arrSitesCategory ) ){
					unset( $arrTree[$firstkey]['node'][$secondkey] );
				}
			}
			if ( empty($arrTree[$firstkey]['node']) ){
				unset( $arrTree[$firstkey] );
		 	}
		}
		$this->out['arrContentCategories'] = $arrTree;
		$this->out['treeJson']=Zend_Registry::get( 'CachedCoreString' )->php2json( $arrTree );
		$this->out['jsonSitesList']=Zend_Registry::get( 'CachedCoreString' )->php2json( $this->_arrList );
		$this->out['projectType'] = $this->_model->getType();
	}


	public function ncsb() {}

	public function ncsb_create() {}

	public function selectcontent() {
		Project_Content::factory( $_GET['flg_source'] )
			->setFilter( $_GET['arrFlt'] )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFlt'] );
	}
	
}
?>