<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Exquisite
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2015, web2innovation
 * @author Slepov Viacheslav <shadowdwarf@mail.ru>
 * @date 23.02.2015
 * @version 1.0
 */


/**
 * Project_Traffic_Subscribers
 *
 * @category Project
 * @package Project_Traffic_Subscribers
 * @copyright Copyright (c) 2005-2015, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */

class Project_Traffic_Subscribers{

	protected $_table='traffic_subscribers';
	protected $_fields=array('id', 'campaign_id', 'ip', 'referer', 'added');

	protected $_withCampaignId=array(); // c данными popup id
	protected $_withIP=array(); // c данными popup id
	protected $_onlyCount=false; // только количество
	protected $_onlyActive=false; // ппроверка активности установленная пользователем
	protected $_onlyOne=false; // только одна запись
	
	public function clearOld() {
		// Core_Sql::setExec( 'DELETE FROM '.$this->_table.' WHERE added<'.( time()-60*60*24*30 ) );
		return $this;
	}
	
	public function withCampaignId( $_arrIds=array() ) {
		$this->_withCampaignId=$_arrIds;
		return $this;
	}

	public function withIP( $_arrIPs=array() ) {
		$this->_withIP=$_arrIPs;
		return $this;
	}

	public function onlyCount() {
		$this->_onlyCount=true;
		return $this;
	}

	public function setLockingChange( $_userId, $_campaignId ) {
		if( !isset( $_userId ) || !isset( $_campaignId ) ){
			return false;
		}
		$_flgLocked=Core_Sql::getRecord( 'SELECT count(*) FROM traffic_locking WHERE user_id IN ('.Core_Sql::fixInjection( $_userId ).') AND campaign_id IN ('.Core_Sql::fixInjection( $_campaignId ).')' );
		if( $_flgLocked['count(*)'] == 1 ){
			Core_Sql::setExec( 'DELETE FROM traffic_locking WHERE user_id IN ('.Core_Sql::fixInjection( $_userId ).') AND campaign_id IN ('.Core_Sql::fixInjection( $_campaignId ).')' );
		}else{
			Core_Sql::setExec( 'INSERT INTO traffic_locking (user_id, campaign_id) VALUES ('.Core_Sql::fixInjection( $_userId ).', '.Core_Sql::fixInjection( $_campaignId ).')' );
		}
		return true;
	}

	public function onlyActive() {
		$this->_onlyActive=true;
		return $this;
	}

	public function onlyOne() {
		$this->_onlyOne=true;
		return $this;
	}

	protected function assemblyQuery() {
		$this->_crawler->set_select( 'd.*' );
		$this->_crawler->set_from( $this->_table.' d' );
		if ( !empty( $this->_withCampaignId ) ) {
			$this->_crawler->set_where( 'd.campaign_id IN ('.Core_Sql::fixInjection( $this->_withCampaignId ).')' );
		}
		if ( !empty( $this->_withIP ) ) {
			$this->_crawler->set_where( 'd.ip IN ('.Core_Sql::fixInjection( $this->_withIP ).')' );
		}
		if ( !empty( $this->_onlyActive ) ) {
			$this->_crawler->set_where( '( SELECT count(*) FROM traffic_locking WHERE d.campaign_id=campaign_id AND d.referer=user_id )<>0' );
		}
	}

	public function getList( &$mixRes ) {
		$this->_crawler=new Core_Sql_Qcrawler();
		$this->assemblyQuery();
		if ( !$this->_onlyCount ) {
			$this->_crawler->get_result_full( $_strSql );
		}
		if ( $this->_onlyCount ) {
			$mixRes=Core_Sql::getCell( $this->_crawler->get_result_counter() );
		} elseif ( $this->_onlyOne ) {
			$mixRes=Core_Sql::getRecord( $_strSql );
		} else {
			$mixRes=Core_Sql::getAssoc( $_strSql );
		}
		$this->init();
		return $this;
	}

	protected function init() {
		$this->_onlyCount=false;
		$this->_onlyActive=false;
		$this->_onlyOne=false;
		$this->_withIP=array();
		$this->_withCampaignId=array();
	}

	public function setEntered( $_mix=array() ) {
		$this->_data=is_object( $_mix )? $_mix:new Core_Data( $_mix );
		return $this;
	}

	public function set() {
		$this->_data->setFilter();
		if( empty( $this->_data->filtered['id'] ) ) {
			$this->_data->setElement( 'added', time() );
		}
		$this->_data->setElement( 'id', Core_Sql::setInsertUpdate( $this->_table, $this->_data->setMask( $this->_fields )->getValid() ) );
	}
	
	public static function checkSubscribers( $data=array() ){
		if( !isset( $data['id'] ) || empty( $data['id'] )
			|| !isset( $data['ip'] ) || empty( $data['ip'] )
			|| !isset( $data['uid'] ) || empty( $data['uid'] )
			|| !isset( $data['referer'] ) || empty( $data['referer'] )
		){
			if( isset( $data['id'] ) && !empty( $data['id'] ) && is_array( $data['id'] ) ){
				$subscribers=new Project_Traffic_Subscribers();
				$subscribers->withCampaignId( $data['id'] )->getList( $_allSubscribers );
				$returnArray=array();
				foreach( $_allSubscribers as $_subscribe ){
					if( !isset( $returnArray[$_subscribe['campaign_id']] ) ){
						$returnArray[$_subscribe['campaign_id']]=array( 's'=>0, 'c'=>0 );
					}
					$returnArray[$_subscribe['campaign_id']]['s']++;
				}
				$campaign=new Project_Traffic_Campaign();
				$campaign->withCampaignId( $data['id'] )->getList( $_allCampaigns );
				foreach( $_allConversions as $_click ){
					if( $_click['added']<=time()-60*60*24*30 ){
						continue;
					}
					if( !isset( $returnArray[$_click['campaign_id']] ) ){
						$returnArray[$_click['campaign_id']]=array( 's'=>0, 'c'=>0 );
					}
					$returnArray[$_click['campaign_id']]['c']++;
				}
				echo json_encode( $returnArray );
				exit;
			}
			die('error');
		}
		$subscribers=new Project_Traffic_Subscribers();
		$subscribers->withCampaignId( $data['id'] )->withIP( $data['ip'] )->onlyOne()->onlyCount()->getList( $_flgRegistered );
		if( $_flgRegistered==1 ){
			die('registered');
		}
		$subscribers->clearOld()->onlyOne()->onlyCount()->withCampaignId( $data['id'] )->getList( $_subscribersCount );
		if( $_subscribersCount >= $_arrSqueeze['restrictions'] ){
			if( $_arrSqueeze['restrictions'] != 0 ){
				die('true');
			}else{
				die('free');
			}
		}else{
			$subscribers->setEntered(array(
				'campaign_id'=>$data['id'],
				'ip'=>$data['ip'],
				'referer'=>$data['referer'],
			))->set();
			die('success');
		}
	}
	
}
?>