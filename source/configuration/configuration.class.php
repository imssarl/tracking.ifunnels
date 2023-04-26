<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2011, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 30.11.2010
 * @version 2.0
 */


/**
 * Management modules, sites & site's trees
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2011, Rodion Konnov
 */
class configuration extends Core_Module {

	public final function set_cfg() {
		$this->inst_script=array(
			'module'=>array(
				'title'=>'Configuration',
			),
			'actions'=>array(
				array( 'action'=>'modules', 'title'=>'Modules' ),
				array( 'action'=>'backups', 'title'=>'DB backups' ),
				array( 'action'=>'sites_list', 'title'=>'Sites list' ),
				array( 'action'=>'set_site', 'title'=>'Set site' ),
				array( 'action'=>'sites_map', 'title'=>'Sites map' ),
				array( 'action'=>'set_page', 'title'=>'Set page' ),
				array( 'action'=>'ajax_fillfields', 'title'=>'Fill fields', 'flg_tpl'=>3 ),
				array( 'action'=>'view_table', 'title'=>'View DB table', 'flg_tpl'=>1 ),
				array( 'action'=>'just_install_me', 'title'=>'Just install me', 'flg_tpl'=>2 ),
			),
			'needed'=>array(),
		);
	}

	public function view_table(){
		if (!empty($_GET['table'])){ 
			$_obj=new Core_Sql_Backup();
			$this->out['arrList']=$_obj->withPagging(array( 
			'url'=>@$_GET, 
			'reconpage'=>50,
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
			))->setTable($_GET['table'])->b_view_table();
			$this->out['arrColumns']=$_obj->b_get_table_columns();
			$_obj->getPaging( $this->out['arrPg'] );
		}
	}
	
	function backups() {
		$this->objMb=new Core_Sql_Backup();
		if ( isset( $_POST['4tracker'] ) ){
			$_userId=$_POST['user_id'];
			try{
				Core_Sql::setConnectToServer( 'lpb.tracker' );
				//========
				$_arrTables=array( 'tables'=>Core_Sql::getField("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='lpb_tracker' AND TABLE_NAME LIKE '%\\_".$_userId."' OR TABLE_NAME='".$_userId."'") );
				var_dump( $_arrTables );
				$this->objMb->b_create_dump( $_arrTables, 'user_'.$_userId );
				//========
				Core_Sql::renewalConnectFromCashe();
			}catch( Exception $e ){
				p( $e );
			}
			$this->location( Core_Module_Router::$uriVar );
		}
		if ( !empty( $_GET['delete'] ) ) {
			$this->objMb->b_del_dump( $_GET['delete'] );
			$this->location( Core_Module_Router::$uriVar );
		}
		if ( !empty( $_GET['restore'] ) ) {
			$this->objMb->b_set_dump( $_GET['restore'] );
			$this->location( Core_Module_Router::$uriVar );
		}
		if ( !empty( $_GET['backup_sys'] ) ) {
			$_POST['arrSet']['tables']=array( 'sys_action', 'sys_module', 'sys_page', 'sys_site', 'u_groups', 'u_rights2group', 'u_rights' );
		}
		if ( !empty( $_POST['arrSet'] ) ) {
			$this->objMb->b_create_dump( $_POST['arrSet'] );
			$this->location( Core_Module_Router::$uriVar );
		}
		$this->objMb->b_get_dumps_list( $this->out['arrDumps'] );
		$this->objMb->b_get_db_tables( $this->out['arrTables'] );
	}

	// action to install this module on the first project start
	public function just_install_me() {}

	public function modules() {
		if ( $this->moduleManagement( @$_POST['arrM']['name'], @$_POST['arrM']['mode'] ) ) {
			$this->location( Core_Module_Router::$uriFull );
		}
		Core_Module_Management_Modules::getModuleList( $this->out['arrMod'] );
	}

	public function sites_list() {
		$obj=new Core_Module_Management_Sites();
		$obj->getSites( $this->out['arrSites'] );
	}

	public function set_site() {
		$obj=new Core_Module_Management_Sites();
		if ( $obj->setSite( $this->out['arrSite'], $this->out['arrErr'], $_POST['arrSite'] ) ) {
			$this->location();
		}
		$obj->getSite( $this->out['arrSite'], $_GET );
	}

	public function sites_map() {
		// данные для селекта сайтов
		$obj=new Core_Module_Management_Sites();
		$obj->getSites( $this->out['arrSites'] );
		if ( empty( $_GET['root_id'] ) ) {
			return;
		}
		// тут ещё нужно подставлять оффсет нужного сайта, если есть TODO!!!
		$this->out['frontendUrl']=$_SERVER['HTTP_HOST'];
		// действия со страницами дерева
		if ( !empty( $_POST['arrTree']['mode'] ) ) {
			switch( $_POST['arrTree']['mode'] ) {
				case 'page_up': $obj->pageUp( $_POST['arrTree']['id'] ); break;
				case 'page_dn': $obj->pageDown( $_POST['arrTree']['id'] ); break;
				case 'page_site': $obj->onSiteMap( $_POST['arrTree']['id'] ); break;
				case 'page_del': $obj->delPage( $_POST['arrTree']['id'] ); break;
			}
		}
		// данные для отрисовки дерева
		$this->out['arrTree']=array();
		$this->objMR->getTreeWithFilter( array( 
			'node_id'=>$_GET['root_id'],
			'with_root_node'=>true,
			'result'=>array( 
				'MOD_TREE'=>&$this->out['arrTree'], 
			),
		) );
		$obj->getSite( $this->out['arrCurrentSite'], $_GET );
	}

	public function set_page() {
		if ( empty( $_GET['root_id'] ) ) {
			$this->location( array( 'action'=>'sites_map' ) );
		}
		$obj=new Core_Module_Management_Sites();
		if ( !empty( $_POST['arrPage'] ) ) {
			if ( $_POST['mode']=='chenge_pid' ) { // перерисовка при смене парента (для обновлния arrPos) можно сделать через ajax
				$this->out['arrPage']['pid']=$_POST['arrPage']['pid'];
			} elseif ( $obj->setPage( $this->out['arrPage'], $this->out['arrErr'], $_POST['arrPage'] ) ) { // пробуем сохранить
				$this->location( array( 'wg'=>'id='.$this->out['arrPage']['id'] ) );
			}
		} elseif ( !empty( $_GET['id'] ) ) { // редактирование существующей страницы
			$obj->getPage( $this->out['arrPage'], array( 'id'=>$_GET['id'] ) );
		} elseif ( !empty( $_GET['pid'] ) ) { // если создаём подчинённую страницуы
			$this->out['arrPage']['pid']=$_GET['pid'];
		} else { // если парент не указан то им является root_id дерева
			$this->out['arrPage']['pid']=$_GET['root_id'];
		}
		// это для выставления позиции ноды
		if ( !empty( $this->out['arrPage']['pid'] ) ) {
			$obj->getLevelPosition( $this->out['arrPos'], $this->out['arrPage']['pid'] );
		}
		// информация по дереву
		$obj->getSite( $this->out['arrSite'], array( 'root_id'=>$_GET['root_id'] ) );
		// данные для отрисовки дерева
		// тут ещё нужно подставлять оффсет нужного сайта, если есть
		$this->out['arrTree']=array();
		$this->objMR->getTreeWithFilter( array( 
			'node_id'=>$_GET['root_id'],
			'with_root_node'=>true,
			'result'=>array( 
				'MOD_TREE'=>&$this->out['arrTree'], 
			),
		) );
		// список экшенов с модулями
		Core_Module_Management_Modules::getModulesWithActions( $this->out['arrModulesWithActions'], $this->out['arrSite']['flg_type'] );
		$obj->getSitesToSelect( $this->out['arrSites'] );
	}

	public function ajax_fillfields() {
		if ( empty( $_GET['type'] )||empty( $_POST['data'] ) ) {
			$this->out_js['error']='type or data don\'t set';
			return;
		}
		$this->out_js['data']=$_GET['type']=='meta_description' ? 
			Core_String::getInstance( $_POST['data'] )->metaDescription( 200, ' ...' ):
			Core_String::getInstance( $_POST['data'] )->metaKeywords( 10 );
		$this->out_js['error']=false;
	}
}
?>