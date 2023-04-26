<?php
	require_once("classes/en_decode.class.php");
	class Niche
	{

		private $no=0;

		function getRow( &$arrDta=array() ) {
			if ( empty( $arrDta ) ) {
				return '';
			}
			$this->no++;
			$str='<tr>
				<td align="center" width="20px">'.$this->no.'</td>
				<td align="left" style="padding-left:50px;">'.$arrDta['topniche'].'</td>
				<td align="left" style="padding-left:50px;">
				<table  cellpadding="2" cellspacing="0" >
					<tr>
						<td><a href="/kwdresearch/index.php?kwd='.$arrDta['topniche'].'">Dig It</a></td>
						<td><a href="/nvsb/create/?keyword='.$arrDta['topniche'].'" title="Create NVSB Site"><img src="/images/nvsb.png" alt="Create NVSB Site" /></a></td>
						<td><a href="/spb/create/?keyword='.$arrDta['topniche'].'" title="Create SPB Site"><img src="/images/psb.png" alt="Create SPB Site" /></a></td>
						<td><a class="mb" onclick="return false;" rel="width:690,height:600" href="/market-trends/popup/?keywords='.$arrDta['topniche'].'" title="Market Trends"><img src="/images/market_trands.png" alt="Market Trends" /></a></td>
					</tr>
				</table>
				</td>
			</tr>';
			return $str;
		}

		function TopNiche()
		{
			global $database,$pg,$order_sql;
			//$endec=new encode_decode();
			//ROWS_PER_PAGE=100;
			$showRec=50;
			if(isset($_GET['pg']))
				$page=$_GET['pg'];
			else
				$page=1;
	
			$offset=($page-1)*$showRec;
			
			$sql="SELECT topniche FROM `".TABLE_PREFIX."niche_topniche` LIMIT ".$offset.",".$showRec;
			$man_rs=$database->getRS($sql);
			if($man_rs) {
				$this->no=$offset;
				while($data=$database->getNextRow($man_rs)) {
					$str .=$this->getRow( $data );
				}
			}
			
			$sql = "select count(*) as numrows from `".TABLE_PREFIX."niche_topniche` ";
			$data=$database->getDataSingleRow($sql);
			$maxPage=ceil($data['numrows']/$showRec);
			//echo "maxpg:".$data['numrows'];
			if($page > 1)
			{
				$pPg=$page-1;
				$prev="<a href=\"?pg=$pPg&process=Top\">PREV</a>";
			}	
			else
			{
				$prev="PREV";
			}
								
			if($page < $maxPage)
			{
				$nPg=$page+1;
				$next="<a href=\"?pg=$nPg&process=Top\">NEXT</a>";
				//$prev="<a href=\"haveasay.php?pg=$page\">PREV</a>";
			}
			else
			{
				$next="NEXT";
			}
			$str .="<tr><td height=5px></td></tr>";	
			$str .="<tr><td colspan=2 align='center'>Total ".$data['numrows']." record(s) found. Showing ".$showRec." record(s) per page. </td></tr>" ;
			$str .="<tr><td height=2px></td></tr>";
			$str .="<tr><td colspan=2 align='center'>".$prev." Showing $page of $maxPage ".$next."</td></tr>" ;	
			
			return $str;
		}	 
		
	function RandomNiche()
		{
			
			global $database,$pg,$order_sql;
			//$endec=new encode_decode();
			//ROWS_PER_PAGE=100;
			$showRec=5;
			$sql = "select count(*) as numrows from `".TABLE_PREFIX."niche_topniche` ";
			$data=$database->getDataSingleRow($sql);
			$offset=mt_rand(0,$data['numrows']);
			
			$sql="SELECT topniche FROM `".TABLE_PREFIX."niche_topniche` LIMIT ".$offset.",".$showRec;
			$man_rs=$database->getRS($sql);
			if($man_rs) {
				$this->no=0;
				while($data=$database->getNextRow($man_rs)) {
					$str .= $this->getRow( $data );
				}
			}
			
		
			$str .="<tr><td colspan=2 align='center'><a href='?process=Random'><< More >> </a></td></tr>" ;	
			
			return $str;
		
		}	
		
		function RelatedNiche()
		{
			global $database,$pg,$order_sql;
			//$endec=new encode_decode();
			//ROWS_PER_PAGE=100;
			//$pattern="[@%&\+\*\$\!`~'\"#\^\(\)-_]+";
			$pattern="[@%&\+\*\$\!`~'\"#\^\(\)_]+";
			$pattern2="(\s+)";
			$kwd=strip_tags(isset($_POST['txtNiche'])?$_POST['txtNiche']:$_GET['txtNiche']);
			//echo "1".$kwd;
			$kwd=ereg_replace($pattern," ",$kwd);
			//echo "2".$kwd;
			$kwd=preg_replace($pattern2,"%",$kwd);
			//echo "3".$kwd;
			$showRec=50;
			if(isset($_GET['pg']))
				$page=$_GET['pg'];
			else
				$page=1;
	
			$offset=($page-1)*$showRec;
			
			$sql="SELECT niche topniche FROM `".TABLE_PREFIX."niche_niches` where niche LIKE '%".$kwd."%' LIMIT ".$offset.",".$showRec;
			//echo $sql;
			$man_rs=$database->getRS($sql);
			if($man_rs) {
				$this->no=0;
				while($data=$database->getNextRow($man_rs)) {
					$str .= $this->getRow( $data );
				}
			}
			
			$str .="<tr><td><input type='hidden' id='xkwd' name='xkwd' value='".$kwd."' /></td></tr>";
			
			$sql = "select count(*) as numrows from `".TABLE_PREFIX."niche_niches` where niche LIKE '%".$kwd."%' ";
			$data=$database->getDataSingleRow($sql);
			$maxPage=ceil($data['numrows']/$showRec);
			//echo "maxpg:".$data['numrows'];
			if($page > 1)
			{
				$pPg=$page-1;
				$prev="<a href=\"?pg=$pPg&process=search&txtNiche=".$kwd."\">PREV</a>";
			}	
			else
			{
				$prev="PREV";
			}
								
			if($page < $maxPage)
			{
				$nPg=$page+1;
				$next="<a href=\"?pg=$nPg&process=search&txtNiche=".$kwd."\">NEXT</a>";
				//$prev="<a href=\"haveasay.php?pg=$page\">PREV</a>";
			}
			else
			{
				$next="NEXT";
			}
			$str .="<tr><td height=5px></td></tr>";	
			$str .="<tr><td colspan=2 align='center'>Total ".$data['numrows']." record(s) found. Showing ".$showRec." record(s) per page. </td></tr>" ;
			$str .="<tr><td height=2px></td></tr>";
			$str .="<tr><td colspan=2 align='center'>".$prev." Showing $page of $maxPage ".$next."</td></tr>" ;	
			
			return $str;
		}
		
}
?>