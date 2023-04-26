<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Exquisite
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2015, web2innovation
 * @author Slepov Viacheslav <shadowdwarf@mail.ru>
 * @date 11.03.2015
 * @version 1.0
 */


/**
 * Billing Aggregator backend
 *
 * @category Project
 * @package Billing Aggregator
 * @copyright Copyright (c) 2005-2015, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class billing_aggregator extends Core_Module {

	public final function set_cfg() {
		$this->inst_script=array(
			'module'=>array(
				'title'=>'Billing Aggregator',
			),
			'actions'=>array(
				array( 'action'=>'manage', 'title'=>'Manage' ),
				array( 'action'=>'statistic', 'title'=>'Statistic' ),
				array( 'action'=>'clientdata', 'title'=>'Client Data' ),
				array( 'action'=>'send_sms', 'title'=>'Send SMS' ),
				array( 'action'=>'cancellations', 'title'=>'Cancellations' ),
			),
		);
	}

	public function cancellations(){	}

	public function clientdata(){
		if( !isset( $_POST['with_clientid'] ) && !isset( $_POST['with_phone'] ) ){
			return true;
		}
		$_billing=new Project_Billing();
		$_billing
			->withClientId( @$_POST['with_clientid'] )
			->withPhone( @$_POST['with_phone'] )
			->withPhoneORClientId()
			->withOrder( 'added--dn' )
			->getList( $_arrList )
			->getFilter( $this->out['arrFilter'] );
		$this->out['arrStatistic']=array( 'opt_in'=>0, 'opt_in_clintids'=>'', 'opt_out'=>0, 'rebils'=>0, 'closers'=>0, 'strange_data_counter' => 0,  );
		$_userPhonesClosers=$_userPhonesUnseted=$_usersRebills=array();
		foreach( $_arrList as $_bills ){
			if( empty( $_bills['phone'] ) ){
				$this->out['arrStatistic']['strange_data'].='<br/>Empty phone number for <a href="'.Core_Module::getUrl( array( 'name'=>'billing_aggregator', 'action'=>'manage' ) ).'?with_transactionid='.$_bills['transactionid'].'" target="_blank">transactionid '.$_bills['transactionid'].'</a>';
				$this->out['arrStatistic']['strange_data_counter']++;
				continue;
			}
			if(  $_bills['event_type'] == 'opt_in' && $_bills['status'] == 'success' ){
				if( isset( $_userPhones[$_bills['phone']] ) ){
					$this->out['arrStatistic']['rebils']++;
					if( isset( $_usersRebills[$_bills['phone']] ) ){
						$_usersRebills[$_bills['phone']]++;
					}else{
						$_usersRebills[$_bills['phone']]=1;
					}
				}else{
					$_userPhones[$_bills['phone']]=$_userPhonesClosers[$_bills['phone']]=true;
					$this->out['arrStatistic']['opt_in']++;
					$this->out['arrStatistic']['opt_in_clintids'].='<br/>'.$_bills['clientid'];
				}
			}
			if(  $_bills['event_type'] == 'opt_out' && $_bills['status'] != 'failed' ){
				if( isset( $_userPhones[$_bills['phone']] ) ){
					unset( $_userPhones[$_bills['phone']] );
					$this->out['arrStatistic']['opt_out']++;
					if( isset( $_userPhonesClosers[$_bills['phone']] ) ){
						unset( $_userPhonesClosers[$_bills['phone']] );
						$this->out['arrStatistic']['closers']++;
						$this->out['arrStatistic']['closers_clintids'].='<br/>'.$_bills['clientid'];
					}
				}else{
					$this->out['arrStatistic']['strange_data'].='<br/>No "OPT IN" or duplicate "OPT OUT" in statistic for <a href="'.Core_Module::getUrl( array( 'name'=>'billing_aggregator', 'action'=>'manage' ) ).'?with_phone='.$_bills['phone'].'&with_transactionid='.$_bills['transactionid'].'" target="_blank">phone '.$_bills['phone'].' & transactionid '.$_bills['transactionid'].'</a>';
					$this->out['arrStatistic']['strange_data_counter']++;
				}
			}
		}
		foreach( $_usersRebills as $_phone=>$_count ){
			if( !isset( $this->out['arrStatistic']['rebill_counter'][$_count] ) ){
				$this->out['arrStatistic']['rebill_counter'][$_count]=0;
				$this->out['arrStatistic']['rebill_phones'][$_count]=array();
			}
			unset( $this->out['arrStatistic']['rebill_phones'][$_count-1][$_phone] );
			$this->out['arrStatistic']['rebill_phones'][$_count][$_phone]=true;
			$this->out['arrStatistic']['rebill_counter'][$_count]++;
		}
		ksort( $this->out['arrStatistic']['rebill_counter'] );
		end( $this->out['arrStatistic']['rebill_counter'] );
		$_last=key( $this->out['arrStatistic']['rebill_counter'] );
		$this->out['arrStatistic']['rebill_cumulate']=array();
		$_rebillLastTime=0;
		for( $_timer=$_last; $_timer > 0; $_timer-- ){
			$_rebillLastTime=$this->out['arrStatistic']['rebill_cumulate'][$_timer]=$_rebillLastTime+@$this->out['arrStatistic']['rebill_counter'][$_timer];
		}
		ksort( $this->out['arrStatistic']['rebill_counter'] );
		ksort( $this->out['arrStatistic']['rebill_cumulate'] );
		//var_dump( $this->out['arrStatistic']['rebill_phones'] );exit;
	}

	public function statistic(){
		$_billing=new Project_Billing();
		$_userPhones=array();
		if( isset( $_GET['with_period'] ) || !empty( $_GET['with_period'] ) ){
			$_oldGet=$_GET;
			$_billing
				->withPeriod( @$_GET['with_period'], @$_GET['with_period_custom_a'], @$_GET['with_period_custom_b'] )
				->withServices( @$_GET['with_services'] )
				->getFilter( $_arrFilter );
			if( empty( $_arrFilter['with_period_custom_a'] ) ){
				$_arrFilter['with_period_custom_a']=time();
			}
			$_billing
				->withPeriod( 'custom_date_selection', 0, $_arrFilter['with_period_custom_a'] )
				->withServices( @$_GET['with_services'] )
				->withOrder( 'added--dn' )
				->getList( $_arrList );
			foreach( $_arrList as $_bills ){
				if(  $_bills['event_type'] == 'opt_in' && $_bills['status'] == 'success' && !isset( $_userPhones[$_bills['phone']] ) ){
					$_userPhones[$_bills['phone']]=true;
				}
				if( $_bills['event_type'] == 'opt_out' && $_bills['status'] != 'failed' && isset( $_userPhones[$_bills['phone']] ) ){
					unset( $_userPhones[$_bills['phone']] );
				}
			}
			$_GET=$_oldGet;
		}
		$_billing
			->withPeriod( @$_GET['with_period'], @$_GET['with_period_custom_a'], @$_GET['with_period_custom_b'] )
			->withServices( @$_GET['with_services'] )
			->withOrder( 'added--dn' )
			->getList( $_arrList )
			->getFilter( $this->out['arrFilter'] );
		$this->out['arrStatistic']=array( 'opt_in'=>0, 'opt_in_clients'=>array(), 'opt_out'=>0, 'rebils'=>0, 'closers'=>0, 'strange_data_counter' => 0 );
		$_userPhonesClosers=$_userPhonesUnseted=$_usersRebills=array();
		foreach( $_arrList as $_bills ){
			if( empty( $_bills['phone'] ) ){
				$this->out['arrStatistic']['strange_data'].='<br/>Empty phone number for <a href="'.Core_Module::getUrl( array( 'name'=>'billing_aggregator', 'action'=>'manage' ) ).'?with_transactionid='.$_bills['transactionid'].'" target="_blank">transactionid '.$_bills['transactionid'].'</a>';
				$this->out['arrStatistic']['strange_data_counter']++;
				continue;
			}
			if(  $_bills['event_type'] == 'opt_in' && $_bills['status'] == 'success' ){
				if( isset( $_userPhones[$_bills['phone']] ) ){
					$this->out['arrStatistic']['rebils']++;
					if( isset( $_usersRebills[$_bills['phone']] ) ){
						$_usersRebills[$_bills['phone']]++;
					}else{
						$_usersRebills[$_bills['phone']]=1;
					}
				}else{
					$_userPhones[$_bills['phone']]=$_userPhonesClosers[$_bills['phone']]=true;
					$this->out['arrStatistic']['opt_in']++;
					$this->out['arrStatistic']['opt_in_clients'][]=$_bills;
				}
			}
			if(  $_bills['event_type'] == 'opt_out' && $_bills['status'] != 'failed' ){
				if( isset( $_userPhones[$_bills['phone']] ) ){
					unset( $_userPhones[$_bills['phone']] );
					$this->out['arrStatistic']['opt_out']++;
					if( isset( $_userPhonesClosers[$_bills['phone']] ) ){
						unset( $_userPhonesClosers[$_bills['phone']] );
						$this->out['arrStatistic']['closers']++;
						$this->out['arrStatistic']['closers_clintids'].='<br/>'.$_bills['clientid'];
					}
				}else{
					$this->out['arrStatistic']['strange_data'].='<br/>No "OPT IN" or duplicate "OPT OUT" in statistic for <a href="'.Core_Module::getUrl( array( 'name'=>'billing_aggregator', 'action'=>'manage' ) ).'?with_phone='.$_bills['phone'].'&with_transactionid='.$_bills['transactionid'].'" target="_blank">phone '.$_bills['phone'].' & transactionid '.$_bills['transactionid'].'</a>';
					$this->out['arrStatistic']['strange_data_counter']++;
				}
			}
		}
		foreach( $_usersRebills as $_phone=>$_count ){
			if( !isset( $this->out['arrStatistic']['rebill_counter'][$_count] ) ){
				$this->out['arrStatistic']['rebill_counter'][$_count]=0;
			}
			$this->out['arrStatistic']['rebill_counter'][$_count]++;
		}
		ksort( $this->out['arrStatistic']['rebill_counter'] );
		end( $this->out['arrStatistic']['rebill_counter'] );
		$_last=key( $this->out['arrStatistic']['rebill_counter'] );
		$this->out['arrStatistic']['rebill_cumulate']=array();
		$_rebillLastTime=0;
		for( $_timer=$_last; $_timer > 0; $_timer-- ){
			$_rebillLastTime=$this->out['arrStatistic']['rebill_cumulate'][$_timer]=$_rebillLastTime+@$this->out['arrStatistic']['rebill_counter'][$_timer];
		}
		ksort( $this->out['arrStatistic']['rebill_counter'] );
		ksort( $this->out['arrStatistic']['rebill_cumulate'] );
		$_now=time();
		$_thisMounth=date("n", $_now);
		$_thisDay=date("j", $_now);
		$_thisYear=date("Y", $_now);
		$this->out['calendar_date_a']=mktime( 0, 0, 0, $_thisMounth, $_thisDay, $_thisYear );
		$this->out['calendar_date_b']=mktime( 23, 59, 59, $_thisMounth, $_thisDay, $_thisYear );
	}

	public function manage(){
		$_billing=new Project_Billing();
		if(!empty($_GET['del'])&&$_billing->withIds($_GET['del'])->del()){
			unset( $_GET['del'] );
			$this->location();
		}
		$_billing->withPaging(array( 'url'=>$_GET ))
			->withOrder( @$_GET['order'] )
			->withEventType( @$_GET['with_event_type'] )
			->withServices( @$_GET['with_services'] )
			->withDate( @$_GET['with_date'] )
			->withAggregator( @$_GET['with_aggregator'] )
			->withStatus( @$_GET['with_status'] )
			->withPhone( @$_GET['with_phone'] )
			->withClientId( @$_GET['with_clientid'] )
			->withTransactionId( @$_GET['with_transactionid'] )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
	}

	public function send_sms(){
		if( isset( $_POST['arrData']['numbers'] ) && !empty( $_POST['arrData']['numbers'] ) && isset( $_POST['arrData']['text'] ) && !empty( $_POST['arrData']['text'] ) ){
			$_arrNumbers=array_filter( explode( ',', str_replace( array( "\r\n", "\r", "\n" ),",", str_replace( array(' ','/','+','-','(',')' ), '', $_POST['arrData']['numbers'] ) ) ) );
			$_client=new Project_Ccs_Twilio_Client();
			foreach( $_arrNumbers as $_number ){
				$_client
					->setSettings( array( 'body'=>$_POST['arrData']['text'] ) )
					->setBuyerPhone( $_number )
					->sendSMS();
			}
		}
	}
}
?>