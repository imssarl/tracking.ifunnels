<?php
/**
 * WorkHorse Framework
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 26.05.2009
 * @version 1.0
 */


/**
 * Mail sender backend module
 *
 * @category WorkHorse
 * @package ProjectSource
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2009, Rodion Konnov
 */
class mail_sender extends Core_Module{

	public function set_cfg() {
		$this->inst_script=array(
			'module'=>array(
				'title'=>'Почта',
			),
			'actions'=>array(
				array( 'action'=>'events', 'title'=>'События' ),
				array( 'action'=>'emails', 'title'=>'Адреса' ),
				array( 'action'=>'attach', 'title'=>'Связываение'),
				/*array( 'action'=>'report', 'title'=>'Logged emails'),*/
			),
		);
	}

	private $mailer;

	public function before_run_parent() {
		$this->mailer = new Core_Mailer_Events();
	}

	public function report() {
		$this->mailer->get_client_log( $this->out['arrLog'], $this->out['arrPg']/*, array( 'arrNav'=>array( 'url'=>$_GET, 'reconpage'=>40 ) )*/ );
	}

	public function events() {
		if ( !empty( $_REQUEST['event'] )&&$this->mailer->add_update_event( $_POST['event'], $this->out['form_data'], $this->out['arrErr'] ) ) {
			$this->location( array( 'action'=>'events' ) );
		}
		if ( !empty( $_POST['DelList'] ) ) {
			$this->mailer->delete_event($_POST['DelList']);
			$this->location();
		}
		if ( !empty( $_GET['edit'] )&&!empty( $_GET['id'] ) ) {
			$this->out['form_data'] = $this->mailer->get_event_info($_GET['id']);
		}
		if ( !empty( $_POST['filter'] ) ) {
			$this->out['event_list'] = $this->mailer->get_events($_POST['filter']);
			$this->out['site_selected'] = $_POST['filter'];
		} else {
			$this->out['event_list'] = $this->mailer->get_events();
		}
		$this->out['sys_sites_list'] = $this->mailer->get_sys_sites_names();
	}

	public function emails() {
		if ( !empty( $_POST['email'] )||!empty( $_POST['list'] ) ) {
			if ( $this->mailer->add_update_email( $this->out['form_data'], $this->out['arrErr'], $_POST['email'], $_POST['list'] ) ) {
				$this->location( array( 'action'=>'emails' ) );
			}
		}
		if ( !empty( $_POST['DelList'] ) ) {
			$this->mailer->delete_email($_POST['DelList']);
			$this->location();
		}
		if ( !empty( $_GET['edit'] )&&!empty( $_GET['id'] ) ) {
			$this->out['form_data'] = $this->mailer->get_email_info($_GET['id']);
		}
		$this->out['sort'] = array('email' => 'email','name' => 'name','added' => 'added');
		if ( !empty( $_POST['sort'] ) ) {
			$this->out['emails'] = $this->mailer->get_emails($_POST['sort']);
		} else {
			$this->out['emails'] = $this->mailer->get_emails();
		}
	}

	public function attach() {
		if (!empty($_POST['event_id'])){
			Core_Sql::setExec("DELETE FROM mail_link WHERE event_id = ".$_POST['event_id']);
			if (!empty($_POST['To'])){
				foreach ($_POST['To'] as $key => $value){
					$this->mailer->address_attach($key, $_POST['event_id'], 1);
				}
			}
			if (!empty($_POST['From'])){
				foreach ($_POST['From'] as $key => $value){
					$this->mailer->address_attach($key, $_POST['event_id'], 2);
				}
			}
			$this->out['confirmation'] = true;
		}
		$this->out['events'] = $this->mailer->get_sites_actions();
		if (!empty($_REQUEST['event']) || !empty($_POST['event_id'])){
			$this->out['emails_to'] = $this->mailer->get_link_emails_to((empty($_POST['event_id']))?$_REQUEST['event']:$_POST['event_id']);
			$this->out['emails_from'] = $this->mailer->get_link_emails_from((empty($_POST['event_id']))?$_REQUEST['event']:$_POST['event_id']);
			foreach ($this->out['emails_from'] as $value){
				if (!empty($value['address_id'])) $this->out['from_not_empty'] = true;
			}
			$this->out['event_id'] = (empty($_POST['event_id']))?$_REQUEST['event']:$_POST['event_id'];
		}
	}
}
?>