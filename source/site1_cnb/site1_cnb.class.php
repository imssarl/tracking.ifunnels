<?php 
/**
 * CNM
 *
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 04.08.2010
 * @version 1.0
 */


/**
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class site1_cnb extends Core_Module {


	public function before_run_parent(){
		// добавление стандартных шаблонов для CNB сайтов.
		$_psb=new Project_Sites_Templates( Project_Sites::CNB  );
		$_psb->addCommonTemplatesToNewUser();
	}	
	
	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Creative Niche Builder', ),
			'actions'=>array(
				array( 'action'=>'fronttemplates', 'title'=>'Manage Templates', 'flg_tree' => 1 ),
				array( 'action'=>'templates', 'title'=>'Templates' ),
				array( 'action'=>'create', 'title'=>'Create', 'flg_tree'=>1 ),
				array( 'action'=>'portal', 'title'=>'Register Portal', 'flg_tree'=>1 ),
				array( 'action'=>'import', 'title'=>'Import', 'flg_tree'=>1 ),
				array( 'action'=>'edit', 'title'=>'Edit', 'flg_tree'=>1 ),
				array( 'action'=>'edit_templates', 'title'=>'Edit Template', 'flg_tree'=>1 ),
				array( 'action'=>'ajax_edit_template', 'title'=>'Ajax edit template', 'flg_tree'=>1, 'flg_tpl' => 1  ),				
				array( 'action'=>'manage', 'title'=>'Manage', 'flg_tree'=>1 ),
				array( 'action'=>'multiboxlist', 'title'=>'Popup Site List', 'flg_tree'=>1, 'flg_tpl' => 1 ),
			),
		);
	}

	public function create(){
		$_model = new Project_Sites( Project_Sites::CNB );
		if ( !empty( $_POST ) ) {
			$_model->setData( $_POST );
			if ( $_model->set() ) {
				$_model->onlyOne()->withOrder()->getList( $_arrLast ); 
				$publishing=new Project_Publishing( new Project_Publishing_Cnb() );
				if ( !empty( $_POST['arrPrj'] ) ) {
					$_POST['arrPrj']['title']=$_POST['arrCnb']['primary_keyword'];
					$_POST['arrPrj']['category_id']=$_POST['arrCnb']['category_id'];
					$_POST['arrPrj']['flg_posting']=3;
					$_POST['arrPrj']['flg_source']=2;
					$_POST['arrPrj']['keyword_source']=2;
					$_POST['arrPrj']['arrSiteIds'][] = array( 'site_id' => $_arrLast['id'] );
					$_POST['arrPrj']['start'] = strtotime($_POST['arrPrj']['start']);
					$publishing->setData( $_POST['arrPrj'] )->set();
				}				
				$this->objStore->toAction( 'manage' )->set( array( 'msg'=>'uploaded' ) );
				$this->location( array( 'action' => 'manage' ) );
			}
			$_model->getErrors( $this->out['arrErr'] );
			$this->out += $_POST;
			$this->out['arrOpt'] = $_POST;

		} elseif ( !empty( $_GET['id'] ) ) {
			$_model->getSite( $this->out, $_GET['id'] );
		}
		
		$_model->onlyPortals()->getList( $this->out['arrPortals'] );
		$_templates=new Project_Sites_Templates( Project_Sites::CNB );
		$_templates->withSpots()->withPreview()->getList( $this->out['arrTemplates'] );
		$this->out['jsonTemplates'] = Zend_Registry::get( 'CachedCoreString' )->php2json( $this->out['arrTemplates']  );
		$category = new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson'] = Zend_Registry::get( 'CachedCoreString' )->php2json($arrTree);
		$profile = new Project_Sites_Profiles();
		$profile->getList( $this->out['arrProfile'] );		
	}
	
	public function portal(){
		$_model = new Project_Sites( Project_Sites::CNB );
		if ( !empty( $_POST ) ) {
			$_model->setData( $_POST );
			if ( $_model->import() ) {
				$this->objStore->toAction( 'manage' )->set( array( 'msg'=>'uploaded' ) );
				$this->location( array( 'action' => 'manage' ) );
			}
			$_model->getErrors( $this->out['arrErr'] );
			$this->out += $_POST;
		} elseif ( !empty( $_GET['id'] ) ) {
			$_model->getSite( $this->out, $_GET['id'] );
		}
		$category = new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson'] = Zend_Registry::get( 'CachedCoreString' )->php2json($arrTree);
	}
	
	public function edit(){
		$this->create();
	}
	
	public function import(){
		$_model = new Project_Sites( Project_Sites::CNB );
		if ( !empty( $_POST ) ) {
			$_model->setData( $_POST );
			if ( $_model->import() ) {
				$this->objStore->toAction( 'manage' )->set( array( 'msg'=>'uploaded' ) );
				$this->location( array( 'action' => 'manage' ) );
			}
			$_model->getErrors( $this->out['arrErr'] );
			$this->out += $_POST;
		}
		$category = new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson'] = Zend_Registry::get( 'CachedCoreString' )->php2json($arrTree);		
	}
	
	public function manage(){
		$this->objStore->getAndClear( $this->out );
		$model=new Project_Sites( Project_Sites::CNB );
		if ( !empty( $_GET['del'] ) && $model->delSites( $_GET['del'] ) ) {
			$this->objStore->set( array( 'msg'=>'deleted' ) );
			$this->location( array( 'action'=>'manage' ) );
		}
		if ( !empty( $_POST['arrNewCat'] ) ) {
			$this->objStore->set( array( 'msg'=>( $model->changeCategory( $_POST['arrNewCat']['id'], $_POST['arrNewCat']['category_id'] )? 'changed':'error' ) ) );
			$this->location( array( 'action'=>'manage' ) );
		}
		$model->getList($this->out['arrChilds']);
		$model->withOrder( @$_GET['order'] )->withPaging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->getList( $this->out['arrList'] );
		
		$model->getPaging( $this->out['arrPg'] );
		$model->getFilter( $this->out['arrFilter'] );
		$category = new Core_Category( 'Blog Fusion' );
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $arrTree );
		$this->out['treeJson']=Zend_Registry::get( 'CachedCoreString' )->php2json( $arrTree );			
		
		$_templates=new Project_Sites_Templates(  Project_Sites::CNB );
		$_templates->withPreview()->getList( $this->out['arrTemplates'] );		
	}
	
	public function fronttemplates(){
		$this->objStore->getAndClear( $this->out );
		$model=new Project_Sites_Templates( Project_Sites::CNB );
		if ( !empty( $_FILES['zip'] ) ) {
			if ( $model->addCommonTemplate( $_POST['theme'], $_FILES['zip'] ) ) {
				$this->objStore->set( array( 'msg'=>'added' ) );
			} else {
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
			}
			$this->location( array('action' => 'fronttemplates') );
		}
		if ( !empty( $_GET['restore'] ) ) {
			if ( !$model->reassignCommonToUser() ) {
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location( array('action' => 'themes') );				
			}
			$this->objStore->set( array( 'msg'=>'restore' ) );
			$this->location( array('action' => 'fronttemplates') );
		}			
		if( !empty( $_GET['delete'] ) ) {
			$model->deleteUserTemplate( $_GET['delete'] );
			$this->objStore->set( array( 'msg'=>'delete' ) );
			$this->location( array( 'action'=>'fronttemplates' ) );
		}
		if( !empty( $_POST['arrCopy'] ) ){
			if ( !$model->copyTemplate( $_POST['arrCopy'] ) ){
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location( array( 'action'=>'fronttemplates' ) );
			}
			$this->objStore->set( array( 'msg'=>'copy' ) );
			$this->location( array( 'action'=>'fronttemplates' ) );
		}		
		$model->withPagging( array( 'page'=>@$_GET['page'], ) )->withOrder( @$_GET['order'] )->withPreview()->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
		$sites = new Project_Sites( Project_Sites::PSB );
		$sites->getList( $this->out['arrSites'] );
	}
	
	public function edit_templates(){
		$this->objStore->getAndClear( $this->out );
		$model=new Project_Sites_Templates( Project_Sites::CNB );
		if ( !empty($_POST['arr']) ){
			if ( !$model->saveTemplate($_POST['arr']['id'], $_FILES['header']) ){
				$this->out['error']=true;
			}
			$this->objStore->toAction( 'fronttemplates' )->set( array( 'msg'=>'saved' ) );
			$this->location( array( 'action'=>'fronttemplates' ) );
				
		}		
		if ( !empty( $_GET['id'] ) ) {
			$model->withPreview()->onlyOne()->withIds( $_GET['id'] )->getList( $this->out['arrTemplate'] );
			$model->template2edit($this->out['arrFiles'], $_GET['id']);
		}		
	}
	
	public function ajax_edit_template(){
		if ( !empty( $_GET['open_file'] ) ){
			Core_Files::getContent($strContent, $_POST['file']);
			echo $strContent;
		}
		if ( !empty( $_GET['save_file'] ) ){
			header('Content-type: application/json;');
			if ( !Core_Files::setContent($_POST['strContent'], $_POST['file']) ){
				echo "{result:0}";
				die();
			}
			echo "{result:1}";
		}
		die();				
	}
	
	public function multiboxlist(){
		$model=new Project_Sites( Project_Sites::CNB );
		$model->withOrder( @$_GET['order'] )->withPaging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
		$model->getPaging( $this->out['arrPg'] );
		$model->getFilter( $this->out['arrFilter'] );		
	}
	
	public function templates() {
		$this->objStore->getAndClear( $this->out );
		$model=new Project_Sites_Templates( Project_Sites::CNB );
		if ( !empty( $_FILES['zip'] ) ) {
			if ( $model->addCommonTemplate( $_POST['theme'], $_FILES['zip'] ) ) {
				$this->objStore->set( array( 'msg'=>'added' ) );
			} else {
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
			}
			$this->location( array('action' => 'templates') );
		}
		if( !empty( $_GET['delete'] ) ) {
			$model->deleteCommonTemplate( $_GET['delete'] );
			$this->objStore->set( array( 'msg'=>'delete' ) );
			$this->location( array( 'action'=>'templates' ) );
		}
		$model->withPagging( array( 'page'=>@$_GET['page'], ) )->onlyCommon()->withOrder( @$_GET['order'] )->withPreview()->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
	}	
}	
?>