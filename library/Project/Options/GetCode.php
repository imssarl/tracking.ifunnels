<?php
class Project_Options_GetCode{

	/**
	 * Get PHP Code for snippets  ( Option name: Rotating ad / snippets )
	 *
	 * @param array $arrSpot 
	 * @return string 
	 */
	public static function getSnippetPhpCode( $arrSpot ) {
		$_strPath  = Zend_Registry::get( 'config' )->engine->project_domain;
		if (empty($arrSpot['snippets'])) {
			return '';
		}
		$strCode='';
		foreach ($arrSpot['snippets'] as $_intId) {
			$_strId = Project_Options_Encode::encode($_intId);
			$strCode .= ' <?php if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"http://'.$_strPath.'/cronjobs/getcontent.php?type_view=snippetsshow&id='.$_strId.'");curl_setopt($ch, CURLOPT_HEADER, 0);	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;} ?> ';
		}
		return $strCode;
	}


	/**
	 * Get PHP Code for articles ( Option name: Saved Article Selection )
	 *
	 * @param array $arrSpot
	 * @return string
	 */
	public static function getSavedSelectionPhpCode( $arrSpot ){
		if (empty($arrSpot['articles'])){
			return '';
		}
		$_strCode='';
		foreach ($arrSpot['articles'] as $_intId) {
			$_strCode.=Core_Sql::getCell("SELECT code FROM hct_am_savedcode WHERE id='{$_intId}'");
		}
		return str_replace(array("\r\n","\t","\n",'  '),' ',$_strCode);
	}

	/**
	 * Get PHP Code for Dams (Option name: Do you want to add a floating, top / bottom, or corner ad)
	 *
	 * @param array $arrParams - $_POST
	 * @return string - PHP code
	 */
	public static function getDamsPhpCode( $arrParams ){
		$_strPath  = Zend_Registry::get( 'config' )->engine->project_domain;
		if (empty($arrParams['dams']['ids'])) {
			return false;
		}
		$strCode = '';
		foreach ($arrParams['dams']['ids'] as &$intId) {
			$_strId = Project_Options_Encode::encode($intId);
			$strCode .= ' <?php if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"http://'.$_strPath.'/dams/showcode.php?id='.$_strId.'&process='.(($arrParams['flg_content'] == 1)?'split':'single').'&ref_url=".$_SERVER["HTTP_REFERER"]."&php_self=".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]);curl_setopt($ch, CURLOPT_HEADER, 0);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;} ?> ';
		}
		return  $strCode;
	}


	/**
	 * Get PHP Code for video (Option name: Embed Video )
	 *
 	 * @param array $arrSpot
	 * @return string
	 */
	public static function getVideoPhpCode( $arrSpot ) {
		$_strPath = Zend_Registry::get( 'config' )->engine->project_domain;
		if ( empty( $arrSpot['video'] ) ){
			return "";
		}
		$strCode='';
		foreach ($arrSpot['video'] as &$intId) {
			$_strId = Project_Options_Encode::encode($intId);
			$strCode .= ' <?php if(function_exists("curl_init")){ $ch = @curl_init();curl_setopt($ch, CURLOPT_URL,"http://'.$_strPath.'/cronjobs/getcontent.php?type_view=showvideo&id='.$_strId.'&title='.( (!empty($arrSpot['flg_title']))? $arrSpot['flg_title'] : 0 ).'");curl_setopt($ch, CURLOPT_HEADER, 0);	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$resp=@curl_exec($ch);$err=curl_errno($ch);if($err === false || $resp ==""){$newsstr = "";}else{if (function_exists("curl_getinfo")){$info = curl_getinfo($ch);if ($info["http_code"]!=200)$resp="";}$newsstr = $resp;}@curl_close ($ch);echo $newsstr;} ?> ';
		}
		return $strCode;	
	}


	/**
	 * Get Customer Code (Option name: Customer code)
	 *
	 * @param array $arrSpot
	 * @return string
	 */
	public static function getCustomerCode( $arrSpot ){
		if (empty($arrSpot['customer'])) {
			return "";
		}
		return  htmlentities( $arrSpot['customer'] );		
	}


	public static function getSpotsCode( $params ) {
		$spotCode = array();
		if ( empty($params['spots']) ) {
			return false;
		}
		foreach ($params['spots'] as $spot ) {
			if ( $spot['flg_default']==0 ) {
				$spotCode[$spot_name] = false;
				continue;
			}
			$_strArticle = self::getSavedSelectionPhpCode( $spot );
			$_strSnippet = self::getSnippetPhpCode( $spot );
			$_strVideo = self::getVideoPhpCode( $spot );
			$_strCustomer = self::getCustomerCode( $spot );
			$a = "php{$spot['type_order'][ Project_Options::ARTICLE ]}";
			$b = "php{$spot['type_order'][ Project_Options::VIDEO ]}";
			$c = "php{$spot['type_order'][ Project_Options::SNIPPET ]}";
			$d = "php{$spot['type_order'][ Project_Options::CUSTOMER ]}";
			$$a = "";
			$$b = "";
			$$c = "";
			$$d = "";
			if ( !empty($_strArticle) ) {
				$$a = $_strArticle ;
			}
			if ( !empty($_strVideo) ) {
				$$b =  $_strVideo ;
			}
			if ( !empty($_strSnippet) ) {
				$$c = $_strSnippet ;
			}
			if ( !empty($_strCustomer) ) {
				$$d =  $_strCustomer ;
			}
			$spotCode[$spot['spot_name']] = html_entity_decode( stripslashes( $php1 . $php2 . $php3 . $php4 ) ) ;
		}
		return !empty($spotCode) ? $spotCode : false ;
	}
}
?>