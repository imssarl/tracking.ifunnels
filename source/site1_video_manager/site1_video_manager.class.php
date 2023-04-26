<?php
/**
 * CNM
 *
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 17.01.2011
 * @version 3.0
 */


/**
 * @category CNM
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2011, Rodion Konnov
 */
class site1_video_manager extends Core_Module {

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'CNM Video Manager', ),
			'actions'=>array(
				array( 'action'=>'category', 'title'=>'Manage Category', 'flg_tree'=>1 ),
				array( 'action'=>'video', 'title'=>'Manage Videos', 'flg_tree'=>1 ),
				array( 'action'=>'view', 'title'=>'View video', 'flg_tree'=>1, 'flg_tpl'=>1 ),
				array( 'action'=>'add', 'title'=>'Add Video', 'flg_tree'=>1 ),
				array( 'action'=>'edit', 'title'=>'Edit Video', 'flg_tree'=>1 ),
				array( 'action'=>'import', 'title'=>'Mass Import', 'flg_tree'=>1 ),
				array( 'action'=>'multibox', 'title'=>'Multibox', 'flg_tree'=>1, 'flg_tpl'=>1 ),
			),
		);
	}

	public function before_run_parent() {
		$this->_model=new Project_Embed();
	}

	public function category() {
		$cat=new Project_Embed_Category();
		if ( !empty( $_POST['arrCats'] )&&$cat->set( $this->out['arrCats'], $this->out['arrErr'], $_POST['arrCats'] ) ) {
			$this->location();
		} else {
			$cat->withPagging( array( 
				'page'=>@$_GET['page'], 
				'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
				'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
			) )->management( $this->out['arrCats'], $this->out['arrPg'] );
		}
		$cat->getType( $this->out['arrType'] ); // только для оформления формы
	}

	public function video() {
		$this->objStore->getAndClear( $this->out );
		if ( !empty( $_POST['del'] ) ) { // del
			if ( !empty( $_POST['del'] ) ) {
				$this->objStore->set( array( 'msg'=>( $this->_model->del( array_keys( $_POST['del'] ) )? 'delete':'delete_error' ) ) );
			}
			$this->location();
		}
		if ( !empty( $_GET['dup'] ) ) { // duplicate
			$this->objStore->set( array( 'msg'=>( $this->_model->duplicate( $_GET['dup'] )? 'duplicated':'duplicated_error' ) ) );
			$this->location( array( 'action'=>'video' ) );
		}
		$this->_model->withOrder( @$_GET['order'] )->withCategory( @$_GET['category'] )->withPaging( array( 
			'url'=>$_GET, 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		) )->getList( $this->out['arrList'] );
		$this->_model->getPaging( $this->out['arrPg'] )->getFilter( $this->out['arrFilter'] );
		$this->_model->getAdditional( $this->out['arrSelect'] );
	}

	public function add() {
		if ( !empty( $_POST['arrData'] )&&$this->_model->setData( $_POST['arrData'] )->set() ) {
			$this->objStore->toAction( 'video' )->set( array( 'msg'=> (( empty($_POST['arrData']['id']) )? 'created':'saved') ) );
			$this->location( array( 'action'=>'video' ) );
		}
		$this->_model->getEntered( $this->out['arrData'] )->getErrors( $this->out['arrErr'] );
		$this->_model->getAdditional( $this->out['arrSelect'] );
	}

	public function edit() {
		if ( !$this->_model->onlyOne()->withIds( @$_GET['id'] )->getList( $this->out['arrData'] ) ) {
			$this->objStore->toAction( 'video' )->set( array( 'error'=>'This video was deleted' ) );
			$this->location( array( 'action'=>'video' ) );
		}
		$this->add();
	}

	public function import() {
		$this->objStore->getAndClear( $this->out );
		$this->_model->getAdditional( $this->out['arrSelect'] );
		if ( empty( $_FILES['file'] ) ) {
			return;
		}
		$import=new Project_Embed_Import();
		$import->massImport( $_POST, $_FILES['file'] );
		$this->objStore->set( array( 'msg'=>$import->getLogLine() ) );
		$this->location();
	}

	public function view() {
		if ( $this->_model->getVideo( $this->out['arrItem'], @$_GET['id'] ) ) {
			$this->_model->getAdditional( $this->out['arrSelect'] );
		}
	}

	public function multibox() {
		$this->video();
	}
}
?>