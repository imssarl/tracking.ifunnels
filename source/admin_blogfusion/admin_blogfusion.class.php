<?php

class admin_blogfusion extends Core_Module {
	
	public final function set_cfg() {
		$this->inst_script=array(
			'module'=>array( 'title'=>'Blogfusion', ),
			'actions'=>array(
				array( 'action'=>'plugins', 'title'=>'Plugins' ),
				array( 'action'=>'themes', 'title'=>'Themes'),
				array( 'action'=>'categories', 'title'=>'Categories' ),
				array( 'action'=>'wp_version', 'title'=>'WP version' ),
			)
		);
	}

	public function wp_version(){
		if ( !empty($_POST) ){
			Project_Wpress::wpVersion( $this->out['strLog'] );	
		}
	}
	
	public function plugins() {
		$this->objStore->getAndClear( $this->out );
		$model = new Project_Wpress_Plugins();
		if( !empty( $_FILES['zip'] ) ) {
			if ( !$model->addCommonPlugin( $_FILES['zip'] )) {
				$model->getErrors( $this->out['errorCode'] );
			} else {
				$this->objStore->set( array( 'msg'=>'added' ) );
				$this->location( array('action' => 'plugins') );
			}
		}
		if( !empty( $_GET['delete'] ) ) {
			$model->deleteCommonPlugin( $_GET['delete'] );
			$this->objStore->set( array( 'msg'=>'delete' ) );
			$this->location( array('action' => 'plugins') );
		}
		$model->withPagging(array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		))->onlyCommon()->withOrder( @$_GET['order'] )->getList( $this->out['arrList'] );
		$model->getPaging( $this->out['arrPg'] );
	}
	
	public function themes() {
		$this->objStore->getAndClear( $this->out );
		$model = new Project_Wpress_Theme();
		if ( !empty( $_FILES['zip'] ) ) {
			if ( !$model->addCommonTheme( $_POST['theme'], $_FILES['zip'] ) ) {
				$model->getErrors( $errorCode );
				$this->objStore->set( array( 'errorCode'=>$errorCode ) );
				$this->location( array('action' => 'themes') );				
				
			} else {
				$this->objStore->set( array( 'msg'=>'added' ) );
				$this->location( array('action' => 'themes') );
			}
		}
		if( !empty( $_GET['delete'] ) ) {
			$model->deleteCommonTheme($_GET['delete']);
			$this->objStore->set( array( 'msg'=>'delete' ) );
			$this->location( array('action' => 'themes') );
		}		
		$model->withPagging(array( 
			'page'=>@$_GET['page'], 
			'reconpage'=>$this->objUser->u_info['arrSettings']['rows_per_page'],
			'numofdigits'=>$this->objUser->u_info['arrSettings']['page_links'],
		))->onlyCommon()->withOrder( @$_GET['order'] )->withPreview()->getList( $this->out['arrList'] );		
		$model->getPaging( $this->out['arrPg'] );
	}
	
	public function categories() {}
}
?>