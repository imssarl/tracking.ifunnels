<?php
session_start();
require_once("config/config.php");
require_once("classes/database.class.php");
require_once("classes/profile.class.php");
require_once("classes/article.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");

$asm_db = new Database();
$asm_db->openDB();
$profile = new Profile();
$article_obj = new Article();
$pg = new Pagination;
$sc = new Search();

$directory_rs=$article_obj->getDirectory();

if(isset($_POST['process']))
{
	$process=$_POST['process'];
}
elseif(isset($_GET['process']))
{
	$process=$_GET['process'];
}
else
{
	$process="manage";
}

if($process=="manage")
{
	
}
elseif($process=="edit")
{
	$profile_data = $profile->getSingleProfileById($_GET['id']);
}

if(isset($_POST['submit_d']) && $_POST['submit_d']=="Yes")
{
	if($_POST['directory']!="")
	{
		$url_rs = $article_obj->getUrl($_POST['directory']);
		$dir_type = $article_obj->getDirType($_POST['directory']);
	}
}

if(isset($_POST['insert']) && $_POST['insert']=="Yes")
{
	//$id = $profile->insert();
	/*if($_POST['type']=="AD")
	{
		$login = $article_obj->getLoginDetail($_POST['url']);
		
		if($login)
		{
			$str="f_username=".$login['username']."&f_password=".$login['password']."&action=login&B7=Submit";
//			echo $str;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $login['url']."login2submitart.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
			curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
//			echo $response;
			$str1="f_penname=".$_POST['author']."&act=add&submit=Submit";
			curl_setopt($ch, CURLOPT_URL, $login['url']."penname.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str1);
			curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			//echo $response; //die();
			
			$content = $response;
			$search   = '<li>This Pen Name was already used by You</li>';
			
			curl_setopt($ch, CURLOPT_URL, $login['url']."submitarticles.php");
		
			$response = curl_exec($ch);
			
			$spartstart = '<select size="1" name="f_pennameid"';//for original
			$spartend = '</select>'; 
			$str = str_replace(array("\n","\r","\t"),array("","",""),$response);
			preg_match_all("|(".$spartstart."(.*)".$spartend.")|U",$str, $out1);
			
			$author = strtolower($_POST["author"]);

			$idstart = '<option value="';//for original
			$idend = '</option>'; 

			preg_match_all("|(".$idstart."(.*)".$idend.")|U",$out1[1][0], $out2);

			for($x=0;$x<count($out2[2]);$x++)
			{
				$string=explode('">',$out2[2][$x]);
				if(strtolower($string[1])==$author)
				{	$penid=$string[0]; 
					break; 
				}
			}

			$pos = strpos($content, $search);
			if ($pos === false)
			{
				$id = $profile->insert($penid);
			}
			else
			{
				$author_rs = $profile->getProfileByDir();
				
				if($author_rs)
				{
					$profile->update($penid); 
				}
				else
				{
					$id = $profile->insert($penid);
				}				
			}
		}
	}
	elseif($_POST['type']=="EA")
	{
		$login = $article_obj->getLoginDetail($_POST['url']);
		$name = $_POST['author']." ".$_POST['author_lname'];
		if($login)
		{
			$str="email=".$login['username']."&pass=".$login['password']."&newlogin=1&jscheck=1";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $login['url']."index.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
			curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);	
			
			curl_setopt($ch, CURLOPT_URL, $login['url']."index.php");
			$response = curl_exec($ch);
			
			$idstart = '<input type="hidden" name="id" value="';//for original
			$idend = '"';
			$passstart = '<input type="hidden" name="pass" value="';//for original
			$passend = '"';
		
			$str = str_replace(array("\n","\r","\t"),array("","",""),$response);
			preg_match_all("|(".$idstart."(.*)".$idend.")|U",$str, $out1);
			preg_match_all("|(".$passstart."(.*)".$passend.")|U",$str, $out2);

			$str2="pass=".$out2[2][0]."&id=".$out1[2][0]."&add_alt_author_first=".$_POST['author']."&add_alt_author_last=".$_POST['author_lname']."&verify=1&add_alt=1&act=addAltAuth";
			curl_setopt($ch, CURLOPT_URL, $login['url']."profile-manager/add_alternate.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str2);
			curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			
			$content = $response;
			$search   = '<div id="error_message">'.$name.' is already in use.<br>';
			//echo $search; die();
			$pos = strpos($content, $search);
			if ($pos === false)
			{
				$id = $profile->insert();
			}
			else
			{
				$author_rs = $profile->getProfileByDir();
				
				if($author_rs)
				{
					//$profile->update(); 
				}
				else
				{
					$id = $profile->insert();
				}				
			}
		//echo $response; die();
	curl_close($ch);
		}
	}
	else
	{
		$id = $profile->insert();
	}*/
	$flag=true;
	if($_POST['biography_html']!="")
	{
		$html = $_POST['biography_html']; // Had echo removed 101108 SDEI
		preg_match_all("/(<([\w]+)[^>]*>)(.*)(<\/\\2>)/", $html, $matches, PREG_SET_ORDER);
		
		foreach ($matches as $val) 
		{
			if(strtolower(substr($val[1],0,4))=="<tab" || strtolower(substr($val[1],0,4))=="<tr>" || strtolower(substr($val[1],0,4))=="<td>")
			{
				//echo "aaya"; die();
				$flag =  false;
			} 
	
		}
	}
	if($flag!=false)
	{
		$id = $profile->insert();
		if($id)
		{ 
			$msg.= "Profile has been created successfully!";
			header("Location:manage_profile.php?msg=".$msg."");
		}
		else
		{ 
			$msg.= "Profile has not been created due to Unnecessary HTML tags!";
			header("Location:manage_profile.php?msg=".$msg."");
		}
	}
	else
	{
		$profile_data['profile_name'] = $_POST['profile_name'];
		$profile_data['author'] = $_POST['author'];
		$profile_data['author_lname'] = $_POST['author_lname'];
		$profile_data['biography'] = $_POST['biography'];
		$profile_data['biography_html'] = $_POST['biography_html'];
		$profile_data['comments'] = $_POST['comments'];
		$process="create";
		$msg="Please Remove Unnecessary HTML tags";
	}
}
elseif(isset($_POST['edit']) && $_POST['edit']=="Yes")
{
	$flag=true;
	if($_POST['biography_html']!="")
	{
		$html = $_POST['biography_html'];
		preg_match_all("/(<([\w]+)[^>]*>)(.*)(<\/\\2>)/", $html, $matches, PREG_SET_ORDER);
		
		foreach ($matches as $val) 
		{
			if(strtolower(substr($val[1],0,4))=="<tab" || strtolower(substr($val[1],0,4))=="<tr>" || strtolower(substr($val[1],0,4))=="<td>")
			{
				//echo "aaya"; die();
				$flag =  false;
			} 
	
		}
	}
	if($flag!=false)
	{
		$id = $profile->edit($_POST['id']);
		if($id)
		{ 
			$msg.= "Profile has been updated successfully!";
			header("Location:manage_profile.php?msg=".$msg."");
		}
		else
		{ 
			$msg.= "Profile has not been updated, try again!";
			header("Location:manage_profile.php?msg=".$msg."");
		}
	}
	else
	{
		$profile_data['profile_name'] = $_POST['profile_name'];
		$profile_data['author'] = $_POST['author'];
		$profile_data['author_lname'] = $_POST['author_lname'];
		$profile_data['biography'] = $_POST['biography'];
		$profile_data['biography_html'] = $_POST['biography_html'];
		$profile_data['comments'] = $_POST['comments'];
		$process="edit";
		$msg="Please Remove Unnecessary HTML tags";
	}
}

if($process=="delete")
{
	$profile->delete($_GET['id']);
	$msg.= "Profile has been deleted successfully!";
	header("Location:manage_profile.php?msg=".$msg."");
}
?>
<?php
require_once("header.php");
?>
<link href="stylesheets/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jscripts/request.js"></script>
<script type="text/javascript" src="jscripts/common.js"></script>
<script language="javascript" type="text/javascript">
function submitdirectory()
{
	//alert(document.getElementById("directory").value);
	document.frm.submit();
}
</script>
<script type="text/javascript" language="javascript">
function opencode(id)
{
	openwindow= window.open ("showarticles.php?id="+id, "GETCODE",
		"'status=0,scrollbars=1',width=650,height=500,resizable=1");
	
	openwindow.moveTo(50,50);
}

function validate(frm)
{
	flag=true;
        chk();
	msg="*******************************************************\n";
	if(frm.profile_name.value=="")
	{
		msg+="Please provide profile name\n";
		flag = false;
	}
	if(frm.author.value=="")
	{
		msg+="Please provide first name\n";
		flag = false;
	}
	if(frm.author_lname.value=="")
	{
		msg+="Please provide last name\n";
		flag = false;
	}
	if(frm.profile_name.value!="" && frm.author.value!="" && frm.author_lname.value!="")
	{
		if((frm.profile_name.value==frm.author.value) || (frm.profile_name.value==frm.author_lname.value))
		{
			msg+="Profile name should be different from user's first and last name\n";
			flag = false;
		}
	}
	if(frm.biography.value=="")
	{
		msg+="Please provide text biography\n";
		flag = false;
	}
	if(frm.comments.value=="")
	{
		msg+="Please provide comments\n";
		flag = false;
	}
	
	msg+="*******************************************************\n";

	if(flag)
		return true;
	else
	{
		alert(msg);
		return flag;
	}
}
</script>
<script language="JavaScript" type="text/javascript" src="jscripts/rte/html2xhtml.js"></script>
<script language="JavaScript" type="text/javascript" src="jscripts/rte/richtext_compressed.js"></script>
<script language="JavaScript" type="text/javascript" src="jscripts/rte/richtext.js"></script>
<script language="javascript">
function chk()
{
	
	updateRTEs(biography_html);
// 	toggleHTMLSrc('biography_html',true,true);
// 	toggleHTMLSrc('biography_html',true,true);

		return true;
}</script>
<script language="javascript">
function con_dup(id)
{
   condup=confirm("Do you want to create duplicate profile?");
   if(condup==true) 
   {
        window.location="request_duprofile.php?id="+id;
   }
}
</script>
<?php
require_once("inc_menu.php");
?>
<?php
if($process=="manage")
{
	$sql = "select count(*) from ".TABLE_PREFIX."profile where user_id=".$_SESSION[MSESSION_PREFIX.'sessionuserid'];
		$totalrecords = $asm_db->getDataSingleRecord($sql);
		if ($totalrecords>0)
		{
			$pg->setPagination($totalrecords);
		}
		else
		{
			$pg->startpos=0;
		}

		$order_sql = $sc->getOrderSql(array("id","author","profile_name","date_created"),"id");

		//$sql = "select * from `".TABLE_PREFIX."project_master`".$order_sql." LIMIT ".$pg->startpos.",".ROWS_PER_PAGE;

		//$project_rs = $cnm_db->getRS($sql);
		$profile_rs = $profile->getProfile();
?>
<table align="center" width="100%" border="0">
			<TR>
				<td align="center">
					<?php if(isset($_GET['msg']) && $_GET['msg']!=""){ ?>
					<span class="optional_field"><?php echo $_GET['msg']; ?></span>
					<?php } if(isset($error) && $error!=""){ ?>
					<span class="error"><?php echo $error; ?></span>
					<?php } ?>
				</TD>
			</TR>
</table>

<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
			<tr>
				<td valign = "top" align="center" class="heading"><a href="manage_profile.php?process=create" class="menu">Create Profile</a></td>
			</tr>
			<br>
			<tr>
				<td colspan="4">
					<?php $pg->showPagination(); ?>
				</td>
			</tr>
</table>
		<br>
		<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
					
		
		<tr  class="tableheading">
		<th><a title = "Sort" class = "menu" href="?sort=id">Profile #</a></th>
		<th><a title = "Sort" class = "menu" href="?sort=profile_name">Profile Name</a></th>
		<th><a title = "Sort" class = "menu" href="?sort=author">Author Name</a></th>
		<th><a title = "Sort" class = "menu" href="?sort=date_created">Date Created</a></th>
		<th>Used</th>
		<th></th>
		<th></th>
		<th></th>
                 <th></th>
		</tr>
		<?php
		if ($profile_rs)
		{
			$tblmat=0;
			$cnt=1;
			while($profile_res = $asm_db->getNextRow($profile_rs))
			{
				$id = $profile_res['id'];
				$count = $profile->getArticleCountByProfileId($id);
				
		?>	
		<tr  id="row<?php echo $id; ?>"  class='<?php echo ($tblmat++%2) ? "tablematter1" : "tablematter2" ?>' >
				<td align="center">
				<?php
					if((isset($_GET['page']) && $_GET['page']==1) || $_GET['page']=="")
					echo $cnt;
					elseif(isset($_GET['page']))
					{
					echo $cnt + (ROWS_PER_PAGE * ($_GET['page']-1));
					}
					else
					{
					echo $cnt;
					}
					$cnt++;
				?>
				</td>
				<td align="center" title=""><?php echo $profile_res['profile_name'];?></td>
				<td align="center" title=""><?php echo $profile_res['author']." ".$profile_res['author_lname'];?></td>
				<td align="center" class="general"><?php echo $profile_res['date_created'];?></td>
				<td align="center">
					<div id="count<?php echo $id;?>"><a href="#" onClick="hndlsr(<?php echo $id; ?>,0); return false;"><?php echo $count;?></a></div>
				</td>
				<td align="center" width="16px">
					<!--<a href="?process=edit&id=<?php echo $id;?>">-->
					<a href="?process=edit&id=<?php echo $id;?>">
					<img src="images/edit.png" border="0" title="Click Here To Edit" style="cursor:pointer">
					</a>
				</td>
				<td align="center" width="16px">
					<a href="#" onclick="makeRequest('count<?php echo $id;?>','<?php echo $id;?>','profile'); hndlsr(<?php echo $id; ?>,1); return false;">
					<img src="images/scan.png" border="0" title="Click Here To Reset Profile" style="cursor:pointer; height:20px;">
					</a>
				</td>
                                <td align="center" width="16px">
					<a href="#"  onclick="con_dup(<?php echo $id;?>)">
					<img src="images/duplicate_profile.gif" border="0" title="Click here for duplicate profile" style="cursor:pointer; height:20px;">
					</a>
				</td>
				<td align="center" width="16px">
					<a onclick="javascript:return confirm('Are you sure you want to delete this profile');"  href="?process=delete&id=<?php echo $id;?>">
					<img src="images/delete.png" border="0" title="Delete" style="cursor:pointer">
					</a>
				</td>
				<td align="center" nowrap="true"></td>
				<td align="center"></td>
			</tr>
			<tr>
			<TD colspan="6">
			</TD>
			</tr>
	<!-- 	/////////////////// Code for Inner Table starts here////////////////////////// -->
		<tr>
			<td colspan="7">
				<div class="noshow" id="ad<?php echo $id ?>">
					<table width="90%"  border="0" cellspacing="1" cellpadding="1" align="center" class="summary">
						<tr  class="tableheading">
							<th width="16">&nbsp;ID&nbsp;</th>
							<th>Article Title</th>
							<th>Article Directory</th>
							<th>Submission Date</th>
							<th>Status</th>
							<th></th>
							<th></th>
						</tr>
					<?php
					
				///////////////////////////////////////////////////////////////////////////
						$article_rs = $profile->getArticleByProfileId($id);
				///////////////////////////////////////////////////////////////////////////

					//print_r($projects_rs);
					if($article_rs)
					{
						$cntinner=1;
						while($article = $asm_db->getNextRow($article_rs))
						{
							$id = $article['id'];
							if($article['isSubmit']=='Y')
							{
								$status = 'Completed';
							}
							else
							{
								$status = 'Pending';
							}
					?>
						<tr  id="row1<?php echo $id;?>"  class='<?php echo ($tblmatinner++%2) ? "tablematter1" : "tablematter2" ?>' >
							<td align="center">
								<?php
									if((isset($_GET['page']) && $_GET['page']==1) || $_GET['page']=="")
									echo $cntinner;
									elseif(isset($_GET['page']))
									{
									echo $cntinner + (ROWS_PER_PAGE * ($_GET['page']-1));
									}
									else
									{
									echo $cntinner;
									}
									$cntinner++;
								?>
							</td>
							<td align="center"><a href="#" onclick="opencode('<?php echo $id;?>')" style="cursor:pointer"><?php echo $article['title'];?></a></td>
							<td align="center"><?php echo $article['directory'];?></td>
							<td align="center"><?php echo $article['schedule'];?></td>		
							<td align="center"><?php echo $status;?></td>
						</tr>
						<?php
						}//End of While Loop Version Details of perticluler Project
					}else
					{
						echo "<tr><td align='center' colspan='6'>No Article Found For This Profile</td></tr>";
					}
					?>	
					</table>
				</div>
			</td>
		</tr>
		<?php	}
		}	  
		else
		{
		echo "<tr><td align='center' colspan='6'>No Profile Found</td></tr>";
		}
		?>	  
		<tr ><td align='center' colspan='14'  class="heading">&nbsp;</td></tr>	  
		</table>	

<?php
}elseif($process=="create" || $process=="edit")
{
?>
<table align="center" width="100%" border="0">
			<TR>
				<td align="center">
					<?php if(isset($msg) && $msg!=""){ ?>
					<span class="optional_field"><?php echo $msg; ?></span>
					<?php } ?>
				</TD>
			</TR>
</table>

<table width="80%" cellpadding="3" cellspacing="3" border="0" class="summary2">
<form action="" name="profile" method="post" onsubmit="return validate(this);">
		<tr>
			<td align="right">Profile Name:</td>
			<td align="left"><input type="text" name="profile_name" value="<?php echo $profile_data['profile_name'];?>" size="32" /></td>
		</tr>
		<tr>
			<td align="right">First Name:</td>
			<td align="left"><input type="text" name="author" value="<?php echo $profile_data['author'];?>" size="28" />
			Last Name:
			<input type="text" name="author_lname" value="<?php echo $profile_data['author_lname'];?>" size="28" /></td>
		</tr>
		<tr>
			<td align="right">Biography(TEXT):</td>
			<td align="left">
			<textarea name="biography" id="biography" rows="5" cols="60"><?php echo $profile_data['biography'];?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="color:#336600">
			<p>The text bio will be used in all cases if the html form is not filled or if the article directory does not accept HTML tags in bios. If the directory you want to submit to allows HTML in bio box, you may also fill the html form below. This one will then be used when an article is submitted to such directory with the current profile</p>
			<p>Certain HTML elements are allowed, namely: &lt;b&gt;, &lt;i&gt;, &lt;br&gt;, &lt;p&gt; and &lt;a href&gt;.  All other HTML elements will be converted to HTML entities so they are not interpreted by your web browser.</p>
			</td>
		<tr>
			<td align="right">Biography(HTML):</td>
			<td align="left"><script language="javascript">

						initRTE("jscripts/rte/images/", "jscripts/rte/", "", true);

						var message = new richTextEditor('biography_html');

						message.html="<?php echo str_replace(array("\n","\r","'","&nbsp;"),array("","","&#39;"," "),addslashes(($profile_data['biography_html'])));?>";

						message.toolbar1=true;

						message.toggleSrc = true;

						message.width=450;

					        message.height=130;

						message.build();

					</script>
			<!--<textarea name="biography_html" id="biography_html" rows="5" cols="60"><?php //echo $profile_data['biography_html'];?></textarea>-->
			</td>
		</tr>
		<tr>
			<td align="right">Comments / Description:</td>
			<td align="left"><textarea name="comments" id="comments" cols="60" rows="5"><?php echo $profile_data['comments'];?></textarea></td>
		</tr>
		
	<tr>
		<td align="center" colspan="2">
		<?php 
		if($process=="edit")
		{
		?>
			<input type="hidden" name="edit" value="Yes" />
			<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
		<?php
		}else
		{
		?>
			<input type="hidden" name="insert" value="Yes" />
		<?php
		}
		?>
		<input type="hidden" name="process" value="<?php echo $process;?>" />
		<input  type="submit" name="submit" value="Submit">&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" onclick="window.location.href = 'manage_profile.php'" />
		</td>
	</tr>
</form>	
</table>								
<?php
}
?>
<?php
require_once("footer.php");
?>
