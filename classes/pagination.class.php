<?php
class PSF_Pagination
{
var $PSF_TOTAL_PAGES;
var $page;
var $start_page;
var $end_page;
var $next_link;
var $previous_link;
var $show_pagination;
var $totalrecords;
var $startpos;
function setPagination($totalrecords)
{
global $blogspage;
$ms_db = new Database();
$ms_db->openDB();
$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
$user_data = $ms_db->getDataSingleRow($sql);
//ROWS_PER_PAGE =$user_data['rows_per_page']
//PAGE_LINKS =$user_data['page_links'];
$PSF_ROWS_PER_PAGE = $user_data['rows_per_page'];
$PSF_TOTAL_PAGES = $user_data['page_links'];
if($PSF_ROWS_PER_PAGE=='' || $PSF_ROWS_PER_PAGE==0){
$PSF_ROWS_PER_PAGE=15;
}
if($PSF_TOTAL_PAGES=='' || $PSF_TOTAL_PAGES==0){
$PSF_TOTAL_PAGES=5;
}
if($totalrecords == 0)
{
$this->show_pagination = false;
return;
}
$this->show_pagination = true;
$this->totalrecords = $totalrecords;
if(isset($_GET["page"]) && is_numeric($_GET["page"]))
{
$this->page = $_GET["page"];
$temp = @($this->page % $PSF_TOTAL_PAGES);
if($temp == 0)
{
$temp = $PSF_TOTAL_PAGES;
}
$this->start_page = $this->page - $temp + 1;
$this->end_page = $this->start_page + $PSF_TOTAL_PAGES - 1;
}
else
{
$this->page = 1;
$this->start_page = 1;
$this->end_page = $this->start_page + $PSF_TOTAL_PAGES - 1;
}
$this->PSF_TOTAL_PAGES = ceil($totalrecords / $PSF_ROWS_PER_PAGE);
if($this->page > $this->PSF_TOTAL_PAGES)
{
$this->page = $this->PSF_TOTAL_PAGES;
}
$this->startpos = (($this->page - 1) * $PSF_ROWS_PER_PAGE);
if($this->end_page > $this->PSF_TOTAL_PAGES)
{
$this->end_page = $this->PSF_TOTAL_PAGES;
}
if($PSF_TOTAL_PAGES==0)$PSF_TOTAL_PAGES=1;
$temp1 = $this->PSF_TOTAL_PAGES % $PSF_TOTAL_PAGES;
if($temp1 == 0)
{
$temp1 = $PSF_TOTAL_PAGES;
}
$temp = $this->PSF_TOTAL_PAGES - $temp1 + 1;
if($this->start_page < $temp)
{
$this->next_link = true;
}
else
{
$this->next_link = false;
}
if($this->start_page >= $PSF_TOTAL_PAGES)
{
$this->previous_link = true;
}
else
{
$this->previous_link = false;
}
}
function showPagination()
{
	global $pagefor;
	$ms_db = new Database();
	$ms_db->openDB();
	$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
	$user_data = $ms_db->getDataSingleRow($sql);
	if($PSF_ROWS_PER_PAGE=='' || $PSF_ROWS_PER_PAGE==0){
		$PSF_ROWS_PER_PAGE=15;
	}
	if($PSF_TOTAL_PAGES=='' || $PSF_TOTAL_PAGES==0){
		$PSF_TOTAL_PAGES=5;
	}
	if (!isset($pagefor)) $pagefor = "record";
	if($this->show_pagination === true)
	{
		$pages = "";
		/*for($x=$this->start_page;$x<= $this->end_page ;$x++)
		{
		if($this->page == $x)
		{
		$pages .= $x."&nbsp;&nbsp;";
		}
		else
		{
		$pages .= "<a class = 'general' href='?page=".$x."&search=".$_GET["search"]."'>".$x."</a>&nbsp;&nbsp;";
		}
		}
		if($this->next_link === true)
		{
		$next_page = $this->end_page + 1;
		$pages = $pages."<a class = 'general' href='?page=".$next_page."&search=".$_GET["search"]."'>Next</a>&nbsp;&nbsp;";
		}
		if($this->previous_link === true)
		{
		$previous_page = $this->start_page - 1;
		$pages = "<a  class = 'general' href='?page=".$previous_page."&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
		}*/
		$xPg=isset($_GET['page'])?$_GET['page']:1;
		if($xPg > 1)
		{
			$previous_page = $xPg - 1;
			$pages = "<a  class = 'general' href='?page=".$previous_page."&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
		}	
		else
		{
			$pages = $pages."Previous&nbsp;&nbsp;";
		}
		if($xPg < $this->PSF_TOTAL_PAGES){
			$next_page = $xPg + 1;
			$pages = $pages."<a class = 'general' href='?page=".$next_page."&search=".$_GET["search"]."'>Next</a>&nbsp;&nbsp;";
		}
		elseif($xPg >= $this->PSF_TOTAL_PAGES){
			$pages = $pages."Next&nbsp;&nbsp;";
		}
		$str = "<table width='100%'>
		<tr>
		<td  align='center'>Total ".$this->totalrecords." $pagefor(s) found. Showing ".$user_data['rows_per_page']." $pagefor(s) per page </td>
		</tr>
		<tr>
		<td align='center'>
		Pages: ".$pages."
		</td>
		</tr>
		</table>";
		echo $str;
		}
}

function showPaginationNVSB()
{
	global $pagefor;
	$ms_db = new Database();
	$ms_db->openDB();
	$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
	$user_data = $ms_db->getDataSingleRow($sql);
	if($PSF_ROWS_PER_PAGE=='' || $PSF_ROWS_PER_PAGE==0){
		$PSF_ROWS_PER_PAGE=15;
	}
	if($PSF_TOTAL_PAGES=='' || $PSF_TOTAL_PAGES==0){
		$PSF_TOTAL_PAGES=5;
	}
	if (!isset($pagefor)) $pagefor = "record";
	if($this->show_pagination === true)
	{
		$pages = "";
		/*for($x=$this->start_page;$x<= $this->end_page ;$x++)
		{
		if($this->page == $x)
		{
		$pages .= $x."&nbsp;&nbsp;";
		}
		else
		{
		$pages .= "<a class = 'general' href='?page=".$x."&search=".$_GET["search"]."'>".$x."</a>&nbsp;&nbsp;";
		}
		}
		if($this->next_link === true)
		{
		$next_page = $this->end_page + 1;
		$pages = $pages."<a class = 'general' href='?page=".$next_page."&search=".$_GET["search"]."'>Next</a>&nbsp;&nbsp;";
		}
		if($this->previous_link === true)
		{
		$previous_page = $this->start_page - 1;
		$pages = "<a  class = 'general' href='?page=".$previous_page."&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
		}*/
		$xPg=isset($_GET['page'])?$_GET['page']:1;
		if($xPg > 1)
		{
			$previous_page = $xPg - 1;
			$pages = "<a  class = 'general' href='?page=".$previous_page."&process=manage_nvsbsites&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
		}	
		else
		{
			$pages = $pages."Previous&nbsp;&nbsp;";
		}
		if($xPg < $this->PSF_TOTAL_PAGES){
			$next_page = $xPg + 1;
			$pages = $pages."<a class = 'general' href='?page=".$next_page."&process=manage_nvsbsites&search=".$_GET["search"]."'>Next</a>&nbsp;&nbsp;";
		}
		elseif($xPg >= $this->PSF_TOTAL_PAGES){
			$pages = $pages."Next&nbsp;&nbsp;";
		}
		$str = "<table width='100%'>
		<tr>
		<td  align='center'>Total ".$this->totalrecords." $pagefor(s) found. Showing ".$user_data['rows_per_page']." $pagefor(s) per page </td>
		</tr>
		<tr>
		<td align='center'>
		Pages: ".$pages."
		</td>
		</tr>
		</table>";
		echo $str;
		}
}

function showPaginationPSB()
{
	global $pagefor;
	$ms_db = new Database();
	$ms_db->openDB();
	$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
	$user_data = $ms_db->getDataSingleRow($sql);
	if($PSF_ROWS_PER_PAGE=='' || $PSF_ROWS_PER_PAGE==0){
		$PSF_ROWS_PER_PAGE=15;
	}
	if($PSF_TOTAL_PAGES=='' || $PSF_TOTAL_PAGES==0){
		$PSF_TOTAL_PAGES=5;
	}
	if (!isset($pagefor)) $pagefor = "record";
	if($this->show_pagination === true)
	{
		$pages = "";

		$xPg=isset($_GET['page'])?$_GET['page']:1;
		if($xPg > 1)
		{
			$previous_page = $xPg - 1;
			$pages = "<a  class = 'general' href='?page=".$previous_page."&process=manage_psbsites&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
		}	
		else
		{
			$pages = $pages."Previous&nbsp;&nbsp;";
		}
		if($xPg < $this->PSF_TOTAL_PAGES){
			$next_page = $xPg + 1;
			$pages = $pages."<a class = 'general' href='?page=".$next_page."&process=manage_psbsites&search=".$_GET["search"]."'>Next</a>&nbsp;&nbsp;";
		}
		elseif($xPg >= $this->PSF_TOTAL_PAGES){
			$pages = $pages."Next&nbsp;&nbsp;";
		}
		$str = "<table width='100%'>
		<tr>
		<td  align='center'>Total ".$this->totalrecords." $pagefor(s) found. Showing ".$user_data['rows_per_page']." $pagefor(s) per page </td>
		</tr>
		<tr>
		<td align='center'>
		Pages: ".$pages."
		</td>
		</tr>
		</table>";
		echo $str;
		}
}

function showPaginationNCSB()
{
	global $pagefor;
	$ms_db = new Database();
	$ms_db->openDB();
	$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
	$user_data = $ms_db->getDataSingleRow($sql);
	if($PSF_ROWS_PER_PAGE=='' || $PSF_ROWS_PER_PAGE==0){
		$PSF_ROWS_PER_PAGE=15;
	}
	if($PSF_TOTAL_PAGES=='' || $PSF_TOTAL_PAGES==0){
		$PSF_TOTAL_PAGES=5;
	}
	if (!isset($pagefor)) $pagefor = "record";
	if($this->show_pagination === true)
	{
		$pages = "";
		$xPg=isset($_GET['page'])?$_GET['page']:1;
		if($xPg > 1)
		{
			$previous_page = $xPg - 1;
			$pages = "<a  class = 'general' href='?page=".$previous_page."&process=manage_ncsbsites&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
		}	
		else
		{
			$pages = $pages."Previous&nbsp;&nbsp;";
		}
		if($xPg < $this->PSF_TOTAL_PAGES){
			$next_page = $xPg + 1;
			$pages = $pages."<a class = 'general' href='?page=".$next_page."&process=manage_ncsbsites&search=".$_GET["search"]."'>Next</a>&nbsp;&nbsp;";
		}
		elseif($xPg >= $this->PSF_TOTAL_PAGES){
			$pages = $pages."Next&nbsp;&nbsp;";
		}
		$str = "<table width='100%'>
		<tr>
		<td  align='center'>Total ".$this->totalrecords." $pagefor(s) found. Showing ".$user_data['rows_per_page']." $pagefor(s) per page </td>
		</tr>
		<tr>
		<td align='center'>
		Pages: ".$pages."
		</td>
		</tr>
		</table>";
		echo $str;
		}
}

function showPagination1()
{
	global $pagefor,$proc;
	$ms_db = new Database();
	$ms_db->openDB();
	$sql = "select * from ".TABLE_PREFIX."admin_settings_tb where user_id='".$_SESSION[SESSION_PREFIX.'sessionuserid']."'";
	$user_data = $ms_db->getDataSingleRow($sql);
	
	if($PSF_ROWS_PER_PAGE=='' || $PSF_ROWS_PER_PAGE==0){
		$PSF_ROWS_PER_PAGE=15;
	}
	if($PSF_TOTAL_PAGES=='' || $PSF_TOTAL_PAGES==0){
		$PSF_TOTAL_PAGES=5;
	}	
		
	if (!isset($pagefor)) $pagefor = "record";
	if($this->show_pagination === true)
	{
	$pages = "";
	for($x=$this->start_page;$x<= $this->end_page ;$x++)
	{
	if($this->page == $x)
	{
	$pages .= $x."&nbsp;&nbsp;";
	}
	else
	{
	// comment on 14 nov by sdei
	//$pages .= "<a class = 'general' href='?process=new&type=P&page=".$x."&search=".$_REQUEST["search"]."&amcat=".$_REQUEST["amcat"]."'>".$x."</a>&nbsp;&nbsp;";
	$pages .= "<a class = 'general' href='?type=P&page=".$x."&search=".$_REQUEST["search"]."&amcat=".$_REQUEST["amcat"]."'>".$x."</a>&nbsp;&nbsp;";
	}
	}
	if($this->next_link === true)
	{
	$next_page = $this->end_page + 1;
	$pages = $pages."<a class = 'general' href='?page=".$next_page."&search=".$_GET["search"]."&amcat=".$_GET["amcat"]."'>Next</a>&nbsp;&nbsp;";
	}
	if($this->previous_link === true)
	{
	$previous_page = $this->start_page - 1;
	$pages = "<a  class = 'general' href='?page=".$previous_page."&search=".$_GET["search"]."&amcat=".$_GET["amcat"]."'>Previous</a>&nbsp;&nbsp;".$pages;
	}
	$str = "<table width='100%'>
	<tr>
	<td  align='center'>Total ".$this->totalrecords." $pagefor(s) found. Showing ".$user_data['rows_per_page']." $pagefor(s) per page </td>
	</tr>
	<tr>
	<td align='center'>
	Pages: ".$pages."
	</td>
	</tr>
	</table>
	";
	echo $str;
	}
}
//////////////////////////////////////////////////////////
function showBlogsPagination()
{
	global $pagefor;
	
	
	if (!isset($pagefor)) $pagefor = "record";
	if($this->show_pagination === true)
	{
		$pages = "";
		for($x=$this->start_page;$x<= $this->end_page ;$x++)
		{
		if($this->page == $x)
		{
			$pages .= $x."&nbsp;&nbsp;";
		}
		else
		{
			$pages .= "<a class = 'general' href='?page=".$x."&search=".$_GET["search"]."'>".$x."</a>&nbsp;&nbsp;";
		}
		}
		if($this->next_link === true)
		{
			$next_page = $this->end_page + 1;
			$pages = $pages."<a class = 'general' href='?page=".$next_page."&search=".$_GET["search"]."'>Next</a>&nbsp;&nbsp;";
		}
		if($this->previous_link === true)
		{
			$previous_page = $this->start_page - 1;
			$pages = "<a class = 'general' href='?page=".$previous_page."&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
		}
		$str = "<table class='general'>
		<tr>
		<td class = 'psf_blog_pagination_matter_css'>Total ".$this->totalrecords." $pagefor(s) found. Showing ".PSF_TOTAL_BLOGS_PER_PAGE." $pagefor(s) per page </td>
		</tr>
		<tr>
		<td  class = 'psf_blog_pagination_matter_css'>
		Pages: ".$pages."
		</td>
		</tr>
		</table>
		";
			return $str;
		}
}
}

?>