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
			$PSF_ROWS_PER_PAGE = ROWS_PER_PAGE;
			$PSF_TOTAL_PAGES = PAGE_LINKS;
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
				$temp = $this->page % $PSF_TOTAL_PAGES;
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
			
			if($this->start_page > $PSF_TOTAL_PAGES)
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
					$pages = "<a  class = 'general' href='?page=".$previous_page."&search=".$_GET["search"]."'>Previous</a>&nbsp;&nbsp;".$pages;
				}
				$str = "<table width='100%'>
				<tr>
					<td  align='center'>Total ".$this->totalrecords." $pagefor(s) found. Showing ".ROWS_PER_PAGE." $pagefor(s) per page </td>
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