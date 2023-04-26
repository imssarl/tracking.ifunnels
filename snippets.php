<?php
session_start();
require_once( "config/config.php" );
require_once( "classes/database.class.php" );
require_once( "classes/settings.class.php" );
require_once( "classes/sites.class.php" );
require_once( "classes/common.class.php" );
require_once( "classes/sites.steps.class.php" );
require_once( "classes/pagination.class.php" );
require_once( "classes/search.class.php" );
require_once( "classes/articles.class.php" );
require_once( "classes/projects.class.php" );
require_once( "classes/pclzip.lib.php" );
require_once( "classes/feed.class.php" );
require_once( "classes/pinger.php" );
require_once( "classes/snippets.class.php" );
require_once( "classes/en_decode.class.php" );
$pf = new PingFeed();
$feed = new xmlParser();
$steps = new Steps();
$prj = new Projects();
$art = new Article();
$encode = new encode_decode();
$settings = new Settings();
$common = new Common();
$settings->checkSession();
$ms_db = new Database();
$ms_db->openDB();
$snippets = new Snippet();
$pg = new PSF_Pagination();
$sc = new psf_Search();
// ////////////////////////////////
// ////Encryption Variables///////
// ///////////////////////////////
if ( isset( $_POST['process'] ) ) {
	$process = $_POST['process'];
} else if ( isset( $_GET['process'] ) ) {
	$process = $_GET['process'];
} else {
	$process = "manage";
} 

if ( isset( $_GET["page"] ) ) {
	$page = $_GET["page"];
} else if ( isset( $_POST["page"] ) ) {
	$page = $_POST["page"];
} else {
	$page = 1;
} 

if ( isset( $_POST['msg'] ) ) {
	$msg = $_POST['msg'];
} else if ( isset( $_GET['msg'] ) ) {
	$msg = $_GET['msg'];
} else {
	$msg = "";
} 

if ( isset( $_POST["inputmode"] ) && $_POST["inputmode"] == "text" ) {
	$_POST["link"] = $_POST["textlink"];
} else if ( isset( $_POST["inputmode"] ) && $_POST["inputmode"] == "html" ) {
	$_POST["link"] = $_POST["htmllink"];
} 

if ( isset( $_POST["snippetform"] ) && $_POST["snippetform"] == "yes" ) {
	if ( $process == "new" ) {
		$exist = $snippets->checkTitleExist( $_POST["title"] );
		if ( !$exist ) {
			$id = $snippets->insertSnippet();
			if ( $id ) {
				header( "location: snippets.php?process=confirmaddpart&id=$id&block_id=$id&msg=Snippet has been stored" );
				exit;
			} else {
				$snippet_data = $common->getPostData();
				$msg = "Error in saving data";
			} 
		} else {
			$snippet_data = $common->getPostData();
			$msg = "Snippet title already exist";
		} 
	} else if ( $process == "edit" ) {
		$exist = $snippets->checkTitleExist( $_POST["title"], $_POST["id"] );
		if ( !$exist ) {
			$ok = $snippets->updateSnippet( $_POST["id"] );
			header( "location: snippets.php?process=manage&msg=Snippet has been modified" );
			exit;
		} else {
			$snippet_data = $common->getPostData();
			$msg = "Snippet title already exist";
		} 
	} 
} else if ( isset( $_POST["snippetpartform"] ) && $_POST["snippetpartform"] == "yes" ) {
	if ( $process == "addsnippetpart" ) {
		if ( $_POST["link"] == '' ) {
			$msg = 'Please enter conent';
		}
		$id = $snippets->insertSnippetPart();
		if ( $id ) {
			header( "location: snippets.php?process=confirmaddpart&id=" . $_POST["snippet_id"] . "&block_id=" . $_POST["snippet_id"] . "&page=$page&msg=Snippet Part has been added" );
			exit;
		} else {
			header( "location: snippets.php?process=manage&msg=Adding part is failed due to some technical reasons" );
			exit;
		} 
	} else if ( $process == "editsnippetpart" ) {
		$ok = $snippets->updateSnippetPart( $_POST["id"] );
		header( "location: snippets.php?process=manage&page=" . $_POST["page"] . "&block_id=" . $_POST["block_id"] . "&msg=Snippet Part has been modified" );
		exit;
	} 
} else if ( $process == "edit" ) {
	$snippet_data = $snippets->getSnippetById( $_GET["id"] );
} else if ( $process == "editsnippetpart" ) {
	$snippet_part_data = $snippets->getSnippetPartById( $_GET["id"] ); 
	// $snippet_part_data["link"] = $snippets->unsetTrackURLForLink($snippet_part_data["link"], $_GET["id"]);
} else if ( $process == "delete" ) {
	$ok = $snippets->deleteSnippet( $_GET["id"] );
	header( "location: snippets.php?process=manage&page=" . $_GET["page"] . "&msg=Snippet has been deleted" );
	exit;
} else if ( $process == "deletesnippetpart" ) {
	$ok = $snippets->deleteSnippetPart( $_GET["id"] );
	header( "location: snippets.php?process=manage&page=" . $_GET["page"] . "&block_id=" . $_GET["block_id"] . "&msg=Snippet Part has been deleted" );
	exit;
} else if ( $process == "resetpart" ) {
	$ok = $snippets->resetSnippetPart( $_GET["id"] );
	header( "location: snippets.php?process=manage&page=" . $_GET["page"] . "&block_id=" . $_GET["block_id"] . "&msg=Snippet has been reset" );
	exit;
} else if ( $process == "duplicate" ) {
	$sid = $snippets->createDuplicateSnippet( $_GET["id"] );
	header( "location: snippets.php?process=manage&page=" . $_GET["page"] . "&msg=Duplicate snippet has been created" );
	exit;
} else if ( $process == "duplicatepart" ) {
	$sid = $snippets->createDuplicateSnippetPart( $_GET["id"] );
	header( "location: snippets.php?process=manage&page=" . $_GET["page"] . "&block_id=" . $_GET["block_id"] . "&msg=Duplicate snippet part has been created" );
	exit;
} elseif ( in_array( $process, array( 'settopouse', 'settoplay' ) ) ) {
	$snippet_rs = $ms_db->getRS( 'UPDATE hct_snippet_parts SET pause=1-pause WHERE id="' . $_GET['id'] . '"' );
	header( "location: snippets.php?process=manage&page=" . $_GET["page"] . "&block_id=" . $_GET["block_id"] . "&msg=Pause/Play snippet has been successful" );
	exit;
} 

if ( $process == "manage" ) {
	$sql = "select count(*) from `" . TABLE_PREFIX . "snippets` where user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'];
	$totalrecords = $ms_db->getDataSingleRecord( $sql );
	if ( $totalrecords > 0 ) {
		$pg->setPagination( $totalrecords );
	} else {
		$pg->startpos = 0;
	} 
	$order_sql = $sc->getOrderSql( array( "s.id", "title", "description", "created_date", "noofparts", "is_itm_enabled", "noofimpression", "noofclicks" ), "s.id" );
	$sql = "select s.*,count(distinct p.id) as noofparts, sum(p.impressions) as noofimpression, sum(p.clicks) as noofclicks
	from `" . TABLE_PREFIX . "snippets` s
	LEFT JOIN `" . TABLE_PREFIX . "snippet_parts` p ON s.id = p.snippet_id
	where user_id=" . $_SESSION[SESSION_PREFIX . 'sessionuserid'] . "
	GROUP BY s.id
	 " . $order_sql . ", s.title LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	$snippet_rs = $ms_db->getRS( $sql );
}
?>
<?php require_once( "header.php" );?>
<title><?php echo SITE_TITLE;?></title>
<head>
<script language="javascript">
function showdiv(no) {
	var ctrl = document.getElementById("part"+no);
	if (ctrl.style.display=='block') {
		ctrl.style.display='none';
		document.getElementById("noofparts"+no).title = "Click Here To Expand";
//		document.getElementById('sign'+no).innerHTML = '+';
		if (LastRowColor=="") {
			document.getElementById('row'+no).className = document.getElementById('row'+no).bgColor;
		} else {
			document.getElementById('row'+no).className = LastRowColor ;
		}
	} else {
		ctrl.style.display='block';
//		document.getElementById('sign'+no).innerHTML = '-';
		document.getElementById("noofparts"+no).title = "Click Here To Collapse";
		LastRowColor = document.getElementById('row'+no).className;
		document.getElementById('row'+no).className = "tablematter3" ;
	}
}

function chkMainForm(frm) {
	var mss = "";
	if (frm.title.value=="") {
		mss += "Please enter snippet title\n";
	}
	if (frm.description.value=="") {
		mss += "Please enter snippet description\n";
	}
	if (!frm.is_itm_enabled[0].checked && !frm.is_itm_enabled[1].checked) {
		mss += "Please select Intelligent link tracking status\n";
	}
	if (mss.length>0) {
		alert(mss);
		return false;
	}
	return true;
}

function disableMyButtons(form) {
	form.nobutton.disabled = true;
	form.yesbutton.disabled = true;
}

function showcode(id) {
	openwindow= window.open ("snippetsgetcode.php?id="+id, "GETCODE", "'status=0,scrollbars=1',width=650,height=250,resizable=1");
	openwindow.moveTo(50,50);
}

function showpart(id) {
	openwindow= window.open ("snippetsshow.php?partidfortest="+id, "SnippetPart", "'status=0,scrollbars=1',width=650,height=300,resizable=1");
	openwindow.moveTo(50,50);
}
</script>
</head>












<?php require_once( "top.php" );?>
<?php require_once( "left.php" );?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td align="left">
<?php
$bcrumbhome = '<a class="general" href="index.php">Home</a>';
if ( $process == "manage" ) {
	$breadmanage = " >> Manage Snippets";
} else {
	$breadmanage = ' >> <a class="general" href="snippets.php">Manage Snippets</a>';
} 
if ( $process == "new" ) {
	$breadprocess = ' >> New Snippet';
} else if ( $process == "edit" ) {
	$breadprocess = ' >> Edit Snippet';
} else if ( $process == "confirmdelete" ) {
	$breadprocess = ' >> Delete Snippet';
} else if ( $process == "confirmduplicate" ) {
	$breadprocess = ' >> Duplicate Snippet';
} else if ( $process == "addsnippetpart" ) {
	$breadprocess = ' >> Add Snippet Part';
} else if ( $process == "editsnippetpart" ) {
	$breadprocess = ' >> Edit Snippet Part';
} else if ( $process == "confirmdeletepart" ) {
	$breadprocess = ' >> Delete Snippet Part';
} else if ( $process == "confirmduplicatepart" ) {
	$breadprocess = ' >> Duplicate Snippet Part';
} else if ( $process == "confirmresetpart" ) {
	$breadprocess = ' >> Reset Snippet Part';
} else if ( $process == "confirmsettopouse" ) {
	$breadprocess = ' >> Pause Snippet Part';
} else if ( $process == "confirmsettoplay" ) {
	$breadprocess = ' >> Play Snippet Part';
} 
echo $bcrumbhome . $breadmanage . $breadprocess;
?>
	<br>
	</td>
</tr>
<tr>
	<td  align="center"> <?php echo $msg ?></td>
</tr>
<tr>
	<td align="center"> <!-- mail block starts -->



<?php if ( $process == "new" || $process == "edit" ) {?>
	<br>
	    <table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
<form name="newsnippet" method="post" action="snippets.php" onSubmit="return chkMainForm(this)">
          <tr>
            <td align="left" width="40%" class="heading" colspan="2"><?php echo ( $process == "new" ) ? "New" : "Edit";?> Snippet</td>
			</tr>
			<br>
          <tr>
            <td align="right">Title : </td>
            <td align="left">
              <input name="title" type="text" id="title" value="<?php echo $snippet_data['title'] ?>" size="50" maxlength="255"  >			</td>
          </tr>
          <tr align="center">
            <td align="right" valign="top">Description : </td>
            <td align="left">
              <textarea name="description" cols="70" rows="12" id="description"  ><?php echo $snippet_data['description'] ?></textarea>
			</td>
          </tr>
          <tr align="center">
            <td align="right">Intelligent link tracking : </td>
            <td align="left">
				<input type="radio" name="is_itm_enabled" id="modeo" value="Y" onClick="pmode(this)" <?php if ( $snippet_data["is_itm_enabled"] == "Y" ) echo "checked";?>>
				Enabled
				<input type="radio" name="is_itm_enabled" id="moder" value="N" onClick="pmode(this)" <?php if ( $snippet_data["is_itm_enabled"] == "N" ) echo "checked";?>>
				Disabled
			</td>
          </tr>
           <tr>
		   <td colspan="2" align="center" class="heading">
              <div align="center">
               <input type="submit" name="Submit" value="Save">
              </div>
                <input type="hidden" name="process" value="<?php echo $process ?>">
                <input type="hidden" name="id" value="<?php echo $snippet_data["id"] ?>">
                <input type="hidden" name="snippetform" value="yes">				
                <input type="hidden" name="created_date" value="<?php echo date( "Y-m-d" ) ?>">			
			</td>
            </tr>
             </form>
        </table> 	






<?php } else if ( $process == "addsnippetpart" || $process == "editsnippetpart" ) {?>
<script type="text/javascript" src="/skin/_js/mootools.js"></script>
<script type="text/javascript" src="/skin/_js/mootools_more.js"></script>
<link href="/skin/_js/multibox/multibox.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]><link rel="stylesheet" href="/skin/_js/multibox/multiboxie6.css" type="text/css" media="all" /><![endif]-->
<script language="javascript" src="/skin/_js/multibox/overlay.js"></script>
<script language="javascript" src="/skin/_js/multibox/multibox.js"></script>
<script language="javascript" src="/skin/_js/fckeditor/fckeditor.js"></script>
<table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
<form name="snippetpart" method="post" action="snippets.php">
	<tr>
		<td align="center"> <?php echo $msg ?><br/></td>
	</tr>
	<tr>
		<td align="left" width="100%" class="heading"><?php echo ( $process == "new" ) ? "New" : "Edit"; ?> Snippet Part</td>
	</tr>
	<tr>
		<td align="cebter">Enter Contents: 
			<input type="radio" name="inputmode" value="text" <?php if ($snippet_part_data['inputmode'] == 'text' || !$snippet_part_data['inputmode']):?> checked="checked" <?php endif;?> id='text' ><label for="text">TEXT</label>
			<input type="radio" name="inputmode" value="html" <?php if ($snippet_part_data['inputmode'] == 'html' ):?> checked="checked" <?php endif;?> id='html'><label for="html">HTML</label>
			<div style="margin-left:10px;"><a href="/video-manager/multibox/" class="mb" 
				title="Import from Video Manager" rel="width:800,height:500" id="mb">Import from Video Manager</a></div>
		</td>
	</tr>
	<tr>
		<td align="center">
			<div id="texteditor" <?php if ($snippet_part_data['inputmode'] == 'html'):?>  style="display:none;" <?php endif;?>>
				<textarea id="textlink" name="textlink" rows="13" style=" width:99%"  ><?php echo $snippet_part_data["link"];?></textarea>
			</div>
			<div id="htmleditor" <?php if ($snippet_part_data['inputmode'] == 'text' || !$snippet_part_data['inputmode']):?>  style="display:none" <?php endif;?>>
				<textarea id="htmllink" name="htmllink" rows="13" style="width:99%"><?php echo $snippet_part_data["link"];?></textarea>
			</div>
		</td>
	</tr>
	<input type="hidden" name="reset_css" value="0" />
	<tr>
		<td><input type="checkbox" value="1" name="reset_css" <?php if($snippet_part_data['reset_css']):?>checked='1'<?php endif;?> id="reset_css"> Reset CSS styles</td>
	</tr>
	<input type="hidden" name="old_css" value="<?php echo $snippet_part_data['reset_css'];?>"/>
	<tr>
		<td  align="center" class="heading">
			<div align="center"><input type="submit" name="Submit" value="Save"></div>
			<input type="hidden" name="process" value="<?php echo $process ?>">
			<input type="hidden" name="snippet_id" value="<?php echo $_GET["snippet_id"] ?>">
			<input type="hidden" name="id" value="<?php if ( isset( $snippet_part_data["id"] ) ) echo $snippet_part_data["id"];?>">
			<input type="hidden" name="block_id" value="<?php echo $_GET["block_id"] ?>">
			<input type="hidden" name="page" value="<?php echo $_GET["page"] ?>">
			<input type="hidden" name="snippetpartform" value="yes">
			<input type="hidden" name="created_date" value="<?php echo date( "Y-m-d" ) ?>">
		</td>
	</tr>
</form>
</table>

<script type="text/javascript">
var multibox=oFCKeditor={};
var mode;
window.addEvent('domready', function() {
	oFCKeditor=new FCKeditor( 'htmllink' ) ;
	oFCKeditor.Width = '100%';
	oFCKeditor.Height = '350';
	oFCKeditor.ReplaceTextarea();
	$each(document.snippetpart.inputmode,function(el){
		el.addEvent('click',function(e){
			if ( mode==this.value ) {
				return;
			}
			$('texteditor').setStyle('display',($('texteditor').getStyle('display')=='none'? 'block':'none'));
			$('htmleditor').setStyle('display',($('htmleditor').getStyle('display')=='none'? 'block':'none'));
			$('mb').setStyle('display',($('mb').getStyle('display')=='none'? 'block':'none'));
			mode=this.value;
		});
		if ( el.checked ) {
			mode=el.value;
		}
	});
	multibox = new multiBox({
		mbClass: '.mb',
		container: $(document.body),
		useOverlay: true,
	});
});

var placeParam={};
var placeDo=function() {
	if ( typeof(placeParam.video_title)!='undefined' ) {
		$('textlink').value+=($('textlink').value>'')? '\n'+placeParam.video_title:placeParam.video_title;
	}
	if ( typeof(placeParam.url_of_video)!='undefined' ) {
		$('textlink').value+=($('textlink').value>'')? '\n'+placeParam.url_of_video:placeParam.url_of_video;
	}
	if ( typeof(placeParam.embed_code)!='undefined' ) {
		$('textlink').value+=($('textlink').value>'')? '\n'+placeParam.embed_code:placeParam.embed_code;
	}
	placeParam={};
}

document.snippetpart.addEvent('submit', function(e) {
	if ( (this.inputmode[0].checked&&this.textlink.value=='')||
		(this.inputmode[1].checked&&!FCKeditorAPI.GetInstance('htmllink').GetData(false)) ) {
		alert('Please enter snippet part contents');
		e.stop();
	}
});
</script>





<?php } else if ( substr( $process, 0, 7 ) == "confirm" ) {?>
			<table class="messagebox" height="300" align="center" border="0">
			<tr align="center">
			<td align="center" valign="middle">
			<form method="get" action="" onSubmit="disableMyButtons(this)">
			<?php if ( $process == "confirmaddpart" ) {?>
			Do you want to add snippet part?<br>
			<input type="hidden" name="process" value="addsnippetpart">
			<?php } else if ( $process == "confirmdeletepart" ) {?>
			Are you sure to delete this  snippet part?<br>
			<input type="hidden" name="process" value="deletesnippetpart">
			<?php } else if ( $process == "confirmdelete" ) {?>
			Are you sure to delete this  snippet?<br>
			<input type="hidden" name="process" value="delete">
			<?php } else if ( $process == "confirmresetpart" ) {?>
			Are you sure to reset this  snippet?<br>
			<input type="hidden" name="process" value="resetpart">
			<?php } else if ( $process == "confirmsettopouse" ) {?>
			Are you sure to pause this  snippet part?<br>
			<input type="hidden" name="process" value="settopouse">
			<?php } else if ( $process == "confirmsettoplay" ) {?>
			Are you sure to play this  snippet part?<br>
			<input type="hidden" name="process" value="settoplay">
			<?php } else if ( $process == "confirmduplicatepart" ) {?>
			Are you sure to create a duplicate snippet part?<br>
			<input type="hidden" name="process" value="duplicatepart">
			<?php } else if ( $process == "confirmduplicate" ) {?>
			Are you sure to create a duplicate snippet?<br>
			<input type="hidden" name="process" value="duplicate">
			<?php } ?>
			<input type="hidden" name="confirm" value="yes">
			<input type="hidden" name="snippet_id" value="<?php echo $_GET["id"] ?>">
			<input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
			<input type="hidden" name="block_id" value="<?php echo $_GET["block_id"] ?>">			
			<input type="hidden" name="page" value="<?php echo $page ?>">
			<input name="yesbutton" type="submit" value="Yes">&nbsp;
			<input name="nobutton" type="button" value="No" onClick="javascript: location='snippets.php?msg=Operation cencelled&page=<?php echo $page ?>&block_id=<?php echo $_GET["block_id"] ?>'">
			</form>
			</td>
			</tr>
			</table>








<?php } else if ( $process == "manage" ) {?>
<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
	<tr><td valign = "top" align="center" class="heading"> <a  class="menu" href = "?process=new">Create new snippets</a> </td></tr>
	<tr><td colspan="12"><?php $pg->showPagination();?></td></tr>
</table>
<br>
<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
<tr class="tableheading">
	<th><a title = "Sort" class = "menu" href="?sort=id">Snippet #</a></th>
	<th><a title = "Sort" class = "menu" href="?sort=title">Title</a></th>
	<th><a title = "Sort" class = "menu" href="?sort=description">Description</a></th>
	<th><a title = "Sort" class = "menu" href="?sort=noofparts"># of parts</a></td>
	<th><a title = "Sort" class = "menu" href="?sort=created_date">Date created</a></td>
	<th><a title = "Sort" class = "menu" href="?sort=is_itm_enabled">Intelligent<br>tracking management<br> enabled</a></td>
	<th><a title = "Sort" class = "menu" href="?sort=noofimpression"># of impressions</a></td>
	<th><a title = "Sort" class = "menu" href="?sort=noofclicks"># of clicks</a></td>
	<th></td>
	<th></td>
	<th></td>
	<th></td>
	<th></td>
</tr>
<?php
	if ( $snippet_rs ) {
		$tblmat = 0;
		while( $snippet = $ms_db->getNextRow( $snippet_rs ) ) {
			if ( $snippet["is_itm_enabled"] == "Y" ) $itm = "Yes";
			else if ( $snippet["is_itm_enabled"] == "N" ) $itm = "No";
			$id = $snippet["id"];
			?>
<tr id="row<?php echo $id ?>"  class='<?php echo ( $tblmat++ % 2 ) ? "tablematter1" : "tablematter2" ?>' >
		<td align="center"><?php echo $id ?></td>
		<td align="left"><?php echo $snippet["title"] ?></td>
		<td align="left" title="" ><?php echo $snippet["description"] ?></td>
		<td align="center" class="general" id="noofparts<?php echo $id ?>" <?php if ( $snippet["noofparts"] > 0 || ( isset( $_GET["block_id"] ) && $_GET["block_id"] == $id ) )
				echo 'title="Click here to expand" onclick="showdiv(\'' . $id . '\')" style="cursor:pointer"' ?> ><?php echo $snippet["noofparts"] ?></td>
		<td align="center"><?php echo $snippet["created_date"] ?></td>
		<td align="center"><?php echo $itm ?></td>		
		<td align="center" title="View Details"><?php echo $snippet["noofimpression"];?></td>
		<td align="center">
		<?php if ( $snippet["noofclicks"] > 0 ) {?>
		<a title="View Details" class="general" href='snippet_summary.php?id=<?php echo $id ?>'>
		<?php } ?>
		<?php echo $snippet["noofclicks"];
			if ( $snippet["noofclicks"] > 0 ) {
				echo "</a>";
			} 
			?>
		</td>
		<td>
		<a href="?process=addsnippetpart&snippet_id=<?php echo $id ?>&block_id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/add.png" border="0" title="Add snippet part(s)">
		</a>
		</td>
		<td>
		<a href="?process=edit&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">
		</a>
		</td>
		<td>
		<a href="?process=confirmdelete&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
		</a>
		</td>
		<td>
		<a href="?process=confirmduplicate&id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/duplicate.png" border="0" title="Duplicate" style="cursor:pointer">
		</a>
		</td>
		<td>
		<?php if ( $snippet["noofparts"] > 0 ) {?>
		<img src="images/getcode.gif" border="0" title="Get code" style="cursor:pointer" onclick="showcode('<?php echo $encode->encode( $id )?>')">
		<?php } else {?>
		<img src="images/denied.png" border="0" title="Please add a part before generate code"
		<?php } ?>
		</td>
	</tr>
	<tr>
	<TD colspan="12">
	<div id="part<?php echo $id ?>" style="display:none;">
		<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center">
		<tr >
		<th>Part #</th>
		<th>View link</th>
		<th>Date created</th>
		<th># of impressions</th>
		<th># of links</th>
		<th># of clicks</th>
		<th>C.T.R.</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
	<th>&nbsp;</th>
		</tr>
<?php
			$part_rs = $snippets->getPartBySnippetId( $id );
			$tblmat2 = $tblmat;
			if ( $part_rs ) {
				$partno = 1;
				while( $part = $ms_db->getNextRow( $part_rs ) ) {
					$pid = $part["id"];
					$contstr = html_entity_decode( $part["link"] ); 
?>
		<tr class='<?php echo ( $tblmat2++ % 2 ) ? "tablematter1" : "tablematter2" ?>' >
		<td align="center" valign="top">
		<div onclick="showpart(<?php echo $part["id"] ?>)" style="cursor:pointer" class="general" title="View Part">
		<?php echo $part["id"] ?>
		</div>
		</td>		
		<td align="left"><?php
		if ( strlen( $part["link"] ) > 200 ) $cont = "....";
		else $cont = "";
		echo substr( $part["link"], 0, 200 ) . $cont;
		?></td>
		<td align="center" valign="top"><?php echo $part["created_date"] ?></td>
		<td align="center" valign="top"><?php echo $part["impressions"] ?></td>
		<td align="center" valign="top"><?php echo substr_count( $contstr , '</a>' ) ?></td>
		<td align="center" valign="top">
		<?php if ( $part["clicks"] > 0 ) {?>
		<a title="View Details" class ="general" href='snippet_summary.php?pid=<?php echo $pid ?>'>
		<?php } ?>
		<?php echo $part["clicks"] ;
		if ( $part["clicks"] > 0 ) {
			echo "</a>";
		} 
		?>
		</td>
		<td align="center" valign="top"><?php
		if ( $part["impressions"] > 0 )
			echo round( ( $part["clicks"] / $part["impressions"] ) * 100, 0 );
		else
			echo "0";
		?></td>
		<td valign="top">
		<a href="?process=editsnippetpart&id=<?php echo $pid?>&block_id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">
		</a>
		</td>
		<td valign="top">
		<a href="?process=confirmdeletepart&id=<?php echo $pid?>&block_id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
		</a>
		</td>
		<td valign="top">
		<a href="?process=confirmduplicatepart&id=<?php echo $pid?>&block_id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/duplicate.png" border="0" title="Duplicate" style="cursor:pointer">
		</a>
		</td>
		<td valign="top">
		<a href="?process=confirmresetpart&id=<?php echo $pid?>&block_id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/resume.gif" border="0" title="Reset" style="cursor:pointer">
		</a>
		</td>
		<td valign="top">
<?php if ( empty( $part['pause'] ) ) { // added by Rodion Konnov 10.06.2009 ?>
		<a href="?process=confirmsettopouse&id=<?php echo $pid?>&block_id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/pause.png" border="0" title="Set to pause" style="cursor:pointer">
<?php } else { ?>
		<a href="?process=confirmsettoplay&id=<?php echo $pid?>&block_id=<?php echo $id?>&page=<?php echo $page?>">
		<img src="images/play.png" border="0" title="Set to play" style="cursor:pointer">
<?php } ?>
		</a>
		</td>
		</tr>
<?php } ?>
<?php } else { ?>
		<tr class='<?php echo ( $tblmat2++ % 2 ) ? "tablematter1" : "tablematter2" ?>' >
		<td colspan="11" align="center">No Snnipet Part Found</td>
		</tr>	
<?php } ?>
		<tr class="subtableheading" >
		<td colspan="11" height="2"></td>
		</tr>	
	</table>
	</div>
	</TD>
	</tr>
<?php }
	} else {
		echo "<tr><td align='center' colspan='12'>No Snippet Found</td></tr>";
	}?>
<tr ><td align='center' colspan='13'  class="heading">&nbsp;</td></tr>	  
</table>
<?php
	if ( isset( $_GET["block_id"] ) && $_GET["block_id"] > 0 ) {
		echo '<script language="javascript">showdiv("' . $_GET["block_id"] . '")</script>';
	} 
} // end manage
?>		
	</td>
</tr>
<tr><TD><br></TD></tr>
	</table>	
<?php require_once( "right.php" );?>
<?php require_once( "bottom.php" );?>