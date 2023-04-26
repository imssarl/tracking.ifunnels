<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2011, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 17.03.2011
 * @version 3.0
 */


/**
 * Tags management module
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2011, Rodion Konnov
 */
class tags extends Core_Module {

	public final function set_cfg() {
		$this->inst_script=array(
			'module'=>array(
				'title'=>'Tags',
			),
			'actions'=>array(
				array( 'action'=>'manage', 'title'=>'Management' ),
				array( 'action'=>'cloud', 'title'=>'Cloud', 'flg_tree'=>1 ),
				array( 'action'=>'setlist', 'title'=>'Set taglist', 'flg_tpl'=>3, 'flg_tree'=>2 ),
			),
		);
	}
/*
	private function set_list() {
		if ( empty( $_POST['arrList'] ) ) {
			return false;
		}
		$_arrSids=$_arrIds=array();
		foreach( $_POST['arrList'] as $k=>$v ) {
			if ( empty( $v['del'] ) ) {
				$_arrSids[]=$v;
			} else {
				$_arrIds[]=$k;
			}
		}
		$this->objGt->tags_edit( $_arrSids );
		$this->objGt->tags_delete( $_arrIds );
		$this->location();
	}

	private function set_filter( &$arrFlt ) {
		if ( !empty( $_POST['arrFilter'] ) ) {
			$this->set_storeparam( $_POST['arrFilter'] );
		} elseif ( empty( $_POST['arrFilter'] )&&!$this->get_storeparam( $arrFlt ) ) {
			$this->set_storeparam( $_POST['arrFilter'] );
		}
		$this->get_storeparam( $arrFlt );
		$arrFlt['arrNav']=array( 'page'=>@$_GET['page'], 'reconpage'=>20 );
	}

	public function manage() {
		$this->objGt=new generic_tags();
		$this->set_list();
		$this->set_filter( $this->out['arrFilter'] );
		$this->objGt->get_tags( $this->out['arrList'], $this->out['arrPg'], $this->out['arrFilter'] );
		$this->objGt->get_types( $this->out['arrTypes'] );
	}

	public function cloud() {
		$obj=new generic_tags( array( 'flg_type'=>'video_file' ) );
		$_arrFlt=array(
			'arrAllUsed'=>array( 'order'=>'items_num+up', 'limit'=>100 ),
			'arrLastWeekUsed'=>array( 'order'=>'items_num+up', 'interval'=>7*24*60*60, 'limit'=>100 ),
			'arrAllSearched'=>array( 'order'=>'search_num+up', 'limit'=>100 ),
			'arrLastWeekSearched'=>array( 'order'=>'search_num+up', 'interval'=>7*24*60*60, 'limit'=>100 ),
		);
		foreach( $_arrFlt as $k=>$v ) {
			$obj->get_tags( $this->out[$k], $_arrTmp, $v );
			shuffle( $this->out[$k] );
		}
	}*/

	public function getlist() {
		if ( empty( $this->params['type'] ) ) {
			trigger_error( ERR_PHP.'|type or item_id not setted for module typedtags:interface_backend' );
		}
		if ( !empty($this->params['item_id']) ){
			$obj=new Core_Tags( $this->params['type'] );
			$obj->setItem( $this->params['item_id'] )->get( $this->out['arrTags'] );
		}
	}

	public function setlist() {
		if ( empty( $_POST['type'] )||empty( $_POST['item_id'] ) ) {
			$this->out_js['error']='flg_type or item_id not setted for module typedtags:ajax_set_taglist_backend';
			return;
		}
		$obj=new Core_Tags( $_POST['type'] );
		if ( !$obj->setItem( $_POST['item_id'] )->setTags( $_POST['tags'] )->set() ) {
			$this->out_js['error']='tags not set though tags:t_set_tags';
			return;
		}
		$obj->get( $this->out_js['tags'] );
		$this->out_js['error']=false;
	}
}
?>