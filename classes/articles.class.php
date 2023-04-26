<?php

class Article {
	function manageArticle() {
		global $database, $pg, $order_sql;

		$sql = "SELECT a.category,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE b.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and a.id=b.category_id " . $order_sql;
		/*$sql="SELECT a.category,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE a.id=b.category_id ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/
		$man_rs = $database->getRS( $sql );
		if ( $man_rs ) {
			$no = 0;
			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;
				$summary = wordwrap( $data['summary'], 100, "\n" );
				$title = wordwrap( $data['title'], 100, "\n" ); 
				// for task11 on 27 nov
				for( $i = 0;$i < count( $_SESSION['ncsb_article_id'] );$i++ ) {
					// $_SESSION['$i']=$ncsb[$i];
					if ( $_SESSION['ncsb_article_id'][$i] == $data['id'] ) {
						$sel = 'checked="checked"';
						break;
					} else
						$sel = '';
				} 
				$str .= "<tr><td align='center' width='20px'>" . $data['id'] . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . stripslashes( $title ) . "</td><td align='center'>" . stripslashes( $summary ) . "</td><td><input name='chk[]' id='chk" . $no . "' type='checkbox' value=" . $data['id'] . "  " . $sel . "  onclick='return test(this)'></td></tr>";
			} 
		} 

		return $str;
	} 

	function selectCategory() {
		global $database, $pg, $order_sql;

		$sql = "SELECT a.category,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE b.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and  b.category_id='" . $_REQUEST['amcat'] . "' and a.id=b.category_id " . $order_sql;

		/*$sql="SELECT a.category,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE b.category_id='".$_REQUEST['amcat']."' and a.id=b.category_id ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/

		$man_rs = $database->getRS( $sql );

		$str = "";

		if ( $man_rs ) {
			$no = 0;

			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;

				$summary = wordwrap( $data['summary'], 100, "\n" );

				$title = wordwrap( $data['title'], 100, "\n" );
				// for task11 on 27 nov
				for( $i = 0;$i < count( $_SESSION['ncsb_article_id'] );$i++ ) {
					// $_SESSION['$i']=$ncsb[$i];
					if ( $_SESSION['ncsb_article_id'][$i] == $data['id'] ) {
						$sel = 'checked="checked"';
						break;
					} else
						$sel = '';
				} 

				$str .= "<tr><td align='center' width='20px'>" . $data['id'] . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . stripslashes( $title ) . "</td><td align='center'>" . stripslashes( $summary ) . "</td><td><input name='chk[]' id='chk" . $no . "' type='checkbox' value=" . $data['id'] . "  " . $sel . "  onclick='return test(this)'></td></tr>";
			} 
		} 

		return $str;
	} 

	function SelectBox() {
		global $database, $article_data;

		$sql = "select id, category from `" . TABLE_PREFIX . "am_categories` where status='Active' and user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];

		$cat_rs = $database->getRS( $sql );

		$str .= "<option value='-1'>All Category</option>";

		?>



			<!--$str .= "<select name='category' id='category'>";-->



			



			<?php

		if ( $cat_rs ) {
			while( $data = $database->getNextRow( $cat_rs ) ) {
				if ( $_REQUEST['amcat'] == $data["id"] ) $selected = 'selected="selected"';
				else $selected = "";

				$str .= '<option value="' . $data['id'] . '" ' . $selected . '>' . $data['category'] . '</option>';

				?>



					



	<?php

			} 
		} 
		// $str .= "</select>";
		return $str;
	} 

	function insertArticleProject( $projectid, $genarticles = 0, $period = 0 ) {
		global $ms_db;

		$sql = "INSERT INTO `" . TABLE_PREFIX . "articles_projects_tb` ( `project_id` , `mode` , `generate_articles` , `generate_period` , `will_keyword_generate` , `last_generated_date`,`user_id` )



VALUES ("

		 . "'" . $ms_db->GetSQLValueString( $projectid, "int" ) . "',"

		 . "'" . $ms_db->GetSQLValueString( $_POST["mode"], "text" ) . "',"

		 . "'" . $ms_db->GetSQLValueString( $genarticles, "int" ) . "',"

		 . "'" . $ms_db->GetSQLValueString( $period, "text" ) . "',"

		 . "'" . $ms_db->GetSQLValueString( $_POST["will_keyword_generate"], "text" ) . "',"

		 . "'" . $ms_db->GetSQLValueString( "", "date" ) . "',"

		 . "'" . $ms_db->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')";

		$id = $ms_db->insert( $sql );

		return $id;
	} 

	function insertArticleSource( $artprojectid, $uploaddata ) {
		global $ms_db;

		foreach( $uploaddata as $filename ) {
			$sql = "INSERT INTO `" . TABLE_PREFIX . "article_source_tb` ( `article_project_id` , `source_file_name` , `source_date` , `generated` )



		VALUES ("

			 . "'" . $ms_db->GetSQLValueString( $artprojectid, "int" ) . "',"

			 . "'" . $ms_db->GetSQLValueString( $filename, "text" ) . "',"

			 . "'" . $ms_db->GetSQLValueString( date( "Y-m-d H:i:s" ), "date" ) . "',"

			 . "'" . $ms_db->GetSQLValueString( "N", "text" ) . "')";

			$id = $ms_db->insert( $sql );

			@rename( "temp_data/" . $filename, "temp_articles/" . $id . ".txt" );
		} 

		return count( $uploaddata );
	}

	public $from_cron=false;

	function uploadArticles( $projectid, $showmessage = "yes" ) {
		global $ms_db, $prj, $feed, $pf, $common, $steps;
		set_time_limit( 0 );
		ob_start();
		$project = $ms_db->getDataSingleRow( "
			Select p.id, p.status,p.site_id, t.title, t.ftp_homepage, t.ftp_address, t.ftp_username, t.ftp_password, t.url, t.feed_writable 
			from `" . TABLE_PREFIX . "projects_tb` p, `" . TABLE_PREFIX . "portals_sites_tb` t 
			where 
				".($this->from_cron?"":"p.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and ")."
				p.site_id = t.id AND p.id = " . $ms_db->GetSQLValueString( $projectid, "int" ) 
		);
		if ( $project["status"] == "C" ) {
			return false;
		}
		$artproject = $ms_db->getDataSingleRow( "Select * from  " . TABLE_PREFIX . "articles_projects_tb where project_id = " . $ms_db->GetSQLValueString( $projectid, "int" ) );
		if ( empty(  $artproject["mode"] ) ) {
			return false;
		}
		if ( $artproject["mode"] == "O" ) {
			$sql = "Select s.id,s.source_file_name from " . TABLE_PREFIX . "article_source_tb s, " . TABLE_PREFIX . "articles_projects_tb a where  s.generated = 'N' and  s.article_project_id = a.id and a.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" );
			$status = "C";
			$why = "Project Completed";
		} else if ( $artproject["mode"] == "R" ) {
			$sql = "Select s.id,s.source_file_name from " . TABLE_PREFIX . "article_source_tb s, " . TABLE_PREFIX . "articles_projects_tb a where  s.generated = 'N' and  s.article_project_id = a.id and a.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" ) . " and a.last_generated_date < '" . date( "Y-m-d H:i:s", strtotime( '-' . $artproject["generate_period"] . ' day' ) ) . "'  ORDER BY s.id LIMIT " . $artproject["generate_articles"];
			$sql2 = "Select count(s.id) from " . TABLE_PREFIX . "article_source_tb s, " . TABLE_PREFIX . "articles_projects_tb a where  s.generated = 'N' and  s.article_project_id = a.id and a.project_id = " . $ms_db->GetSQLValueString( $projectid, "int" ) . " ORDER BY s.id  LIMIT " . $artproject["generate_articles"];
			$noofarticles = $ms_db->getDataSingleRecord( $sql2 );
			if ( $noofarticles < $artproject["generate_articles"] ) {
				$status = "P";
				$why = "Project is out of articles";
			} else {
				$status = "I";
				$why = "Running";
			} 
		} 
		$artdetails = $ms_db->getRS( $sql );
		$keyfile = false;
		if ( $artdetails != false ) {
			$conn_id=@ftp_connect( $project["ftp_address"] );
			if ( $conn_id===false ) {
				echo "wrong ftp_connect(".$project["ftp_address"].")!\n";
				return false;
			}
			if ( !@ftp_login( $conn_id, $project["ftp_username"], html_entity_decode($project["ftp_password"]) ) ) {
				ftp_close( $conn_id ); 
				echo "wrong ftp_login(".$project["ftp_username"].", ".html_entity_decode($project["ftp_password"]).")!\n";
				return false;
			}
@ftp_pasv( $conn_id, true );
			$str = $project['ftp_homepage'];
			$cut = $project['ftp_username']; 
			$ftphomepage = $common->getFTPhomePage( $str, $cut );

			$newAfeed = "";
			$no = 0;
			$isfeedupdate = false;
			$finallyfeedhassomechanges = false;
			while( $article = $ms_db->getNextRow( $artdetails ) ) {
				$source_file = ROOT_PATH . "temp_articles/" . $article["id"] . ".txt";
				$destination_file = $ftphomepage . "datas/articles/" . $article["source_file_name"];
				$upload = @ftp_put( $conn_id, $destination_file, $source_file, FTP_ASCII );
				if ( !$upload ) {
					echo "Upload failed from ".$source_file." to ".$destination_file."\n";
					return false;
				}
				$this->setArticleUploaded( $article["id"] );
				echo "Articles upload from ".$source_file." to ".$destination_file."\n";
				$feeddest = $project['url'] . "feed.xml";
				if ( $project["feed_writable"] == "Y" ) {
					// $isfeedupdate = $this->updateRemoteFeedXML($project["url"], $title, $description, $link, $guid); // this whole block is shifted to 123remote script, i will grab title desc locally there.
					$isfeedupdate = $this->updateRemoteFeedXML( $project["url"], $article["source_file_name"] );
					if ( $isfeedupdate == true ) {
						$finallyfeedhassomechanges = true; 
						// $pf->pingFeedXML($title, $link, "", $feeddest);
					} 
				} else if ( $project["feed_writable"] == "Z" ) {
					// the second way when user wants to play with feed.xml but it is not writable, use ftp functions.
					$matter = @file( $source_file ); // block 101 starts
					$title = "";
					$description = "";
					for ( $i = 0;$i < count( $matter );$i++ ) {
						if ( strlen( trim( $title ) ) == 0 ) {
							$title = str_replace( "\n", " ", $matter[$i] );

							$title = str_replace( "\r", " ", $title );
						} else if ( strlen( trim( $title ) ) > 0 ) {
							$description .= $matter[$i];
						} 
					} 
					$description = substr( trim( $description ), 0, MAX_LENGTH_FEED_DESC );
					$title = trim( $title );
					$link = $project['url'] . "permalink.php?article=" . $article["source_file_name"];
					$guid = $link; // block 101 ends here
					$moreontitle = $feed->GetXmlString( $title );
					$moreonlink = "...[ More on <a href = '$link'>$moreontitle</a> ]";
					$newItem = $feed->getInsertBlock( $title, $description, $link, $guid, $moreonlink );
					if ( $newItem != "" ) {
						$newAfeed = $newItem . $newAfeed;
						$finallyfeedhassomechanges = true;
					} 
				} 
				unlink( $source_file );
				if ( $showmessage == "yes" ) {
					flush();
					ob_flush();
				} else {
					ob_clean();
				} 
			} 
		} 

		if ( $upload != false ) {
			$this->setLastGenerated( $artproject["id"] );
			if ( $artproject["will_keyword_generate"] == "Y" ) {
				$filename = "datas/key_articles"; 
				$chmo = $steps->makeWritable( $project["url"], $filename, 777 ); 
				// if ($chmo!="1") echo "<br> Can't change mode of ".$ftphomepage.$filename." to 0777";
				$this->generateKeywordRelevantArticles( $project["url"] ); 
				$chmo = $steps->makeWritable( $project["url"], $filename, 755 ); 
			} 
			if ( $finallyfeedhassomechanges == true ) {
				if ( $project["feed_writable"] == "Y" ) {
					$pf->pingFeedXML( $project["title"], $feeddest , "", "" ); 
					// grand generation of feed XML is removed due to ping feed while ad an article.
				} else if ( $project["feed_writable"] == "Z" ) {
					$localfeed = $feed->updateFeedXML( $project["url"], $newAfeed );
					if ( $localfeed != "" ) {
						$uploadfeed = ftp_put( $conn_id, $ftphomepage . "feed.xml" , $localfeed, FTP_BINARY );
						if ( !$uploadfeed ) {
							echo "<br>Failed to upload Feed.xml";
						} else {
							$pf->pingFeedXML( $project["title"], $feeddest , "", "" );
						} 
					} 
				} 
			} 
		} 
		$prj->setProjectStatus( $project["id"], $status, $why );
		@ftp_close( $conn_id );
		if ( $showmessage == "yes" ) {
			flush();
			ob_flush();
		} else {
			ob_end_clean();
		} 
	} 

	function setArticleUploaded( $artid ) {
		global $ms_db;

		$sql = "Update " . TABLE_PREFIX . "article_source_tb set generated = 'Y', generated_date = '" . date( "Y-m-d H:i:s" ) . "' where id = " . $ms_db->GetSQLValueString( $artid, "int" );

		$id = $ms_db->modify( $sql );

		return $id;
	} 

	function setLastGenerated( $artprojectid ) {
		global $ms_db;

		$sql = "Update " . TABLE_PREFIX . "articles_projects_tb set last_generated_date = '" . date( "Y-m-d H:i:s" ) . "' where id = " . $ms_db->GetSQLValueString( $artprojectid, "int" );

		$id = $ms_db->modify( $sql );

		return $id;
	} 
	// //////////////////////////////////////////////////////////////////////////////////////////////////////
	function showSiteList( $selected ) {
		global $ms_db;

		$sql = "select id,url from `" . TABLE_PREFIX . "portals_sites_tb` where is_under_portal != 'Y' and type = 'S' and user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' order by url";

		$title_rs = $ms_db->getRS( $sql );

		if ( $_POST["type"] == "2" ) $style = "";
		else $style = 'style="display:none"';

		echo '<select name="site_id1" id="sitelist" ' . $style . '>';

		echo '<option selected value="0"><-- Select Site --></option>';

		if ( $title_rs ) {
			while ( $site = $ms_db->getNextRow( $title_rs ) ) {
				if ( $site['id'] == $selected ) $select = "selected";
				else $select = "";

				echo '<option value="' . $site['id'] . '"' . $select . '>' . $site['url'] . '</option>';
			} 
		} 

		echo '</select>';
	} 

	function showPortalList( $selected ) {
		global $ms_db;

		$sql = "select id,url from `" . TABLE_PREFIX . "portals_sites_tb` where type = 'P' and user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' order by url";

		$title_rs = $ms_db->getRS( $sql );

		if ( $_POST["type"] == "1" ) $style = "";
		else $style = 'style="display:none"';

		echo '     <select name="packagelist" id="packagelist" onChange="JavaScript:PopulateCategory(document.forms[0].packagelist,document.getElementById(\'packagesitelist\'),-999)"  ' . $style . '>  ';

		echo '        <option selected value="0"><-- Select Package --></option>';

		if ( $title_rs ) {
			while ( $site = $ms_db->getNextRow( $title_rs ) ) {
				if ( $site['id'] == $selected ) $select = "selected";
				else $select = "";

				echo '<option value="' . $site['id'] . '"' . $select . '>' . $site['url'] . '</option>';
			} 
		} 

		echo '		</select>';
	} 
	// //////////////// * * * * * * * * * *  * *  * * * * * * * * * * * * * *  ////////////////////////////////////////////
	function checkUploadedFile() {
		$filename = array();

		$flag = false;

		$mess = array();

		if ( isset( $_POST['source_type'] ) && ( $_POST['source_type'] == "T" || $_POST['source_type'] == "Z" ) ) {
			for( $no = 0;$no < count( $_FILES["importtextzip"]["name"] );$no++ ) {
				$uplfilext = $this->getExt( $_FILES['importtextzip']['name'][$no] );

				if ( ( ( $uplfilext == "txt" || $uplfilext == "text" ) && $_POST['source_type'] != "T" ) || ( ( $uplfilext == "zip" ) && $_POST['source_type'] != "Z" ) ) {
					$mess[] = "Wrong Format : " . $_FILES['importtextzip']['name'][$no] . "";
				} else if ( !( $_FILES['importtextzip']['size'][$no] == 0 ) ) {
					if ( $_FILES['importtextzip']['type'][$no] == "application/zip" || $uplfilext == "zip" ) {
						$archive = new PclZip( $_FILES['importtextzip']['tmp_name'][$no] );

						$zipfilename = $_FILES['importtextzip']['name'][$no];

						$tempname = $archive->listContent();

						$nooffiles = count( $tempname );

						if ( $archive->extract( "temp_data/" ) != 0 ) {
							for ( $fno = 0; $fno < $nooffiles; $fno++ ) {
								$source_file = "temp_data/" . $tempname[$fno]["stored_filename"];

								$srcext = $this->getExt( $source_file );

								if ( !( strtolower( $srcext ) == "txt" || strtolower( $srcext ) == "text" ) ) {
									$mess[] = "Wrong Format : " . $tempname[$fno]["stored_filename"] . " (in " . $zipfilename . ")";

									unlink( $source_file );
								} else if ( @filesize( $source_file ) == 0 ) {
									$mess[] = "Blank File : " . $tempname[$fno]["stored_filename"] . " (in " . $zipfilename . ")";

									@unlink( $source_file );
								} else {
									// here i will rename the stored file with a unique name.
									/*	$tmp_file_name = $this->getName($tempname[$fno]["stored_filename"]);



									$tgt_file_name = $tmp_file_name.".txt"; // changed to txt  after issue



									echo "<br>Correct Format: ".$source_file;



									rename($source_file,"temp_data/".$tgt_file_name);



									$filename[] = $tgt_file_name; */// blocked after issue rised
									$title = $this->getFileNameByTitleInFile( $source_file );

									$title .= ".txt";

									rename( $source_file, "temp_data/" . $title );

									$filename[] = $title; 
									// $filename[] = $tempname[$fno]["stored_filename"];
								} 
							} 
						} else {
							$flag = false; // $archive->extract("temp_articles/") when return zero
						} 
					} else if ( $_FILES["importtextzip"]["type"][$no] == "text/plain" || $uplfilext == "txt" || $uplfilext == "text" ) { // ///////// if file is a TEXT file
						$source_file = $_FILES['importtextzip']['tmp_name'][$no]; 
						// $tmp_file_name = $this->getName($_FILES['importtextzip']['name'][$no]);
						// $tgt_file_name = $tmp_file_name.".txt";
						// $target_file = "temp_data/".$tgt_file_name;
						$target_file = "temp_data/" . $_FILES['importtextzip']['name'][$no];

						if ( move_uploaded_file( $source_file, $target_file ) ) {
							$title = $this->getFileNameByTitleInFile( $target_file );

							$title .= ".txt";

							rename( $target_file, "temp_data/" . $title );

							$filename[] = $title; 
							// $filename[] = $_FILES['importtextzip']['name'][$no];
						} else {
							$mess[] = "Problem in saving to temp_data/ : " . $target_file . "";

							$flag = false; //  problem in move file					
						} 
					} else { // is file id not text or zip
						$mess[] = "Wrong Format : " . $_FILES['importtextzip']['name'][$no] . "";

						$flag = false;
					} 
				} else {
					$mess[] = "Blank File : " . $_FILES['importtextzip']['name'][$no] . "";

					$flag = false; // when file size is ZERO
				} 
			} 
		} else if ( $_POST["source_type"] == "M" ) {
			// $articles = explode("###NEW###",$_POST["importmanual"]);
			$articles = explode( ARTICLE_SEPARATOR, $_POST["importmanual"] );

			foreach( $articles as $article ) {
				$matter = explode( "\n", $article );

				for ( $i = 0;$i < count( $matter );$i++ ) {
					$title = $matter[$i];

					if ( strlen( trim( $title ) ) > 0 ) {
						$articlename = trim( str_replace( " ", "-", $title ) ) . ".txt";

						$article_file_path = "temp_data/" . $articlename;

						$outfile = @fopen( $article_file_path, "wb" ); // old ftp_put code starts here
						$written = @fputs( $outfile, $article );

						@fclose( $outfile );

						if ( $outfile && $written ) {
							$filename[] = $articlename;
						} else {
							$mess[] = "Article Writing failed : " . $articlename . "";
						} 

						break;
					} 
				} 
			} 
		} else if ( $_POST["source_type"] == "C" ) { // print_r($_REQUEST['art']);
			if ( isset( $_REQUEST['art1'] ) && $_REQUEST['art1'] != "" ) { // echo "imran";
				if ( isset( $_REQUEST['chk'] ) && $_REQUEST['chk'] != "" ) {
					$filename1 = array_merge( $_REQUEST['art1'], $_REQUEST['chk'] );
				} else {
					foreach( $_REQUEST['art1'] as $article ) {
						$filename1[] = $article;
					} 
				} 

				/*foreach($_REQUEST['art'] as $article)

			{

				$filenam1e12[]=$article;

			}	

			foreach($_REQUEST['chk'] as $article)

			{

				$filename11[]=$article;

			}*/
				// print_r($filename11);
				// echo "<br>";
				// print_r($filename12);
			} else {
				foreach( $_REQUEST['chk'] as $article ) {
					$filename1[] = $article;
				} 
			} 

			foreach( $filename1 as $article_id ) {
				/*$sql="SELECT * FROM `".TABLE_PREFIX."am_article`  WHERE id=".$article_id;

				

				$rs=mysql_query($sql);

				$data=mysql_fetch_array($rs);

				//$man_rs=$ms_db->getRS($sql);

				//$data=$ms_db->getNextRow($man_rs);

				$contain .=$data['title'].'<imran>'.$data['body']."###NEW###";*/

				$sql = "SELECT * FROM `" . TABLE_PREFIX . "am_article`  WHERE id='" . $article_id . "'";

				$rs = @mysql_query( $sql );

				$data = @mysql_fetch_array( $rs ); 
				// $man_rs=$ms_db->getRS($sql);
				// $data=$ms_db->getNextRow($man_rs);
				// $str=explode(""$data['body'];
				$xBody = nl2br( $data['body'] );
				$contain .= $data['title'] . "\n" . $data['author'] . "<br/>" . $xBody . "###NEW###";
			} 
			// $articles = explode("###NEW###",$_POST["importmanual"]);
			$articles = explode( ARTICLE_SEPARATOR, $contain );

			foreach( $articles as $article ) {
				// print_r($article);
				// print_r($article);
				$matter = explode( "\n", $article );

				for ( $i = 0;$i < count( $matter );$i++ ) {
					$title = $matter[$i];

					if ( strlen( trim( $title ) ) > 0 ) {
						$articlename = trim( str_replace( " ", "-", $title ) ) . ".txt";

						$article_file_path = "temp_data/" . $articlename;

						$outfile = @fopen( $article_file_path, "wb" ); // old ftp_put code starts here
						$written = @fputs( $outfile, $article );

						@fclose( $outfile );

						if ( $outfile && $written ) {
							$filename[] = $articlename;
						} else {
							$mess[] = "Article Writing failed : " . $articlename . "";
						} 

						break;
					} 
				} 
			} 
			// die;
		} 

		if ( count( $mess ) > 0 ) {
			foreach( $mess as $msg ) {
				echo "<br>" . $msg;
			} 

			echo str_repeat( " ", 4000 );

			flush();
		} 

		if ( count( $filename ) == 0 && $flag == false ) {
			return false;
		} else {
			return $filename;
		} 
	} 

	function getFileNameByTitleInFile( $source_file ) {
		$matter = file_get_contents( $source_file );
		$title = "";
		$matter=explode("\r",$matter);
		for ( $i = 0;$i < count( $matter );$i++ ) {
			if ( strlen( trim( $title ) ) == 0 ) {
				$title = str_replace( "\n", "", $matter[$i] );

				$title = trim( str_replace( "\r", "", $title ) );
			} else if ( strlen( $title ) > 0 ) {
				$title = str_replace( " ", "-", $title );

				berak;
			} 
		} 

		$title = @preg_replace( "([^a-z0-9_.-])i", "_", trim( $title ) );
		return $title;
	} 

	function getExt( $file ) {
		return strtolower( strrev( substr( strrev( $file ), 0, strpos( strrev( $file ), "." ) ) ) );
	} 

	function getName( $file ) {
		return strrev( substr( strrev( $file ), strpos( strrev( $file ), "." ) + 1 ) );
	} 

	function getArtProjectIdFromProjectId( $projectid ) {
		global $ms_db;

		$sql = "Select a.id from " . TABLE_PREFIX . "articles_projects_tb a, " . TABLE_PREFIX . "projects_tb p where p.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and a.project_id = p.id AND p.id = " . $ms_db->GetSQLValueString( $projectid, "int" );

		$apid = $ms_db->getDataSingleRecord( $sql );

		return $apid;
	} 

	function generateKeywordRelevantArticles( $url ) {
		if ( function_exists( "fopen" ) ) {
			$fp = @fopen( $url . "datas/admin/form_gen_kwd_article.php", "r" );

			if ( !$fp ) {
				echo "<br>&nbsp;Unable to open " . $url . "datas/admin/form_gen_kwd_article.php";

				return false;
			} else {
				echo "<br>&nbsp;Launched " . $url . "datas/admin/form_gen_kwd_article.php";

				fclose( $fp );
			} 
		} else if ( function_exists( "curl_init" ) ) {
			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_URL, $url . "datas/admin/form_gen_kwd_article.php" );

			curl_setopt( $ch, CURLOPT_HEADER, 0 );

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			$resp = curl_exec( $ch );

			curl_close ( $ch ); 
			// echo $resp;
		} 
	} 

	/*function updateRemoteFeedXML($url, $title, $description, $link, $guid)



{



	$title =  trim($title);



	$description =  trim($description);



	$link =  trim($link);



	$guid =  trim($guid);			



	$url =  trim($url);				



	$fileurl = "123_controlpanel_remote_methods.php?title=".$title."&description=".$description."&link=".$link."&guid=".$guid;



*/

	function updateRemoteFeedXML( $url, $RemoteArticleFile, $keytitle = "", $keyfilename = "" ) {
		// $url = urlencode($url);
		$RemoteArticleFile = urlencode( $RemoteArticleFile );

		$keytitle = urlencode( $keytitle );

		$keyfilename = urlencode ( $keyfilename );

		$fileurl = "123_controlpanel_remote_methods.php?url=" . $url . "&articlefilename=" . $RemoteArticleFile . "&maxsize=" . MAX_LENGTH_FEED_DESC . "&maxitem=" . MAX_FEED_ITEMS . "&title=" . $keytitle . "&keywordfile=" . $keyfilename;

		if ( function_exists( "fopen" ) ) {
			$resp = "";

			$fp = @fopen( $url . $fileurl, "r" );

			if ( !$fp ) {
				echo "<br>&nbsp;Unable to launch " . $url . "123_controlpanel_remote_methods.php?articlefilename=" . $RemoteArticleFile;

				return false;
			} else {
				// echo "<br>&nbsp;Launched ".$url.$fileurl;
				while( !feof( $fp ) ) {
					$resp .= fgets( $fp );
				} 

				fclose( $fp );
			} 
		} else if ( function_exists( "curl_init" ) ) {
			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_URL, $url . $fileurl );

			curl_setopt( $ch, CURLOPT_HEADER, 0 );

			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

			$resp = curl_exec( $ch );

			curl_close ( $ch );
		} 

		if ( $resp == "true" ) {
			return true;
		} else {
			// echo $resp;
			return false;
		} 
	} 

	function generatePingFeedForKeywordPages( $siteid, $keywords, $keywordfiles ) {
		global $feed, $ms_db, $common, $pf, $steps;

		$keywordname = explode( "<-!!->", $keywords );

		$keyfilename = explode( "<-!!->", $keywordfiles );

		$noofkeywords = count( $keywordname )-1;

		$sql = "Select s.id,s.source_file_name, t.url, t.title, t.description , t.ftp_address, t.ftp_username, t.ftp_password, t.ftp_homepage, t.feed_writable  from " . TABLE_PREFIX . "article_source_tb s, " . TABLE_PREFIX . "articles_projects_tb a, `" . TABLE_PREFIX . "projects_tb` p, `" . TABLE_PREFIX . "portals_sites_tb` t where  s.generated = 'Y' and  s.article_project_id = a.id and a.project_id = p.id and p.site_id = t.id and p.site_id = " . $siteid . " and t.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' ORDER BY RAND() LIMIT " . MAX_FEED_ITEMS;

		$rs = $ms_db->getRS( $sql );

		if ( $rs ) {
			$totalarticles = mysql_num_rows( $rs );

			$newAfeed = "";

			$feedcreated = false;

			$keycounter = 1;

			while( $artcl = $ms_db->getNextRow( $rs ) ) {
				if ( $keycounter > $totalarticles - $noofkeywords ) {
					$keyname = $keywordname[$totalarticles - $keycounter + 1];

					$keyfile = $keyfilename[$totalarticles - $keycounter + 1];
				} else {
					$keyname = "";

					$keyfile = "";
				} 

				$keycounter++;

				$siteurl = $artcl["url"];

				$sitetitle = $artcl["title"];

				$sitedescription = $artcl["description"];

				$isfeedwritable = $artcl["feed_writable"];

				if ( $artcl["feed_writable"] == "Y" ) {
					if ( $feedcreated == false ) {
						$steps->createFeedXML( $sitetitle , $sitedescription, $siteurl, $siteurl );

						$feedcreated = true;
					} 

					$isfeedupdate = $this->updateRemoteFeedXML( $artcl["url"], $artcl["source_file_name"], $keyname, $keyfile );

					if ( $isfeedupdate == true ) {
						$finallyfeedhassomechanges = true;
					} 
				} else if ( $artcl["feed_writable"] == "Z" ) {
					$ftphome = $artcl["ftp_homepage"];

					$ftpaddr = $artcl["ftp_address"];

					$ftpuser = $artcl["ftp_username"];

					$ftppass = html_entity_decode($artcl["ftp_password"]);

					$source_file = $artcl["url"] . "/datas/articles/" . $artcl["source_file_name"];

					$matter = @file( $source_file ); // block 101 starts
					if ( $matter != false ) {
						$link = $artcl['url'] . "permalink.php?article=" . $artcl["source_file_name"];

						$guid = $link; // block 101 ends here
						$title = "";

						$description = "";

						for ( $i = 0;$i < count( $matter );$i++ ) {
							if ( strlen( trim( $title ) ) == 0 ) {
								$title = str_replace( "\n", " ", $matter[$i] );

								$title = str_replace( "\r", " ", $title );
							} else if ( strlen( trim( $title ) ) > 0 ) {
								$description .= $matter[$i];
							} 
						} 

						if ( $keyname != "" ) {
							$title = $keyname;

							$moreon = $artcl['url'] . $keyfile;
						} else {
							$moreon = $link;
						} 

						$title = trim( $title );

						$description = substr( trim( $description ), 0, MAX_LENGTH_FEED_DESC );

						$moreontitle = $feed->GetXmlString( $title );

						$moreonlink = "...[ More on <a href = '$moreon'>$moreontitle</a> ]";

						$newItem = $feed->getInsertBlock( $title, $description, $link, $guid, $moreonlink );

						if ( $newItem != "" ) {
							$newAfeed = $newItem . $newAfeed;

							$finallyfeedhassomechanges = true;
						} 
					} 
				} 
			} 

			if ( $finallyfeedhassomechanges == true ) {
				/*			



			$sitefeed = $feed->getSiteFeedContents($artcl["url"]);



			$feedstart = htmlentities(substr($sitefeed,0,strpos($sitefeed,"<item>")));



			$feedend = htmlentities(strrev(substr(strrev($sitefeed),0,strpos(strrev($sitefeed),">meti/<"))));



*/

				if ( $isfeedwritable == "Y" ) {
					$pf->pingFeedXML( $keywords , $siteurl . "feed.xml" , "", "" );
				} else if ( $isfeedwritable == "Z" ) {
					$feedtemplate = @file( "feedtemplate.txt" );

					$i = 0;

					for ( $i = 0; $i < count( $feedtemplate ) ; $i++ ) {
						if ( $i < 6 ) {
							$feedstart .= $feedtemplate[$i];
						} else {
							$feedend .= $feedtemplate[$i];
						} 
					} 

					$keywordurl = $siteurl . $keywordfile;

					$feedstart = str_replace( "[title]", $sitetitle, $feedstart );

					$feedstart = str_replace( "[description]", $sitedescription, $feedstart );

					$feedstart = str_replace( "[url]", $siteurl, $feedstart );

					$finalfeed = $feedstart . $newAfeed . $feedend;

					$localfeedfile = $feed->createLocalFeed( $finalfeed );

					$feeddest = $siteurl . "feed.xml";

					if ( $localfeedfile != "" ) {
						$conn_id = @ftp_connect( $ftpaddr );

						$login_result = @ftp_login( $conn_id, $ftpuser, $ftppass );
@ftp_pasv( $conn_id, true );
						$str = $ftphome;

						$cut = $ftpuser; 
						// $ftphomepage = substr($str, strpos($str,$cut) + strlen($cut)+1 , strlen($str) - strpos ($str,$cut)-strlen($cut));
						$ftphomepage = $common->getFTPhomePage( $str, $cut );

						if ( ( $conn_id ) && ( $login_result ) ) {
							$uploadfeed = @ftp_put( $conn_id, $ftphomepage . "feed.xml" , $localfeedfile, FTP_BINARY );

							ftp_close( $conn_id );
						} else {
							echo "<br>cant connect wih FTP server";
						} 

						if ( !$uploadfeed ) {
							echo "<br>Failed to upload Feed.xml";
						} else {
							$pf->pingFeedXML( $keywords, $feeddest , "", "" );
						} 
					} 
				} 
			} else {
				$localfeedfile = "";
			} 
		} else {
			$localfeedfile = "";
		} 
	} 
} 

?>