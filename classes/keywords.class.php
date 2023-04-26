<?php

class Keyword {
	function test() {
		global $prj;

		echo "TEST" . $prj;
	} 

	/*function connectToServer($ftp_server){
$conn_id = @ftp_connect($ftp_server);
	if(!$conn_id){	
	return connectToServer($ftp_server);
	}else{
	return $conn_id;
	}
}*/

	function insertKeywordProject( $projectid, $isgenall = "N", $genkeywords = 0, $israndom = "N", $period = 0 ) {
		global $ms_db;
		$sql = "INSERT INTO `" . TABLE_PREFIX . "keywords_projects_tb` (`project_id` , `mode` , `generate_all_keywords` , `generate_keywords` , `generate_random` , `generate_period` , `last_generated_date`,`user_id` )
	VALUES ("
		 . "'" . $ms_db->GetSQLValueString( $projectid, "int" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $_POST["mode"], "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $isgenall, "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $genkeywords, "int" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $israndom, "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $period, "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( "", "date" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')";
		$id = $ms_db->insert( $sql );
		return $id;
	} 

	function insertKeywordSource( $kprojectid ) {
		global $ms_db;
		$sql = "INSERT INTO `" . TABLE_PREFIX . "keyword_source_tb` (  `keyword_project_id` , `source_type` , `source_file_name` , `source_date` )
		VALUES ("
		 . "'" . $ms_db->GetSQLValueString( $kprojectid, "int" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $_POST["source_type"], "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $_FILES["importtextcsv"]["name"], "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( date( "Y-m-d H:i:s" ), "date" ) . "')";
		$id = $ms_db->insert( $sql );
		return $id;
	} 

	function insertKeywordDetails( $sourceid, $keyname ) {
		global $ms_db;
		$sql = "INSERT INTO `" . TABLE_PREFIX . "keyword_details_tb` ( `keyword_source_id` , `keyword_name` , `generated` , `generated_date` , `generated_file_name` )
		VALUES ("
		 . "'" . $ms_db->GetSQLValueString( $sourceid, "int" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( $keyname, "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( 'N', "text" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( '', "date" ) . "',"
		 . "'" . $ms_db->GetSQLValueString( '', "text" ) . "')";
		$id = $ms_db->insert( $sql );
		return $id;
	} 

	function insertKeywordDetailsFromSource( $sourceid, $cond ) {
		global $ms_db;
		if ( isset( $_POST["site_id1"] ) && $_POST["site_id1"] != 0 ) {
			$site_id = $_POST["site_id1"];
		} else if ( isset( $_POST["site_id2"] ) && $_POST["site_id2"] != 0 ) {
			$site_id = $_POST["site_id2"];
		} else {
			$site_id = $_POST["site_id"];
		} 
		if ( $_POST["source_type"] == "T" ) {
			$keywords = @file( $_FILES["importtextcsv"]["tmp_name"] );
		} else if ( $_POST["source_type"] == "C" ) {
			$keywords = explode( ",", str_replace( array( "\n", "\r" ), "", @file_get_contents( $_FILES["importtextcsv"]["tmp_name"] ) ) );
		} else if ( $_POST["source_type"] == "M" ) {
			$keywords = explode( "\n", $_POST["importmanual"] );
		} elseif ( $_POST["source_type"] == "K" ) {
			// $keywords = explode(",",$_POST['keywordX']);
			// //////////////////////////////////////////////////
			$list = explode( ",", $_POST['keywordX'] );
			foreach( $list as $val ) {
				$sql = "SELECT `keyword` from `" . TABLE_PREFIX . "kwd_savedkwds` where list_id='" . $val . "'";
				$rs = $ms_db->getRS( $sql );
				while( $data = $ms_db->getNextRow( $rs ) ) {
					$keywords [] = str_replace( "", "-", $data['keyword'] );
				} 
			} 
			// ///////////////////////////////////////////////
			// UPDATED SDEI 240209
		} //
		$new = array();

		foreach( $keywords as $keyvalue ) {
			if ( strlen( trim( $keyvalue ) ) > 0 ) {
				$new[] = trim( $keyvalue );
			} 
		} 
		$keywords = $new;
		if ( $cond == 2 ) { // random
			$key = array_rand( $keywords, $_POST['genkeywordsr'] );
			$new = array();
			for ( $i = 0;$i < $_POST['genkeywordsr'];$i++ ) {
				$new[] = $keywords[$key[$i]];
			} 
			$keywords = $new;
		} else if ( $cond == 3 ) { // first X
			$allkeys = @array_chunk( $keywords, $_POST['genkeywordsf'] );
			$keywords = $allkeys[0];
		} 

		if ( is_array( $keywords ) ) {
			foreach( $keywords as $keyword ) {
				$isexist = $this->checkExistKeyword( $site_id, trim( $keyword ) );
				if ( !$isexist ) {
					$this->insertKeywordDetails( $sourceid, trim( $keyword ) );
				} else {
					echo "<br>Keyword already added : " . $keyword;
				} 
			} 
		} 
		echo str_repeat( " ", 4000 );
		flush();
		return count( $keywords );
	} 

	function checkExistKeyword( $siteid, $keyword ) {
		global $ms_db;

		$sql = "Select d.id from " . TABLE_PREFIX . "keyword_details_tb d,  " . TABLE_PREFIX . "keyword_source_tb s,  " . TABLE_PREFIX . "keywords_projects_tb k,  " . TABLE_PREFIX . "projects_tb p where p.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and d.keyword_source_id = s.id and s.keyword_project_id = k.id and k.project_id = p.id and  p.site_id = " . $siteid . " and d.keyword_name = '" . $ms_db->GetSQLValueString( $keyword, "text" ) . "'";

		$exist = $ms_db->getDataSingleRecord( $sql );

		if ( $exist != false ) {
			// return true;
			return false;
		} else {
			return false;
		} 
	} 

	function generateKeywordPages( $projectid, $showmessage = "yes" ) {
		global $ms_db, $prj, $art;
		set_time_limit( 0 );
		ob_start();
		$sql = "Select id, status,site_id from `" . TABLE_PREFIX . "projects_tb` where id = " . $ms_db->GetSQLValueString( $projectid, "int" );

		$project = $ms_db->getDataSingleRow( $sql );
		if ( $project["status"] != "C" ) {
			$sql = "Select * from  " . TABLE_PREFIX . "keywords_projects_tb where project_id = " . $ms_db->GetSQLValueString( $projectid, "int" );
			$keyproject = $ms_db->getDataSingleRow( $sql );
			if ( $keyproject["mode"] == "O" ) {
				if ( $keyproject["generate_all_keywords"] == "Y" ) {
					$sql = "Select d.id,d.keyword_name from " . TABLE_PREFIX . "keyword_details_tb d, " . TABLE_PREFIX . "keyword_source_tb s, " . TABLE_PREFIX . "keywords_projects_tb k where  d.generated = 'N' and  d.keyword_source_id = s.id and s.keyword_project_id = k.id and k.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" );
				} else if ( $keyproject["generate_random"] == "Y" ) {
					$sql = "Select d.id,d.keyword_name from " . TABLE_PREFIX . "keyword_details_tb d, " . TABLE_PREFIX . "keyword_source_tb s, " . TABLE_PREFIX . "keywords_projects_tb k where  d.generated = 'N' and  d.keyword_source_id = s.id and s.keyword_project_id = k.id and k.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" ) . " ORDER BY RAND() LIMIT " . $keyproject["generate_keywords"];
				} else { // Generate X firstkeywords
					$sql = "Select d.id,d.keyword_name from " . TABLE_PREFIX . "keyword_details_tb d, " . TABLE_PREFIX . "keyword_source_tb s, " . TABLE_PREFIX . "keywords_projects_tb k where  d.generated = 'N' and  d.keyword_source_id = s.id and s.keyword_project_id = k.id and k.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" ) . " ORDER BY d.id LIMIT " . $keyproject["generate_keywords"];
				} 
				$status = "C";
				$why = "Project Completed";
			} else if ( $keyproject["mode"] == "R" ) {
				$sql = "Select d.id,d.keyword_name from " . TABLE_PREFIX . "keyword_details_tb d, " . TABLE_PREFIX . "keyword_source_tb s, " . TABLE_PREFIX . "keywords_projects_tb k where  d.generated = 'N' and  d.keyword_source_id = s.id and s.keyword_project_id = k.id and k.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" ) . " and k.last_generated_date < '" . date( "Y-m-d H:i:s", strtotime( '-' . $keyproject["generate_period"] . ' day' ) ) . "'  ORDER BY d.id LIMIT " . $keyproject["generate_keywords"];

				$sql2 = "Select count(d.id) from " . TABLE_PREFIX . "keyword_details_tb d, " . TABLE_PREFIX . "keyword_source_tb s, " . TABLE_PREFIX . "keywords_projects_tb k where  d.generated = 'N' and  d.keyword_source_id = s.id and s.keyword_project_id = k.id and k.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" ) . " ORDER BY d.id  LIMIT " . $keyproject["generate_keywords"];

				$noofkeywords = $ms_db->getDataSingleRecord( $sql2 );
				if ( $noofkeywords < $keyproject["generate_keywords"] ) {
					$status = "P";
					$why = "Project is out of keywords";
				} else {
					$status = "I";
					$why = "Running";
				} 
			} 
			$keydetails = $ms_db->getRS( $sql );
			$keyfile = false;
			if ( $keydetails != false ) {
				// $this->showTopOfPage();
				$finallysomekeywordsadded = false;
				$keywordsadded = "";
				$keywordfiles = "";
				$ftp_conn = $this->ftpContect($project["site_id"]);
				if ( $ftp_conn ){
					while( $keyword = $ms_db->getNextRow( $keydetails ) ) {
						$keyfile = $this->generateKeywordPageForSite( $keyword["id"], $keyword["keyword_name"], $ftp_conn['connect'] , $ftp_conn['ftp_homepage']);
						//echo str_repeat( " ", 4000 );
						if ( $showmessage == "yes" ) {
							flush();
							ob_flush();
						} else {
							ob_clean();
						}
						if ( $keyfile != false ) {
							$this->setKeywordGenerated( $keyword["id"], $keyfile );
							$finallysomekeywordsadded = true;
							$keywordsadded .= "<-!!->" . $keyword["keyword_name"];
							$keywordfiles .= "<-!!->" . $keyfile;
						} else {
						}
					}
					@unlink( ROOT_PATH . "temp_data/keywords.txt" ) ;
					ftp_close( $ftp_conn['connect'] );
				}
				// $this->showBottomOfPage();
			} 
			if ( $keyfile != false ) {
				$this->setLastGenerated( $keyproject["id"] );
			} 
			$prj->setProjectStatus( $project["id"], $status, $why );
			if ( $finallysomekeywordsadded == true ) {
				$localfeed = $art->generatePingFeedForKeywordPages( $project["site_id"], $keywordsadded , $keywordfiles );
			} 
		} 
		if ( $showmessage == "yes" ) {
			flush();
			ob_flush();
		} else {
			ob_end_clean();
		} 
	} 

	function ftpContect($site_id){
		global $ms_db;
		if ( empty( $site_id ) ) {
			echo '$site_id'." is empty!\n";
			return false;
		}
		$site = $ms_db->getDataSingleRow( "SELECT * FROM `". TABLE_PREFIX."portals_sites_tb` WHERE id=$site_id " );
		if ( empty( $site ) ) {
			echo '$site'."  array is empty - no data in portals_sites_tb!\n";
			return false;
		}

		$conn_id = ftp_connect( $site["ftp_address"] );
		if ( $conn_id === false ) {
			echo "wrong ftp_connect(".$site["ftp_address"].")!\n";
			return false;
		}
		if ( !ftp_login( $conn_id, $site["ftp_username"], html_entity_decode($site["ftp_password"]) ) ) {
			echo "wrong ftp_login(".$site["ftp_username"].", ".$site["ftp_password"]. " - " . html_entity_decode($site["ftp_password"]) .")!\n";
			ftp_close( $conn_id );
			return false;
		}
@ftp_pasv( $conn_id, true );
		return array('connect' => $conn_id, 'ftp_homepage' => $site['ftp_homepage']);
	}

	function setKeywordGenerated( $keyid, $keyfilename ) {
		global $ms_db;

		$sql = "Update " . TABLE_PREFIX . "keyword_details_tb set generated = 'Y', generated_date = '" . date( "Y-m-d H:i:s" ) . "', generated_file_name = '" . $ms_db->GetSQLValueString( $keyfilename, "text" ) . "' where id = " . $ms_db->GetSQLValueString( $keyid, "int" );

		$id = $ms_db->modify( $sql );

		return $id;
	} 

	function setLastGenerated( $keyprojectid ) {
		global $ms_db;

		$sql = "Update " . TABLE_PREFIX . "keywords_projects_tb set last_generated_date = '" . date( "Y-m-d H:i:s" ) . "' where id = " . $ms_db->GetSQLValueString( $keyprojectid, "int" );

		$id = $ms_db->modify( $sql );

		return $id;
	}   

	// refactoring by Rodion Konnov 12.03.2009
	function generateKeywordPageForSite( $keyphrasesid, $keyphrases, $conn_id, $ftp_homepage ) {
		global $KEYWORD_MAIN_TAG, $KEYWORD_TITLE_TAG, $KEYWORD_SUMMARY_TAG, $KEYWORD_SUMMARY_SEPARATOR, $KEYWORD_SOURCE_SITES, $KEYWORD_DATAS, $KEYWORD_SEARCH_BY, $KEYWORD_START_VARS, $ms_db, $prj, $common;
		$process = true;

		//$ftphomepage = $common->getFTPhomePage( $site['ftp_homepage'], $site['ftp_username'] ); // what is it?
		$targetfilename = str_replace( " ", "-", trim( $keyphrases ) );
		
		//new process generate keywords add 09.09.09
			$fileStatus = 0;
			$strKeywordsTxtFile = ROOT_PATH . "temp_data/keywords.txt";
			$strDestinationFile = $ftp_homepage . "datas/keywords.txt";
			if (!is_file($strKeywordsTxtFile)) {
				$fileStatus = 1;
			}
			$resOutFile = fopen($strKeywordsTxtFile, "ab");
			@chmod( $strKeywordsTxtFile, 0777 );
			if ($fileStatus == 1){ 
				$oldFile = @ftp_fget($conn_id, $resOutFile, $strDestinationFile, FTP_BINARY);
			}
			if ( $resOutFile === false ) {
				echo "wrong fopen $strKeywordsTxtFile!\n";
				return false;				
			}
			
			
			fwrite( $resOutFile, $targetfilename. "\n" );
			fclose( $resOutFile );
			
			$keywordsFtpUpload = @ftp_put( $conn_id, $strDestinationFile, $strKeywordsTxtFile, FTP_BINARY );
			
			
		//------------------------------------------
		
		/*
		$keyword_file = $targetfilename . ".php";
		//$destination_file = $ftphomepage . $keyword_file;
		$destination_file = $site['ftp_homepage'] . $keyword_file;
		$keyword_temp_file = ROOT_PATH . "temp_data/" . $keyphrasesid . ".php";
		@unlink( $keyword_temp_file ); // for old generated files. de bene esse
		$outfile = @fopen( $keyword_temp_file, "wb" );
		if ( $outfile===false ) {
			echo "wrong fopen $keyword_temp_file!\n";
			return false;
		}
		fwrite( $outfile, '<?php $keyword="' . $targetfilename . '"; include("datas/pages.php"); ?>' );
		fclose( $outfile );
		chmod( $keyword_temp_file, 0777 );
		// upload & close connection
		$upload=@ftp_put( $conn_id, $destination_file, $keyword_temp_file, FTP_BINARY ); 
		ftp_close( $conn_id ); 
		unlink( $keyword_temp_file );*/
		if ( !$keywordsFtpUpload ) {
			echo "Upload failed from ".$strKeywordsTxtFile." to ".$strDestinationFile."\n";
			return false;
		}
		return $targetfilename . '.html';
	} 

	function getKwdProjectIdFromProjectId( $projectid ) {
		global $ms_db;

		$sql = "Select k.id from " . TABLE_PREFIX . "keywords_projects_tb k, " . TABLE_PREFIX . "projects_tb p where p.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and k.project_id = p.id AND p.id = " . $ms_db->GetSQLValueString( $projectid, "int" );

		$kpid = $ms_db->getDataSingleRecord( $sql );

		return $kpid;
	} 
} 

if ( isset( $_GET["projectid"] ) && $_GET["projectid"] > 0 ) {
	set_time_limit( 0 );

	require_once( "../config/config.php" );

	require_once( "database.class.php" );

	require_once( "projects.class.php" ); 
	// echo "requires compl";
	$prj = new Projects();

	$ms_db = new Database();

	$ms_db->openDB();

	$keylocal = new Keyword(); 
	// echo "obj crtd";
	// echo "Article has been created, request sent to server";
	$keylocal->generateKeywordPages( $_GET["projectid"] );

	echo "generated";
} 

?>