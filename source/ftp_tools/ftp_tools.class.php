<?php
/**
 * CNM
 *
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 19.05.2010
 * @version 1.5
 */


/**
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class ftp_tools extends Core_Module {

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'Ftp tools', ),
			'actions'=>array(
				array( 'action'=>'manage', 'title'=>'Manage', 'flg_tree'=>1 ),
				array( 'action'=>'browse', 'title'=>'Browse directory', 'flg_tree'=>2, 'flg_tpl'=>1 ),
				array( 'action'=>'check', 'title'=>'Check ftp info', 'flg_tree'=>2, 'flg_tpl'=>3 ),
			),
		);
	}

	public function browse() {
		$_ftp=new Project_Ftp();
		if ( !$_ftp->setData( $_GET )->browse( $this->out['arrDirs'], (empty($_GET['mode'])?Core_Media_Ftp::LS_DIRS_ONLY:Core_Media_Ftp::LS_DIRS_FILES) ) ) {
			$_ftp->getErrors( $this->out['arrErrors'] );
			return;
		}
		// исправляем ftp_password и генерим ссылку для подстановки в шаблоне
		$_arrUrl=parse_url( Core_Module_Router::$uriFull );
		parse_str( $_arrUrl['query'], $_srrVars );
		$_ftp->getForLsDir( $_srrVars['directory'] ); // если небыло в гет
		$_srrVars['ftp_password']=urlencode( $_srrVars['ftp_password'] );
		$this->out['strUrl']=$_arrUrl['path'].'?'.http_build_query( $_srrVars );
		if ( $_ftp->makeDirAndClose( @$_POST['new_folder'] ) ) {
			$this->location( $this->out['strUrl'] );
		}
		$this->out['strCurrentDir']=$_srrVars['directory'];
		$_ftp->getPrevDir( $this->out['strPrevDir'] );
	}

	public function manage() {
		$this->objStore->getAndClear( $this->out );
		$_ftp=new Project_Ftp();
		if (!empty($_FILES['ftp']['name'])){
			if ( !$_ftp->setData( $_FILES['ftp'] )->import() ){
				$this->objStore->set( array( 'msg'=> 'import_error' ) );
				$this->location();
			}
			$this->location();
		}
		if ( !empty( $_POST ) ) {
			if ( $_POST['mode']=='delete'&&!empty( $_POST['del'] ) ) { // del
				$this->objStore->set( array( 'msg'=>( $_ftp->del( array_keys( $_POST['del'] ) )? 'delete':'error' ) ) );
				$this->location();
			} elseif ( !empty( $_POST['arrFtp'] ) ) { // edit
				if ( $_ftp->setData( $_POST['arrFtp'] )->set() ) {
					$this->objStore->set( array( 'msg'=>(empty( $_POST['arrFtp']['id'] )? 'stored':'changed') ) );
					$this->location();
				}
				$_ftp->getErrors( $this->out['arrErr'] )->getEntered( $this->out['arrFtp'] );
			}
		} elseif ( !empty( $_GET['id'] ) ) {
			$_ftp->onlyOne()->withIds( $_GET['id'] )->getList( $this->out['arrFtp'] );
		}
		$_ftp->withPagging( array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'], ) )
		->withOrder( @$_GET['order'] )
		->getList( $this->out['arrList'] );
		$_ftp->getPaging( $this->out['arrPg'] )->getFilter( $this->out['arrFilter'] );
	}

	public function check() {}

	public function set() {
		if ( !empty( $this->out['arrPrm']['selected'] ) ) {
			$this->out['arrFtp']=$this->out['arrPrm']['selected'];
		}
		$this->out['arrayName']=empty( $this->out['arrPrm']['arrayName'] ) ? 'arrFtp':$this->out['arrPrm']['arrayName'];
		$_ftp=new Project_Ftp();
		$_ftp->toSetect()->getList( $this->out['arrFtps'] );
		$_ftp->toJson()->getList( $this->out['strFtps'] );
	}
}
?>