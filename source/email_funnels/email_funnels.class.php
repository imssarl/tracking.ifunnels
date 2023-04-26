<?php
class email_funnels extends Core_Module {

	public function set_cfg(){
		$this->inst_script=array(
			'module'=>array(
				'title'=>'Email Funnels QJMPZ',
			),
			'actions'=>array(
				array( 'action'=>'getcode', 'title'=>'Get Code', 'flg_tpl'=>1, 'flg_tree'=>1 ),
				array( 'action'=>'unsubscribe', 'title'=>'Unsubscribe page', 'flg_tpl'=>1, 'flg_tree'=>1 ),
				array( 'action'=>'webhook', 'title'=>'Webhook action', 'flg_tpl'=>1, 'flg_tree'=>1 ),
			),
		);
	}

	public function getcode(){
		if( !empty( $_POST ) && isset( $_POST['code'] ) && isset( $_POST['email'] ) ){
			$redirectUrl=$_POST['ef_redirect_url'];
			if( @file_get_contents($redirectUrl, FALSE, NULL, 0, 10) === false ){
				$redirectUrl=$_SERVER['HTTP_REFERER'];
			}
			$_arrData = Core_Payment_Encode::decode( $_POST['code'] );
			if( !isset( $_arrData['user_id'] ) ||  !isset( $_arrData['id'] ) ){
				// багованная шифровка
				header( 'Location: '.$redirectUrl );
				exit;
			}
			$curl=Core_Curl::getInstance();
			if( isset( $_POST ) && !empty( $_POST ) ){
				$_POST['ip']=Project_Conversionpixel::getUserIp();
				$curl->setPost( $_POST );
			}
			$link='https://app.ifunnels.com/email-funnels/getcode/';
			if( $_SERVER['SERVER_NAME']=='qjmpz.local' ){
				$link='http://cnm.local/email-funnels/getcode/';
			}
			$curl->getContent($link);
			$_return=$curl->getResponce();
			if( $_return == 'true' ){
				header( 'Location: '.$redirectUrl );
			}else{
				echo $_return;
			}
			exit;
		}
		header( 'Location: '.$_SERVER['HTTP_REFERER'] );
		exit;
	}
	
	public function webhook(){
		if( !empty( $_GET['code'] ) ){
			$_code=$_GET['code'];
		}
		if( !empty( $this->out['arrPrm']['action_vars'] ) ){
			$_code=$this->out['arrPrm']['action_vars'];
		}
		if( empty( $_code ) ){
			echo 'Error empty request!';
			exit;
		}
		$_arrData=Project_Efunnel_Subscribers::decode( $_code );
		if( !isset( $_arrData['smtpid'] ) || empty( $_arrData['smtpid'] ) ){
			echo 'Can\'t get data!';
			exit;
		}

		// TODO включено по сообщению от 06.08.2021 в Slack
		// if ($_arrData['user_id'] == '1' && $_arrData['event'] == 'click') {
		// 	ob_clean();
		// 	header('Location: ' . $_arrData['link']);
		// 	exit();
		// }

		//=======
		$_withLogger=true;
		$_firstStart=$_start=$_memoryStart=0;
		if( $_withLogger ){
			$_writer=new Zend_Log_Writer_Stream( Zend_Registry::get('config')->path->absolute->logfiles.'Project_Efunnels_Webhook.log' );
			$_writer->setFormatter( new Zend_Log_Formatter_Simple("%timestamp% %priorityName% (%priority%): %message%\r\n") );
			$_logger=new Zend_Log( $_writer );
			$_firstStart=$_start=microtime(true);
			$_logger->info('Start #'.$_firstStart.'#-----------------------------------------------------------------------------------------------------' );
			$_logger->info('#'.$_firstStart.'# '.serialize( $_arrData ) );
			$_memoryStart=memory_get_usage();
		}
		//=======
		try{
			Core_Sql::setConnectToServer( 'lpb.tracker' );
			//========
			if( isset( $_GET['debugcode'] ) ){
				var_dump( $_arrData );
				Core_Sql::renewalConnectFromCashe();
				exit;
			}
			$_obj=new Project_Efunnel_Subscribers($_arrData['user_id']);
			$_obj->withEmail( $_arrData['email'] )->withSmtpId( $_arrData['smtpid'] )->getList( $_dataEmail );
			if( isset( $_GET['debuguser'] ) ){
				var_dump( $_dataEmail );
				Core_Sql::renewalConnectFromCashe();
				exit;
			}
			if( !empty( $_dataEmail ) ){
				$_dataEmail=$_dataEmail[0];
				$_thisEvent=false;
				foreach( $_dataEmail['efunnel_events'] as $ev_id=>$_event ){
					if( $_event['smtpid'] == $_arrData['smtpid'] ){
						$_thisEvent=$_event+array( 'ev_id'=>$ev_id );
					}
				}
				if( !isset( $_thisEvent['subject'] ) && isset( $_arrData['subject'] ) ){
					$_thisEvent['search_text']=json_decode( $_thisEvent['search_text'], 1 );
					if( empty( $_thisEvent['search_text'] ) ){
						$_thisEvent['search_text']=array();
					}
					$_thisEvent['search_text']['subject']=$_arrData['subject'];
					Core_Sql::setExec( 'UPDATE s8rs_events_'.$_arrData['user_id'].' SET search_text=\''.json_encode( $_thisEvent['search_text'] ).'\' WHERE search_var="'.$_thisEvent['smtpid'].'"');
				}
			}else{
				if( $_withLogger ){
					$_logger->info('No user in #'.$_firstStart.'#' );
				}
			}
			// проверяем не пришел ли ответ от smtp сервиса о доставке раньше и использоватся будет только smtp лог
			if( isset( $_thisEvent['opened'] ) && $_thisEvent['opened']==1 && $_arrData['event'] == 'open' ){
				header( "Content-type: image/png" );
				$fakeimg=@imagecreate( 16, 10 )
					or die("Can't create img!");
				$background_color=imagecolorallocate($fakeimg, 255, 255, 255);
				imagestring($fakeimg, 1, 1, 1,  "CNM", imagecolorallocate($fakeimg, 233, 14, 91));
				imagepng( $fakeimg );
				imagedestroy( $fakeimg );
				//========
				Core_Sql::renewalConnectFromCashe();
				$_cnmUrl='https://app.ifunnels.com:443/services/automations_api.php';
				if( $_SERVER['SERVER_NAME']=='qjmpz.local' ){
					$_cnmUrl='http://cnm.local/email-funnels/services/automations_api.php';
				}
				Core_Curl::async( $_cnmUrl, array(
					'a'=>'o',
					'e'=>$_arrData['email'],
					'f'=>$_thisEvent['ef_id'],
					'm'=>$_thisEvent['message_id'],
					'u'=>$_arrData['user_id']
				), 'POST' );
				exit;
			}
			if( isset( $_thisEvent['clicked'] ) && $_thisEvent['clicked']==1 && $_arrData['event'] == 'click' ){
				if( isset( $_arrData['link'] )){
					ob_clean();
					header( 'Location: '.html_entity_decode( $_arrData['link'] ) );
				}else{
					echo 'Redirect to error '.$_arrData['link'];
				}
				//========
				Core_Sql::renewalConnectFromCashe();
				exit;
			}
			if( $_thisEvent !== false ){
				$_cnmUrl='https://app.ifunnels.com:443/services/automations_api.php';
				if( $_SERVER['SERVER_NAME']=='qjmpz.local' ){
					$_cnmUrl='http://cnm.local/email-funnels/services/automations_api.php';
				}
				switch( $_arrData['event'] ){
					case 'click':
						$_thisEvent['search_text']=json_decode( $_thisEvent['search_text'], 1 );
						if( empty( $_thisEvent['search_text'] ) ){
							$_thisEvent['search_text']=array();
						}
						if( !isset( $_thisEvent['opened'] ) ){
							$_thisEvent['search_text']['opened']=date( "Y-m-d", time());
						}
						if( !isset( $_thisEvent['delivered'] ) ){
							$_thisEvent['search_text']['delivered']=date( "Y-m-d", time());
						}
						$_thisEvent['search_text']['clicked']=date( "Y-m-d", time());
						if( isset( $_GET['debug'] ) ){
							var_dump( 'UPDATE s8rs_events_'.$_arrData['user_id'].' SET search_text=\''.json_encode( $_thisEvent['search_text'] ).'\' WHERE search_var="'.$_thisEvent['smtpid'].'"' );
							Core_Sql::renewalConnectFromCashe();
							exit;
						}
						Core_Sql::setExec( 'UPDATE s8rs_events_'.$_arrData['user_id'].' SET search_text=\''.json_encode( $_thisEvent['search_text'] ).'\' WHERE search_var="'.$_thisEvent['smtpid'].'"' );
						if( $_withLogger ){
							$_logger->info('Update click #'.$_firstStart.'#' );
						}
						Core_Curl::async( $_cnmUrl, array(
							'a'=>'c',
							'e'=>$_arrData['email'],
							'f'=>$_thisEvent['ef_id'],
							'm'=>$_thisEvent['message_id'],
							'u'=>$_arrData['user_id']
						), 'POST' );
						if( !isset( $_thisEvent['opened'] ) | $_thisEvent['opened']!=1 ){
							Core_Curl::async( $_cnmUrl, array(
								'a'=>'o',
								'e'=>$_arrData['email'],
								'f'=>$_thisEvent['ef_id'],
								'm'=>$_thisEvent['message_id'],
								'u'=>$_arrData['user_id']
							), 'POST' );
						}
					break;
					case 'open':
						$_thisEvent['search_text']=json_decode( $_thisEvent['search_text'], 1 );
						if( empty( $_thisEvent['search_text'] ) ){
							$_thisEvent['search_text']=array();
						}
						if( !isset( $_thisEvent['delivered'] ) ){
							$_thisEvent['search_text']['delivered']=date( "Y-m-d", time());
						}
						$_thisEvent['search_text']['opened']=date( "Y-m-d", time());
						if( isset( $_GET['debug'] ) ){
							var_dump( 'UPDATE s8rs_events_'.$_arrData['user_id'].' SET search_text=\''.json_encode( $_thisEvent['search_text'] ).'\' WHERE search_var="'.$_thisEvent['smtpid'].'"' );
							Core_Sql::renewalConnectFromCashe();
							exit;
						}
						Core_Sql::setExec( 'UPDATE s8rs_events_'.$_arrData['user_id'].' SET search_text=\''.json_encode( $_thisEvent['search_text'] ).'\' WHERE search_var="'.$_thisEvent['smtpid'].'"');
						if( $_withLogger ){
							$_logger->info('Update open #'.$_firstStart.'#' );
						}
						Core_Curl::async( $_cnmUrl, array(
							'a'=>'o',
							'e'=>$_arrData['email'],
							'f'=>$_thisEvent['ef_id'],
							'm'=>$_thisEvent['message_id'],
							'u'=>$_arrData['user_id']
						), 'POST' );
					break;
				}
			}
			if( $_arrData['event'] == 'open' ){
				header( "Content-type: image/png" );
				$fakeimg=@imagecreate( 16, 10 )
					or die("Can't create img!");
				$background_color=imagecolorallocate($fakeimg, 255, 255, 255);
				imagestring($fakeimg, 1, 1, 1,  "CNM", imagecolorallocate($fakeimg, 233, 14, 91));
				imagepng( $fakeimg );
				imagedestroy( $fakeimg );
			}
			if( $_arrData['event'] == 'click' && isset( $_arrData['link'] ) ){
				if( isset( $_arrData['link'] ) ){
					ob_clean();
					header('Location: '.$_arrData['link']);
				}else{
					echo 'Redirect to error '.$_arrData['link'];
				}
			}
			//========
			Core_Sql::renewalConnectFromCashe();
		} catch(Exception $e) {
			Core_Sql::renewalConnectFromCashe();
			echo 'Links do not work in test emails so that statistics data is not altered.';
		}
		exit;
	}
	
	public function unsubscribe(){
		if( !isset( $_REQUEST['c'] ) ){
			ob_clean();
			header('HTTP/1.1 404 Not Found');
			exit;
		}
		$curl=Core_Curl::getInstance();
		$_get='';
		if( isset( $_GET ) && !empty( $_GET ) ){
			$_get='?'.http_build_query( $_GET );
		}
		if( isset( $_POST ) && !empty( $_POST ) ){
			$curl->setPost( $_POST );
		}
		$link='https://app.ifunnels.com/email-funnels/unsubscribe/'.$_get;
		if( $_SERVER['SERVER_NAME']=='qjmpz.local' ){
			$link='http://cnm.local/email-funnels/unsubscribe/'.$_get;
		}
		if ( !$curl->getContent($link) ){
			exit;
		}
		$_data=false;
		$_responce=$curl->getResponce();
		$this->out['responce']=$_responce;
		if( is_string( $_responce ) ){
			$_data=@unserialize($_responce);
		}
		if( is_array(  $_data ) ){
			$this->out=$_data+$this->out;
		}else{
			echo( $_responce );exit;
		}
	}
}
?>