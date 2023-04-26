<?php
class site1_traffic extends Core_Module {

	public function set_cfg(){
		$this->inst_script=array(
			'module'=>array( 'title'=>'Traffic module', ),
			'actions'=>array(
				/*array( 'action'=>'create', 'title'=>'Traffic Locator create', 'flg_tree'=>1 ), */
				
				array( 'action'=>'campaign', 'title'=>'Create Campaign', 'flg_tree'=>1 ),
				array( 'action'=>'manager', 'title'=>'Manage Campaigns', 'flg_tree'=>1 ),
				array( 'action'=>'promote', 'title'=>'Promote Campaigns', 'flg_tree'=>1 ),
				array( 'action'=>'manage_promote', 'title'=>'Manage Promotions', 'flg_tree'=>1 ),
				array( 'action'=>'browse', 'title'=>'Browse Campaigns', 'flg_tree'=>1 ),
				array( 'action'=>'credits', 'title'=>'Credits', 'flg_tree'=>1 ),
				array( 'action'=>'show_campaign', 'title'=>'Show Campaign', 'flg_tree'=>1, 'flg_tpl'=>1 ),
				
				array( 'action'=>'client_trafic_exchange', 'title'=>'Client Traffic Exchange Action', 'flg_tree'=>1, 'flg_tpl'=>1 ),
			),
		);
	}

	public function campaign(){
		$this->objStore->getAndClear( $this->out );
		$this->showCredits();
		$_campaigns=new Project_Traffic();
		if( isset( $_GET['id'] ) ){
			$_campaigns->withIds( $_GET['id'] )->onlyOne()->getList( $this->out['arrCampaign'] );
		}
		if( isset( $_POST['submit'] ) ){
			if( !$_campaigns->setEntered( $_POST['arrData'] )->set() ){
				$_campaigns
					->getEntered( $this->out['arrCampaign'] )
					->getErrors( $this->out['arrErrors'] );
				$this->objStore->set( array( 'error'=>'create' ) );
				$this->location();
			}else{
				$this->objStore->toAction( 'manager' )->set( array( 'msg'=>'success' ) );
				$this->location(array('action'=>'manager'));
			}
		}
		$this->out['arrCampaign']['user_id']=Core_Users::$info['id'];
		$category=new Core_Category( 'Trafic Exchanger' );
		$category->getTree( $arrCategories );
		$_arrCategories=array();
		foreach( $arrCategories as $_e ){
			$_arrCategories[$_e['title']]=$_e;
		}
		$this->out['arrCategoryTree']=$_arrCategories;
		$this->out['trafficCredits']=$_campaigns->withUserId(Core_Users::$info['id'])->getUserCredits();
		$_model=new Project_Placement();
		$_model->onlyOwner()->getList( $this->out['arrDomains'] );
	}

	public function manager(){
		$this->objStore->getAndClear( $this->out );
		$this->showCredits();
		$_campaigns=new Project_Traffic();
		if( isset( $_GET['del'] ) && !empty( $_GET['del'] ) ){
			if( !$_campaigns->withIds( $_GET['del'] )->del() ){
				$this->objStore->set(array( 'error'=>'del' ));
			}else{
				$this->objStore->set(array( 'msg'=>'success' ));
			}
			unset( $_GET );
			$this->location();
		}
		$_campaigns
			->withOrder( @$_GET['order'] )
			->withUserId( Core_Users::$info['id'] )
			->withPaging( array(
				'page'=>@$_GET['page'], 
				'reconpage'=>Core_Users::$info['arrSettings']['rows_per_page'],
				'numofdigits'=>Core_Users::$info['arrSettings']['page_links'],
			) )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] );
		$category=new Core_Category( 'Trafic Exchanger' );
		$category->getTree( $arrCategories );
		$_arrCategories=array();
		foreach( $arrCategories as $_e ){
			$_arrCategories[$_e['id']]=$_e;
		}
		$this->out['arrCategoryTree']=$_arrCategories;
	}

	public function promote(){
		$this->showCredits();
		$_campaigns=new Project_Traffic();
		$_campaigns
			->withOrder( @$_GET['order'] )
			->withCategoryId( @$_GET['with_category_id'] )
			->withoutUserId( Core_Users::$info['id'] )
			->onlyActive()
			->withPaging( array(
				'page'=>@$_GET['page'], 
				'reconpage'=>Core_Users::$info['arrSettings']['rows_per_page'],
				'numofdigits'=>Core_Users::$info['arrSettings']['page_links'],
			) )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
		$category=new Core_Category( 'Trafic Exchanger' );
		$category->getTree( $arrCategories );
		foreach( $this->out['arrList'] as &$_data ){
			foreach( $arrCategories as $_e ){
				if( $_e['id'] == $_data['category_id'] ){
					$_data['category_id']=$_e['title'];
				}
			}
		}
		$_arrCategories=array();
		foreach( $arrCategories as $_e ){
			$_arrCategories[$_e['title']]=$_e;
		}
		$this->out['arrCategoryTree']=$_arrCategories;
	}

	public function manage_promote(){
		$this->objStore->getAndClear( $this->out );
		$_campaigns=new Project_Traffic();
		if( isset( $_GET['stop'] ) && !empty( $_GET['stop'] ) ){
			if( !$_campaigns->withUserId( Core_Users::$info['id'] )->stopCampaign( $_GET['stop'] ) ){
				$this->objStore->set(array( 'error'=>'stop' ));
			}else{
				$this->objStore->set(array( 'msg'=>'success' ));
			}
			unset( $_GET );
			$this->location();
		}
		$this->showCredits();
		$_campaigns
			->withOrder( @$_GET['order'] )
			->withCategoryId( @$_GET['with_category_id'] )
			->withoutUserId( Core_Users::$info['id'] )
			->onAction()
			->onlyPromoted()
			->withPaging( array(
				'page'=>@$_GET['page'], 
				'reconpage'=>Core_Users::$info['arrSettings']['rows_per_page'],
				'numofdigits'=>Core_Users::$info['arrSettings']['page_links'],
			) )
			->getList( $this->out['arrList'] )
			->getPaging( $this->out['arrPg'] )
			->getFilter( $this->out['arrFilter'] );
		$category=new Core_Category( 'Trafic Exchanger' );
		$category->getTree( $arrCategories );
		foreach( $this->out['arrList'] as &$_data ){
			foreach( $arrCategories as $_e ){
				if( $_e['id'] == $_data['category_id'] ){
					$_data['category_id']=$_e['title'];
				}
			}
		}
		$_arrCategories=array();
		foreach( $arrCategories as $_e ){
			$_arrCategories[$_e['title']]=$_e;
		}
		$this->out['arrCategoryTree']=$_arrCategories;
	}

	public function browse(){
		$this->showCredits();
		if( isset( $_POST['browse'] ) ){
			$_campaigns=new Project_Traffic();
			$_campaigns
				->withoutUserId( Core_Users::$info['id'] )
				->onlyActive()
				->getList( $arrCampaigns );
			$_item=array_rand($arrCampaigns, 1);
			if( !empty( $arrCampaigns[$_item] ) ){
				$this->objStore->toAction( 'show_campaign' )->set( array( 'url'=>$arrCampaigns[$_item]['url'] ) );
			}else{
				$this->objStore->toAction( 'show_campaign' )->set( array( 'url'=>$_campaigns->getDefaultPage() ) );
			}
			$this->location( array( 'module'=> 'site1_traffic' ,'action'=>'show_campaign' ) );
		}
	}

	public function show_campaign(){
		$this->objStore->getAndClear( $this->out );
	}

	public function showCredits(){
		$_campaigns=new Project_Traffic();
		$this->out['trafficCredits']=$_campaigns->withUserId(Core_Users::$info['id'])->getUserCredits();
	}

	public function client_trafic_exchange(){
		$_object=new Project_Traffic();
		$_object->checkIP();
		Core_Files::getContent($_str,'./services/traffic/data-'.date('Y-m-d').'.txt');
		$_str.="\n".date('d.m.Y H:i:s').' - POST '.serialize($_POST).' - GET '.serialize($_GET).' FROM '.$_SERVER['REQUEST_URI'].' IP '.$_object->ip;
		Core_Files::setContent($_str,'./services/traffic/data-'.date('Y-m-d').'.txt');
		
		Core_Files::getContent($endUrl,'./services/traffic/remote_url.txt' );
		if( strpos( $endUrl, 'http' ) !== 0 ){
			$endUrl='http://'.$endUrl;
		}
		if( ( isset( $_GET['v'] )
			&& !empty( $_GET['v'] )
			&& isset( $_GET['u'] )
			&& !empty( $_GET['u'] ) )
			|| isset( $_GET['c'] )
		){
			if( !empty( $_GET['c'] ) ){
				$_GET=$_object->regenerateUrl( $_GET['c'] )+(array)$_GET;
			}
			if( !( isset( $_GET['v'] )
				&& !empty( $_GET['v'] )
				&& isset( $_GET['u'] )
				&& !empty( $_GET['u'] ) )
			){
				$_object->showAds( $endUrl );
				exit;
			}
			$_subscribers=new Project_Traffic_Subscribers();
			$_object->withIds( array( $_GET['v'] ) )->onlyOne()->withoutUserId( $_GET['u'] )->onAction()->notLinkUpdate()->getList( $arrCampaign );
			if( strpos( $arrCampaign['url'], 'http' ) !== 0 ){
				$arrCampaign['url']='http://'.$arrCampaign['url'];
			}
			if( $arrCampaign['flg_locking'] > 0 ){
				$_object->showAds( $endUrl );
				exit;
			}
			$_subscribers->withIP( $_object->ip )->withCampaignId( $_GET['v'] )->onlyOne()->onlyCount()->getList( $_viewCount );
			if( $_viewCount > 0 ){
				$_object->showAds( $arrCampaign['url'] );
				exit;
			}
			$_subscribers->withCampaignId( $_GET['v'] )->onlyOne()->onlyCount()->getList( $_countAllView );
			$_credits=new Project_Traffic_Credits();
			$_credits->onlyOne()->withIds( $arrCampaign['user_id'] )->getList( $creditsFrom );
			if( $_countAllView < $arrCampaign['credits'] && $creditsFrom>0 ){
				$_object->showAds( $arrCampaign['url'], $arrCampaign['user_id'] );
			}else{
				$_object->showAds( $endUrl );
			}
			//exit;
		}else{
			$_object->showAds( $endUrl );
		}
		exit;
	}

	public function credits(){
		$this-> showCredits();
		$_campaigns=new Project_Traffic();
		if( isset( $_POST['convert_credits'] ) && $_POST['convert_credits']!=0 ){
			//проверим есть ли у пользователя столько кредитов
			if( $_campaigns->withUserId(Core_Users::$info['id'])->addUserCredits( $_POST['convert_credits'] ) ){
				unset( $_POST );
				$this->location();
			}else{
				$_campaigns
					->getErrors($this->out['arrErrors']);
			}
		}
	}

	/* public function create(){
		$trafic=new Project_Content_Adapter_Googlesearch();
		if( $_GET['file']=='true' ){
			ob_end_clean();
			$trafic->setSettings( $_GET['arrData'] )->setFile();
		}
		$this->out['arrDatacenters']=$trafic->datacenters;
		$this->out['arrTypes']=$trafic->type;
		if( isset( $_GET['arrData'] ) ){
			$_search=new Project_Content_Adapter_Googlesearch();
			$_search
				->withPaging( array(
					'url'=>$_GET,
					'page'=>@$_GET['page'],
					'numofdigits'=>Core_Users::$info['arrSettings']['pagging_links'],
				) )
				->setSettings( $_GET['arrData'] )
				->getList( $this->out['arrList'] )
				->getPaging( $this->out['arrPg'] );
		}
	} */
}
?>