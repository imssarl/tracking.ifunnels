<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Billing
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2015, web2innovation
 * @author Slepov Viacheslav <shadowdwarf@mail.ru>
 * @date 11.03.2015
 * @version 1.0
 */
 
/**
 * Project_Billing
 *
 * @category Project
 * @package Project_Billing
 * @copyright Copyright (c) 2005-2015, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */

class Project_Billing extends Core_Data_Storage {

	protected $_table='billing_aggregator';
	protected $_fields=array( 'id', 'services' ,'aggregator', 'status', 'errormessage', 'event_type', 'clientid', 'revenuecurrency', 'phone', 'amount', 'service', 'transactionid', 'enduserprice', 'country', 'mno', 'mnocode', 'revenue', 'interval', 'opt_in_channel', 'sign', 'userid', 'added' );
	
	private $_withPhone=false;
	private $_withTransactionId=false;
	private $_withEventType=false;
	private $_withServices=false;
	private $_withDate=false;
	private $_withPeriod=false;
	private $_withPeriodDateA=false;
	private $_withPeriodDateB=false;
	private $_withAggregator=false;
	private $_withStatus=false;
	private $_withClientId=false;
	private $_withPhoneORClientId=false;

	public function withPhone( $_strType='' ) {
		if ( !isset( $_strType ) || empty( $_strType ) ) {
			$this->_withPhone=false;
			return $this;
		}
		$_arrType=array_filter( explode( ',', str_replace( array( "\n\r", "\n", "\r" ), ',', $_strType ) ) );
		$this->_withPhone=$_arrType;
		$this->_cashe['with_phone']=implode( "\n", $_arrType );
		return $this;
	}

	public function withPhoneORClientId(){
		$this->_withPhoneORClientId=true;
		return $this;
	}

	public function withTransactionId( $_str ){
		if ( !isset( $_str ) || empty( $_str ) ) {
			$this->_withTransactionId=false;
			return $this;
		}
		$this->_withTransactionId=$_str;
		$this->_cashe['with_transactionid']=$_str;
		return $this;
	}

	public function withServices( $_strType='' ) {
		if ( !isset( $_strType ) || $_strType==='' ) {
			$this->_withServices=false;
			return $this;
		}
		$this->_withServices=$_strType;
		$this->_cashe['with_services']=$_strType;
		return $this;
	}

	public function withEventType( $_intType='' ) {
		if ( !isset( $_intType ) || empty( $_intType ) ) {
			$this->_withEventType=false;
			return $this;
		}
		$this->_withEventType=$_intType;
		$this->_cashe['with_event_type']=$_intType;
		return $this;
	}

	public function withAggregator( $_intType='' ) {
		if ( !isset( $_intType ) || empty( $_intType ) ) {
			$this->_withAggregator=false;
			return $this;
		}
		$this->_withAggregator=$_intType;
		$this->_cashe['with_aggregator']=$_intType;
		return $this;
	}

	public function withStatus( $_intType='' ) {
		if ( !isset( $_intType ) || empty( $_intType ) ) {
			$this->_withStatus=false;
			return $this;
		}
		$this->_withStatus=$_intType;
		$this->_cashe['with_status']=$_intType;
		return $this;
	}

	public function withClientId( $_strType='' ) {
		if ( !isset( $_strType ) || empty( $_strType ) ) {
			$this->_withClientId=false;
			return $this;
		}
		$_arrType=array_filter( explode( ',', str_replace( array( "\n\r", "\n", "\r" ), ',', $_strType ) ) );
		$this->_withClientId=$_arrType;
		$this->_cashe['with_clientid']=implode( "\n", $_arrType );
		return $this;
	}
	
	public function withDate( $_intType='' ) {
		if ( !isset( $_intType ) || empty( $_intType ) ) {
			$this->_withDate=false;
			return $this;
		}
		switch ( $_intType ){
			case 'daily': $this->_withDate=24*60*60; break;
			case 'weekly': $this->_withDate=7*24*60*60; break;
			case 'monthly': $this->_withDate=31*24*60*60; break;
		}
		$this->_cashe['with_date']=$_intType;
		return $this;
	}

	public static function getMounthDays( $_thisMounth, $_thisYear ) {
		switch( $_thisMounth ){
			case '2':
				return ((($_thisYear%4==0) && ($_thisYear%100)) || $_thisYear%400==0) ? 29 : 28;
			break;
			case '4':
			case '6':
			case '9':
			case '11':
				return 30;
			break;
			default:
				return 31;
			break;
		}
	}

	public function withPeriod( $_strType='', $_dateA=0, $_dateB=0 ) {
		if ( !isset( $_strType ) || empty( $_strType ) ) {
			$this->_withPeriod=false;
			return $this;
		}
		$_now=time();
		$_thisMounth=date("n", $_now);
		$_thisDay=date("j", $_now);
		$_thisYear=date("Y", $_now);
		$_nowStart=mktime( 0, 0, 0, $_thisMounth, $_thisDay, $_thisYear );
		$_nowEnd=mktime( 23, 59, 59, $_thisMounth, $_thisDay, $_thisYear );
		$this->_withPeriod=$_strType;
		$_weekDay=date("w", $_now);
		if( $_weekDay == 0 ){
			$_weekDay=7;
		}
		switch ( $_strType ){
			case 'today':
				$this->_withPeriodDateA=$_nowStart;
				$this->_withPeriodDateB=$_nowEnd;
			break;
			case 'yesterday':
				$this->_withPeriodDateA=$_nowStart-24*60*60;
				$this->_withPeriodDateB=$_nowEnd-24*60*60;
			break;
			case 'this_week':
				$this->_withPeriodDateA=$_nowStart-($_weekDay-1)*24*60*60;
				$this->_withPeriodDateB=$_nowEnd-($_weekDay-8)*24*60*60;
			break;
			case 'last_week':
				$this->_withPeriodDateA=$_nowStart-($_weekDay+6)*24*60*60;
				$this->_withPeriodDateB=$_nowEnd-$_weekDay*24*60*60;
			break;
			case 'this_month':
				$this->_withPeriodDateA=mktime( 0, 0, 0, $_thisMounth, 1, $_thisYear );
				$this->_withPeriodDateB=mktime( 23, 59, 59, $_thisMounth, self::getMounthDays( $_thisMounth, $_thisYear ), $_thisYear );
			break;
			case 'last_month':
				if( $_thisMounth == 1 ){
					$_thisMounth = 12;
					$_thisYear-=1;
				}else{
					$_thisMounth-=1;
				}
				$this->_withPeriodDateA=mktime( 0, 0, 0, $_thisMounth, 1, $_thisYear );
				$this->_withPeriodDateB=mktime( 23, 59, 59, $_thisMounth, self::getMounthDays( $_thisMounth, $_thisYear ), $_thisYear );
			break;
			case 'custom_date_selection':
				$this->_withPeriodDateA=$_dateA;
				$this->_withPeriodDateB=$_dateB;
			break;
		}
		$this->_cashe['with_period']=$_strType;
		if( $this->_withPeriodDateA < $this->_withPeriodDateB ){
			$this->_cashe['with_period_custom_a']=$this->_withPeriodDateA;
			$this->_cashe['with_period_custom_b']=$this->_withPeriodDateB;
		}else{
			$this->_cashe['with_period_custom_a']=$this->_withPeriodDateB;
			$this->_cashe['with_period_custom_b']=$this->_withPeriodDateB=$this->_withPeriodDateA;
			$this->_withPeriodDateA=$this->_cashe['with_period_custom_a'];
		}

		return $this;
	}
	
	protected function init() {
		parent::init();
		$this->_withPhone=false;
		$this->_withTransactionId=false;
		$this->_withEventType=false;
		$this->_withServices=false;
		$this->_withDate=false;
		$this->_withAggregator=false;
		$this->_withClientId=false;
		$this->_withPhoneORClientId=false;
	}
	
	protected function assemblyQuery() {
		parent::assemblyQuery();
		if( $this->_withPhoneORClientId && ( $this->_withPhone || $this->_withClientId ) ){
			$this->_crawler->set_where('( d.phone IN ( '.Core_Sql::fixInjection( str_replace( array(' ','-','+','(',')'),'',$this->_withPhone ) ).' ) OR d.clientid IN ( '.Core_Sql::fixInjection( $this->_withClientId ).' ) )' );
		}else{
			if( $this->_withPhone ){
				$this->_crawler->set_where('d.phone IN ( '.Core_Sql::fixInjection( str_replace( array(' ','-','+','(',')'),'',$this->_withPhone ) ).' )' );
			}
			if( $this->_withClientId ){
				$this->_crawler->set_where('d.clientid IN ( '.Core_Sql::fixInjection( $this->_withClientId ).' )' );
			}
		}
		if( $this->_withTransactionId ){
			$this->_crawler->set_where('d.transactionid='.Core_Sql::fixInjection( $this->_withTransactionId ) );
		}
		if( $this->_withAggregator ){
			$this->_crawler->set_where('d.aggregator='.Core_Sql::fixInjection( $this->_withAggregator ) );
		}
		if( $this->_withStatus == 'others'){
			$this->_crawler->set_where('d.status NOT IN ("failed","success")' );
		}elseif( $this->_withStatus ){
			$this->_crawler->set_where('d.status='.Core_Sql::fixInjection( $this->_withStatus ) );
		}
		if( $this->_withEventType !== false && $this->_withEventType != 'no_event' ){
			$this->_crawler->set_where('d.event_type='.Core_Sql::fixInjection( $this->_withEventType ) );
		}elseif( $this->_withEventType == 'no_event' ){
			$this->_crawler->set_where("( d.event_type is NULL or d.event_type = '' )");
		}
		if( $this->_withServices !== false && $this->_withServices != 'all_services' ){
			$this->_crawler->set_where('d.services='.Core_Sql::fixInjection( $this->_withServices ) );
		}
		if( $this->_withDate !== false ){
			$this->_crawler->set_where('d.added >= '.Core_Sql::fixInjection( time()-$this->_withDate ) );
		}
		if( $this->_withPeriodDateA !== false && $this->_withPeriodDateB !== false ){
			$this->_crawler->set_where('d.added >= '.Core_Sql::fixInjection( $this->_withPeriodDateA ).' AND d.added <= '.Core_Sql::fixInjection( $this->_withPeriodDateB )  );
		}
	}
}
?>