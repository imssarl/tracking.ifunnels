<?php
session_start();
require_once( "config/config.php" );
require_once( "classes/database.class.php" );
require_once( "classes/settings.class.php" );
require_once( "classes/common.class.php" );
require_once( "classes/pagination.class.php" );
require_once( "classes/search.class.php" );
require_once( "classes/affiliate.class.php" );
require_once( "classes/tracking.class.php" );
require_once( "classes/campaign.class.php" );
$affiliate = new affiliate();
$track = new track();
$campaign = new campaign();
$settings = new Settings();
$common = new Common();
$settings->checkSession();
$ms_db = new Database();
$ms_db->openDB();
$pg = new PSF_Pagination();
$sc = new psf_Search(); 
// ////////////////////////////////
if ( isset( $_POST['process'] ) ) {
	$process = $_POST['process'];
} else if ( isset( $_GET['process'] ) ) {
	$process = $_GET['process'];
} else {
	$process = "manage";
} 
if ( isset( $_GET['aid'] ) ) {
	$aid = $_GET['aid'];
} else if ( isset( $_POST['aid'] ) ) {
	$aid = $_POST['aid'];
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
if ( isset( $_GET["apid"] ) && $_GET["apid"] > 0 ) {
	$_SESSION["apid"] = $_GET["apid"];
	$_SESSION["reftpu"] = $_SERVER['HTTP_REFERER'];
} 
if ( isset( $_SESSION["apid"] ) && $_SESSION["apid"] > 0 )
	$apid = $_SESSION["apid"];
else
	$apid = 0;
if ( isset( $_POST["trackingform"] ) && $_POST["trackingform"] == "yes" ) {
	$addata = $campaign->getAdById( $aid );
	$merchantlink = $affiliate->getMerchantLink( $addata["affiliate_network"], $addata["merchant_link"] );
	$filename = $_POST["creating_page"];
	$createdpage = $track->createTrackingPage( $aid, $addata["ad_env"], $merchantlink );
	if ( $createdpage ) {
		if ( isset( $_POST["type"] ) ) $sitetype = $_POST["type"];
		else $sitetype = 0;
		if ( $sitetype == 4 ) {
			/*As per mail:
                i- append a code at the end of the filename: anatomy-n45ve.php (code is:
                n45ve)
                ii- AND we create the pages on another of our domain (qjmp.com) Then
                all pages will be something like qjmp.com/track-n45ve.php
                (qjmp is  neutral and good for tracking pages)
                */
			$createdpage = $createdpage;
			$destinationpage = "public_html/trackingpages/" . str_replace( ".php", "", $filename ) . "-" . $aid . "_" . substr( md5( rand() * time() ), 0, 4 ) . ".php"; 
			// set up basic connection
			$conn_id = ftp_connect( "qjmp.com" ); 
			// login with username and password
			$login_result = ftp_login( $conn_id, "qjmp", "cj9Bjkf;" );
			@ftp_pasv( $conn_id, true );
			// upload a file
			if ( ftp_put( $conn_id, $destinationpage, $createdpage, FTP_ASCII ) ) {
				$track->insertTrackPageDetails( $aid, 0, "/home/qjmp/" . $destinationpage, "L" );
				header( "location: campaign.php?process=manage&msg=Tracking page $destination has ben created" );
				exit;
			} else {
				header( "location: campaign.php?process=manage&msg=Problem in creating tracking page.<br>" );
				exit;
			} 
			// close the connection
			ftp_close( $conn_id );
			/*$destination = ROOT_PATH."trackingpages/".$filename;			
                if(copy(ROOT_PATH.$createdpage,$destination))
                {			
                //@chmod( $destination, 0777 );
                $track->insertTrackPageDetails($aid, 0, ROOT_PATH.$destination,"L");
                header("location: campaign.php?process=manage&msg=Tracking page $destination has ben created");			
                exit;				
                }
                else
                {
                header("location: campaign.php?process=manage&msg=Problem in creating tracking page in trackingpages folder.<br>Please check write permissions");
                exit;				
                }
                */
		} else if ( $sitetype == 3 ) {
			$trackingpageurl = $track->uploadTrackingPageOnSite( $aid, 0, $filename, $createdpage, "R" );
			if ( $trackingpageurl ) {
				// $track->insertTrackPageDetails($aid, $site_id, $trackingpagepth);
				$st = $track->insertSite( $aid );
			} 
		} else {
			if ( isset( $_POST["site_id1"] ) && $_POST["site_id1"] != 0 )
				$site_id = $_POST["site_id1"]; 
			// else
			// $site_id =  $_POST["site_id2"];
			// 
			$trackingpageurl = $track->uploadTrackingPageOnSite( $aid, $site_id, $filename, $createdpage, "" );
		} 
		if ( substr( $trackingpageurl, 0, 6 ) === "OK::::" ) {
			$files = explode( '::::', $trackingpageurl );
			$trackingpageurl = $files[1];
			$trackingpagepth = $files[2];
			if ( $sitetype == 3 ) {
				$trackingpageurl = "File uploaded on " . $trackingpageurl . ". File path is : " . $trackingpagepth;
				$track->insertTrackPageDetails( $aid, $st, $trackingpagepth, "R" );
			} else
				$track->insertTrackPageDetails( $aid, $site_id, $trackingpagepth, "" );
			header( "location: tracking.php?process=sl&msg=Tracking page has been created&link=$trackingpageurl" );
			exit;
		} else {
			header( "location: campaign.php?process=manage&msg=$trackingpageurl" );
			exit;
		} 
	} else {
		header( "location: campaign.php?process=manage&msg=Problem in creating tracking page" );
		exit;
	} 
} 
if ( $process == "manage" ) {
	$countsql = "Select count(*)
                from `" . TABLE_PREFIX . "trackingpages`  where ad_id = $apid";
	$totalrecords = $ms_db->getDataSingleRecord( $countsql );
	$pg->setPagination( $totalrecords );
	$order_sql = $sc->getOrderSql( array( "id", "url", "remote_path", "date" ), "id" );
	$sql = "Select p.* , s.url, d.ad_name, n.campaign_name 
                from `" . TABLE_PREFIX . "trackingpages` p
                LEFT JOIN  `" . TABLE_PREFIX . "site` s On s.id = p.site_id 
                LEFT JOIN  `" . TABLE_PREFIX . "ad` d On d.id = p.ad_id 
                LEFT JOIN  `" . TABLE_PREFIX . "campaign` n On n.id = d.campaign_id 
                where  p.ad_id = $apid $order_sql LIMIT " . $pg->startpos . "," . ROWS_PER_PAGE;
	if ( $totalrecords !== false && $totalrecords > 0 ) {
		$dtl_rs = $ms_db->getRS( $sql );
	} else {
		$totalrecords = 0;
		$dtl_rs = false;
	} 
	if ( $dtl_rs )
		$dtl = $ms_db->getNextRow( $dtl_rs );
} 

?>
                <?php require_once( "header.php" );
?>
                <title>
                <?php echo SITE_TITLE;
?>
                </title>
                <script language="javascript">
                function URLEncode(id)
                {
                // The Javascript escape and unescape functions do not correspond
                // with what browsers actually do...
                var SAFECHARS = "0123456789" +					// Numeric
                "ABCDEFGHIJKLMNOPQRSTUVWXYZ" +	// Alphabetic
                "abcdefghijklmnopqrstuvwxyz" +
                "-_.!~*'()";					// RFC2396 Mark characters
                var HEX = "0123456789ABCDEF";
                var plaintext = document.getElementById(id).value;
                var encoded = "";
                for (var i = 0; i < plaintext.length; i++ ) {
                var ch = plaintext.charAt(i);
                if (ch == " ") {
                encoded += "+";				// x-www-urlencoded, rather than %20
                } else if (SAFECHARS.indexOf(ch) != -1) {
                encoded += ch;
                } else {
                var charCode = ch.charCodeAt(0);
                if (charCode > 255) {
                alert( "Unicode Character '" 
                + ch 
                + "' cannot be encoded using standard URL encoding.\n" +
                "(URL encoding only supports 8-bit characters.)\n" +
                "A space (+) will be substituted." );
                encoded += "+";
                } else {
                encoded += "%";
                encoded += HEX.charAt((charCode >> 4) & 0xF);
                encoded += HEX.charAt(charCode & 0xF);
                }
                }
                } // for
                //document.URLForm.F2.value = encoded;
                //document.URLForm.F2.select();
                return encoded;
                };
                function chkMainForm(frm)
                {
                var mss = "";
                if(valsel(frm))
                {
                if (frm.creating_page.value=="")
                mss += "Enter the name of the php page you want to create.\n";
                }
                else { return false; }
                /*		if (frm.site_name.value=="")
                {
                mss += "Enter the name of the site on which you want to upload.\n";
                }*/
                if (mss.length>0)
                {
                alert(mss);
                return false;
                }
                else
                {
                return true;
                }
                }
                function showList(type)
                {
                hideNewFTPBlock(type.value);
                // 	if (type.value=="2") {
                // 		document.getElementById("sitelist").style.display = 'block';
                // 		document.getElementById("packagelist").style.display = 'none';
                // 		document.getElementById("packagesitelist").style.display = 'none';
                // 	}
                // 	else  if (type.value=="1") {
                // 		document.getElementById("sitelist").style.display = 'none';
                // 		document.getElementById("packagelist").style.display = 'block';
                // 		document.getElementById("packagesitelist").style.display = 'block';
                // 	} else {
                // 			document.getElementById("packagesitelist").style.display = 'none';
                // 			document.getElementById("sitelist").style.display = 'none';
                // 			document.getElementById("packagelist").style.display = 'none';
                // 	}
                }
                function hideNewFTPBlock(type)
                {
                var nsf = document.getElementById("newsiteftp");
                if (type=="3") 
                nsf.className = 'show';
                else
                nsf.className = 'noshow';
                }
                function displayftpserver()
                {
                if(document.getElementById("ftpserveroption").value=="new_ftp")	{	
                document.getElementById('address').value= "";
                document.getElementById('username').value= "";
                document.getElementById('password').value= "";
                document.getElementById('address').readOnly=false;
                document.getElementById('username').readOnly=false;
                document.getElementById('password').readOnly=false;
                }
                else if(document.getElementById("ftpserveroption").value=="" || document.getElementById("ftpserveroption").value!="new_ftp")		{
                var temp = new Array();
                var str=document.getElementById("ftpserveroption").value;
                temp=str.split(' ');
                document.getElementById('address').readOnly=true;
                document.getElementById('username').readOnly=true;
                document.getElementById('password').readOnly=true;
                document.getElementById('address').value= temp[0];
                document.getElementById('username').value= temp[1];
                document.getElementById('password').value= temp[2];
                }
                }
                function delme(url)
                {
                if(confirm("Are you sure to delete this tracking page"))
                {
                location = url;
                }
                }
                function browseR()
                {
                var url = valdsbox(2);
                if (url != false)
                {
                openwindow= window.open ("browse2.php?dir=&homebox="+url, "Browse","status=0,scrollbars=1,width=400,height=100,resizable=1");
                openwindow.moveTo(50,50);
                }
                }
                function ftpurl(from)
                {
                var url = document.getElementById("url").value;
                var addr = document.getElementById("address").value;
                var user = document.getElementById("username").value;
                var pass = document.getElementById("password").value;
                which="";
                if (url.length==0 || addr.length==0 || user.length == 0 || pass.length == 0)
                {
                alert("Please enter all FTP details");
                return false;
                }
                if(from==1) return true;
                pass=URLEncode('password');
                openwindow= window.open ("browsef.php?onlyf=yes&dir=&address="+addr+"&username="+user+"&password="+pass+"&homebox="+which, "Browse","status=0,scrollbars=1,width=450,height=500,resizable=1");
                openwindow.moveTo(50,50);
                return false;
                }
                function valdsbox(from)
                {
                var typ = document.getElementById("type").value;
                var sel;
                if (typ==3)
                {
                return ftpurl(from);
                }
                else if (typ==4) {return true;}
                else
                {
                alert("Please select site type");
                return false;
                }
                //		alert(sel.value)
                if ((sel!=undefined && sel != null))
                if (sel.value>0)
                {
                url = sel[sel.selectedIndex].text;
                return url;
                }
                alert("Please select a site");
                return false;
                }
                function valsel(frm)
                {
                var msg = "";
                if (frm.type.value==4) return true;
                if (valdsbox(1)!= false)
                if (frm.remote_file.value!="" || frm.type.value != 3)
                return true;
                else alert("Please enter remote file path");
                return false;
                }
                </script>
                <?php require_once( "top.php" );
?>
                <?php require_once( "left.php" );
?>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                <tr>
                <td align="left">
                <?php
$bcrumbhome = '<a class="general" href="../index.php">Home</a>';
$campaignbc = ' >> <a class="general" href="' . $_SESSION["reftpu"] . '">Manage Campaign</a>';
if ( $process == "new" )
	$breadprocess = ' >> New Track Page';
else if ( $process == "sl" )
	$breadprocess = ' >> New Track Page >> Track Page URL';
else if ( $process == "manage" )
	$breadprocess = ' >> Manage Tracking pages';
echo $bcrumbhome . $campaignbc . $breadprocess;

?>
                <br>
                </td>
                </tr>
                <tr>
                <td  align="center"> <?php echo $msg ?></td>
                </tr>
                <tr>
                <td align="center"> <!-- mail block starts -->
                <?php if ( $process == "new" || $process == "edit" ) {
	?>
                <br>
                <form name="newtracking" method="post" action="tracking.php" onSubmit="return chkMainForm(this)">
                <table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
                <tr><td align="left" colspan="2">&nbsp;</td></tr>
                <tr>
                <td align="left" width="20%" class="heading" colspan="2"><?php echo ( $process == "new" ) ? "New" : "Edit";
	?> Tracking Page</td>
                </tr>
                <tr><td align="left" colspan="2">&nbsp;</td></tr>
                <tr >
                <td align="right" width="20%" id="sitetitle" valign="top" nowrap="nowrap">Select a site : </td>
                <td align="left">
                <div id="sitetype" style="display:block " align="left">
                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr  align="left" id="rd">
                <td align="left" width="10%">
                <select name="type" id = "type" onChange="showList(this)">
                <option selected><-- Select Type --></option>
                <option value="3" <?php if ( $tracking_data["type"] == "3" ) echo "selected";
	?>>Your own site</option>
                <option value="4" <?php if ( $tracking_data["type"] == "4" ) echo "selected";
	?>>Local Site</option>		
                </select>
                </td>
                </tr>
                </table>
                </div>
                </td>
                </tr>
                <tr><td colspan="2">
                <div id ="newsiteftp" class="noshow">
                <table width="100%"  border="0" cellspacing="2" cellpadding="0" >
                <tr>
                <td align="right" width="20%">URL of site : </td>
                <td align="left">
                <input name="url" type="text" id="url" value="<?php echo $_POST["url"] ?>" size="50" maxlength="255"   >
                </td>
                </tr>
                <tr>
                <td align="right" width="20%">Existing FTP Server  : </td>
                <td align="left" width="80%">
                <select id="ftpserveroption" onchange="displayftpserver()">
                <option value='new_ftp'>--New FTP--</option>
                <?php
	$sql = "select * from " . MTABLE_PREFIX . "ftp_details_tb where user_id='" . $_SESSION[MSESSION_PREFIX . 'sessionuserid'] . "'";
	$Ftp_rs = $ms_db->getRS( $sql );
	if ( $Ftp_rs ) {
		while( $Ftp = $ms_db->getNextRow( $Ftp_rs ) ) {
			echo "<option value='" . $Ftp["ftp_address"] . " " . $Ftp["ftp_username"] . " " . $Ftp["ftp_password"] . "'>" . $Ftp["ftp_address"] . "(" . $Ftp["ftp_username"] . ")" . "</option>";
		} 
	} 
	?>
                </select>
                </td>
                </tr>
                <tr>
                <td align="right">FTP address  :&nbsp;</td>
                <td align="left">
                <input name="ftp_address" type="text" id="address" value="<?php echo $_POST["ftp_address"] ?>" size="30" maxlength="255"  >	
                </td>
                </tr>
                <tr>
                <td align="right">FTP username  :&nbsp;</td>
                <td align="left">
                <input name="ftp_username" type="text" id="username" value="<?php echo $_POST["ftp_username"] ?>" size="30" maxlength="255"  >
                </td>
                </tr>
                <tr>
                <td align="right">FTP password  :&nbsp;</td>
                <td align="left">
                <input name="ftp_password" type="password" id="password" value="<?php echo $_POST["ftp_password"] ?>" size="30" maxlength="255"  >	
                </td>
                </tr>
                <tr>
                <td align="right"> Remote path:</td>
                <td align="left"><input type="text" name="remote_file" id="remote_file" size="40" value="<?php echo $_POST["remote_file"] ?>"><input type="button" value="Browse" onClick="browseR()"></td>
                </tr>
                </table>
                </div>
                </td>
                </tr>  
                <tr>
                <TD align="right" width="20%">Name of PHP Page:&nbsp;&nbsp;</TD>
                <TD width="80%" align="left"><input type="text" name="creating_page" id="creating_page" value="<?php echo $tracking_data["creating_page"];
	?>" size="40"></TD>
                </tr>
                
			     <!--Cloak Link-->
                <tr>
                	<td align="right">Cloak Link&nbsp;:&nbsp;</td>
                	<td align="left"> <input type="hidden" name="cloak[check]" value="0" /><input type="checkbox" id="cloak" name="cloak[check]" value="1" /> </td>
                </tr>
                <tr>
                	<td>&nbsp;</td>
                	<td>
                		<div id="cloak_block" style="display:none;" >
                			<table border="0">
                				<tr>
                					<td width="150">Page title:</td> <td><input style="width:250px; " type="text" name="cloak[title]"></td>
                				</tr>
                				<tr>
                					<td>Meta tags (keywords):</td> <td><textarea   name="cloak[keywords]" style="width:250px; height:100px"></textarea> </td>
                				</tr>
                				<tr>
                					<td colspan="2"><input type="hidden" name="cloak[dams_on]" value="0"><input type="checkbox" name="cloak[dams_on]"  id="dams_check" value="1">
                					 Do you want to add a list building subscrpition form, or a scarcity message or any other High Impact Campaign to your tracking page?<br/>
									(Warning: this option could be responsible for big improvements to your bottom line!)
                					</td>
                				</tr>
                			</table>
                		</div>
                		<div id="dams_select" style="display:none; padding:0 0 0 20px;">
                			<table>
                				<tr>
                					<td><input type="radio" value="single" onclick="get_dams('single')" name="cloak[dams][type]" />Campaigns</td>
                				</tr>
                				<tr>	
                					<td><input type="radio" value="split" onclick="get_dams('split')"  name="cloak[dams][type]" />Split</td>
                				</tr>
                			</table>
                			<div id="dams_conteiner"></div>
                		</div>
                		<script type="text/javascript" src="../skin/_js/mootools.js"></script>
                		<script type="text/javascript" src="../skin/_js/mootools_more.js"></script>

                		<script>
                		
                		function get_dams(type) {
							var req = new Request({url: "/advancedopt/ad/", onSuccess: function(responseText){
								$('dams_conteiner').innerHTML = responseText;
							}}).post({'process':type, 'spot':'spot1'});
                		}
                		function get_damscode(el,type){ };
                		function checkUncheckAll(el,type){ 
                			$$('.check_all_items').each(function(el){
                				if( $('chkall').checked ) {
                					el.checked = true;
                				} else {
                					el.checked = false;
                				}
                			});                		
                		};
                		
                		$('dams_check').addEvent('click', function(){
                			if( $('dams_check').checked ) {
                				$('dams_select').style.display='block';
                			} else {
                				$('dams_select').style.display='none';
                			}
                			
                		});
                		
                		$('cloak').addEvent('click', function(){ 
                			if( $('cloak').checked ) {
                				$('cloak_block').style.display='block';
                			} else {
                				$('cloak_block').style.display='none';
                			}
                		});
                		</script>
                	</td>
                </tr>
                
                
                <!--End Cloak Link-->                
                
                <tr>
                <td colspan="2" align="center" class="heading">
                <div align="center">
                <input type="submit" name="Submit" value="Save">
                </div>
                <input type="hidden" name="process" value="<?php echo $process;
	?>">
                <input type="hidden" name="id" value="<?php echo $_GET['id'];
	?>">
                <input type="hidden" name="aid" value="<?php echo $aid;
	?>">
                <input type="hidden" name="trackingform" value="yes">				
                </td>
                </tr>
                </table> </form>	
                <?php } else if ( $process == "sl" ) { // end manage
	?>
                <table width="90%"  border="0" cellspacing="0" cellpadding="0" class="inputform">
                <tr>
                <td align="left" width="100%" class="heading" >Link for the page which you want to promote in your PPC campaign
                </td>
                </tr>
                <tr>
                <td align="center">
                <br><br>
                <?php
	if ( isset( $_GET["link"] ) && $_GET["link"] != "" )
		$slink = $_GET["link"];
	else
		$slink = "Undefined";
	echo $slink;

	?>		
                <br><br><br>
                </td>
                </tr>
                <tr><td align="center"  class="heading"><input type="button" value="Ok" onClick="javascript: location='campaign.php'"></td></tr>
                </table>
                <?php } else if ( $process == "manage" ) {
	?>
                <br>
                <?php if ( $totalrecords > 0 ) {
		?>
                <table align="center" width="90%" cellpadding="0" cellspacing="0">
                <tr>
                <td colspan="7">
                <?php $pg->showPagination();
		?>
                </td>
                </tr>
                </table>
                <?php } 
	?>
                <table align="center" width="90%" cellpadding="1" cellspacing="2" class="summary">
                <tr>
                <td align="left" colspan="6">Campaign : <?php echo $dtl["campaign_name"];
	?> | Ad : <?php echo $dtl["ad_name"];
	?> </td>
                </tr>
                <tr>
                <th width="20px">Srno</th>
                <th><a class="menu" href="?sort=url">Tracking Page URL</a></th>
                <th><a class="menu" href="?sort=remote_path">Tracking Page path</a></th>
                <th><a class="menu" href="?sort=date">Date/Time</a></th>
                <th></th>
                <th></th>
                </tr>
                <?php if ( $dtl_rs ) {
		$tblmat = $pg->startpos + 1;
		do {
			if ( $dtl["type"] == "L" ) {
				$revstr = strrev( $dtl["remote_path"] );
				$pagename = strrev( substr( $revstr, 0, strpos( $revstr, "/" ) ) ); 
				// $tpurl = SERVER_PATH."trackingpages/".$pagename;
				$tpurl = "http://www.qjmp.com/trackingpages/" . $pagename;
				$isSameServer = true;
			} else {
				$revstr = strrev( $dtl["remote_path"] );
				$pagename = strrev( substr( $revstr, 0, strpos( $revstr, "/" ) ) );
				$tpurl = $dtl["url"] . $pagename;
				$isSameServer = false;
			} 

			?>
                <tr class='<?php echo ( $tblmat % 2 ) ? "tablematter1" : "tablematter2" ?>'>
                <td align="center"><?php echo $tblmat++ ?></td>
                <td align="left" title="Now, you can copy and paste this
                URL in your promotion"><?php echo $tpurl ?></td>
                <td align="left"><?php echo $dtl["remote_path"] ?></td>
                <td align="center"><?php echo $dtl["date"] ?></td>
                <?php
			if ( $isSameServer ) {

				?>
                <td align="center">
                <a href="remotefileeditor.php?process=editfile&sameserver=yes&serverfile=<?php echo $pagename;
				?>&aid=<?php echo $_GET['apid'];
				?>">
                <img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">	
                </a>
                </td>			
                <td align="center">
                <img src="images/delete.png" border="0" title="Delete" style="cursor:pointer" onClick="delme('remotefileeditor.php?process=delfile&aid=<?php echo $_GET['apid'] ?>&sameserver=yes&rfid=<?php echo $dtl["id"] ?>&serverfile=<?php echo $pagename ?>')">	
                </td>	
                <?php
			} else {

				?>	
                <td align="center">	
                <a href="/affiliate-module/manage/edit-file/?id=<?php echo $dtl["id"];?>&cpp=1">
                <img src="images/edit.png" border="0" title="Edit" style="cursor:pointer">	
                </a>
                </td>					
                <td align="center">
                <img src="images/delete.png" border="0" title="Delete" style="cursor:pointer" onClick="delme('remotefileeditor.php?process=delfile&aid=<?php echo $_GET['apid'] ?>&remotefile=yes&rfid=<?php echo $dtl["id"] ?>&remote_file=<?php echo $dtl["remote_path"] ?>')">	
                </td>	
                <?php
			} 

			?>
                </tr>
                <?php
		} while( $dtl = $ms_db->getNextRow( $dtl_rs ) );
	} else {
		?>
                <tr><td colspan="6" align="center">No Detail Available</td></tr>
                <?php } 
	?>
                <tr><td colspan="6" class="heading">&nbsp;</td></tr>
                </table>
                <?php } // End manage 
?>
                </td> <!-- main block ends -->
                </tr>
                <tr><TD><br></TD></tr>
                </table>	
                <?php require_once( "right.php" );
?>
                <?php require_once( "bottom.php" );
?>