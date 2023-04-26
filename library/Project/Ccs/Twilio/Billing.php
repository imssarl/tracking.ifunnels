<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Ccs_Twilio
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2015, Web2Innovation
 * @author Slepov Viacheslav <shadowdwarf@mail.ru>
 * @date 11.03.2015
 * @version 0.1
 */

/**
 * Входящие сообщения и звонки от Twilio для Billing
 *
 * @category Project
 * @package Project_Ccs_Twilio
 * @copyright Copyright (c) 2015, Web2Innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Ccs_Twilio_Billing extends Project_Ccs_Twilio_Abstract {

	/**
	 * Принимает входящие SMS, обрабатывает их и принимает решения что с ними дальше делать
	 * @throws Project_Ccs_Exception
	 */
	public function sms(){
		if( empty($this->_settings['From']) ){
			throw new Project_Ccs_Exception('Can not find user info');
		}
		$_billings=new Project_Billing();
		$_billings->withPhone( $this->_settings['From'] )->getList( $arrUserBillings );
		$_twilio=new Project_Ccs_Twilio_Client();
		if( count( $arrUserBillings )==0 ){
			$_twilio->setSettings(array('body'=>'Mobile number registered as this one does not exist.'))
				->setBuyerPhone( $this->_settings['From'] )
				->sendSMS();
			throw new Project_Ccs_Exception('Mobile number registered as this one does not exist.');
		}
		if( empty($this->_settings['SmsSid']) ){
			throw new Project_Ccs_Exception('SMS sid is empty');
		}
		$_message=$this->_client->account->sms_messages->get( $this->_settings['SmsSid'] );
		$_model=new Project_Ccs_Sms();
		$_model->setEntered(array(
			'SmsSid'=>$_message->sid,
			'To'=>$_message->to,
			'From'=>$_message->from,
			'SmsStatus'=>$_message->status,
			'Direction'=>$_message->direction,
			'Body'=>$_message->body,
			'user_id'=>$_message->from
		))->set();
		$_command=strtolower($_message->body);
		$_arrSettings=array();
		switch( $_command ){
			case 'stop':
			case 'texts stop':
			case 'unsubscribe':
			case 'cancel':
				$_twilio->setSettings(array('body'=>Project_Ccs_Twilio_Billing::unsubscribe( $arrUserBillings ) ))
					->setBuyerPhone( $this->_settings['From'] )
					->sendSMS();
			break;
			default :
				$_twilio->setSettings(array('body'=>'Code <'.$_command.'> is not recognized'))
					->setBuyerPhone( $this->_settings['From'] )
					->sendSMS();
				throw new Project_Ccs_Exception( 'Code <'.$_command.'>  is not recognized');
			break;
		}
	}

	// return last billing array
	public static function lastBillings( $_billings=array() ){
		$_arrBillings=$_arrRebilling=array();
		$_sortedByDate=array();
		foreach( $_billings as $_billing ){
			$_sortedByDate[$_billing['added']]=$_billing;
		}
		asort($_sortedByDate);
		$_billings=$_sortedByDate;
		unset( $_sortedByDate );
		foreach( $_billings as $_date=>$_billing ){
			if( $_billing['status'] == 'failed' ){
				continue;
			}
			if( $_billing['status'] == 'success' && $_billing['event_type'] == 'opt_in' ){
				if( !isset( $_arrRebilling[$_billing['aggregator']] ) ){
					$_arrRebilling[$_billing['aggregator']]=array();
				}
				$_arrRebilling[$_billing['aggregator']][$_billing['transactionid']]['flg_rebiling']=true;
				if( !empty( $_billing['phone'] ) ){
					$_arrRebilling[$_billing['aggregator']][$_billing['transactionid']]['phone']=$_billing['phone'];
				}
				if( !empty( $_billing['clientid'] ) ){
					$_arrRebilling[$_billing['aggregator']][$_billing['transactionid']]['clientid']=$_billing['clientid'];
				}
			}
			if( !isset( $_arrBillings[$_billing['aggregator']] ) ){
				$_arrBillings[$_billing['aggregator']]=array();
			}
			$_arrBillings[$_billing['aggregator']][$_billing['transactionid']]=$_billing;
		}
		foreach( $_arrBillings as $_key=>&$_aggregator ){
			foreach( $_aggregator as $_id=>&$_transactionid ){
				if( isset( $_arrRebilling[$_key][$_id]['flg_rebiling'] ) ){
					$_transactionid['flg_rebiling']=true;
					if( empty( $_transactionid['phone'] ) && !empty( $_arrRebilling[$_key][$_id]['phone'] ) ){
						$_transactionid['phone']=$_arrRebilling[$_key][$_id]['phone'];
					}
					if( empty( $_transactionid['clientid'] ) && !empty( $_arrRebilling[$_key][$_id]['clientid'] ) ){
						$_transactionid['clientid']=$_arrRebilling[$_key][$_id]['clientid'];
					}
				}
			}
		}
		return $_arrBillings;
	}

	// return different message
	public static function unsubscribe( $_billings=array() ){
		$output='';
		$_arrBillings=self::lastBillings( $_billings );
		if( isset( $_arrBillings['centili'] ) && !empty( $_arrBillings['centili'] ) ){
			foreach( $_arrBillings['centili'] as $_bill ){
				if( $_bill['event_type'] == 'opt_in' ){
					//https://www.centili.com/manual/payment-api.pdf
					$ch=curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://api.centili.com/api/payment/1_3/unsubscribe' );
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, "apikey=32f70a647af9046e58316c5b5babe432&msisdn=".$_bill['phone']."&mccmnc=".$_bill['mno']."&mno=".$_bill['mnocode'] );
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					$output=curl_exec($ch);
					curl_close($ch);
				}
			}
		}
		if( isset( $_arrBillings['txtnations'] ) && !empty( $_arrBillings['txtnations'] ) ){
			foreach( $_arrBillings['txtnations'] as $_bill ){
				if( $_bill['event_type'] == 'opt_in' ){
					//http://wiki.txtnation.com/wiki/Payforit_Subscription_API
					$ch=curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://payforit.txtnation.com/api/stop/?company=ims&password=75d030b8c55b7ba2d57df9062f61a27b&transactionId='.$_bill['transactionid'] );
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					$output=curl_exec($ch);
					$output=json_decode( $output, true );
					curl_close($ch);
					if( $output !== false ){
						if( $output['status'] == 'ERROR' ){
							curl_close($ch);
							return 'Error: '.$output['result'];
						}
					}
				}
			}
		}
		if( count( $_arrBillings ) == 0 ){
			return 'Your subscription is already cancelled. All the best. Instaffiliate Team';
		}
		return 'Your subscription has now been cancelled.';
	}

	public function voice(){
		if( empty($this->_settings['From']) ){
			throw new Project_Ccs_Exception('Can not find user info');
		}
		if( empty($this->_settings['CallSid']) ){
			throw new Project_Ccs_Exception('Call sid is empty');
		}
		$_call=$this->_client->account->calls->get($this->_settings['CallSid']);
		$_model=new Project_Ccs_Voice();
		$_model->setEntered(array(
			'CallSid'=>$_call->sid,
			'To'=>$_call->to,
			'From'=>$_call->from,
			'CallStatus'=>$_call->status,
			'Direction'=>$_call->direction,
			'user_id'=>$_call->from
		))->set();
		$_twilio=new Project_Ccs_Twilio_Apps();
		$_twilio->setSettings( array('app'=>'Unsubscribe','action'=>'unsubscribe') )->run();
	}

}
?>