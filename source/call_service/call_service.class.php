<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2013, Pavel Livinskiy
 * @author Pavel Livinskiy <ikontakts@gmail.com>
 * @date 30.04.2013
 * @version 1.0
 */


/**
 * Call service backend interface
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-20013, Pavel Livinskiy
 */
class call_service extends Core_Module {

	public final function set_cfg() {
		$this->inst_script=array(
			'module'=>array(
				'title'=>'Call Service',
			),
			'actions'=>array(
				array( 'action'=>'voice', 'title'=>'Calls' ),
				array( 'action'=>'sms', 'title'=>'SMS' ),
				array( 'action'=>'cron', 'title'=>'Cron' ),
//				array( 'action'=>'members', 'title'=>'Members' ),
//				array( 'action'=>'broadcast', 'title'=>'Broadcast' ),
//				array( 'action'=>'ajax', 'title'=>'Check Confirm Status', 'flg_tree'=>1, 'flg_tpl'=>3 ),
			),
		);
	}

	public function broadcast(){
		if( !empty($_POST['arrData']) ){
			$_client=new Project_Ccs_Twilio_Client();
			$this->out['successCount']=$_client->broadcast($_POST['arrData'],$this->out['arrErrors']);
		}
		$_users=new Project_Users_Management();
		$_users
			->withCallInfo()
			->withConfirmPhone()
			->getList( $this->out['arrUsers'] );

	}

	public function members(){
		$_users=new Project_Users_Management();
		$_groups=new Core_Acs_Groups();
		if ( !empty( $_POST['arrFilter']['action'] ) ) {
			switch( $_POST['arrFilter']['action'] ) {
				case 'delete': $_users->withIds( array_keys( $_POST['arrList'] ) )->del(); break;
			}
			$this->location();
		}
		if(!empty($_GET['auth'])){
			$_id=Core_Payment_Encode::decode($_GET['auth']);
			header('Location: /?a='.Core_Payment_Encode::encode($_id.Zend_Registry::get('config')->user->salt.time()));
		}
		if(!empty($_GET['resend'])&&$_users->changePassword( $_GET['resend'] )){
			$this->location();
		}
		if( !empty($_GET['arrFilter']['search']['nickname']) ){
			$_users->likeNickname($_GET['arrFilter']['search']['nickname']);
		}
		if( !empty($_GET['arrFilter']['search']['email']) ){
			$_users->withEmail($_GET['arrFilter']['search']['email']);
		}
		if(!empty($_GET['arrFilter']['package_id'])){
			$_users->withPackage($_GET['arrFilter']['package_id']);
		}
		if(!empty($_GET['arrFilter']['group_id'])){
			$_groups->withIds( $_GET['arrFilter']['group_id'] )->onlyOne()->getList( $_arrGroup );
			$_users->withGroups(array($_arrGroup['sys_name']));
		}
		$_groups->toSelect()->getList( $this->out['arrGroups'] );
		$_pack=new Core_Payment_Package();
		$_pack->withHided()->toSelect()->onlyTariffPkg()->getList( $this->out['arrPack'] );
		$this->objStore->getAndClear( $this->out );
		$_users
			->withCallInfo()
			->withConfirmPhone()
			->withPaging( array( 'url'=>$_GET ) )
			->withOrder( @$_GET['order'] )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
		$this->out['arrActions']=array(
			0=>'-- Select an Action --',
			'assign'=>'Assign to Package',
			'remove'=>'Remove from Package',
			1=>'-------',
			'cancel'=>'Cancel from Package',
			'uncancel'=>'UnCancel from Package',
			2=>'-------',
			'approve'=>'Approve Registration',
			'unapprove'=>'UnApprove Registration',
			3=>'-------',
			'delete'=>'Delete Selected Users',
		);
	}

	public function ajax(){
		if( !empty($_POST['check']) ){
			$_user=new Project_Users_Management();
			$_user->withIds( Core_Users::$info['id'] )->onlyOne()->getList( $arrProfile );
			$this->out_js['result']=$arrProfile['flg_phone'];
		}
	}

	public function cron(){
		$_cron=new Project_Ccs_Arrange();
		if(!empty($_GET['del'])&&$_cron->withIds($_GET['del'])->del()){
			$this->location();
		}
		$_cron->withPaging(array( 'url'=>$_GET ))
			->withOrder( @$_GET['order'] )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
	}

	public function sms(){
		$_sms=new Project_Ccs_Sms();
		if(!empty($_GET['del'])&&$_sms->withIds($_GET['del'])->del()){
			$this->location();
		}
		$_sms->withPaging(array( 'url'=>$_GET ))
			->withOrder( @$_GET['order'] )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
	}

	public function voice(){
		$_voice=new Project_Ccs_Voice();
		if(!empty($_GET['del'])&&$_voice->withIds($_GET['del'])->del()){
			$this->location();
		}
		$_voice->withPaging(array( 'url'=>$_GET ))
			->withOrder( @$_GET['order'] )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
	}
}
?>