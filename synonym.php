<?php 
	session_start();
//PHP code will come here
require_once("config/config.php");
require_once("classes/settings.class.php");
require_once("classes/database.class.php");
require_once("classes/amarticle.class.php");
require_once("classes/pagination.class.php");
require_once("classes/search.class.php");
require_once("classes/pclzip.lib.php");
require_once("classes/keyword.class.php");
require_once("classes/en_decode.class.php");

$endec=new encode_decode();
$settings = new Settings();
$settings->checkSession();
$article = new Article();
$database = new Database();
$pg = new PSF_Pagination();
$sc = new psf_Search();
$archive = new PclZip($_FILES['file_art']['tmp_name']);
$key=new keyword();
$database->openDB();
$var =array();

$var_ex=explode("|",$_REQUEST['var']);
//print_r($var_ex);
$rep=$_REQUEST['replace'];
		if($_REQUEST['txtarea']=="yes")
		{
			//$sql=mysql_query("select * from ".TABLE_PREFIX."variations_tab where rep_string='".$_REQUEST['replace']."'");
			$sql=mysql_query("select distinct(w2.lemma) from sense
				left join word as w2 on w2.wordid=sense.wordid
				where sense.synsetid in(
				select sense.synsetid from word as w1
				left join sense on w1.wordid=sense.wordid
				where w1.lemma='".$_REQUEST['replace']."')and w2.lemma<>'".$_REQUEST['replace']."' limit 0,35 ");
				
			if(mysql_num_rows($sql)>0)
			{
				$i=0;
				while($row=mysql_fetch_array($sql))
				{
					echo $row['lemma']."<br>";
					$var[$i]=$row['lemma'];
					$i++;
				}
			}
			else if(mysql_num_rows($sql)==0)
				echo "No Synonyms found";
			
		}
		else
		{
			foreach($var_ex as $var)
			{
				if($_REQUEST['replace']!=$var)
				{
		
					$sql="select * from ".TABLE_PREFIX."variations_tab where rep_string='".$_REQUEST['replace']."' and variations='".$var."'";
		
					$res=mysql_query($sql);
		
					$rows=mysql_num_rows($res);
		
					if($rows<1)
		
					{
		
						mysql_query("insert into ".TABLE_PREFIX."variations_tab(rep_string,variations) values ('".$_REQUEST['replace']."','".$var."') ");
		
					}	
		
				}
			}
		}

?>