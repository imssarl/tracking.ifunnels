<?php

require_once( "classes/en_decode.class.php" );

class Article {
	function categorySelectBox_rewrite() {
		global $database, $article_data;
		$sql = "select id, category from `" . TABLE_PREFIX . "am_categories` where status='Active' and user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];

		$cat_rs = $database->getRS( $sql );

		?>
			<!--$str .= "<select name='category' id='category'>";-->
			<option value='-1'><--Select Category--></option>
			<?php
		if ( $cat_rs ) {
			while( $data = $database->getNextRow( $cat_rs ) ) {

				?>
					<option value="<?php echo $data['id'];
				?>" ><?php echo $data['category'];
				?></option>
				<?php
			} 
		} 
		// $str .= "</select>";
		// return $str;
	} 

	function manageArticleReWrite() {
		global $database, $pg, $order_sql;

		$pg = isset( $_GET['page'] )?$_GET['page']:1;

		$totRec = 50;

		if ( $pg == 1 ) {
			$offset = 0;
		} else {
			$offset = ( $pg-1 ) * $totRec;
		} 

		$order_sql = " Order By b.id Limit $offset,$totRec";

		$sql = "SELECT a.category,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE b.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and a.id=b.category_id " . $order_sql;

		/*$sql="SELECT a.category,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE a.id=b.category_id ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/

		$man_rs = $database->getRS( $sql );

		if ( $man_rs ) {
			$no = 0;

			while( $data = $database->getNextRow( $man_rs ) ) {
				if ( $no == 0 )

					$no = $no + 1 + $offset;

				else

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
				// $str .= "<tr><td align='center' width='20px'>".$no."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']."  ".$sel."  onclick='return test(this)'></td></tr>";
				/*$str .= "<tr><td align='center' width='20px'>".$no."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']."  ".$sel."  onclick='return test(this)'></td></tr>";*/
				// on 10_dec
				$str .= "<tr><td align='center' width='20px'>" . $no . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . $title . "</td><td align='center'>" . $summary . "</td><td colspan=2><a href='javascript:open_art(" . $data['id'] . ")'   >Article Rewriter</a></td></tr>";
			} 
		} 

		return $str;
	} 

	function selectCategoryReWrite() {
		global $database, $pg, $order_sql;

		$pg = isset( $_GET['page'] )?$_GET['page']:1;

		$totRec = 50;

		if ( $pg == 1 ) {
			$offset = 0;
		} else {
			$offset = ( $pg-1 ) * $totRec;
		} 

		$order_sql = " Order By b.id Limit $offset,$totRec";

		$sql = "SELECT a.category,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE b.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and  b.category_id='" . $_REQUEST['amcat'] . "' and a.id=b.category_id " . $order_sql;

		/*$sql="SELECT a.category,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE b.category_id='".$_REQUEST['amcat']."' and a.id=b.category_id ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/

		$man_rs = $database->getRS( $sql );

		$str = "";

		if ( $man_rs ) {
			$no = 0;

			while( $data = $database->getNextRow( $man_rs ) ) {
				if ( $no == 0 )

					$no = $no + 1 + $offset;

				else

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

				/*$str .= "<tr><td align='center' width='20px'>".$no."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']." ".$sel." onclick='return test(this)'></td></tr>";*/

				/*$str .= "<tr><td align='center' width='20px'>".$no."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']." ".$sel." onclick='return test(this)'></td></tr>";*/
				// on 10_dec
				$str .= "<tr><td align='center' width='20px'>" . $no . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . $title . "</td><td align='center'>" . $summary . "</td><td colspan=2><a href='javascript:open_art(" . $data['id'] . ")'  >Article Rewriter</a></td></tr>";
			} 
		} 

		return $str;
	} 

	function manageArticle() {
		global $database, $pg, $order_sql;
		$endec = new encode_decode(); 
		// on 03_dec for task 116
		/*$sql="SELECT a.category,a.id as cat_id,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE a.id=b.category_id  and b.user_id=".$_SESSION[SESSION_PREFIX.'sessionuserid']." ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/

		$sql = "SELECT a.category,a.id as cat_id,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE a.id=b.category_id and a.status='Active' and b.user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
		$man_rs = $database->getRS( $sql );
		if ( $man_rs ) {
			$no = 0;
			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;
				$summary = wordwrap( $data['summary'], 100, "\n" ); 
				// comment on 06_nov
				// $title=wordwrap($data['title'], 100, "\n");
				// echo ">>>>>".strlen($data['title']); die;
				if ( strlen( $data['title'] ) > 100 )
					$title = substr( $data['title'], 0, 100 ) . '...';
				else
					$title = $data['title'];
				/*$str .= "<tr><td align='center' width='20px'>".$data['id']."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td align='center'>".$data['source']."</td><td align='center'>".$data['status']."</td><td align='center' width='20px'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(".$data['id'].")'></td><td align='center' width='20px'><a href='?process=duplicate&id=".$data['id']."'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']."></td></tr>";*/
				$str .= "<tr><td align='center' width='20px'>" . $no . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . stripslashes( $title ) . "</td><td align='left'>" . stripslashes( $summary ) . "</td><td align='center'>" . stripslashes( $data['source'] ) . "</td><td align='center'>" . stripslashes( $data['status'] ) . "</td><td align='center' width='20px'><a href='?process=edit&id=" . $data['id'] . "'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(\"" . $endec->encode( $data['id'] ) . "\")'></td><td align='center' width='20px'><a href='?process=duplicate&id=" . $data['id'] . "'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=" . $data['id'] . "'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td><input name='chk[]' id='chk" . $no . "' type='checkbox' value=" . $data['id'] . " onclick='return test(this)'></td></tr>";
				// onclick applied for task93 issue3 changes on 06_nov
			} 
		} 
		return $str;
	} 

	function manageArticleGCPaging() {
		global $database, $pg, $order_sql;
		$str = "";
		$showRec = 50;
		if ( isset( $_GET['pg'] ) )
			$page = $_GET['pg'];
		else
			$page = 1;

		$sql = "select count(b.id) as numrows FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE a.id=b.category_id and a.status='Active' and b.user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];

		$data = $database->getDataSingleRow( $sql );
		$maxPage = ceil( $data['numrows'] / $showRec ); 
		// echo "maxpg:".$data['numrows'];
		if ( $page > 1 ) {
			$pPg = $page-1;
			$prev = "<a href=\"?pg=$pPg&process=art\">Prev</a>";
		} else {
			$prev = "Prev";
		} 

		if ( $page < $maxPage ) {
			$nPg = $page + 1;
			$next = "<a href=\"?pg=$nPg&process=art\">Next</a>"; 
			// $prev="<a href=\"haveasay.php?pg=$page\">PREV</a>";
		} else {
			$next = "Next";
		} 
		$str .= "<table width='90%'>";
		$str .= "<tr><td height=5px></td></tr>";
		$str .= "<tr><td colspan=2 align='center'>Total " . $data['numrows'] . " record(s) found. Showing " . $showRec . " record(s) per page. </td></tr>" ;
		$str .= "<tr><td height=2px></td></tr>";
		$str .= "<tr><td colspan=2 align='center'>" . $prev . " Showing $page of $maxPage " . $next . "</td></tr>" ;
		$str .= "</table>";
		return $str;
	} 

	function manageArticleGC() {
		global $database, $pg, $order_sql;
		$endec = new encode_decode();
		$showRec = 50;
		if ( isset( $_GET['pg'] ) )
			$page = $_GET['pg'];
		else
			$page = 1;

		$offset = ( $page-1 ) * $showRec;
		$sql = "SELECT a.category,a.id as cat_id,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE a.id=b.category_id and a.status='Active' and b.user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $offset . "," . $showRec;

		$man_rs = $database->getRS( $sql );
		if ( $man_rs ) {
			$no = 0;
			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;
				$summary = wordwrap( $data['summary'], 100, "\n" ); 
				// comment on 06_nov
				// $title=wordwrap($data['title'], 100, "\n");
				// echo ">>>>>".strlen($data['title']); die;
				if ( strlen( $data['title'] ) > 100 )
					$title = substr( $data['title'], 0, 100 ) . '...';
				else
					$title = $data['title'];
				$checked = "";
				if ( $no == 1 )
					$checked = "checked='checked'";
				/*$str .= "<tr><td align='center' width='20px'>".$data['id']."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td align='center'>".$data['source']."</td><td align='center'>".$data['status']."</td><td align='center' width='20px'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(".$data['id'].")'></td><td align='center' width='20px'><a href='?process=duplicate&id=".$data['id']."'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']."></td></tr>";*/
				$str .= "<tr><td align='center' width='20px'>" . $no . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . $title . "</td><td align='left'>" . $summary . "</td><td align='center'>" . $data['source'] . "</td><td><input name='chk[]' id='chk" . $no . "' type='radio' " . $checked . " value=" . $data['id'] . "></td></tr>";
				// onclick applied for task93 issue3 changes on 06_nov<td align='center'>".$data['status']."</td><td align='center' width='20px'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(\"".$endec->encode($data['id'])."\")'></td><td align='center' width='20px'><a href='?process=duplicate&id=".$data['id']."'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td>
			} 
		} 
		return $str;
	} 

	function manageArticleGCCatPaging() {
		global $database, $pg, $order_sql;
		$str = "";
		$showRec = 50;
		if ( isset( $_GET['pg'] ) )
			$page = $_GET['pg'];
		else
			$page = 1;

		$sql = "select count(b.id) as numrows FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE a.id=b.category_id and a.status='Active' and b.user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];

		$data = $database->getDataSingleRow( $sql );
		$maxPage = ceil( $data['numrows'] / $showRec ); 
		// echo "maxpg:".$data['numrows'];
		if ( $page > 1 ) {
			$pPg = $page-1;
			$prev = "<a href=\"?pg=$pPg&process=artcat\">Prev</a>";
		} else {
			$prev = "Prev";
		} 

		if ( $page < $maxPage ) {
			$nPg = $page + 1;
			$next = "<a href=\"?pg=$nPg&process=artcat\">Next</a>"; 
			// $prev="<a href=\"haveasay.php?pg=$page\">PREV</a>";
		} else {
			$next = "Next";
		} 
		$str .= "<table width='90%'>";
		$str .= "<tr><td height=5px></td></tr>";
		$str .= "<tr><td colspan=2 align='center'>Total " . $data['numrows'] . " record(s) found. Showing " . $showRec . " record(s) per page. </td></tr>" ;
		$str .= "<tr><td height=2px></td></tr>";
		$str .= "<tr><td colspan=2 align='center'>" . $prev . " Showing $page of $maxPage " . $next . "</td></tr>" ;
		$str .= "</table>";
		return $str;
	} 

	function manageArticleGCCat() {
		global $database, $pg, $order_sql;
		$endec = new encode_decode();
		$showRec = 50;
		if ( isset( $_GET['pg'] ) )
			$page = $_GET['pg'];
		else
			$page = 1;

		$offset = ( $page-1 ) * $showRec;
		$sql = "SELECT a.category,a.id as cat_id,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE a.id=b.category_id and a.status='Active' and b.user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $offset . "," . $showRec;

		$man_rs = $database->getRS( $sql );
		if ( $man_rs ) {
			$no = 0;
			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;
				$summary = wordwrap( $data['summary'], 100, "\n" ); 
				// comment on 06_nov
				// $title=wordwrap($data['title'], 100, "\n");
				// echo ">>>>>".strlen($data['title']); die;
				if ( strlen( $data['title'] ) > 100 )
					$title = substr( $data['title'], 0, 100 ) . '...';
				else
					$title = $data['title'];
				$checked = ""; 
				// if($no==1)
				// $checked="checked='checked'";
				/*$str .= "<tr><td align='center' width='20px'>".$data['id']."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td align='center'>".$data['source']."</td><td align='center'>".$data['status']."</td><td al".$checked."ign='center' width='20px'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(".$data['id'].")'></td><td align='center' width='20px'><a href='?process=duplicate&id=".$data['id']."'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']."></td></tr>";*/
				$str .= "<tr><td align='center' width='20px'>" . $no . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . $title . "</td><td align='left'>" . $summary . "</td><td align='center'>" . $data['source'] . "</td><td><input name='chk[]' id='chk" . $no . "' type='checkbox'  value=" . $data['id'] . "></td></tr>";
				// onclick applied for task93 issue3 changes on 06_nov<td align='center'>".$data['status']."</td><td align='center' width='20px'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(\"".$endec->encode($data['id'])."\")'></td><td align='center' width='20px'><a href='?process=duplicate&id=".$data['id']."'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td>
			} 
		} 
		return $str;
	} 

	function manageCode() {
		global $database, $pg, $order_sql;
		$endec = new encode_decode();

		$sql = "SELECT id,user_id,disp_option,code,name,description FROM `" . TABLE_PREFIX . "am_savedcode` where user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . " " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;

		$man_rs = $database->getRS( $sql );
		if ( $man_rs ) {
			$no = 0;
			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;

				switch ( $data['disp_option'] ) {
					case "artsnip":
						$disp = "Article Snippet";
						break;
					case "randart":
						$disp = "Random Article";
						break;
					case "artcat":
						$disp = "Category Specific";
						break;
					case "kwdart":
						$disp = "Keyword Based";
						break;
					case "art":
						$disp = "Single Article";
						break;
				} 

				$str .= "<tr><td align='center' width='20px'>" . $no . "</td><td align='center' width='200px'>" . $data['name'] . "</td><td align='center'>" . $data['description'] . "</td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getxcode(\"" . $endec->encode( $data['id'] ) . "\")'><td align='center' width='20px'><a href='?process=sedit&id=" . $data['id'] . "'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=savedcode&id=" . $data['id'] . "&subpro=confirmdelete'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td></tr>"; 
				// <td align='left' width='350px'><textarea readonly='readonly' cols=50 rows=5>".html_entity_decode($data['code'])."</textarea></td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td>onclick applied for task93 issue3 changes on 06_nov<td align='center'>".$data['status']."</td><td align='center' width='20px'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(\"".$endec->encode($data['id'])."\")'></td><td align='center' width='20px'><a href='?process=duplicate&id=".$data['id']."'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']." onclick='return test(this)'></td>
			} 
		} 
		return $str;
	} 

	function getCodeById( $id ) {
		global $database;
		$sql = "SELECT * from  `" . TABLE_PREFIX . "am_savedcode` where id = " . $id;
		$rs = $database->getDataSingleRow( $sql );
		return $rs;
	} 

	function selectCategory() {
		$endec = new encode_decode();
		global $database, $pg, $order_sql; 
		// on 03_dec for task 116
		/*$sql="SELECT a.category,b.* FROM `".TABLE_PREFIX."am_categories` a,`".TABLE_PREFIX."am_article` b WHERE b.user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'  and  b.category_id='".$_REQUEST['amcat']."' and a.id=b.category_id ".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;*/

		$sql = "SELECT a.category,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE b.user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' and a.status='Active' and  b.category_id='" . $_REQUEST['amcat'] . "' and a.id=b.category_id " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;

		$man_rs = $database->getRS( $sql );
		$str = "";
		if ( $man_rs ) {
			$no = 0;
			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;
				$summary = wordwrap( $data['summary'], 100, "\n" ); 
				// cooment on 06_nov
				// $title=wordwrap($data['title'], 100, "\n");
				// echo ">>>>>".strlen($data['title']); die;
				if ( strlen( $data['title'] ) > 100 )
					$title = substr( $data['title'], 0, 100 ) . '...';
				else
					$title = $data['title']; 
				// $str .= "<tr><td align='center' width='20px'>".$data['id']."</td><td align='center'>".$data['category']."</td><td align='center' onclick='opencode(".$data['id'].")' style='cursor:pointer'>".$title."</td><td align='center'>".$summary."</td><td align='center'>".$data['source']."</td><td align='center'>".$data['status']."</td><td align='center' width='20px'><a href='?process=edit&id=".$data['id']."'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(".$data['id'].")'></td><td align='center' width='20px'><a href='?process=duplicate&id=".$data['id']."'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=".$data['id']."'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td><input name='chk[]' id='chk".$no."' type='checkbox' value=".$data['id']."></td></tr>";
				$str .= "<tr><td align='center' width='20px'>" . $no . "</td><td align='center'>" . $data['category'] . "</td><td align='center' onclick='opencode(" . $data['id'] . ")' style='cursor:pointer'>" . $title . "</td><td align='center'>" . $summary . "</td><td align='center'>" . $data['source'] . "</td><td align='center'>" . $data['status'] . "</td><td align='center' width='20px'><a href='?process=edit&id=" . $data['id'] . "'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='getcode(\"" . $endec->encode( $data['id'] ) . "\")'></td><td align='center' width='20px'><a href='?process=duplicate&id=" . $data['id'] . "'><img src='images/duplicate.png' border='0' title='Duplicate' style='cursor:pointer'></a></td><td align='center' width='20px'><a href='?process=confirmdelete&id=" . $data['id'] . "'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td><td><input name='chk[]' id='chk" . $no . "' type='checkbox' value=" . $data['id'] . " onclick='return test(this)'></td></tr>";
				// onclick applied for task93 issue3 changes on 06_nov
			} 
		} 

		return $str;
	} 

	function viewArticle( $id ) {
		global $database;

		$sql = "SELECT a.category,b.* FROM `" . TABLE_PREFIX . "am_categories` a,`" . TABLE_PREFIX . "am_article` b WHERE a.id=b.category_id and b.id=" . $id . " and b.user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];

		$data = $database->getDataSingleRow( $sql );

		?>
			<tr><td class="amtitle"><?php echo $data['title'];
		?></TD></tr><tr><TD><br></TD></tr><tr><td class="amsummary"><?php echo $data['summary'];
		?></td></tr><tr><TD><br></TD></tr><tr><TD class="amarticle"><?php echo $data['body'];
		?></TD></tr><tr><TD><br></TD></tr><tr>
	<?php
		return $id;
	} 

	function manageCategory() {
		global $database, $pg, $order_sql;
		$endec = new encode_decode();
		$no = 0;
		$sql = "SELECT * from `" . TABLE_PREFIX . "am_categories` where user_id='" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "' " . $order_sql . " LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;;
		$man_rs = $database->getRS( $sql );
		if ( $man_rs ) {
			while( $data = $database->getNextRow( $man_rs ) ) {
				$no = $no + 1;

				?>
			<tr><td align='center' width="20px"><?php echo $no;
				?></td><td align='center'><?php echo $data['category'];
				?></td><td align='center'><?php echo $data['status'];
				?></td><td align='center' width="20px"><a href='?process=editcategory&id=<?php echo $data['id'];
				?>'><img src='images/edit.png' border='0' title='Edit' style='cursor:pointer'></a></td><td align='center' width='20px'><img src='images/getcode.gif' border='0' title='GetCode' style='cursor:pointer' onclick='viewcode("<?php echo $endec->encode( $data['id'] );
				?>")'></td><td align='center' width="20px"><a href='?process=deletecategory&id=<?php echo $data['id'];
				?>'><img src='images/delete.png' border='0' title='Delete' style='cursor:pointer'></a></td></tr><?php

			} 
		} 
		// return $str;
	} 
	// function categorySelectBox($sel=0)
	// {
	// global $database;
	// $sql="select id, category from `".TABLE_PREFIX."am_categories` where status='Active'";
	// $cat_rs=$database->getRS($sql);
	// $str = "";
	// //$str .= "<select name='category' id='category'>";
	// $str .= "<option value='-1'><--Select Category--></option>";
	// if ($cat_rs)
	// {
	// while($data = $database->getNextRow($cat_rs))
	// {
	// if($sel==$article_data["category_id"]) $selected = "selected"; else $selected = "";
	// $str .= "<option value='".$data["id"]."' $selected>".$data["category"]."</option>";
	// }
	// }
	// //$str .= "</select>";
	// return $str;
	// 
	// }
	function categorySelectBox() {
		global $database, $article_data;
		$sql = "select id, category from `" . TABLE_PREFIX . "am_categories` where status='Active' and user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];

		$cat_rs = $database->getRS( $sql );

		?>
			<!--$str .= "<select name='category' id='category'>";-->
			<option value='-1'><--Select Category--></option>
			<?php
		if ( $cat_rs ) {
			while( $data = $database->getNextRow( $cat_rs ) ) {
				if ( $_REQUEST['amcat'] == $data["id"] || $article_data['category_id'] == $data["id"] || $_REQUEST['category'] == $data["id"] ) $selected = 'selected="selected"';
				else $selected = "";

				?>
					<option value="<?php echo $data['id'];
				?>" <?php echo $selected;
				?>><?php echo $data['category'];
				?></option>
				<?php
			} 
		} 
		// $str .= "</select>";
		// return $str;
	} 

	function SelectBox() {
		global $database, $article_data;
		$sql = "select id, category from `" . TABLE_PREFIX . "am_categories` where status='Active' and user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];
		$cat_rs = $database->getRS( $sql );

		?>
			<!--$str .= "<select name='category' id='category'>";-->
			<option value='-1'>All Category</option>
			<?php
		if ( $cat_rs ) {
			while( $data = $database->getNextRow( $cat_rs ) ) {
				if ( $_REQUEST['amcat'] == $data["id"] ) $selected = 'selected="selected"';
				else $selected = "";

				?>
					<option value="<?php echo $data['id'];
				?>" <?php echo $selected;
				?>><?php echo $data['category'];
				?></option>

			<?php
			} 
		} 
		// $str .= "</select>";
		// return $str;
	} 

	function insertArticle() {
		global $database;

		$tit = trim( $_POST["title"] );

		$regextit = "([^0-9 a-zA-Z’“”]+)";
		$tit = preg_replace( $regextit, "" , $tit );

		$tit = str_replace( "–", '-', $tit );
		$tit = str_replace( "“", '"', $tit );
		$tit = str_replace( "”", '"', $tit );
		$tit = str_replace( "’", "'", $tit );

		$author = str_replace( "“", '"', $_POST["author"] );
		$author = str_replace( "–", '-', $author );
		$author = str_replace( "”", '"', $author );
		$author = str_replace( "’", "'", $author );

		$summary = str_replace( "“", '"', $_POST["summary"] );
		$summary = str_replace( "–", '-', $summary );
		$summary = str_replace( "”", '"', $summary );
		$summary = str_replace( "’", "'", $summary );

		$body = str_replace( "“", '"', $_POST["body"] );
		$body = str_replace( "–", '-', $body );
		$body = str_replace( "”", '"', $body );
		$body = str_replace( "’", "'", $body );

		$sql = "INSERT INTO `" . TABLE_PREFIX . "am_article` ( `category_id` , `title` , `author` , `summary` , `body` , `source`, `status`,`date`,`user_id`)
		VALUES ("
		 . "'" . $database->GetSQLValueString( addslashes( $_POST["category"] ), "text" ) . "',"
		 . "'" . $database->GetSQLValueString( addslashes( $tit ), "text" ) . "',"
		 . "'" . $database->GetSQLValueString( addslashes( $author ), "text" ) . "',"
		 . "'" . $database->GetSQLValueString( addslashes( $summary ), "text" ) . "',"
		 . "'" . $database->GetSQLValueString( addslashes( $body ), "text" ) . "',"
		 . "'" . $database->GetSQLValueString( addslashes( str_replace( "’", "'", $_POST["source"] ) ), "text" ) . "',"
		 . "'" . $database->GetSQLValueString( addslashes( str_replace( "’", "'", $_POST["status"] ) ), "text" ) . "',"
		 . "'" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "',"
		 . "'" . $database->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')";
		$id = $database->insert( $sql );
		return $id;
	} 

	function saveCode() {
		global $database;
		$tit = trim( $_POST["title"] );
		$regextit = "([^0-9 a-zA-Z]+)";
		$tit = preg_replace( $regextit, "" , $tit );
		$sql = "INSERT INTO `" . TABLE_PREFIX . "am_article` ( `category_id` , `title` , `author` , `summary` , `body` , `source`, `status`,`date`,`user_id`)
		VALUES ("
		 . "'" . $database->GetSQLValueString( $_POST["category"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $tit, "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["author"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["summary"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["body"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["source"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["status"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "',"
		 . "'" . $database->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')";
		$id = $database->insert( $sql );
		return $id;
	} 

	function insertDuplicateArticle( $id ) {
		global $database;
		$sql = "SELECT * from  `" . TABLE_PREFIX . "am_article` where id = " . $id;
		$rs = $database->getDataSingleRow( $sql );
		$sql1 = "INSERT INTO `" . TABLE_PREFIX . "am_article` ( `category_id` , `title` , `author` , `summary` , `body` , `source`, `status`,`date`,`user_id`)
		VALUES ("
		 . "'" . $database->GetSQLValueString( $rs['category_id'], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $rs["title"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $rs["author"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $rs["summary"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $rs["body"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $rs["source"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $rs["status"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "',"
		 . "'" . $database->GetSQLValueString( $rs["user_id"], "text" ) . "')";
		$id = $database->insert( $sql1 );
		return $id;
	} 

	function insertSnippet() {
		global $database;
		$tit = trim( $_POST["title"] );
		$regextit = "([^0-9 a-zA-Z]+)";
		$tit = preg_replace( $regextit, "" , $tit );
		$sql = "INSERT INTO `" . TABLE_PREFIX . "am_article_snippets` ( `category_id` , `title` , `author` ,  `summary` ,  `source`, `status`,`date`,`user_id`)
		VALUES ("
		 . "'" . $database->GetSQLValueString( $_POST["category"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $tit, "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST['author'], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["summary"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["source"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["status"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "',"
		 . "'" . $database->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')";
		$id = $database->insert( $sql );
		return $id;
	} 

	function insertCategory() {
		global $database;
		$sql = "INSERT INTO `" . TABLE_PREFIX . "am_categories` ( `category` , `date` ,  `status`, `user_id`)
		VALUES ("
		 . "'" . $database->GetSQLValueString( $_POST["newcategory"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "',"
		 . "'" . $database->GetSQLValueString( $_POST["status"], "text" ) . "',"
		 . "'" . $database->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')";
		$id = $database->insert( $sql );
		return $id;
	} 

	function deleteCat( $id ) {
		global $database;

		$sql = "DELETE from `" . TABLE_PREFIX . "am_article` WHERE `category_id` = " . $id;

		$id2 = $database->modify( $sql );

		$sql = "Delete from  `" . TABLE_PREFIX . "am_categories` WHERE `id` = " . $id;

		$id = $database->modify( $sql );

		return $id;
	} 

	function deleteArticle( $id ) {
		global $database;

		$sql = "Delete from  `" . TABLE_PREFIX . "am_article` WHERE `id` = " . $id;

		$id = $database->modify( $sql );

		return $id;
	} 

	function deleteArticleSnippet( $id ) {
		global $database;

		$sql = "Delete from  `" . TABLE_PREFIX . "am_article_snippets` WHERE `id` = " . $id;

		$id = $database->modify( $sql );

		return $id;
	} 

	function updateArticle( $id ) {
		global $database;

		$tit = trim( $_POST["title"] );

		$regextit = "([^0-9 a-zA-Z’]+)";
		$tit = preg_replace( $regextit, "" , $tit );

		$tit = str_replace( "–", '-', $tit );
		$tit = str_replace( "“", '"', $tit );
		$tit = str_replace( "”", '"', $tit );
		$tit = str_replace( "’", "'", $tit );

		$author = str_replace( "“", '"', $_POST["author"] );
		$author = str_replace( "–", '-', $author );
		$author = str_replace( "”", '"', $author );
		$author = str_replace( "’", "'", $author );

		$summary = str_replace( "“", '"', $_POST["summary"] );
		$summary = str_replace( "–", '-', $summary );
		$summary = str_replace( "”", '"', $summary );
		$summary = str_replace( "’", "'", $summary );

		$body = str_replace( "“", '"', $_POST["body"] );
		$body = str_replace( "–", '-', $body );
		$body = str_replace( "”", '"', $body );
		$body = str_replace( "’", "'", $body );

		$sql = "UPDATE `" . TABLE_PREFIX . "am_article` SET
		`category_id` = '" . $database->GetSQLValueString( addslashes( str_replace( "’", "'", $_POST["category"] ) ), "text" ) . "',
		`title` = '" . $database->GetSQLValueString( addslashes( $tit ), "text" ) . "',
		`author` = '" . $database->GetSQLValueString( addslashes( $author ), "text" ) . "',
		`summary` = '" . $database->GetSQLValueString( addslashes( $summary ), "text" ) . "',
		`body` = '" . $database->GetSQLValueString( addslashes( $body ), "text" ) . "',
		`source` = '" . $database->GetSQLValueString( addslashes( str_replace( "’", "'", $_POST["source"] ) ), "text" ) . "',
		`status` = '" . $database->GetSQLValueString( addslashes( str_replace( "’", "'", $_POST["status"] ) ), "text" ) . "',
		`date` = '" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "'
		WHERE `id` = " . $id;
		$id = $database->modify( $sql );
		return $id;
	} 

	function updateArticleSnippet( $id ) {
		global $database;
		$tit = trim( $_POST["title"] );
		$sql = "UPDATE `" . TABLE_PREFIX . "am_article_snippets` SET
		`category_id` = '" . $database->GetSQLValueString( $_POST["category"], "text" ) . "',
		`title` = '" . $database->GetSQLValueString( $tit, "text" ) . "',
		`author` = '" . $database->GetSQLValueString( $_POST['author'], "text" ) . "',
		`summary` = '" . $database->GetSQLValueString( $_POST["summary"], "text" ) . "',
		`source` = '" . $database->GetSQLValueString( $_POST["source"], "text" ) . "',
		`status` = '" . $database->GetSQLValueString( $_POST["status"], "text" ) . "',
		`date` = '" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "'
		WHERE `id` = " . $id;
		$id = $database->modify( $sql );
		return $id;
	} 

	function updateCategory( $id ) {
		global $database;

		$sql = "UPDATE `" . TABLE_PREFIX . "am_categories` SET 
		`category` = '" . $database->GetSQLValueString( $_POST["newcategory"], "text" ) . "',
		`status` = '" . $database->GetSQLValueString( $_POST["status"], "text" ) . "',
		`date` = '" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "'
		 WHERE `id` = " . $id;
		$id = $database->modify( $sql );
		return $id;
	} 

	function getArticleById( $id ) {
		global $database;

		$sql = "SELECT * from  `" . TABLE_PREFIX . "am_article` where id = " . $id;

		$rs = $database->getDataSingleRow( $sql );

		return $rs;
	} 

	function getCategoryById( $id ) {
		global $database;

		$sql = "SELECT * from  `" . TABLE_PREFIX . "am_categories` where id = " . $id;

		$rs = $database->getDataSingleRow( $sql );

		return $rs;
	} 

	function checkUploadedFile() {
		global $database, $key;

		$filename = array();
		$flag = false;
		$mess = array();

		$uplfilext = $this->getExt( $_FILES['importtextzip']['name'] );
		if ( $uplfilext != "zip" ) {
			$mess[] = "Wrong Format : " . $_FILES['importtextzip']['name'] . "";
		} else if ( !( $_FILES['importtextzip']['size'] == 0 ) ) {
			if ( $_FILES['importtextzip']['type'] == "application/zip" || $uplfilext == "zip" ) {
				$archive = new PclZip( $_FILES['importtextzip']['tmp_name'] );
				$zipfilename = $_FILES['importtextzip']['name'];
				$tempname = $archive->listContent();
				$nooffiles = count( $tempname );

				if ( $archive->extract( "temp_data/" ) != 0 ) {
					for ( $fno = 0; $fno < $nooffiles; $fno++ ) {
						$source_file = "temp_data/" . $tempname[$fno]["stored_filename"];
						$srcext = $this->getExt( $source_file );

						if ( !( strtolower( $srcext ) == "txt" || strtolower( $srcext ) == "text" ) ) {
							$mess[] = "Wrong Format : " . $tempname[$fno]["stored_filename"] . " (in " . $zipfilename . ")";

							@unlink( $source_file );
						} else if ( @filesize( $source_file ) == 0 ) {
							$mess[] = "Bad File : " . $tempname[$fno]["stored_filename"] . " (in " . $zipfilename . ")";

							@unlink( $source_file );
						} else {
							// here i will rename the stored file with a unique name.
							/*	$tmp_file_name = $this->getName($tempname[$fno]["stored_filename"]);
						$tgt_file_name = $tmp_file_name.".txt"; // changed to txt  after issue
						echo "<br>Correct Format: ".$source_file;
						rename($source_file,"temp_data/".$tgt_file_name);
						$filename[] = $tgt_file_name; */// blocked after issue rised
							// $title = $this->getFileNameByTitleInFile($source_file);
							// $title .= ".txt";
							// rename($source_file,"temp_data/".$title);
							// $filename[] = $title;
							$filename[] = $source_file;
						} 
					} 
				} else {
					$flag = false; // $archive->extract("temp_articles/") when return zero
				} 
			} else {
				// is file id not text or zip
				$mess[] = "Wrong Format ";
				$flag = false;
			} 
		} else {
			$mess[] = "Bad File ";
			$flag = false; // when file size is ZERO
		} 

		if ( count( $mess ) > 0 ) {
			$this->showTopOfPage();

			foreach( $mess as $msg ) {
				echo "<br>" . $msg;
			} 

			echo str_repeat( " ", 4000 );

			flush();

			echo "<br>"; 
			// echo "<tr><td align='center'><a href='amarticle.php?process=manage'><input type='button' value='OK'></a></td></tr>";
			// changes for task 93 0n 12 nov sdei
			echo "<tr><td align='center'>
		<form action='amarticle.php?process=upload&ncsb=" . $_REQUEST['ncsb'] . "&source=" . $_REQUEST['source'] . "&category=" . $_REQUEST['category'] . "&author=" . $_REQUEST['author'] . "'  method='post'><input type='submit' value='OK'></form>
		</td></tr>";

			$this->showBottomOfPage();
			exit();
		} 

		if ( count( $filename ) == 0 && $flag == false ) {
			return false;
		} else {
			foreach( $filename as $f ) {
				mb_detect_order( 'UTF-8,ISO-8859-1' );

				$handle = fopen( $f, "r" );
				$content = fread( $handle, filesize( $f ) );
				$content = strip_tags( $content );

				$posfix = 'UTF-8' == mb_detect_encoding( $content ) ? 'u' : '';

				$regex = "([^0-9 a-zA-Z`~’“”!@#$%\^&\*\(\)-_=\+\|\\\{\}\[\]:;\"'<>\?/\n\r\t]+)" . $posfix;
				$regextit = "([^0-9 a-zA-Z’“”]+)" . $posfix;

				$str1 = preg_replace( $regex, "" , $content );

				$data1 = explode( "\n", $str1 );

				$tit = trim( $data1[0] );

				$tit = strip_tags( $tit );
				$tit = preg_replace( $regextit, "" , $tit );

				$aut = str_replace( "by", "", $data1[1] );
				$au = str_replace( "from", "", $aut );
				$author = trim( $au );

				if ( $author == "" )$author = $_POST['author'];
				$body = "";
				for( $i = 2;$i < count( $data1 );$i++ ) {
					$body .= $data1[$i];
				} 
				$pos = strpos( $body, " ", 200 );
				$summ = substr( $body, 0, $pos );

				$sum = $summ . ".....";

				$tit = preg_replace( $regextit, "" , $tit );

				$tit = str_replace( "–", '-', $tit );
				$tit = str_replace( "“", '"', $tit );
				$tit = str_replace( "”", '"', $tit );
				$tit = str_replace( "’", "'", $tit );

				$author = str_replace( "“", '"', $author );
				$author = str_replace( "–", '-', $author );
				$author = str_replace( "”", '"', $author );
				$author = str_replace( "’", "'", $author );

				$sum = str_replace( "“", '"', $sum );
				$sum = str_replace( "–", '-', $sum );
				$sum = str_replace( "”", '"', $sum );
				$sum = str_replace( "’", "'", $sum );

				$body = str_replace( "“", '"', $body );
				$body = str_replace( "–", '-', $body );
				$body = str_replace( "”", '"', $body );
				$body = str_replace( "’", "'", $body );

				$sql = "INSERT INTO `" . TABLE_PREFIX . "am_article` ( `category_id` , `title` , `author`, `summary` , `body` , `source`, `status`,`date`,`user_id`)
					VALUES ("

				 . "'" . $database->GetSQLValueString( addslashes( $_POST["category"] ), "text" ) . "',"
				 . "'" . $database->GetSQLValueString( addslashes( $tit ), "text" ) . "',"
				 . "'" . $database->GetSQLValueString( addslashes( $author ), "text" ) . "',"
				 . "'" . $database->GetSQLValueString( addslashes( $sum ), "text" ) . "',"
				 . "'" . $database->GetSQLValueString( addslashes( $body ), "text" ) . "',"
				 . "'" . $database->GetSQLValueString( addslashes( $_POST["source"] ), "text" ) . "',"
				 . "'" . $database->GetSQLValueString( addslashes( $_POST["status"] ), "text" ) . "',"
				 . "'" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "',"
				 . "'" . $database->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')"; 
				// $database->insert($sql);
				// for task 11 on 27 nov
				// $database->insert($sql);
				if ( $_REQUEST['ncsb'] == 'yes' )
					$file_name[] = $database->insert( $sql );
				else
					$database->insert( $sql );

				$sql1 = "INSERT INTO `" . TABLE_PREFIX . "am_article_snippets` ( `category_id` , `title` , `author`, `summary` , `source`, `status`,`date`,`user_id`)
				VALUES ("

				 . "'" . $database->GetSQLValueString( $_POST["category"], "text" ) . "',"
				 . "'" . $database->GetSQLValueString( $tit, "text" ) . "',"
				 . "'" . $database->GetSQLValueString( $author, "text" ) . "',"
				 . "'" . $database->GetSQLValueString( $sum, "text" ) . "',"
				 . "'" . $database->GetSQLValueString( $_POST["source"], "text" ) . "',"
				 . "'" . $database->GetSQLValueString( $_POST["status"], "text" ) . "',"
				 . "'" . $database->GetSQLValueString( date( 'Y-m-d' ), "date" ) . "',"
				 . "'" . $database->GetSQLValueString( $_SESSION[SESSION_PREFIX . 'sessionuserid'], "int" ) . "')";

				$database->insert( $sql1 );

				$key->keywordgenerator();
			} 
		} 
		// return true;
		// for task11 on 27 nov
		if ( $_REQUEST['ncsb'] == 'yes' )
			return $file_name;
		else
			return true;
	} 

	function getFileNameByTitleInFile( $source_file ) {
		$matter = @file( $source_file );

		$title = "";

		for ( $i = 0;$i < count( $matter );$i++ ) {
			if ( strlen( trim( $title ) ) == 0 ) {
				$title = str_replace( "\n", "", $matter[$i] );

				$title = trim( str_replace( "\r", "", $title ) );
			} else if ( strlen( $title ) > 0 ) {
				$title = str_replace( " ", "-", $title );

				berak;
			} 
		} 

		return $title;
	} 

	function getExt( $file ) {
		return strtolower( strrev( substr( strrev( $file ), 0, strpos( strrev( $file ), "." ) ) ) );
	} 

	function showTopOfPage() {
		require_once( "header.php" );

		echo "<title>";

		echo SITE_TITLE;

		echo "</title>";
		// echo '<link href="stylesheets/style.css" rel="stylesheet" type="text/css">';
		$donotshowmwnu = "yes";

		require_once( "top.php" );

		require_once( "left.php" );

		echo '
	<br>
	<table width = "100%" align = "center" class = "messwindow">
	<tr>
	<td width = "100%" align = "left">';
	} 

	function showBottomOfPage() {
		echo '
	</td>
	</tr>
	</table>
	<br>';
		require_once( "right.php" );
		require_once( "bottom.php" );
	} 
} 
?>