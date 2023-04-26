<?php

class site1_syndication extends Core_Module {

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Content Syndication', ),
			'actions'=>array(
				array( 'action'=>'create', 'title'=>'Create project', 'flg_tree'=>1 ),
				array( 'action'=>'manage', 'title'=>'Manage project', 'flg_tree'=>1 ),
				array( 'action'=>'site_manage', 'title'=>'Site manage', 'flg_tree'=>1 ),
				array( 'action'=>'content_manage', 'title'=>'Content manage', 'flg_tree'=>1 ),
				array( 'action'=>'manage_sites', 'title'=>'Manage sites' ),
				array( 'action'=>'categories', 'title'=>'Categories' ),
				array( 'action'=>'review', 'title'=>'Review backend' ),
				array( 'action'=>'review_content', 'title'=>'Review popap content', 'flg_tpl'=>1)
			),
		);
	}

	public function before_run_parent(){
		if ( WorkHorse::$isBackend ) {
			return;
		}
		$points=new Project_Syndication_Counters();
		$points->initUserPoints( $this->out['arrPoints'] );
	}

	public function categories(){
		$category = new Core_Category( 'Blog Fusion' );
		$category->getTree( $this->out['arrCategories'] );
//		p($this->out['arrCategories']);
	}
	
	public function manage_sites() {
		$this->objStore->getAndClear( $this->out );
		if ( !empty( $_POST['del'] ) ) {
			Project_Syndication_Sites::delByAdmin( $_POST['del'] );
			$this->location();
		}
		$_model=new Project_Syndication_Sites_Backend();
		if ( !empty( $_POST['arrNewCat'] ) ) {
			$_model->changeCategory( $_POST['arrNewCat']['site_id'], $_POST['arrNewCat']['category_id'], $_POST['arrNewCat']['site_type'] );
			$this->location();
		}
		$category = new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson']=Zend_Registry::get( 'CachedCoreString' )->php2json( $arrTree );
		$_model
			->withoutCategories( @$_GET['without_categories'] )
			->withType( @$_GET['with_type'] )
			->withOrder( @$_GET['order'] )
			->withPaging( array( 'url'=>$_GET, 'reconpage'=>30, ) )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
	}

	/**
	 * Создание проекта
	 *
	 */
	public function create() {
		$_model = new Project_Syndication();
		$this->out['arrStatus'] = Project_Syndication::$stat;
		// сохраняем проект
		if ( !empty( $_POST ) ) {
			$_model->setData( $_POST['arrPrj'] );
			if ( $_model->set() ) {
				$this->objStore->toAction( 'manage' )->set( array( 'msg'=> (($_POST['arrPrj']['id'])? 'saved':'created') ) );
				$this->location( array( 'action' => 'manage' ) );
			}
			$_model->getErrors( $this->out['arrErr'] );
			$this->out['arrPrj']=$_POST['arrPrj'];
			$this->out['jsonContent']=Zend_Registry::get( 'CachedCoreString' )->php2json( $_POST['arrPrj']['content'] );
			$this->out['jsonSites']=Zend_Registry::get( 'CachedCoreString' )->php2json( $_POST['arrPrj']['manual'] );
			$this->out['jsonSelectedCategories']=Zend_Registry::get( 'CachedCoreString' )->php2json( $_POST['arrPrj']['categories'] );
		}
		// берём данные для редактирования
		if ( !empty( $_GET['id'] ) ) {
			$_model->get( $this->out['arrPrj'], $_GET['id'] );
			$this->out['jsonContent']=Zend_Registry::get( 'CachedCoreString' )->php2json( $this->out['arrPrj']['content'] );
			$this->out['jsonSites']=Zend_Registry::get( 'CachedCoreString' )->php2json( $this->out['arrPrj']['manual'] );
			$this->out['jsonSelectedCategories']=Zend_Registry::get( 'CachedCoreString' )->php2json( $this->out['arrPrj']['categories'] );
			if ( $this->out['arrPrj']['flg_status'] == 4 ){
				Project_Syndication_Content_Plan::getProjectPlan( $this->out['arrPlan'], $this->out['arrContent'], $_GET['id'] );
				$this->out['arrStatus']=array_flip(Project_Syndication::$stat);
				$this->out['arrSiteType']=Project_Sites::$code;
			}
		}
		$this->out['jsonCategory']=Zend_Registry::get( 'CachedCoreString' )->php2json( Project_Syndication_Project_Category::getNoempty() ); // категории
	}
	
	/**
	 * Управление проектами
	 *
	 */
	public function manage() {
		$this->objStore->getAndClear( $this->out );
		$_model = new Project_Syndication();
		if ( !empty( $_POST ) ) { // del
			if ( !empty( $_POST['del'] ) ) {
				$this->objStore->set( array( 'msg'=>( $_model->del( array_keys( $_POST['del'] ) )? 'delete':'delete_error' ) ) );
			}
			$this->location();
		}
		$_model->withOrder( @$_GET['order'] )->withPaging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->getList( $this->out['arrList'] ); 
		$_model->getPaging( $this->out['arrPg'] );
	}
	
	/**
	 * Управление сайтами
	 *
	 */
	public function site_manage() {
		$this->objStore->getAndClear( $this->out );
		$_model = new Project_Syndication_Sites();
		if ( !empty($_POST) ){
			$_arrSites = json_decode($_POST['jsonSite'],true);
			$_model->setData($_arrSites);
			if ( $_model->set()){
				$this->objStore->set( array( 'msg'=>'added' ) );
				$this->location();
			}
			$this->objStore->set( array( 'msg'=>'add_error' ) );
			$this->location();
		}
		if ( !empty( $_GET['del'] ) ) {
			$this->objStore->set( array( 'msg'=>( $_model->del( array($_GET['del'])  )? 'delete':'delete_error' ) ) );
			$this->location(array('action'=>'site_manage'));
		}
		$_model->onlyOwner()->onlyType( @$_GET['site_type'] )->withOrder( @$_GET['order'] )->withPaging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->getList( $this->out['arrList'] );
		$_model->onlyOwner()->onlyType( @$_GET['site_type'] )->toPopupSelected()->getList( $_selectedSites );
		$this->out['jsonSites']=Zend_Registry::get( 'CachedCoreString' )->php2json($_selectedSites);
		$_model->getPaging( $this->out['arrPg'] );
	}
	
	/**
	 * Список контента который был опубликован
	 *
	 */
	public function content_manage() {
		$this->objStore->getAndClear( $this->out );
		Project_Syndication_Content::getContent( $this->out['arrList'] );
		if ( !empty( $_GET['del']) ){
			if ( !Project_Syndication_Content::deleteContent( $_GET['del'] ) ){
				$this->objStore->set( array( 'msg'=> 'delete_error') );
				$this->location( array( 'action'=>'content_manage' ));
			}
			$this->objStore->set( array( 'msg'=> 'delete') );
			$this->location( array( 'action'=>'content_manage' ));
		}
	}
	
	/**
	 * список КТ на проверку админу
	 *
	 */
	public function review() {
		$_model=new Project_Syndication_Content_Review();
		if (!empty($_POST['ajax_unblock'])){
			$_model->unBlockById($_POST['id']);
			exit();
		}
		$_model->getList( $this->out['arrList'] );
	}
	
	/**
	 * Поап с для контента
	 *
	 */
	public function review_content() {
		$this->objStore->getAndClear( $this->out );
		$_model=new Project_Syndication_Content_Review();
		if ( !empty($_POST['arr']) ){
			$_model->setData($_POST['arr']);
			if ( $_model->set() ){
				$_model->unBlockById($_POST['arr']['id']);
				$this->objStore->set( array( 'msg'=> 'success') );
				$this->location();
			}
			$this->out['msg']='error';
			$this->out['arr']=$_POST['arr'];
			$_model->unBlockById($_POST['arr']['id']);
		}
		if ( !$_model->get( $this->out['arrContent'], $_GET['id']) ){
			$this->out['blocked'] = 1;
		}
	}
}
?>