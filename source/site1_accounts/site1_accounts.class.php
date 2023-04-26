<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.04.2009
 * @version 1.0
 */


/**
 * Typical first started frontend module
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class site1_accounts extends Core_Module {

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM accounts and general', ),
			'actions'=>array(
				array( 'action'=>'logoff', 'title'=>'Logoff', 'flg_tpl'=>2, 'flg_tree'=>1 ),
				array( 'action'=>'tutorials', 'title'=>'Tutorials and How-To Videos', 'flg_tree'=>1 ),
				array( 'action'=>'player', 'title'=>'Webinar 2010-2', 'flg_tree'=>1 ),
				array( 'action'=>'traffic', 'title'=>'Traffic Generation Training', 'flg_tree'=>1 ),
				array( 'action'=>'settings', 'title'=>'User Settings', 'flg_tree'=>1 ),
				array( 'action'=>'register', 'title'=>'Register Site', 'flg_tree'=>1 ),
				array( 'action'=>'templates', 'title'=>'Manage Template', 'flg_tree'=>1 ),
				array( 'action'=>'history', 'title'=>'History', 'flg_tree'=>1 ),
				array( 'action'=>'copyprophet', 'title'=>'Copy Prophet', 'flg_tree'=>1 ),
				array( 'action'=>'copyprophet_ajax', 'title'=>'Copy Prophet Ajax', 'flg_tree'=>1,'flg_tpl'=>1 ),
				array( 'action'=>'externalData', 'title'=>'All External Data', 'flg_tree'=>1 ),
				array( 'action'=>'sorceContent', 'title'=>'All Source Content', 'flg_tree'=>1 ),
			),
		);
	}
	
	public function externalData() {
		//подключаем  список articles categorys
		$this->out['articles']=array();
		$categoryart=new Project_Articles_Category();
		$categoryart->withFlags(array('active'))->toSelect()->get( $this->out['articles'], $_arrTmp );
		//подключаем  список video categorys
		$this->out['video']=array();
		$categoryvid=new Project_Embed_Category();
		$categoryvid->toSelect()->get( $this->out['video'], $_arrTmp );
		/// подключаем параметры формы
		$_model = new Project_Content_Settings ();
		// если первый раз зашли на форму
		if ( !empty($_POST) ) {
			$_model->setData($_POST['arrCnt'])->set();
		} 
		$_model->getContent( $_getRes );//в $getRes - данные из таблицы
		$this->out['arrCnt']=$_getRes;
		//
		if (empty($this->params['modelSeting'])) 
			$this->params['modelSeting']==0;
		// clickbankcategorys
		$category=new Core_Category( 'Clickbank' );
		if (!empty($_GET['id'])){
			$_model->withIds($_GET['id'])->onlyOne()->getList($this->out['arrData']);
			$category->getLng()->setCurLang( Core_Language::$flags[$this->out['arrData']['flg_language']]['title'] );
		}
		$category->getLevel( $this->out['arrCategories'], @$_GET['pid'] );
		$category->getTree( $this->out['arrCatTree'] );
		//p($this->out['arrCategories']);
	}
	
	public function copyprophet(){
	}
	
	public function copyprophet_ajax(){
		$_model=new Project_Copyprophet();
		if ( isset( $_GET["s"] ) ){
			$this->out['score']=$_model->getScore( $_GET["s"] );
		}		
	}
	
	public function player(){
		
	}
	
	public function history() {
		$_history=new Project_Sites_History();
		$_history
		->withOrder( @$_GET['order'] )
		->withPaging( array( 
			'url'=>$_GET, 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )
		->getList( $this->out['arrList'] )
		->getPaging( $this->out['arrPg'] )
		->getFilter( $this->out['arrFilter'] );
	}

	public function manage() {}
	public function register() {}
	public function tutorials() {}
	public function traffic() {}
	public function templates() {}

	public function login() {
		if ( empty( $_POST ) ) {
			return;
		}
		if ( !$this->objUser->setData( $_REQUEST )->authorise() ) {
			$this->out['intError']=$this->objUser->getError();
		} else {
			ini_set( "session.gc_maxlifetime", 60*60 );
			$this->location( $this->objML->get() );
		} 
	}

	public function settings() {
		$this->objStore->getAndClear( $this->out );
		if ( empty( $_POST )&&empty( $this->out['arrSettings'] ) ) {
			$this->out['arrSettings']=Core_Users::$info['arrSettings'];
			return;
		}
		if ( $this->objUser->setData( $_POST )->setSettings() ) {
			$this->objStore->set( array( 'strMsg'=>'Administrative settings updated successfully' ) );
		} else {
			$this->objUser->getEntered( $out['arrSettings'] )->getErrors( $out['arrErr'] );
			$this->objStore->set( $out );
		}
		$this->location();
	}

	public function logoff() {
		Core_Users::logout();
		$this->location();
	}

	public function main() {}

	public function categoryWarning() {
		$_model=new Project_Wpress();
		$_bool1=$_model->onlyCount()->withoutCategories()->getList( $this->out['arrNum']['wpress'] );
		$_model=new Project_Sites( Project_Sites::NCSB );
		$_bool2=$_model->onlyCount()->withoutCategories()->getList( $this->out['arrNum']['ncsb'] );
		$_model=new Project_Sites( Project_Sites::PSB );
		$_bool3=$_model->onlyCount()->withoutCategories()->getList( $this->out['arrNum']['psb'] );
		$_model=new Project_Sites( Project_Sites::NVSB );
		$_bool4=$_model->onlyCount()->withoutCategories()->getList( $this->out['arrNum']['nvsb'] );
		$_model=new Project_Sites( Project_Sites::CNB );
		$_bool5=$_model->onlyCount()->withoutCategories()->getList( $this->out['arrNum']['cnb'] );
		if ( $_bool1||$_bool2||$_bool3||$_bool4||$_bool5 ) {
			$this->out['boolShow']=true;
		}
	}
}
?>