<?php

class Project_Affiliate extends Core_Media_Ftp   {

	private $tempDir = '';
	public $ftp_params = array();
	private $_userTmpDir = '';

	public function init( $action = "", $params ){
		if ( !$this
			->setHost( urldecode( $params['arrFtp']['address'] ) )
			->setUser( urldecode( $params['arrFtp']['username'] ) )
			->setPassw( urldecode( $params['arrFtp']['password'] ) )
			->makeConnect() ) {
			return false;
		}
		
		$this->tempDir = 'Project_Affiliate@'. $action;
		if ( !Zend_Registry::get( 'objUser' )->prepareTmpDir( $this->tempDir ) ) {
			trigger_error( 'no _tempDir set' );
		}		
		
		return true;
	}

	public function getFile( $params ) {
		$filename = Core_Files::getBaseName($params['arrFtp']['directory']);
		if( $this->fileDownload( $params['arrFtp']['directory'], $this->tempDir . $filename)) {
			$this->closeConnection(); 
			Core_Files::getContent( $content, $this->tempDir . $filename );
			return $content;
		} else { 
			return false;
		}
	}

	public function writeFile( $arrData ){
		$_filename = Core_Files::getBaseName($arrData['arrFtp']['directory']);
		$_ftpDir=Core_Files::getDirName($arrData['arrFtp']['directory']);
		$strFile = $arrData['file_content'];

		if ( !Core_Files::setContent( $strFile, $this->tempDir.$_filename ) ) {
			return false;
		}
		$this->permissionFile ="0644";
		ftp_delete($this->ftp,$_ftpDir.'/'.$_filename);
		if ( !$this->fileUpload( $_ftpDir.'/'.$_filename, $this->tempDir.$_filename ) ){
			return false;
		}
		return true;
	}

	public function creatPage($arrData){
		$strFile= '';
		$this->permissionFile ="0644";
		$_strDir = $arrData['arrFtp']['directory'];
		$_strFilename = ($arrData['cloack'] == 'redirect')? $arrData['file_name'] : $arrData['file_name_ad'];
		if ( $arrData['convert_page'] == 1 ) {
			$_strFilename = Core_Files::getBaseName($_strFilename);
			$_strDir = Core_Files::getDirName($_strDir)  . '/';
		}
		
		if ($arrData['cpp'] == 1) {
			$strFile .= $this->getTrackingCode($arrData['ad_id'], $arrData['ad_env'], $this->getTrackingIdByAdId($arrData['ad_id']));
		}

		if (isset($arrData['cloack']) && $arrData['cloack'] == 'redirect') {
			$strFile .= '<?php header("Location: '. $arrData['redirect_url'] .'"); ?>';
			Core_Files::setContent($strFile,$this->tempDir.$arrData['file_name']);
			if ( !$this->fileUpload($_strDir.$arrData['file_name'], $this->tempDir.$arrData['file_name']) ){
				return false;
			}
		} else {

			$strFile .= '<!doctype html>
			<html>
				<head>
					<base href="'.$arrData['redirect_url2'].'">
					<title>'. htmlentities( $arrData['page_title'] ) .'</title>
					<meta name="keywords" content="'. htmlentities( $arrData['meta_tag'] ) .'"/>
					<style type="text/css">
						html, body, div.iframe, iframe { margin:0; padding:0; height:100%; }
						iframe { display:block; width:100%; border:none; }
						html, body {overflow: hidden;}
					</style>
				</head>
				<body>
					<div  class="iframe">
						<iframe src="'.$arrData['redirect_url2'].'" height="100%" width="100%"></iframe>
			</div>';

			$code = "";
			if ($arrData['headlines_spot1'] && $arrData['dams_add'] == 1) {
				$serverPath = $_SERVER['SERVER_NAME'];
				foreach ($arrData['chkselect'] as $id) {
					$code .= '  <?php
 					if(function_exists("curl_init")){
 					$ch = @curl_init();
 					curl_setopt($ch, CURLOPT_URL,"http://'.$serverPath.'/dams/showcode.php?id='.$id.'&process='.$_POST["headlines_spot1"].'&ref_url=".$_SERVER["HTTP_REFERER"]."&php_self=".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]);
 					curl_setopt($ch, CURLOPT_HEADER, 0);
 					curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
 					$resp=@curl_exec($ch);
 					$err=curl_errno($ch);
 					if($err === false || $resp ==""){
 						$newsstr = "";
 					} else {
 						if (function_exists("curl_getinfo")){
 							$info = curl_getinfo($ch);
 							if ($info["http_code"]!=200)$resp="";
 					}
 					$newsstr = $resp;
 					}
 					@curl_close ($ch);
 					echo $newsstr;
 					}
					?> ';
				}
			}
			$strFile  .= $code . '</body></html>';
			Core_Files::setContent($strFile,$this->tempDir.$_strFilename);
			if ( !$this->fileUpload($_strDir.$_strFilename, $this->tempDir.$_strFilename) ){
				return false;
			}
			
		}
		if ( !$this->setAffiliatePage( $arrData ) ) {
			return false; 
		}
		
		return true;
	}
	
	private function getTrackingIdByAdId( $aid ) {
		$sql = "SELECT id FROM  hct_ccp_track WHERE ad_id = $aid LIMIT 1";
		$kid = Core_Sql::getCell($sql);
		return $kid;
	}
		
	private function getTrackingCode($cid, $env='K', $tid=0)
	{
		if ($env == 'C') { 
			$clink = "&tid=$tid"; 
		} else {
			$clink = "";
		}
		
		$code ='<?php';
		if ($env == 'K' || $env == 'C') {
		$code .= '
		$href = urlencode(@$_SERVER["HTTP_REFERER"]);
		if($href=="")$href=urlencode($_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]);
		$rfip = $_SERVER["REMOTE_ADDR"];
		$url = "http://'.$_SERVER['HTTP_HOST'].'/ccp/trackid.php?href=$href&ip=$rfip&id='.Project_Options_Encode::encode($cid).$clink.'";';
		} else if ($env == 'T') {
		$code .= '
		////////////////////////////////////////////////////////////////////////////
		$amount = "AMOUNT"; // AMOUNT can be replaced with actual amount of product
		$items = "ITEMS";   // ITEMS can be replaced with no of items
		////////////////////////////////////////////////////////////////////////////
		$track_id = $_COOKIE["track_id"];
		$url = "http://'.$_SERVER['HTTP_HOST'].'/ccp/trackid.php?mytid=$track_id&items=$items&amount=$amount";';
		}
		
		
		$code .= '
		if(function_exists("curl_init"))
		{
			$ch = @curl_init();
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_HEADER, 0);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$resp = @curl_exec($ch); 
			
			$curl_resp = curl_errno($ch);

			if ($curl_resp == 0)
			{
				$val = $resp;
			}
			else if($curl_resp != 0 && $resp == "") 
			{
				$val = "";
			} 

			@curl_close($ch);
			unset($ch);		
		}
		else if(function_exists("fopen"))
		{
				$fp = @fopen($url,"r");
				if($fp)
				{		
					while(!@feof($fp))
					{
						$val .= @fgets($fp);
					}
					@fclose($fp);
				}
				else 
				{
					$val = "";
				}
		} ';
		
		if ($env != 'T') {
			$code .= '
			$tid = trim($val);
			setcookie("track_id", $tid); ?>';
		} else {
			$code .= '
			setcookie ("track_id", "", time() - 3600);
			unset($_COOKIE["track_id"]); ?>';
		}
		return $code;	
	}
	
	
	public function getCppTrakingPage( $id ) {
		$sql = "SELECT p.* , p.id as p_id, s.*, d.*, d.id as aid, n.* FROM hct_ccp_trackingpages p "
		." LEFT JOIN  hct_ccp_site s ON s.id = p.site_id "
		." LEFT JOIN  hct_ccp_ad d ON d.id = p.ad_id "
		." LEFT JOIN  hct_ccp_campaign n ON n.id = d.campaign_id "
		." WHERE p.id = $id ";
		$page = Core_Sql::getRecord($sql);
		$arrRes = $this->cppFormatArray($page);
		 
		return $arrRes;
	}

	private function getCppTrakingPages( $userId ){
		$sql = "SELECT p.*,(SELECT COUNT(*) FROM hct_affiliate_compaign as c WHERE p.id = c.page_id ) as is_compaign , p.id as p_id, s.*, d.*, d.id as aid, n.* FROM hct_ccp_trackingpages p "
			 ." LEFT JOIN  hct_ccp_site s ON s.id = p.site_id "
			 ." LEFT JOIN  hct_ccp_ad d ON d.id = p.ad_id "
			 ." LEFT JOIN  hct_ccp_campaign n ON n.id = d.campaign_id "
			 ." WHERE d.user_id = $userId ";
		$pages = Core_Sql::getAssoc($sql);
		$arrRes = array();
		foreach ($pages as $page) {
			$arrRes[] = $this->cppFormatArray($page);
		}
		return $arrRes;
	}
	
	private function cppFormatArray( $page = array() ) {
		$pageName = explode('/',$page['remote_path']);
		$len = strlen($pageName[count($pageName)-1]);
		$pagePath = substr($page['remote_path'],0,-$len);
		
		$page =	array(
		'page_id' 			=> $page['p_id'],
		'page_type' 		=> ($page['cloaked']) ? 'cloaked' : 'redirect',
		'page_name' 		=> $pageName[count($pageName)-1],
		'page_address'  	=> $page['url'],
		'user_id' 			=> $userId,
		'page_affiliate_url'=> $page['merchant_link'],
		'page_date_created' => $page['date'],
		'page_title' 		=> $page['title'], 
		'page_keywords' 	=> $page['keywords'], 
		'ftp_directory' 	=> $pagePath, 
		'ftp_address' 		=> $page['ftp_address'],
		'ftp_username' 		=> $page['ftp_username'],
		'ftp_password' 		=> $page['ftp_password'],
		'is_compaign' 		=> $page['is_compaign'], 
		'is_cpp'			=> 1,
		'ad_env'			=> $page['ad_env'],
		'ad_id'				=> $page['ad_id'],
		'aid'				=> $page['aid']
		);
		
		$page['compaigns'] = Core_Sql::getAssoc("SELECT * FROM hct_affiliate_compaign WHERE page_id = {$page['page_id']}  ");
		
		if ( is_array( $page['compaigns'] ) ) {
			foreach ($page['compaigns'] as $item) {
				$page['ids'][] = $item['compaign_id'];
				$page['compaign_type'] = $item['compaign_type'];
			}
		}
		$page['arrFtp']['id'] = $page['id']; 
		$page['arrFtp']['address'] = $page['ftp_address']; 
		$page['arrFtp']['username'] = $page['ftp_username']; 
		$page['arrFtp']['password'] = $page['ftp_password']; 
		$page['arrFtp']['directory'] = $page['ftp_directory']; 			
		
		return $page;
	}
	
	public function getAffiliatePages() {
		Zend_Registry::get( 'objUser' )->getId( $user_id );
		$cppPages = $this->getCppTrakingPages($user_id);
		$pages = Core_Sql::getAssoc("SELECT *,(SELECT COUNT(*) FROM hct_affiliate_compaign as c WHERE c.page_id = page.page_id ) as is_compaign  FROM hct_affiliate_pages as page LEFT JOIN hct_ftp_details_tb as ftp ON ftp.id = page.ftp_id WHERE page.user_id = $user_id ORDER BY page.page_date_created DESC");
		$pages = array_merge($pages, $cppPages);

		return !empty($pages) ? $pages : false;
	}
	
	public function setAffiliatePage( $arrPage ) {
		$update = '';
		$path = $arrPage['arrFtp']['directory'];
		$filename = ( $arrPage['file_name'] ) ? htmlspecialchars( $arrPage['file_name']) : htmlspecialchars( $arrPage['file_name_ad']);
		
		if ( $arrPage['convert_page'] == 1 ) {
			
			$path = explode("/",$arrPage['arrFtp']['directory']);
			unset($path[count($path)-1]);
			$path = implode('/', $path);
			$path = (!empty($path)) ? $path .'/' : '/';
			
			$filename = $filename;
			$filename = explode('/',$filename);
			$filename = $filename[count($filename)-1];			
		}
		
		Zend_Registry::get( 'objUser' )->getId( $user_id );
		$host = 'http://'.str_replace( 'http://', '', $arrPage['arrFtp']['address'] ).str_replace( '/public_html', '', $path );;
		$data = array(
			'page_address'			=> $host,
			'page_name' 			=> $filename,
			'page_affiliate_url' 	=> ( $arrPage['redirect_url'] ) ? htmlspecialchars( $arrPage['redirect_url']) : htmlspecialchars( $arrPage['redirect_url2']), 
			'page_title'			=> ( $arrPage['page_title']) ? htmlspecialchars($arrPage['page_title']) : '',
			'page_keywords'			=> ( $arrPage['meta_tag']) ? htmlspecialchars($arrPage['meta_tag']) : '',
			'ftp_id'				=> ( $arrPage['arrFtp']['id'] ) ? $arrPage['arrFtp']['id'] : 0,
			'ftp_directory'			=> $path,
			'user_id'				=> $user_id,
			'page_type'				=> (isset($arrPage['cloack']) && $arrPage['cloack'] == 'redirect' ) ? 'redirect' : 'cloaked' 
		);
		
		if ( $arrPage['page_id'] ) {
			$data['page_id'] = $arrPage['page_id'];
			$update = 'page_id';
			$cppUpdate = 'id';
		}
		
		if ( $arrPage['cpp'] == 1) {
			$cppData = array(
				'id'	=> $arrPage['page_id'],
				'cloaked' => $arrPage['headlines_spot1'],
				'title'	=> $arrPage['page_title'],
				'keywords'	=> $arrPage['meta_tag']
			);
		 
			Core_Sql::setInsertUpdate('hct_ccp_ad', array('id' => $arrPage['aid'], 'merchant_link' => $data['page_affiliate_url']),  'id');
			
			
			if ( !$id = Core_Sql::setInsertUpdate('hct_ccp_trackingpages', $cppData, $cppUpdate) ) {
				return false;
			}
			
			 
		} else {
		
			if ( !$id = Core_Sql::setInsertUpdate('hct_affiliate_pages', $data, $update) ) {
				return false;
			}
		}
		Core_Sql::setExec("DELETE FROM hct_affiliate_compaign WHERE page_id = {$id}");
		if ( $arrPage['headlines_spot1'] && $arrPage['dams_add'] ) {
			$compaigns = array();
			foreach ($arrPage['chkselect'] as $compaign_id ) {
				$compaigns[] = array(
					'page_id' 		=> $id,
					'compaign_id' 	=> Project_Options_Encode::decode($compaign_id),
					'compaign_type'	=> ( $arrPage['headlines_spot1'] ) ? $arrPage['headlines_spot1'] : '',
					'mod_type'		=> ($arrPage['cpp'] == 1) ? 'cpp':'affilite'
				);
			}
			if ( !Core_Sql::setMassInsert('hct_affiliate_compaign', $compaigns) ) {
				return false;
			}
		}
		
		return true;
	}
	
	public function getAffiliatePageById( $id ) {
		Zend_Registry::get( 'objUser' )->getId( $user_id );
		$page = Core_Sql::getRecord("SELECT * FROM hct_affiliate_pages as p LEFT JOIN hct_ftp_details_tb as ftp ON ftp.id = p.ftp_id  WHERE p.page_id = {$id} AND p.user_id = {$user_id} ");
		if ( !$page ) {
			return false;
		}
		$page['compaigns'] = Core_Sql::getAssoc("SELECT * FROM hct_affiliate_compaign WHERE page_id = {$page['page_id']}  ");
		
		if ( is_array( $page['compaigns'] ) ) {
			foreach ($page['compaigns'] as $item) {
				$page['ids'][] = $item['compaign_id'];
				$page['compaign_type'] = $item['compaign_type'];
			}
		}
		$page['arrFtp']['id'] = $page['id']; 
		$page['arrFtp']['address'] = $page['ftp_address']; 
		$page['arrFtp']['username'] = $page['ftp_username']; 
		$page['arrFtp']['password'] = $page['ftp_password']; 
		$page['arrFtp']['directory'] = $page['ftp_directory']; 
		return $page;
	}
	
	public function deleteAffiliatePage( $id, $type ) {
		Zend_Registry::get( 'objUser' )->getId( $user_id );
		if ($type == 'cpp') {
			$page = $this->getCppTrakingPage($id);
		} else {
			$page = Core_Sql::getRecord("SELECT * FROM hct_affiliate_pages as p LEFT JOIN hct_ftp_details_tb as ftp ON ftp.id = p.ftp_id  WHERE p.page_id = {$id} AND p.user_id = {$user_id} ");
		}
		if ($type == 'cpp') {
			Core_Sql::setExec("DELETE FROM hct_ccp_trackingpages WHERE id = {$id} ");
		} else {
			Core_Sql::setExec("DELETE FROM hct_affiliate_pages WHERE page_id = {$id} ");
		}
		Core_Sql::setExec("DELETE FROM hct_affiliate_compaign WHERE page_id = {$id} ");
		
		 $params['arrFtp'] = array(
		 	'address'	=> $page['ftp_address'],
		 	'username'	=> $page['ftp_username'],
		 	'password'	=> $page['ftp_password'],
		 	'directory'	=> $page['ftp_directory']
		 );
		$this->init('get', $params, $arrRes ); 
		
		if ( !$this->ftp  ) { 
			return false;
		}
		@ftp_delete($this->ftp, $page['ftp_directory'] . $page['page_name'] );
		return true;
	}
	
	
}

?>