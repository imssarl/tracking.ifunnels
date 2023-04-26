<?php

class site1_blogfusion extends Core_Module {
	
	private $_model;
	
	public function before_run_parent(){
		// добавление стандартных тем и плагинов модуля блогфьюжн для новых пользователей
		$_theme=new Project_Wpress_Theme();
		$_theme->addCommonThemesToNewUser();
		$_plugin=new Project_Wpress_Plugins();
		$_plugin->addCommonPluginsToNewUser();
		// модель для использования в модуле
		$this->_model=new Project_Wpress();
	}

	public function after_run_parent(){
		if ( $_GET['id'] ) {
			$this->_model->getList( $this->out['menuBlog'] );
		}
	}

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Blogfusion', ),
			'actions'=>array(
				array( 'action'=>'upgrade', 'title'=>'Upgrade Blog', 'flg_tree'=>1 ),
				array( 'action'=>'create', 'title'=>'Create Blog', 'flg_tree'=>1 ),
				array( 'action'=>'ajaxcreate', 'title'=>'Create', 'flg_tree'=>1, 'flg_tpl' => 1 ),
				array( 'action'=>'import', 'title'=>'Import Blog', 'flg_tree'=>1 ),
				array( 'action'=>'manage', 'title'=>'Manage Blog', 'flg_tree'=>1 ),
				array( 'action'=>'plugins', 'title'=>'Plugins', 'flg_tree'=>1 ),
				array( 'action'=>'themes', 'title'=>'Themes', 'flg_tree'=>1 ),
				array( 'action'=>'themes_search', 'title'=>'Search Themes', 'flg_tree'=>1,'flg_tpl'=>1 ),
				array( 'action'=>'plugin_search', 'title'=>'Search Plugins', 'flg_tree'=>1,'flg_tpl'=>1 ),
				array( 'action'=>'general', 'title'=>'Manage Blog Data', 'flg_tree'=>1 ),
				array( 'action'=>'categories', 'title'=>'Blog Categories', 'flg_tree'=>1 ),
				array( 'action'=>'posts', 'title'=>'Blog Posts', 'flg_tree'=>1 ),
				array( 'action'=>'comments', 'title'=>'Blog Comments', 'flg_tree'=>1 ),
				array( 'action'=>'pages', 'title'=>'Blog Pages', 'flg_tree'=>1 ),
				array( 'action'=>'edittheme', 'title'=>'Blog Edit Theme', 'flg_tree'=>1 ),
				array( 'action'=>'changetheme', 'title'=>'Blog Change Theme', 'flg_tree'=>1 ),
				array( 'action'=>'testdb', 'title'=>'Test DB Connection', 'flg_tree'=>1, 'flg_tpl' => 1 ),
				array( 'action'=>'muliboxmanage', 'title'=>'Popup manage blog', 'flg_tree'=>1, 'flg_tpl' => 1 ),
				array( 'action'=>'multiboxlist', 'title'=>'Popup Blog List', 'flg_tree'=>1, 'flg_tpl' => 1 ),
				array( 'action'=>'multiboxtheme', 'title'=>'Popup Theme', 'flg_tree'=>1, 'flg_tpl' => 1 ),
				array( 'action'=>'multiboxwidget', 'title'=>'Popup Widgets', 'flg_tree'=>1, 'flg_tpl' => 1 ),
				array( 'action'=>'blogclone', 'title'=>'Clone blog', 'flg_tree'=>1  ),
			),
		);
	}

	public function multiboxwidget(){
		$this->_model->getBlog($this->out['arrBlog'],$_GET['id']);
	}
	
	public function themes_search(){
		$this->objStore->getAndClear( $this->out );
		$_model=new Project_Wpress_Theme();
		if (!empty($_POST)){
			if ( !$_model->downloadTheme($_POST['arr']['link']) ){
				$_model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location();				
			}
			$this->objStore->set( array( 'msg'=>'added' ) );
			$this->location();
		}			
		if (!empty($_GET)){
			$_GET['arr']['per_page']=21;
			$_GET['arr']['page']=(!empty($_GET['page']))?$_GET['page']:1;
			$this->out['arrList']=$_model->search($_GET['arr']);
		}
	}

	public function plugin_search(){
		$this->objStore->getAndClear( $this->out );
		$_model=new Project_Wpress_Plugins();
		if ( !empty($_POST) ){
			if ( !$_model->downloadPlugin($_POST['arr']['link']) ){
				$_model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location();				
			}
			$this->objStore->set( array( 'msg'=>'added' ) );
			$this->location();
		}			
		if ( !empty($_GET) ){
			$_GET['arr']['per_page']=21;
			$_GET['arr']['page']=(!empty($_GET['page']))?$_GET['page']:1;
			$this->out['arrList']=$_model->search($_GET['arr']);
		}
	}
		
	public function upgrade() {
		$_upgrader=Project_Wpress_Connector_Upgrade::getInstance()->runAsApplication();
		if ( !empty( $_POST['arrSettings'] )&&$_upgrader->setUpgradeSettings( $_POST['arrSettings'] ) ) {
			$_upgrader->setUpgradeBlogs( $_POST['jsonBlogs'], $_POST['arrSettings'] );
			$this->location();
		}
		$_upgrader->getCurVersion( $this->out['newVersion'] );
		$_upgrader->getUpgradeSettings( $this->out['arrSettings'] );
		$this->_model
			->onlyCount()
			->toVersion( $this->out['newVersion'] )
			->getList( $this->out['intNumOldBlogs'] );
		if ( $_upgrader->getUpgradeBlogs( $this->out['arrBlogsStatus'] ) ) {
			$this->_model
				->withIds( array_keys( $this->out['arrBlogsStatus'] ) )
				->getList( $this->out['arrList'] );
		}
	}
	
	public function blogclone(){
		if ( !empty($_POST) && !empty($_GET['id']) ){
			$this->_model->getBlog($oldBlog,$_GET['id']);
			$_POST['arrBlog']['ftp_host']=$_POST['arrFtp']['address'];
			$_POST['arrBlog']['ftp_username']=$_POST['arrFtp']['username'];
			$_POST['arrBlog']['ftp_password']=$_POST['arrFtp']['password'];
			$_POST['arrBlog']['ftp_directory']=$_POST['arrFtp']['directory'];
			if ( $this->_model->setData( $oldBlog )->copyBlog( $_POST['arrBlog'] ) ){
				$this->location( array( 'action'=>'manage' ) );
			}
			$this->out['arrErr']=$this->_model->getErrors();
			$this->out['arrBlog']=$_POST['arrBlog'];
			$this->out['arrFtp']=$_POST['arrFtp'];
		}
	}
	
	public function multiboxtheme(){
		$modelThemes=new Project_Wpress_Theme();
		$modelThemes->withPreview()->getList( $this->out['arrThemes'] );
	}
	
	public function multiboxlist() {
		if ( !isset( $_GET['noversion'] ) ) {
			Project_Wpress_Connector_Upgrade::getInstance()->getCurVersion( $newVersion );
			$this->_model->toVersion( $newVersion );
		}
		$this->_model
		->withOrder( @$_GET['order'] )
		->withCategories( @$_GET['category_id'] )
		->getList( $this->out['arrList'] );
		if ( isset( $_GET['noversion'] ) ) {
			$_category=new Project_Wpress_Content_Category();
			foreach ( $this->out['arrList'] as &$blog ) {
				$_category->setBlogById( $blog['id'] );
				$_category->getList( $blog['categories'] );
			}
		}
	}

	public function create() {
		if ( !empty( $_POST['arrBlog'] ) ) {
			$_POST['arrBlog']['arrFtp']=$_POST['arrFtp'];
			unset( $_POST['arrFtp']['id'] );
			$_POST['arrBlog']['ftp_host']=$_POST['arrFtp']['address'];
			$_POST['arrBlog']['ftp_username']=$_POST['arrFtp']['username'];
			$_POST['arrBlog']['ftp_password']=$_POST['arrFtp']['password'];
			$_POST['arrBlog']['ftp_directory']=$_POST['arrFtp']['directory'];
			$_POST['arrBlog']['files']=$_FILES;
			if ( $this->_model->setData( $_POST['arrBlog'] )->setBlog() ) {
				$this->location( array( 'action'=>'manage' ) );
			}
			$this->out['arrErr']=$this->_model->getErrors();
			$this->out['arrBlog']=$this->_model->getData();
		}
		$modelPlugin=new Project_Wpress_Plugins();
		$modelPlugin->getList( $this->out['arrPlugins'] );
		$modelThemes=new Project_Wpress_Theme();
		$modelThemes->withPreview()->getList( $this->out['arrThemes'] );
		$category=new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson']=Zend_Registry::get( 'CachedCoreString' )->php2json($arrTree);
		$this->out['arrPermalink']=$this->_model->getPermalink();
		if ( $this->_model->getSettingsBlog( $arrSettings ) ) {
			$this->_model->withOrder( 'b.title--up' )->onlySettings()->toSelect()->getList( $this->out['arrSettingsSelect'] );
			$this->out['jsonSettings']=Zend_Registry::get( 'CachedCoreString' )->php2json( $arrSettings );
		}
		if ( !empty( $_GET['ncsb'] ) ) {
			$_site=new Project_Sites( Project_Sites::NCSB );
			$_site->getSite( $_arrSite, $_GET['ncsb'] );
			$this->out['arrBlog']['arrFtp']['address']=$_arrSite['arrNcsb']['ftp_address'];
			$this->out['arrBlog']['arrFtp']['username']=$_arrSite['arrNcsb']['ftp_username'];
			$this->out['arrBlog']['arrFtp']['password']=$_arrSite['arrNcsb']['ftp_password'];
			$this->out['arrBlog']['arrFtp']['directory']=$_arrSite['arrNcsb']['ftp_homepage'];
		}	
		if ( !empty( $_GET['psb'] ) ) {
			$_site=new Project_Sites( Project_Sites::PSB );
			$_site->getSite( $_arrSite, $_GET['psb'] );
			$this->out['arrBlog']['arrFtp']['address']=$_arrSite['arrPsb']['ftp_host'];
			$this->out['arrBlog']['arrFtp']['username']=$_arrSite['arrPsb']['ftp_username'];
			$this->out['arrBlog']['arrFtp']['password']=$_arrSite['arrPsb']['ftp_password'];
			$this->out['arrBlog']['arrFtp']['directory']=$_arrSite['arrPsb']['ftp_directory'];
		}
		if ( !empty( $_GET['nvsb'] ) ) {
			$_site=new Project_Sites( Project_Sites::NVSB );
			$_site->getSite( $_arrSite, $_GET['nvsb'] );
			$this->out['arrBlog']['arrFtp']['address']=$_arrSite['arrNvsb']['ftp_host'];
			$this->out['arrBlog']['arrFtp']['username']=$_arrSite['arrNvsb']['ftp_username'];
			$this->out['arrBlog']['arrFtp']['password']=$_arrSite['arrNvsb']['ftp_password'];
			$this->out['arrBlog']['arrFtp']['directory']=$_arrSite['arrNvsb']['ftp_directory'];
		}		
	}

	public function manage() {
		$this->objStore->getAndClear( $this->out );
		if ( !empty( $_POST ) ) {
			if ( !empty( $_POST['arrNewCat'] ) ) {
				$this->objStore->set( array( 'msg'=>( $this->_model->changeCategory( $_POST['arrNewCat']['id'], $_POST['arrNewCat']['category_id'] )? 'changed':'error' ) ) );
			} elseif ( $_POST['mode']=='delete'&&!empty( $_POST['del'] ) ) {
				$this->objStore->set( array( 'msg'=>( $this->_model->deleteBlog( array_keys( $_POST['del'] ) )? 'delete':'error' ) ) );
			} elseif ( $_POST['mode']=='store-settings'&&!empty( $_POST['ids'] ) ) {
				$this->objStore->set( array( 'msg'=>( $this->_model->setSettingsBlog( 
					$_POST['ids'], 
					( empty( $_POST['set'] )? 0:array_keys( $_POST['set'] ) 
				) )? 'stored':'error' ) ) );
			}
			$this->location();
		}
		$this->_model->withPagging(array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		))
		->withOrder( @$_GET['order'] )
		->withCategories( @$_GET['cat'] )
		->withTitle( @$_GET['blog_title'] )
		->getList( $this->out['arrList'] );
		$this->_model->getPaging( $this->out['arrPg'] )->getFilter( $this->out['arrFilter'] );
		$category=new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson']=Zend_Registry::get( 'CachedCoreString' )->php2json( $arrTree );
	}

	public function import() {
		if ( !empty( $_POST['arrBlog'] ) ) {
			$_POST['arrBlog']['arrFtp']=$_POST['arrFtp'];
			unset( $_POST['arrFtp']['id'] );
			$_POST['arrBlog']['ftp_host']=$_POST['arrFtp']['address'];
			$_POST['arrBlog']['ftp_username']=$_POST['arrFtp']['username'];
			$_POST['arrBlog']['ftp_password']=$_POST['arrFtp']['password'];
			$_POST['arrBlog']['ftp_directory']=$_POST['arrFtp']['directory'];			
			if ( $this->_model->setData( $_POST['arrBlog'] )->import() ) {
				$this->location( array( 'action'=>'manage' ) );
			}
			$this->out['arrErr']=$this->_model->getErrors();
			$this->out['arrBlog']=$this->_model->getData();
		}
		$category=new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson']=Zend_Registry::get( 'CachedCoreString' )->php2json($arrTree);		
	}
	
	public function plugins(){
		$this->objStore->getAndClear( $this->out );
		$model=new Project_Wpress_Plugins();
		if ( !empty( $_GET['restore'] ) ) {
			if ( !$model->reassignCommonToUser() ) {
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location( array('action' => 'plugins') );				
			}
			$this->objStore->set( array( 'msg'=>'restore' ) );
			$this->location( array('action' => 'plugins') );
		}
		if ( !empty( $_FILES['zip'] ) ) {
			if(!$model->addUserPlugin( $_FILES['zip'] )){
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location( array('action' => 'plugins') );
			} else {
				$this->objStore->set( array( 'msg'=>'added' ) );
				$this->location( array('action' => 'plugins') );
			}
		}
		if ( !empty( $_GET['del_id'] ) ) {
			$model->deleteUserPlugin( $_GET['del_id'] );
			$this->objStore->set( array( 'msg'=>'delete' ) );
			$this->location( array('action' => 'plugins') );
		}
		$model->withPagging(array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		))->withOrder( @$_GET['order'] )->getList( $this->out['arrPlugins'] );
		$model->getPaging( $this->out['arrPg'] );
	}
	
	public function themes(){
		$this->objStore->getAndClear( $this->out );
		$model=new Project_Wpress_Theme();
		if ( !empty( $_GET['restore'] ) ) {
			if ( !$model->reassignCommonToUser() ) {
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location( array('action' => 'themes') );				
			}
			$this->objStore->set( array( 'msg'=>'restore' ) );
			$this->location( array('action' => 'themes') );
		}	
		if ( !empty( $_POST['zip'] ) ){
			$_FILES['zip']=$_POST['zip'];
		}
		if ( !empty( $_FILES['zip'] ) ) {
			if ( !$model->addUserTheme( $_FILES['zip'] ) ) {
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location( array('action' => 'themes') );
			} else {
				$this->objStore->set( array( 'msg'=>'added' ) );
				$this->location( array('action' => 'themes') );
			}
		}
		if ( !empty( $_GET['del_id'] ) ) {
			$model->deleteUserTheme( $_GET['del_id'] );
			$this->objStore->set( array( 'msg'=>'delete' ) );
			$this->location( array('action' => 'themes') );
		}
		$model->withPagging(array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		))->withPreview()->withOrder( @$_GET['order'] )->getList( $this->out['arrThemes'] );
		$model->getPaging( $this->out['arrPg'] );
	}
	
	public function general(){
		$this->objStore->getAndClear( $this->out );
		if (empty($_GET['id'])) {
			$this->location(array('action'=>'manage'));
		}
		if (!empty($_POST)) {
			$_POST['arrBlog']['arrFtp']=$_POST['arrFtp'];
			unset( $_POST['arrFtp']['id'] );
			$_POST['arrBlog']['ftp_host']=$_POST['arrFtp']['address'];
			$_POST['arrBlog']['ftp_username']=$_POST['arrFtp']['username'];
			$_POST['arrBlog']['ftp_password']=$_POST['arrFtp']['password'];
			$_POST['arrBlog']['ftp_directory']=$_POST['arrFtp']['directory'];
			$_POST['arrBlog']['files']=$_FILES;
			if ($this->_model->setData( $_POST['arrBlog'] )->setBlog()){
				$this->objStore->set( array( 'msg'=>'success' ) );
				$this->location(array( 'wg'=>'id='.$_GET['id']));
			}
			$this->objStore->set( array( 'msg'=>'error' ) );
			$this->location(array( 'wg'=>'id='.$_GET['id']));
			
		}
		$this->_model->getBlog($this->out['arrBlog'],$_GET['id']);
		$this->out['arrBlog']['arrFtp']=array(
			'address' => $this->out['arrBlog']['ftp_host'],
			'username' => $this->out['arrBlog']['ftp_username'],
			'password' => $this->out['arrBlog']['ftp_password'],
			'directory' => $this->out['arrBlog']['ftp_directory']
		);
		if ( !empty( $this->out['arrBlog']['theme'] ) ) {
			$this->out['arrBlog']['theme_id']=current( $this->out['arrBlog']['theme'] );
		}
		$modelPlugin=new Project_Wpress_Plugins();
		$modelPlugin->getList( $this->out['arrPlugins'] );
		$modelThemes=new Project_Wpress_Theme();
		$modelThemes->withPreview()->getList( $this->out['arrThemes'] );
		$this->out['arrPermalink']=$this->_model->getPermalink();
		$category=new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson']=Zend_Registry::get( 'CachedCoreString' )->php2json($arrTree);
	}

	public function categories() {
		$model=new Project_Wpress_Content_Category();
		if ( !$model->setBlogById( $_GET['id'] ) ) {
			$this->location( array( 'action'=>'manage' ) );
		}
		$this->out['arrBlog']=$model->blog->filtered;
		if ( !empty( $_POST ) ) {
			if ( $model->setData( $_POST['arrList'] )->set() ) {
				$this->location();
			}
			$this->out['arrErr']=$model->getErrors();
			$this->out['arrList']=$model->getData();
		}
		$model->withPagging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->withOrder( @$_GET['order'] )->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
	}

	public function posts() {
		$cats=new Project_Wpress_Content_Category();
		if ( !$cats->setBlogById( $_GET['id'] ) ) {
			$this->location( array( 'action'=>'manage' ) );
		}		
		$cats->withOrder('flg_default--up')->getList( $this->out['arrCats'] );
		$model=new Project_Wpress_Content_Posts();
		if ( !$model->setBlogById( $_GET['id'] ) ) {
			$this->location( array( 'action'=>'manage' ) );
		}
		$this->out['arrBlog']=$model->blog->filtered;
		if ( !empty( $_POST ) ) {
			if ( $model->setData( $_POST['arrPost'] )->set() ) {
				$this->location();
			}
			$this->out['arrErr']=$model->getErrors();
			$this->out['arrPost']=$model->getData();
		}
		$model->withPagging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->withCategory( @$_GET['cat_id'] )->withCategories()->withOrder( @$_GET['order'] )->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
	}
	
	public function comments() {
		$posts=new Project_Wpress_Content_Posts();
		if ( !$posts->setBlogById( $_GET['id'] ) ) {
			$this->location( array( 'action'=>'manage' ) );
		}		
		$posts->withOrder('title--up')->getList( $this->out['arrPosts'] );
		$model=new Project_Wpress_Content_Comments();
		if ( !$model->setBlogById( $_GET['id'] ) ) {
			$this->location( array( 'action'=>'manage' ) );
		}
		$this->out['arrBlog']=$model->blog->filtered;
		if ( !empty( $_POST ) ) {
			if ( $model->setData( $_POST['arrComment'] )->set() ) {
				$this->location((!empty($_GET['redirect'])) ? $_GET['redirect'].'?id='.$_GET['id'] : '');
			}
			$this->out['arrErr']=$model->getErrors();
			$this->out['arrPost']=$model->getData();
		}
		$model->withPagging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->onlyPost( @$_GET['post_id'] )->withOrder( @$_GET['order'] )->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
	}
	
	public function pages() {
		$model=new Project_Wpress_Content_Pages();
		if ( !$model->setBlogById( $_GET['id'] ) ) {
			$this->location( array( 'action'=>'manage' ) );
		}
		$this->out['arrBlog']=$model->blog->filtered;
		if ( !empty( $_POST ) ) {
			if ( $model->setData( $_POST['arrPage'] )->set() ) {
				$this->location();
			}
			$this->out['arrErr']=$model->getErrors();
			$this->out['arrPage']=$model->getData();
		}
		$model->withPagging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->withOrder( @$_GET['order'] )->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
	}

	// refactor TODO!!! 07.06.2010
	// убрать всю эту красоту в Project_Wpress_Theme
	public function edittheme() {
		$this->_model->onlyOne()->withIds($_GET['id'])->getList($arrBlog);
		$this->out['arrBlog'] =$arrBlog;
		$modelThemes=new Project_Wpress_Theme();
		$modelThemes->onlyOne()->onlySiteId( $arrBlog['id'] )->getList( $arrTheme );
		$_ftp=new Core_Media_Ftp();
		if ( !$_ftp
			->setHost( $arrBlog['ftp_host'] )
			->setUser( $arrBlog['ftp_username'] )
			->setPassw( $arrBlog['ftp_password'] )
			->setRoot( $arrBlog['ftp_directory'] )
			->makeConnectToRootDir() ) {
			return false;
		}
		$pathTheme='wp-content/themes/' . str_replace('.zip', '', $arrTheme['filename']) . '/';
		$this->out['strPath'] =$pathTheme;
		if ( !$_ftp->dirForLs( $pathTheme )->ls( $arrDirs, Core_Media_Ftp::LS_DIRS_FILES ) ) {
			return;
		}
		foreach ($arrDirs as &$item) {
			$item['view']=false;
			if (stripos($item['name'],'.php') || stripos($item['name'],'.css') || stripos($item['name'],'.html') || stripos($item['name'],'.txt')) {
				$item['view']=true;
			} 
		}
		$this->out['arrDirs'] =$arrDirs;
		
	}
	
	public function changetheme() {
		$this->objStore->getAndClear( $this->out );
		$modelThemes=new Project_Wpress_Theme();
		if(!empty($_POST)){
			if ( !$modelThemes->blogLink( $_GET['id'], $_POST['theme'] ) ) {
				$this->objStore->set( array( 'msg'=>'error' ) );
				$this->location(array('wg'=>'id='.$_GET['id']));
			}
			
			$this->_model->getBlog($arrBlog, $_GET['id']);
			$this->_model->setData($arrBlog);
			if (!$this->_model->setTheme()) {
				$this->objStore->set( array( 'msg'=>'error' ) );
				$this->location(array('wg'=>'id='.$_GET['id']));
			}
			$this->objStore->set( array( 'msg'=>'change' ) );
			$this->location(array('wg'=>'id='.$_GET['id']));
		}
		$this->_model->onlyOne()->withIds($_GET['id'])->getList($this->out['arrBlog']);
		$modelThemes->onlyOne()->withPreview()->onlySiteId($_GET['id'])->getList( $this->out['selectedTheme'] );
		$modelThemes->withPreview()->getList( $this->out['arrList'] );
	}
	
	public function testdb() {
		$_POST['ftp_password']=urldecode($_POST['ftp_password']);
		$data=new Core_Data( $_POST );
		if ( !$data->setFilter( array( 'strip_tags', 'trim', 'clear' ) )->setChecker( array(
			'db_name'=>empty( $data->filtered['db_name'] ),
			'db_host'=>empty( $data->filtered['db_host'] ),
			'db_username'=>empty( $data->filtered['db_username'] ),
			'db_password'=>empty( $data->filtered['db_password'] ),
			'url'=>empty( $data->filtered['url'] ),
			'ftp_host'=>empty( $data->filtered['ftp_host'] ),
			'ftp_username'=>empty( $data->filtered['ftp_username'] ),
			'ftp_password'=>empty( $data->filtered['ftp_password'] ),
			'ftp_directory'=>empty( $data->filtered['ftp_directory'] ),
		) )->check() ) {
			$data->getErrors( $this->out_js['error'] );
			echo 'empty';
			die();
		}
		$connect=new Project_Wpress_Connector($data);
		if (!$connect->prepare()) {
			echo 'error';
			die();
		}
		echo 'succ';
		die();
	}
	
	public function muliboxmanage() {
		$this->manage();
	}
}
?>