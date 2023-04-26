<?php 
session_start();
//error_reporting(0);
include("config.php");
$keywords=$_GET['kwd'];
$gsearch=1;
$lcomp="Not Used";
$SORT="searches";
$ORDER="ASC";
$RESULTS=50;
if($_POST){
$keywords=$_POST['keywords'];
$gsearch=$_POST['gsearch'];
$lcomp=$_POST['lcomp'];
$SORT=$_POST['SORT'];
$ORDER=$_POST['ORDER'];
$RESULTS=$_POST['RESULTS'];
}
?>
<?php require_once("incheader.php"); ?>
<?php //require_once("inctop.php"); ?>
 <?php //require_once("incleft.php"); ?>
<?php if(isset($_SESSION[SESSION_PREFIX.'sessionusername'])){?>
<table align="right" style="padding-left:50px;">	
	<TR>		
		<TD align="right" style="font-weight:bold;">
		Welcome <?php echo $_SESSION[SESSION_PREFIX.'sessionusername'];?>
		</TD>
	</TR>
</table><br><br>
<?php
}
?>
  <font color="#001E71"><a href="../index.php" style="text-decoration: none">Home</a>>>Keyword Research</font><br/><br/>
	<table width="80%"  border="0" cellspacing="0" cellpadding="0"  align="center">
		<tr>
			<td valign = "top" align="center" class="heading">
			<a class="menu" href = "index.php">Search Keywords</a>  |  <a  class="menu" href = "savedkwds.php">Saved Keywords Selections</a>  
			</td>
		</tr>
	</table><br/><br/>
    <table border="0" cellpadding="3" cellspacing="0" style="border-collapse:collapse;padding-left:50px;" width="900px" id="Autonumber1">
      <tr>
        <td >
        <form method="POST" action="/kwdresearch/index.php">
        <p align="left">
			<input type="hidden" name="xid" value="<?php if(isset($_GET['xid']))echo $_GET['xid'];else echo $_POST['xid']; ?>" />
			<input type="hidden" name="proc" value="<?php if(isset($_GET['proc']))echo $_GET['proc'];else echo $_POST['proc']; ?>" />
            <input type="text" name="keywords" size="20" value="<?php echo $keywords; ?>">
			<input type="submit" value="Search Keywords" name="Search" style="float: middle">
  <br/><font face="Tahoma"><span style="font-size: 11pt">
          &nbsp;&nbsp;&nbsp;
		   <?php $SORT_array=array("searches","competition","rs","KEI"); $SORT_array_val=array("Searches","Competition","R/S","KEI");?>
		  Sort by: 
		  		<?php 
					for($i=0;$i<count($SORT_array);$i++){
						if($SORT==$SORT_array[$i])
							echo '<input type="radio" value="'.$SORT_array[$i].'" checked name="SORT"> '.$SORT_array_val[$i];
						else
							echo '<input type="radio" value="'.$SORT_array[$i].'"  name="SORT"> '.$SORT_array_val[$i];
					}	
					?>
		  <!--<input type="radio" value="searches" checked name="SORT"> Searches  
		  <input type="radio" value="competition" name="SORT">  Competition  
		  <input type="radio" value="rs" name="SORT">  R/S 
		  <input type="radio" value="KEI" name="SORT"> KEI  -->
		  <br>
          </span></font><br><span style="font-size: 11pt">
          <font face="Tahoma"><b>Advanced Search:&nbsp;&nbsp;</b>  Results:&nbsp;&nbsp;</font> 
		  <?php $RESULTS_array=array(25,50,250,500,1000,2500,5000); ?>
          <select size="1" name="RESULTS">
		  		<?php 
					for($i=0;$i<count($RESULTS_array);$i++){
						if($RESULTS==$RESULTS_array[$i])
							echo '<option value="'.$RESULTS_array[$i].'" selected >'.$RESULTS_array[$i].'</option>';
						else
							echo '<option value="'.$RESULTS_array[$i].'" >'.$RESULTS_array[$i].'</option>';
					}	
					?>
			<!--<option  value="25">25</option>
			<option selected value="50">50</option>
			<option value="250">250</option>
			<option value="500">500</option>
			<option value="1000">1000</option>
			<option value="2500">2500</option>
			<option value="5000">5000</option> -->
		</select></span>
<span style="font-size: 11pt">&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if($ORDER=="ASC")
					{?>
					  <input type="radio" value="ASC" name="ORDER" checked> Ascending&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <input type="radio" value="DESC" name="ORDER" > Descending </span>
		 	<?php }else if($ORDER=="DESC")
					{?>
						<input type="radio" value="ASC" name="ORDER"> Ascending&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <input type="radio" value="DESC" name="ORDER" checked> Descending </span>
			<?php  }else {?>		
						<input type="radio" value="ASC" name="ORDER"> Ascending&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  <input type="radio" value="DESC" name="ORDER" checked> Descending </span>
			<?php  }?>
          
<span style="font-size: 11pt"><br>
<font face="Tahoma">&nbsp;With more than &nbsp;&nbsp; 
		<?php $gsearch_array=array(1,500,750,1000,2500,5000,10000,20000,30000); ?>
          <select size="1" name="gsearch">
		  		<?php 
					for($i=0;$i<count($gsearch_array);$i++){
						if($gsearch==$gsearch_array[$i])
							echo '<option value="'.$gsearch_array[$i].'" selected >'.$gsearch_array[$i].'</option>';
						else
							echo '<option value="'.$gsearch_array[$i].'" >'.$gsearch_array[$i].'</option>';
					}	
					?>
				
		 </select>
&nbsp;&nbsp;Searches and</font>
<font face="Tahoma">&nbsp;Less than &nbsp;&nbsp; 
	<?php $lcomp_array=array(100,500,1000,2500,5000,10000,25000,50000,100000,250000,500000); ?>
          <select size="1" name="lcomp">
		  	<?php 
				for($i=0;$i<count($lcomp_array);$i++){
					if($lcomp==$lcomp_array[$i])
						echo '<option value="'.$lcomp_array[$i].'" selected >'.$lcomp_array[$i].'</option>';
					else
						echo '<option value="'.$lcomp_array[$i].'" >'.$lcomp_array[$i].'</option>';
				}	
			?>
			<!--<option value="100">100</option>
			<option value="500">500</option>
			<option value="1000">1000</option>
			<option value="2500">2500</option>
			<option value="5000">5000</option>
			<option value="10000">10000</option>
			<option value="25000">25000</option>
			<option value="50000">50000</option>
			<option value="100000">100000</option>
			<option value="250000">250000</option>
			<option value="500000">500000</option>
			<option selected>Not Used</option> -->
			</select>
&nbsp;&nbsp;Competitors.</font>
</span>

        </form>
<br>
How to use the output table? Once the system returns the keywords : 
<ul><li>Select those you want to extract (you can also select all)</li>
<li>Select  the type of output you want (<b>Basic</b> : you will only get the keywords ; <b>Full data</b>You will get all the info for each keyword in a TAB delimited file)</li>
<li>Click "Generate"</li>
<li>Copy and past the content of the new window in a txt file</li>
</ul>
        </td>
      </tr>
    </table>
<hr>
    <table border="0" cellpadding="5" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber2">
       <tr>
        <td width="100%">
			<font face="Tahoma">
				<span style="font-size: 11pt">
					<?php

					class Inflector 
					{ 
					
					
						/** 
						* Singularizes English nouns. 
						*  
						* @access public 
						* @static 
						* @param    string    $word    English noun to singularize 
						* @return string Singular noun. 
						*/ 
						function singularize($word) 
						{ 
							$singular = array ( 
							'/(quiz)zes$/i' => '\1', 
							'/(matr)ices$/i' => '\1ix', 
							'/(vert|ind)ices$/i' => '\1ex', 
							'/^(ox)en/i' => '\1', 
							'/(alias|status)es$/i' => '\1', 
							'/([octop|vir])i$/i' => '\1us', 
							'/(cris|ax|test)es$/i' => '\1is', 
							'/(shoe)s$/i' => '\1', 
							'/(o)es$/i' => '\1', 
							'/(bus)es$/i' => '\1', 
							'/([m|l])ice$/i' => '\1ouse', 
							'/(x|ch|ss|sh)es$/i' => '\1', 
							'/(m)ovies$/i' => '\1ovie', 
							'/(s)eries$/i' => '\1eries', 
							'/([^aeiouy]|qu)ies$/i' => '\1y', 
							'/([lr])ves$/i' => '\1f', 
							'/(tive)s$/i' => '\1', 
							'/(hive)s$/i' => '\1', 
							'/([^f])ves$/i' => '\1fe', 
							'/(^analy)ses$/i' => '\1sis', 
							'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis', 
							'/([ti])a$/i' => '\1um', 
							'/(n)ews$/i' => '\1ews', 
							'/s$/i' => '', 
							); 
					
							$uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep'); 
					
							$irregular = array( 
							'person' => 'people', 
							'man' => 'men', 
							'child' => 'children', 
							'sex' => 'sexes', 
							'move' => 'moves'); 
					
							$lowercased_word = strtolower($word); 
							foreach ($uncountable as $_uncountable){ 
								if(substr($lowercased_word,(-1*strlen($_uncountable))) == $_uncountable){ 
									return $word; 
								} 
							} 
					
							foreach ($irregular as $_plural=> $_singular){ 
								if (preg_match('/('.$_singular.')$/i', $word, $arr)) { 
									return preg_replace('/('.$_singular.')$/i', substr($arr[0],0,1).substr($_plural,1), $word); 
								} 
							} 
					
							foreach ($singular as $rule => $replacement) { 
								if (preg_match($rule, $word)) { 
									return preg_replace($rule, $replacement, $word); 
								} 
							} 
					
							return $word; 
						} 
					
					}
					$keywords = str_replace("%20", " ", $keywords);
					$keywords = str_replace("%", "", $keywords);
					$keywords = str_replace("-", "", $keywords);
					$keywordsingle = Inflector::singularize("$keywords"); 
					
					//echo "KWD1".$keywords;
					if (!empty($keywords) ) {
					
					echo "Keyword Search Results for:  $keywords<br><br>";
					
					?>
					
					<script type="text/javascript"><!--
					
					var formblock;
					var forminputs;
					
					function prepare() {
					formblock= document.getElementById('form_id');
					forminputs = formblock.getElementsByTagName('input');
					}
					
					function select_all(name, value) {
					for (i = 0; i < forminputs.length; i++) {
					// regex here to check name attribute
					var regex = new RegExp(name, "i");
					if (regex.test(forminputs[i].getAttribute('name'))) {
					if (value == '1') {
					forminputs[i].checked = true;
					} else {
					forminputs[i].checked = false;
					}
					}
					}
					}
					
					if (window.addEventListener) {
					window.addEventListener("load", prepare, false);
					} else if (window.attachEvent) {
					window.attachEvent("onload", prepare)
					} else if (document.getElementById) {
					window.onload = prepare;
					}
					
					function showSave()
					{
						document.getElementById("showSave").style.display="block";
					}
					
					function showAdd()
					{
						document.getElementById("showAdd").style.display="block";
					}
					
					function saveList(){
						var checked;
						if(document.posting.txtListTitle.value==""){
							alert("Please enter a title");
							return false;
						}
						
						checked=checkSelected();
						if(checked){
							document.posting.method='post';
							document.posting.action="savelist.php";
							document.posting.submit();
						}
					}
					
					function addToList(){
						var checked;
						if(document.posting.cboList.value==""){
							alert("Please select a list");
							return false;
						}
						
						checked=checkSelected();
						if(checked){
							document.posting.method='post';
							document.posting.action="addtolist.php";
							document.posting.submit();
						}
					}
								
					function checkSelected()
					{
						flags=false;
						var element;
						var numberOfControls = document.posting.length;
						for (Index = 0; Index < numberOfControls; Index++)
						{
							element = document.posting[Index];
							if (element.type == "checkbox")
							{
								if (element.checked == true)
								{
									flags=true;
								}
							}
						}
						if (flags==false)
						{
							alert("Please select at least one keyword.");
							return false;
						}
						else
						{
							//TO DO AJAX
							return true;
						}
					}
					
					
					//--></script>
					<form id="form_id" method='POST' action='generate.php' name='posting'>
					<INPUT 
					  TYPE=button 
					  VALUE='Select All' 
					  ONCLICK="select_all('keywords', '1');">
					<INPUT 
					  TYPE=button 
					  VALUE='Clear All'
					  ONCLICK="select_all('keywords', '0');">
					
					<?PHP
					echo "<input type='hidden' name='keywords' value='$keywords'>";
					echo "<input type='submit' value='Generate Keywords'>";
					echo "<input type='button' value='Save Keywords Selection' onclick='showSave();'>";
					echo "<input type='button' value='Add to Existing Keywords List'onclick='showAdd();'>";
					?>
					&nbsp;&nbsp;&nbsp;Output: <input type="radio" value="Basic" checked name="DATA"> Basic <input type="radio" value="FULL" name="DATA"> Full Data <br/><br/><br/>
					<div id="showSave" style="display:none">
						<table border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="50%">
							<tr>
								<td>Enter List Title:</td>
								<td style="width:50%"><input type="text" id="txtListTitle" name="txtListTitle" style="width:200px;" /></td>
								<td><input type="button" id="cmdGo" value="Save" onclick="saveList();" /></td>
							</tr>
						</table>
					</div>
					<?php if($_POST['proc']=='add')
						  { $style="block";}
						  else
						  { $style="none"; }
					?>
					
					
					<div id="showAdd" style="display:<?php echo $style; ?>">
						<table border="0" align="center" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="30%">
							<tr>
								<td style="width:70%">
									<select id="cboList" name="cboList" style="width:200px">
									<option value="">Select List</option>
									<?php
										include("../config/config.php");
										require_once("../classes/database.class.php");
										
										$database = new Database();
										$database->openDB();
										
										$sql="SELECT * from `".TABLE_PREFIX."kwd_savedlist` where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
										$rs=$database->getRS($sql);
										if($rs){
											while($data=$database->getNextRow($rs)){
												if($_POST['xid']==$data['list_id'])$var="selected='selected'";
												echo "<option value='".$data['list_id']."' ".$var." >".$data['list_title']."</option>";
											}
										}
									?>
									</select>
								</td>
								<td><input type="button" id="cmdGo" value="Add to List" onclick="addToList();" /></td>
							</tr>
						</table>
					</div>
					<?PHP
					$keywords = str_replace(" ", "%", $keywords);
					//echo "KWD".$keywords;
switch( @$_SERVER['HTTP_HOST'] ) {
	case 'cnm.local': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_utf',
	); break;
	case 'cnm.dev': $_arrPrm=array(
		'host'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'db_cnm_keywords',
	); break;
	case 'cnm.cnmbeta.info':
	case 'members.creativenichemanager.info':
	default: $_arrPrm=array(
		'host'=>'10.206.73.226',
		'username'=>'prod_kwdtool',
		'password'=>'VXh1Tw5Ak0',
		'dbname'=>'prod_kwdtool',
	); break;
}
$host=$_arrPrm['host'];
$database=$_arrPrm['dbname'];
$username=$_arrPrm['username'];
$password=$_arrPrm['password'];
					$link = mysql_connect($host,$username,$password);
					
					
					if ($lcomp == "Not Used") { $lcomp = "99999999999"; }
					
					mysql_select_db($database , $link);
					//die("SELECT * FROM keywords WHERE ( keyword='$keywords' or keyword like CONVERT(_utf8'%$keywords%' USING latin1) or keyword like '%$keywordsingle%' ) and searches > '$gsearch' and competition < '$lcomp' ORDER BY $SORT $ORDER LIMIT $RESULTS");
					$result = mysql_query("SELECT * FROM keywords WHERE ( keyword='$keywords' or keyword like CONVERT(_utf8'%$keywords%' USING latin1) or keyword like '%$keywordsingle%' ) and searches > '$gsearch' and competition < '$lcomp' ORDER BY $SORT $ORDER LIMIT $RESULTS",$link);
					echo "<br/><br/><table style='border:solid; border-width:1px; border-color:#666666' cellpadding=0 cellspacing=2 width = '900px' align='center'>";
					echo "<tr style='background:#646464; color:#FFFFFF'><th width='18'>&nbsp;</th><th width='300'><b>Keyword</b></td><td width='120'><b>Keyword Searches</b></th><th width='100'><b>Daily Searches</b></th><th width='120'><b>Keyword Competition</b></th><th width='100'><b>R/S Ratio</b></th><th width='100'><b>KEI</b></th></tr>";
					
					
					
					while ($myrow = mysql_fetch_row($result)) {
					
					$kcount++;
					if ($myrow[3] > 9) { $kei = number_format($myrow[3]); } else { $kei = $myrow[3]; }
					$daily=$myrow[1] / 30;
					$daily=number_format($daily);
					if($kcount%2==0)
						$xcolor="#FFFFFF";
					else
						$xcolor="#ebebeb";
					printf("<tr style='background:$xcolor'><td width='18'>%s</td><td width='328'><a href='index.php?SORT=$SORT&RESULTS=$RESULTS&ORDER=$ORDER&keywords=$myrow[0]&lcomp=Not Used&gsearch=0' style='text-decoration: none' title='Dig for more Keywords'><font color='#000000'>%s</font></a></td><td width='87'>%s</td><td width='61'>%s</td><td width='87'><a target = '_new' href='http://www.google.com/search?hl=en&q=\"$myrow[0]\"' style='text-decoration: none' title='Verify Competion'><font color='#000000'>%s</a></td><td width='60'>%s</td><td width='61'>%s</td></tr>\n",
					"<input type='checkbox' value=\"$myrow[0]\" name='keywords[]'>", $myrow[0], number_format($myrow[1]), $daily , number_format($myrow[2]), number_format($myrow[4]), $kei);
					}
					
					
					printf("<tr><td width='18'>&nbsp;</td><td width='328'><font face='Tahoma'><span style='FONT-SIZE: 10pt'><br><b>Displaying $kcount results<b></font></span></td><td width='87'>&nbsp;</td><td width='61'>&nbsp;</td><td width='87'>&nbsp;</td><td width='60'><b>&nbsp;</b></td><td width='61'>&nbsp;</td>\n");
					echo "</table><br></form>\n";
					
					
					}
					
					if (empty($keywords)) {  
					
					
					$db = mysql_connect($host,$username,$password);
					
					mysql_select_db($database , $db);
					$sql="SELECT count(*) from keywords";
					$cmd = mysql_query($sql);
					$rs = mysql_fetch_array($cmd);
					$count = number_format($rs[0]);
					
					?>
					
					
					<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
					  <tr>
						<td width="100%" colspan="2">
						<p align="center"><b><font face="Verdana" color="#FF0000">Keywords Area<br><br></font></b></td>
					  </tr>
					  <tr>
						<td width="100%" colspan="2">
						<p align="center"><b><font face="Verdana" color="#FF0000"><?php if(isset($_GET['resp'])) echo $_GET['resp']?><br><br></font></b></td>
					  </tr>
					  <tr>
						<td width="50%"><font face="Verdana" color="#000000"><?php echo "<b>Total Keywords in Database:</b>  $count"; ?></font></td>
						<td width="50%"></td>
					  </tr>
					  <tr>
						<td width="50%"></td>
						<td width="50%"><font face="Verdana" color="#FF0000"><center><br></center></td>
					  </tr>
					</table>
					
					<?PHP
					
					
					 }
					
					?>
				</span>
			</font>
   		</td>
	</tr>	
   
    </table>
    </td>
  </tr>
  
</table>
</center>
<?php require_once("incright.php"); ?>
 <?php require_once("incbottom.php"); ?>