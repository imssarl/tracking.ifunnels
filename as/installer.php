<?php
if(file_exists("config/config.php") && !isset($_POST['Submit']))
{	
	header("location:index.php");
	exit();
}

	//die("error1234");
	function checkForWriteable()
		{
			global $ok_to_install;
			$ok_config = false;
			$ok_temp_files = false;
			echo " <br>
          <br>		  
          <li>File/folder called &quot;config&quot ; in the root folder (usually public_htm or www or htdocs) <br>
		  (permission should be set to 0707 or above for this folder)
		  <br>
          ";


			if(!is_writable("config"))
			{
				if (!@chmod("config", 0707))
				{
					echo "<li><b><font color='red'>Current Status: Folder ..\\config is not writable </font></b>";
					$ok_to_install=false; 
					$ok_config = false;
				}
			}
			else
			{
				$ok_config = true;
			}



			if(!is_writable("temp_data"))
			{ 

				if (!@chmod("temp_data", 0707))
				{
					echo "<li><b><font color='red'>Current Status: Folder ..\\temp_data is not writable </font></b>";
					$ok_to_install=false; 
					$ok_temp_files = false;
				}
			}
			else
			{
				$ok_temp_files = true;
				echo "<li><b><font color='blue'>Current Status: Folder ..\\temp_data Ok</font></b>";
			}




			
			
			if ($ok_config === true)
			{
			$file = "config/config.php";
			$somecontent = "<li>This is the Config File. You have not completed the installation process complete. Complete it first.";
			if ($handle = @fopen($file, "w"))
			  {
						if (!@fwrite($handle, $somecontent))
						{
							 echo "<li><b><font color='red'>Current Status: Not Able To Write to Config File</font></b>";
							$ok_to_install=false; 
						}
						else
						 {
						echo "<li><b><font color='blue'>Current Status: Folder ..\\Config Ok</font></b>";
						}
			  }
				else
				{
				echo "<li><b><font color='red'>Current Status: Not Able To Create Temp File</font></b>";
				$ok_to_install=false; 
				}
			}
		}
	if((isset($_POST['install_db'])) && ($_POST['install_db']=="form1")){
			//echo "here ".$newversion;
			$host_name=$_POST['server_name'];
			$db_name=$_POST['database_name'];
			$user_name=$_POST['user_name'];
			$password=$_POST['password'];
			$tableprefix=trim($_POST['tableprefix']);
			$site_name=trim($_POST['site_name']);
			$site_path = trim($_POST['site_url']);
			$root_path = trim($_POST['root_path']);

		$error_msg="<LI>Could not connect to database.";
		error_reporting(E_ALL ^ E_WARNING);
		$error_msg1="<br><div align='center'><a href='javascript:history.go(-1)'><< Back</a></div>";
		$install=mysql_pconnect($host_name,$user_name,$password) or die("<table width='70%' border='1' align='center' bgcolor='#ffccff'><tr><td align='center'><font color='#FFFFFF'><b>Installation Error</b></font></td></tr><tr><td><font color='red'><b>".mysql_error().$error_msg.$error_msg1."</b></font></td></tr></table>");
		
			

//=============================================================================================		
			
			$install=mysql_pconnect($host_name,$user_name,$password);
			
	$sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."article`";
	$sql_data[]="CREATE TABLE `".$tableprefix."article` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `summary` varchar(255) collate utf8_unicode_ci default NULL,
  `body` text collate utf8_unicode_ci,
  `keyword` text collate utf8_unicode_ci,
  `flag` char(1) collate utf8_unicode_ci default NULL,
  `re_inject` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  PRIMARY KEY  (`id`)
	)";

	$sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."article_profile`";
	$sql_data[]="CREATE TABLE `".$tableprefix."article_profile` (
  `id` int(10) NOT NULL auto_increment,
  `article_id` int(10) default NULL,
  `profile_id` varchar(100) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ";
		
	$sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."category`";
	$sql_data[]="CREATE TABLE `".$tableprefix."category` (
  `id` int(10) NOT NULL auto_increment,
  `directory_id` int(10) default NULL,
  `url_id` int(10) default NULL,
  `cat_name` varchar(100) collate utf8_unicode_ci default NULL,
  `cat_id` int(10) default NULL,
  `user_id` int(15) NOT NULL,
  PRIMARY KEY  (`id`)
) ";

	$sql_data[]="INSERT INTO `".$tableprefix."category` VALUES
(1, 1, 1, 'Arts & Entertainment', 63,-1),
(2, 1, 1, 'Arts & Entertainment:Celebrities', 80,-1),
(3, 1, 1, 'Arts & Entertainment:Humanities', 76,-1),
(4, 1, 1, 'Arts & Entertainment:Movies', 81,-1),
(5, 1, 1, 'Arts & Entertainment:Music', 75,-1),
(6, 1, 1, 'Arts & Entertainment:Photography', 79,-1),
(7, 1, 1, 'Arts & Entertainment:Poetry', 78,-1),
(8, 1, 1, 'Business', 54,-1),
(9, 1, 1, 'Business:Advertising', 86,-1),
(10, 1, 1, 'Business:Article Marketing', 82,-1),
(11, 1, 1, 'Business:Careers', 90,-1),
(12, 1, 1, 'Business:Customer Service', 88,-1),
(13, 1, 1, 'Business:Entrepreneurs', 94,-1),
(14, 1, 1, 'Business:Ethics', 93,-1),
(15, 1, 1, 'Business:Home Based Business', 95,-1),
(16, 1, 1, 'Business:Management', 89,-1),
(17, 1, 1, 'Business:Marketing', 85,-1),
(18, 1, 1, 'Business:Networking', 91,-1),
(19, 1, 1, 'Business:Public Relations', 87,-1),
(20, 1, 1, 'Business:Sales', 84,-1),
(21, 1, 1, 'Business:Small Business', 92,-1),
(22, 1, 1, 'Communications', 59,-1),
(23, 1, 1, 'Communications:Broadband Internet', 165,-1),
(24, 1, 1, 'Communications:GPS', 170,-1),
(25, 1, 1, 'Communications:Mobile Phones', 166,-1),
(26, 1, 1, 'Communications:Satellite Radio', 168,-1),
(27, 1, 1, 'Communications:Satellite TV', 167,-1),
(28, 1, 1, 'Communications:Video Conferencing', 171,-1),
(29, 1, 1, 'Communications:VOIP', 169,-1),
(30, 1, 1, 'Computers', 58,-1),
(31, 1, 1, 'Computers:Computer Certification', 258,-1),
(32, 1, 1, 'Computers:Data Recovery', 162,-1),
(33, 1, 1, 'Computers:Games', 163,-1),
(34, 1, 1, 'Computers:Hardware', 160,-1),
(35, 1, 1, 'Computers:Networks', 164,-1),
(36, 1, 1, 'Computers:Software', 161,-1),
(37, 1, 1, 'Disease & Illness', 65,-1),
(38, 1, 1, 'Disease & Illness:Breast Cancer', 139,-1),
(39, 1, 1, 'Disease & Illness:Colon Cancer', 141,-1),
(40, 1, 1, 'Disease & Illness:Leukemia', 144,-1),
(41, 1, 1, 'Disease & Illness:Mesothelioma', 143,-1),
(42, 1, 1, 'Disease & Illness:Multiple Sclerosis', 264,-1),
(43, 1, 1, 'Disease & Illness:Ovarian Cancer', 140,-1),
(44, 1, 1, 'Disease & Illness:Prostate Cancer', 142,-1),
(45, 1, 1, 'Disease & Illness:Skin Cancer', 145,-1),
(46, 1, 1, 'Fashion', 251,-1),
(47, 1, 1, 'Fashion:Clothing', 262,-1),
(48, 1, 1, 'Fashion:Jewelry', 257,-1),
(49, 1, 1, 'Fashion:Shoes', 263,-1),
(50, 1, 1, 'Finance', 57,-1),
(51, 1, 1, 'Finance:Credit', 101,-1),
(52, 1, 1, 'Finance:Currency Trading', 109,-1),
(53, 1, 1, 'Finance:Debt Consolidation', 99,-1),
(54, 1, 1, 'Finance:Fundraising', 249,-1),
(55, 1, 1, 'Finance:Insurance', 103,-1),
(56, 1, 1, 'Finance:Investing', 97,-1),
(57, 1, 1, 'Finance:Leasing', 106,-1),
(58, 1, 1, 'Finance:Loans', 102,-1),
(59, 1, 1, 'Finance:Mortgage', 100,-1),
(60, 1, 1, 'Finance:Mutual Funds', 105,-1),
(61, 1, 1, 'Finance:Personal Finance', 108,-1),
(62, 1, 1, 'Finance:Real Estate', 98,-1),
(63, 1, 1, 'Finance:Stock Market', 104,-1),
(64, 1, 1, 'Finance:Taxes', 107,-1),
(65, 1, 1, 'Finance:Wealth Building', 96,-1),
(66, 1, 1, 'Food & Beverage', 66,-1),
(67, 1, 1, 'Food & Beverage:Coffee', 175,-1),
(68, 1, 1, 'Food & Beverage:Cooking', 173,-1),
(69, 1, 1, 'Food & Beverage:Gourmet', 176,-1),
(70, 1, 1, 'Food & Beverage:Recipes', 172,-1),
(71, 1, 1, 'Food & Beverage:Wine', 174,-1),
(72, 1, 1, 'Health & Fitness', 61,-1),
(73, 1, 1, 'Health & Fitness:Acne', 138,-1),
(74, 1, 1, 'Health & Fitness:Alternative Medicine', 131,-1),
(75, 1, 1, 'Health & Fitness:Beauty', 136,-1),
(76, 1, 1, 'Health & Fitness:Cardio', 124,-1),
(77, 1, 1, 'Health & Fitness:Depression', 137,-1),
(78, 1, 1, 'Health & Fitness:Diabetes', 132,-1),
(79, 1, 1, 'Health & Fitness:Exercise', 123,-1),
(80, 1, 1, 'Health & Fitness:Fitness Equipment', 125,-1),
(81, 1, 1, 'Health & Fitness:Hair Loss', 135,-1),
(82, 1, 1, 'Health & Fitness:Medicine', 130,-1),
(83, 1, 1, 'Health & Fitness:Meditation', 127,-1),
(84, 1, 1, 'Health & Fitness:Men''s Issues', 133,-1),
(85, 1, 1, 'Health & Fitness:Muscle Building', 122,-1),
(86, 1, 1, 'Health & Fitness:Nutrition', 128,-1),
(87, 1, 1, 'Health & Fitness:Supplements', 129,-1),
(88, 1, 1, 'Health & Fitness:Weight Loss', 121,-1),
(89, 1, 1, 'Health & Fitness:Women''s Issues', 134,-1),
(90, 1, 1, 'Health & Fitness:Yoga', 126,-1),
(91, 1, 1, 'Home & Family', 67,-1),
(92, 1, 1, 'Home & Family:Babies', 206,-1),
(93, 1, 1, 'Home & Family:Crafts', 210,-1),
(94, 1, 1, 'Home & Family:Elderly Care', 204,-1),
(95, 1, 1, 'Home & Family:Gardening', 203,-1),
(96, 1, 1, 'Home & Family:Hobbies', 211,-1),
(97, 1, 1, 'Home & Family:Holidays', 208,-1),
(98, 1, 1, 'Home & Family:Home Improvement', 199,-1),
(99, 1, 1, 'Home & Family:Home Security', 201,-1),
(100, 1, 1, 'Home & Family:Interior Design', 200,-1),
(101, 1, 1, 'Home & Family:Landscaping', 202,-1),
(102, 1, 1, 'Home & Family:Parenting', 205,-1),
(103, 1, 1, 'Home & Family:Pets', 207,-1),
(104, 1, 1, 'Home & Family:Pregnancy', 209,-1),
(105, 1, 1, 'Internet Business', 56,-1),
(106, 1, 1, 'Internet Business:Affiliate Programs', 188,-1),
(107, 1, 1, 'Internet Business:Auctions', 189,-1),
(108, 1, 1, 'Internet Business:Audio-Video Streaming', 192,-1),
(109, 1, 1, 'Internet Business:Blogging', 177,-1),
(110, 1, 1, 'Internet Business:Domains', 191,-1),
(111, 1, 1, 'Internet Business:Ebooks', 183,-1),
(112, 1, 1, 'Internet Business:Ecommerce', 184,-1),
(113, 1, 1, 'Internet Business:Email Marketing', 179,-1),
(114, 1, 1, 'Internet Business:Ezine Marketing', 181,-1),
(115, 1, 1, 'Internet Business:Ezine Publishing', 180,-1),
(116, 1, 1, 'Internet Business:Forums', 197,-1),
(117, 1, 1, 'Internet Business:Internet Marketing', 198,-1),
(118, 1, 1, 'Internet Business:Podcasts', 193,-1),
(119, 1, 1, 'Internet Business:PPC Advertising', 196,-1),
(120, 1, 1, 'Internet Business:RSS', 178,-1),
(121, 1, 1, 'Internet Business:Security', 190,-1),
(122, 1, 1, 'Internet Business:SEO', 194,-1),
(123, 1, 1, 'Internet Business:Site Promotion', 187,-1),
(124, 1, 1, 'Internet Business:Spam', 182,-1),
(125, 1, 1, 'Internet Business:Traffic Generation', 195,-1),
(126, 1, 1, 'Internet Business:Web Design', 185,-1),
(127, 1, 1, 'Internet Business:Web Hosting', 186,-1),
(128, 1, 1, 'Politics', 70,-1),
(129, 1, 1, 'Politics:Commentary', 229,-1),
(130, 1, 1, 'Politics:Current Events', 226,-1),
(131, 1, 1, 'Politics:History', 227,-1),
(132, 1, 1, 'Product Reviews', 73,-1),
(133, 1, 1, 'Product Reviews:Book Reviews', 242,-1),
(134, 1, 1, 'Product Reviews:Consumer Electronics', 243,-1),
(135, 1, 1, 'Product Reviews:Digital Products', 245,-1),
(136, 1, 1, 'Product Reviews:Movie Reviews', 247,-1),
(137, 1, 1, 'Product Reviews:Music Reviews', 246,-1),
(138, 1, 1, 'Recreation & Sports', 72,-1),
(139, 1, 1, 'Recreation & Sports:Biking', 236,-1),
(140, 1, 1, 'Recreation & Sports:Extreme', 241,-1),
(141, 1, 1, 'Recreation & Sports:Fishing', 238,-1),
(142, 1, 1, 'Recreation & Sports:Gambling & Casinos', 248,-1),
(143, 1, 1, 'Recreation & Sports:Golf', 235,-1),
(144, 1, 1, 'Recreation & Sports:Hunting', 239,-1),
(145, 1, 1, 'Recreation & Sports:Martial Arts', 237,-1),
(146, 1, 1, 'Recreation & Sports:Running', 240,-1),
(147, 1, 1, 'Recreation & Sports:Tennis', 270,-1),
(148, 1, 1, 'Reference & Education', 64,-1),
(149, 1, 1, 'Reference & Education:Adult', 267,-1),
(150, 1, 1, 'Reference & Education:College', 118,-1),
(151, 1, 1, 'Reference & Education:Environmental', 266,-1),
(152, 1, 1, 'Reference & Education:Homeschooling', 261,-1),
(153, 1, 1, 'Reference & Education:K-12 Education', 260,-1),
(154, 1, 1, 'Reference & Education:Language', 119,-1),
(155, 1, 1, 'Reference & Education:Legal', 265,-1),
(156, 1, 1, 'Reference & Education:Philosophy', 120,-1),
(157, 1, 1, 'Reference & Education:Psychology', 115,-1),
(158, 1, 1, 'Reference & Education:Science', 117,-1),
(159, 1, 1, 'Reference & Education:Sociology', 116,-1),
(160, 1, 1, 'Reference & Education:Weather', 268,-1),
(161, 1, 1, 'Self Improvement', 62,-1),
(162, 1, 1, 'Self Improvement:Attraction', 148,-1),
(163, 1, 1, 'Self Improvement:Coaching', 154,-1),
(164, 1, 1, 'Self Improvement:Creativity', 259,-1),
(165, 1, 1, 'Self Improvement:Goal Setting', 155,-1),
(166, 1, 1, 'Self Improvement:Grief', 159,-1),
(167, 1, 1, 'Self Improvement:Happiness', 158,-1),
(168, 1, 1, 'Self Improvement:Innovation', 149,-1),
(169, 1, 1, 'Self Improvement:Inspirational', 152,-1),
(170, 1, 1, 'Self Improvement:Leadership', 147,-1),
(171, 1, 1, 'Self Improvement:Motivation', 151,-1),
(172, 1, 1, 'Self Improvement:Organizing', 157,-1),
(173, 1, 1, 'Self Improvement:Spirituality', 153,-1),
(174, 1, 1, 'Self Improvement:Stress Management', 156,-1),
(175, 1, 1, 'Self Improvement:Success', 146,-1),
(176, 1, 1, 'Self Improvement:Time Management', 150,-1),
(177, 1, 1, 'Society', 69,-1),
(178, 1, 1, 'Society:Dating', 220,-1),
(179, 1, 1, 'Society:Divorce', 223,-1),
(180, 1, 1, 'Society:Marriage', 221,-1),
(181, 1, 1, 'Society:Relationships', 219,-1),
(182, 1, 1, 'Society:Religion', 225,-1),
(183, 1, 1, 'Society:Sexuality', 224,-1),
(184, 1, 1, 'Society:Weddings', 222,-1),
(185, 1, 1, 'Travel & Leisure', 68,-1),
(186, 1, 1, 'Travel & Leisure:Aviation', 216,-1),
(187, 1, 1, 'Travel & Leisure:Boating', 215,-1),
(188, 1, 1, 'Travel & Leisure:Cruises', 214,-1),
(189, 1, 1, 'Travel & Leisure:Destinations', 217,-1),
(190, 1, 1, 'Travel & Leisure:Outdoors', 213,-1),
(191, 1, 1, 'Travel & Leisure:Travel Tips', 218,-1),
(192, 1, 1, 'Travel & Leisure:Vacations', 212,-1),
(193, 1, 1, 'Vehicles', 71,-1),
(194, 1, 1, 'Vehicles:Boats', 233,-1),
(195, 1, 1, 'Vehicles:Cars', 230,-1),
(196, 1, 1, 'Vehicles:Motorcycles', 232,-1),
(197, 1, 1, 'Vehicles:RVs', 234,-1),
(198, 1, 1, 'Vehicles:Trucks-SUVS', 231,-1),
(199, 1, 1, 'Writing & Speaking', 60,-1),
(200, 1, 1, 'Writing & Speaking:Article Writing', 113,-1),
(201, 1, 1, 'Writing & Speaking:Book Marketing', 114,-1),
(202, 1, 1, 'Writing & Speaking:Copywriting', 111,-1),
(203, 1, 1, 'Writing & Speaking:Public Speaking', 112,-1),
(204, 1, 1, 'Writing & Speaking:Writing', 110,-1),
(205, 2, 2, 'Advertising', 1,-1),
(206, 2, 2, 'Advice', 2,-1),
(207, 2, 2, 'Affiliate Programs', 3,-1),
(208, 2, 2, 'Autos', 65,-1),
(209, 2, 2, 'Awards', 4,-1),
(210, 2, 2, 'Blogs', 92,-1),
(211, 2, 2, 'Book Reviews', 77,-1),
(212, 2, 2, 'Business', 5,-1),
(213, 2, 2, 'Careers', 66,-1),
(214, 2, 2, 'CGI', 6,-1),
(215, 2, 2, 'Communication', 67,-1),
(216, 2, 2, 'Computers', 7,-1),
(217, 2, 2, 'Copywriting', 8,-1),
(218, 2, 2, 'CSS', 9,-1),
(219, 2, 2, 'Dating', 78,-1),
(220, 2, 2, 'DHTML', 10,-1),
(221, 2, 2, 'Direct Mail', 11,-1),
(222, 2, 2, 'Domain Names', 12,-1),
(223, 2, 2, 'EBooks', 13,-1),
(224, 2, 2, 'ECommerce', 14,-1),
(225, 2, 2, 'Education', 15,-1),
(226, 2, 2, 'Email', 16,-1),
(227, 2, 2, 'Entertainment', 68,-1),
(228, 2, 2, 'Environment', 17,-1),
(229, 2, 2, 'Family', 18,-1),
(230, 2, 2, 'Finance', 19,-1),
(231, 2, 2, 'Fitness', 75,-1),
(232, 2, 2, 'Food', 74,-1),
(233, 2, 2, 'Free', 20,-1),
(234, 2, 2, 'Gambling', 79,-1),
(235, 2, 2, 'Gardening', 69,-1),
(236, 2, 2, 'Government', 21,-1),
(237, 2, 2, 'Health', 22,-1),
(238, 2, 2, 'Hobbies', 23,-1),
(239, 2, 2, 'Home Accessories', 80,-1),
(240, 2, 2, 'Home Business', 24,-1),
(241, 2, 2, 'Home Repair', 25,-1),
(242, 2, 2, 'HTML', 26,-1),
(243, 2, 2, 'Humor', 27,-1),
(244, 2, 2, 'Insurance', 81,-1),
(245, 2, 2, 'Internet', 28,-1),
(246, 2, 2, 'Investment', 82,-1),
(247, 2, 2, 'Javascript', 29,-1),
(248, 2, 2, 'Law', 30,-1),
(249, 2, 2, 'Link Popularity', 31,-1),
(250, 2, 2, 'Malware', 83,-1),
(251, 2, 2, 'Management', 70,-1),
(252, 2, 2, 'Marketing', 32,-1),
(253, 2, 2, 'Marriage', 71,-1),
(254, 2, 2, 'Metaphysical', 33,-1),
(255, 2, 2, 'MLM', 34,-1),
(256, 2, 2, 'Motivational', 35,-1),
(257, 2, 2, 'Multimedia', 36,-1),
(258, 2, 2, 'Music', 84,-1),
(259, 2, 2, 'Newsletters', 37,-1),
(260, 2, 2, 'Non-Profit', 85,-1),
(261, 2, 2, 'Off line Promotion', 38,-1),
(262, 2, 2, 'Online Promotion', 39,-1),
(263, 2, 2, 'Other', 40,-1),
(264, 2, 2, 'Outdoors', 86,-1),
(265, 2, 2, 'Pets', 76,-1),
(266, 2, 2, 'Politics', 41,-1),
(267, 2, 2, 'Press Releases', 87,-1),
(268, 2, 2, 'Product Reviews', 88,-1),
(269, 2, 2, 'Psychology', 42,-1),
(270, 2, 2, 'Publishing', 72,-1),
(271, 2, 2, 'Real Estate', 89,-1),
(272, 2, 2, 'Religion', 43,-1),
(273, 2, 2, 'RSS', 90,-1),
(274, 2, 2, 'Sales', 44,-1),
(275, 2, 2, 'Scams', 45,-1),
(276, 2, 2, 'Science', 46,-1),
(277, 2, 2, 'Self Help', 50,-1),
(278, 2, 2, 'SE Optimization', 47,-1),
(279, 2, 2, 'SE Positioning', 48,-1),
(280, 2, 2, 'SE Tactics', 49,-1),
(281, 2, 2, 'Sexuality', 51,-1),
(282, 2, 2, 'Site Security', 52,-1),
(283, 2, 2, 'Social Issues', 53,-1),
(284, 2, 2, 'Spam', 54,-1),
(285, 2, 2, 'Spirituality', 91,-1),
(286, 2, 2, 'Sports', 55,-1),
(287, 2, 2, 'Technology', 56,-1),
(288, 2, 2, 'Traffic Analysis', 57,-1),
(289, 2, 2, 'Travel', 58,-1),
(290, 2, 2, 'Viral Marketing', 59,-1),
(291, 2, 2, 'Web Design', 61,-1),
(292, 2, 2, 'Web Hosting', 60,-1),
(293, 2, 2, 'Webmasters', 62,-1),
(294, 2, 2, 'Weight Loss', 73,-1),

(295, 2, 2, 'Women''s Issues', 63,-1),
(296, 2, 2, 'Writing', 64,-1),
(297, 3, 3, 'Arts-and-Entertainment', NULL,-1),
(298, 3, 3, 'Arts-and-Entertainment:Astrology', NULL,-1),
(299, 3, 3, 'Arts-and-Entertainment:Casino-Gambling', NULL,-1),
(300, 3, 3, 'Arts-and-Entertainment:Humanities', NULL,-1),
(301, 3, 3, 'Arts-and-Entertainment:Humor', NULL,-1),
(302, 3, 3, 'Arts-and-Entertainment:Movies-TV', NULL,-1),
(303, 3, 3, 'Arts-and-Entertainment:Music', NULL,-1),
(304, 3, 3, 'Arts-and-Entertainment:Performing-Arts', NULL,-1),
(305, 3, 3, 'Arts-and-Entertainment:Philosophy', NULL,-1),
(306, 3, 3, 'Arts-and-Entertainment:Photography', NULL,-1),
(307, 3, 3, 'Arts-and-Entertainment:Poetry', NULL,-1),
(308, 3, 3, 'Arts-and-Entertainment:Tattoos', NULL,-1),
(309, 3, 3, 'Arts-and-Entertainment:Visual-Graphic-Arts', NULL,-1),
(310, 3, 3, 'Automotive', NULL,-1),
(311, 3, 3, 'Automotive:ATV', NULL,-1),
(312, 3, 3, 'Automotive:Mobile-Audio-Video', NULL,-1),
(313, 3, 3, 'Automotive:Motorcycles', NULL,-1),
(314, 3, 3, 'Automotive:RV', NULL,-1),
(315, 3, 3, 'Automotive:Repairs', NULL,-1),
(316, 3, 3, 'Automotive:Trucks', NULL,-1),
(317, 3, 3, 'Business', NULL,-1),
(318, 3, 3, 'Business:Accounting', NULL,-1),
(319, 3, 3, 'Business:Accounting-Payroll', NULL,-1),
(320, 3, 3, 'Business:Advertising', NULL,-1),
(321, 3, 3, 'Business:Branding', NULL,-1),
(322, 3, 3, 'Business:Careers-Employment', NULL,-1),
(323, 3, 3, 'Business:Change-Management', NULL,-1),
(324, 3, 3, 'Business:Continuity-Disaster-Recovery', NULL,-1),
(325, 3, 3, 'Business:Customer-Service', NULL,-1),
(326, 3, 3, 'Business:Entrepreneurialism', NULL,-1),
(327, 3, 3, 'Business:Ethics', NULL,-1),
(328, 3, 3, 'Business:Franchising', NULL,-1),
(329, 3, 3, 'Business:Fundraising', NULL,-1),
(330, 3, 3, 'Business:Human-Resources', NULL,-1),
(331, 3, 3, 'Business:Industrial-Mechanical', NULL,-1),
(332, 3, 3, 'Business:International-Business', NULL,-1),
(333, 3, 3, 'Business:Management', NULL,-1),
(334, 3, 3, 'Business:Marketing', NULL,-1),
(335, 3, 3, 'Business:Marketing-Direct', NULL,-1),
(336, 3, 3, 'Business:Negotiation', NULL,-1),
(337, 3, 3, 'Business:Networking', NULL,-1),
(338, 3, 3, 'Business:Non-Profit', NULL,-1),
(339, 3, 3, 'Business:Outsourcing', NULL,-1),
(340, 3, 3, 'Business:PR', NULL,-1),
(341, 3, 3, 'Business:Presentation', NULL,-1),
(342, 3, 3, 'Business:Productivity', NULL,-1),
(343, 3, 3, 'Business:Resumes-Cover-Letters', NULL,-1),
(344, 3, 3, 'Business:Retail', NULL,-1),
(345, 3, 3, 'Business:Sales', NULL,-1),
(346, 3, 3, 'Business:Sales-Management', NULL,-1),
(347, 3, 3, 'Business:Sales-Teleselling', NULL,-1),
(348, 3, 3, 'Business:Sales-Training', NULL,-1),
(349, 3, 3, 'Business:Security', NULL,-1),
(350, 3, 3, 'Business:Small-Business', NULL,-1),
(351, 3, 3, 'Business:Solo-Professionals', NULL,-1),
(352, 3, 3, 'Business:Strategic-Planning', NULL,-1),
(353, 3, 3, 'Business:Team-Building', NULL,-1),
(354, 3, 3, 'Business:Top7-or-10-Tips', NULL,-1),
(355, 3, 3, 'Business:Venture-Capital', NULL,-1),
(356, 3, 3, 'Business:Workplace-Communication', NULL,-1),
(357, 3, 3, 'Cancer', NULL,-1),
(358, 3, 3, 'Cancer:Breast-Cancer', NULL,-1),
(359, 3, 3, 'Cancer:Colon-Rectal-Cancer', NULL,-1),
(360, 3, 3, 'Cancer:Leukemia-Lymphoma-Cancer', NULL,-1),
(361, 3, 3, 'Cancer:Lung-Mesothelioma-Asbestos', NULL,-1),
(362, 3, 3, 'Cancer:Ovarian-Cervical-Uterine-Cancer', NULL,-1),
(363, 3, 3, 'Cancer:Prostate-Cancer', NULL,-1),
(364, 3, 3, 'Cancer:Skin-Cancer', NULL,-1),
(365, 3, 3, 'Communications', NULL,-1),
(366, 3, 3, 'Communications:Broadband-Internet', NULL,-1),
(367, 3, 3, 'Communications:Fax', NULL,-1),
(368, 3, 3, 'Communications:GPS', NULL,-1),
(369, 3, 3, 'Communications:Mobile-Cell-Phone', NULL,-1),
(370, 3, 3, 'Communications:Mobile-Cell-Phone-Accessories', NULL,-1),
(371, 3, 3, 'Communications:Mobile-Cell-Phone-Reviews', NULL,-1),
(372, 3, 3, 'Communications:Mobile-Cell-Phone-SMS', NULL,-1),
(373, 3, 3, 'Communications:Satellite-Radio', NULL,-1),
(374, 3, 3, 'Communications:Satellite-TV', NULL,-1),
(375, 3, 3, 'Communications:Telephone-Systems', NULL,-1),
(376, 3, 3, 'Communications:VOIP', NULL,-1),
(377, 3, 3, 'Communications:Video-Conferencing', NULL,-1),
(378, 3, 3, 'Computers-and-Technology', NULL,-1),
(379, 3, 3, 'Computers-and-Technology:Certification-Tests', NULL,-1),
(380, 3, 3, 'Computers-and-Technology:Computer-Forensics', NULL,-1),
(381, 3, 3, 'Computers-and-Technology:Data-Recovery', NULL,-1),
(382, 3, 3, 'Computers-and-Technology:Hardware', NULL,-1),
(383, 3, 3, 'Computers-and-Technology:Mobile-Computing', NULL,-1),
(384, 3, 3, 'Computers-and-Technology:Personal-Tech', NULL,-1),
(385, 3, 3, 'Computers-and-Technology:Software', NULL,-1),
(386, 3, 3, 'Finance', NULL,-1),
(387, 3, 3, 'Finance:Auto-Loans', NULL,-1),
(388, 3, 3, 'Finance:Bankruptcy', NULL,-1),
(389, 3, 3, 'Finance:Bankruptcy-Lawyers', NULL,-1),
(390, 3, 3, 'Finance:Bankruptcy-Medical', NULL,-1),
(391, 3, 3, 'Finance:Bankruptcy-Personal', NULL,-1),
(392, 3, 3, 'Finance:Bankruptcy-Tips-Advice', NULL,-1),
(393, 3, 3, 'Finance:Commercial-Loans', NULL,-1),
(394, 3, 3, 'Finance:Credit', NULL,-1),
(395, 3, 3, 'Finance:Credit-Counseling', NULL,-1),
(396, 3, 3, 'Finance:Credit-Tips', NULL,-1),
(397, 3, 3, 'Finance:Currency-Trading', NULL,-1),
(398, 3, 3, 'Finance:Debt-Consolidation', NULL,-1),
(399, 3, 3, 'Finance:Debt-Management', NULL,-1),
(400, 3, 3, 'Finance:Debt-Relief', NULL,-1),
(401, 3, 3, 'Finance:Estate-Plan-Trusts', NULL,-1),
(402, 3, 3, 'Finance:Home-Equity-Loans', NULL,-1),
(403, 3, 3, 'Finance:Leases-Leasing', NULL,-1),
(404, 3, 3, 'Finance:Loans', NULL,-1),
(405, 3, 3, 'Finance:PayDay-Loans', NULL,-1),
(406, 3, 3, 'Finance:Personal-Finance', NULL,-1),
(407, 3, 3, 'Finance:Personal-Loans', NULL,-1),
(408, 3, 3, 'Finance:Structured-Settlements', NULL,-1),
(409, 3, 3, 'Finance:Student-Loans', NULL,-1),
(410, 3, 3, 'Finance:Taxes', NULL,-1),
(411, 3, 3, 'Finance:Taxes-Income', NULL,-1),
(412, 3, 3, 'Finance:Taxes-Property', NULL,-1),
(413, 3, 3, 'Finance:Taxes-Relief', NULL,-1),
(414, 3, 3, 'Finance:Taxes-Tools', NULL,-1),
(415, 3, 3, 'Finance:Unsecured-Loans', NULL,-1),
(416, 3, 3, 'Finance:VA-Loans', NULL,-1),
(417, 3, 3, 'Finance:Wealth-Building', NULL,-1),
(418, 3, 3, 'Food-and-Drink', NULL,-1),
(419, 3, 3, 'Food-and-Drink:Chocolate', NULL,-1),
(420, 3, 3, 'Food-and-Drink:Coffee', NULL,-1),
(421, 3, 3, 'Food-and-Drink:Cooking-Tips', NULL,-1),
(422, 3, 3, 'Food-and-Drink:Crockpot-Recipes', NULL,-1),
(423, 3, 3, 'Food-and-Drink:Desserts', NULL,-1),
(424, 3, 3, 'Food-and-Drink:Low-Calorie', NULL,-1),
(425, 3, 3, 'Food-and-Drink:Main-Course', NULL,-1),
(426, 3, 3, 'Food-and-Drink:Pasta-Dishes', NULL,-1),
(427, 3, 3, 'Food-and-Drink:Recipes', NULL,-1),
(428, 3, 3, 'Food-and-Drink:Restaurant-Reviews', NULL,-1),
(429, 3, 3, 'Food-and-Drink:Salads', NULL,-1),
(430, 3, 3, 'Food-and-Drink:Soups', NULL,-1),
(431, 3, 3, 'Food-and-Drink:Tea', NULL,-1),
(432, 3, 3, 'Food-and-Drink:Wine-Spirits', NULL,-1),
(433, 3, 3, 'Gaming', NULL,-1),
(434, 3, 3, 'Gaming:Communities', NULL,-1),
(435, 3, 3, 'Gaming:Computer-Games', NULL,-1),
(436, 3, 3, 'Gaming:Console-Games', NULL,-1),
(437, 3, 3, 'Gaming:Console-Systems', NULL,-1),
(438, 3, 3, 'Gaming:Online-Gaming', NULL,-1),
(439, 3, 3, 'Gaming:Video-Game-Reviews', NULL,-1),
(440, 3, 3, 'Health-and-Fitness', NULL,-1),
(441, 3, 3, 'Health-and-Fitness:Acne', NULL,-1),
(442, 3, 3, 'Health-and-Fitness:Aerobics-Cardio', NULL,-1),
(443, 3, 3, 'Health-and-Fitness:Allergies', NULL,-1),
(444, 3, 3, 'Health-and-Fitness:Alternative', NULL,-1),
(445, 3, 3, 'Health-and-Fitness:Anti-Aging', NULL,-1),
(446, 3, 3, 'Health-and-Fitness:Anxiety', NULL,-1),
(447, 3, 3, 'Health-and-Fitness:Arthritis', NULL,-1),
(448, 3, 3, 'Health-and-Fitness:Asthma', NULL,-1),
(449, 3, 3, 'Health-and-Fitness:Back-Pain', NULL,-1),
(450, 3, 3, 'Health-and-Fitness:Beauty', NULL,-1),
(451, 3, 3, 'Health-and-Fitness:Build-Muscle', NULL,-1),
(452, 3, 3, 'Health-and-Fitness:Contraceptives-Birth-Control', NULL,-1),
(453, 3, 3, 'Health-and-Fitness:Critical-Care', NULL,-1),
(454, 3, 3, 'Health-and-Fitness:Dental-Care', NULL,-1),
(455, 3, 3, 'Health-and-Fitness:Depression', NULL,-1),
(456, 3, 3, 'Health-and-Fitness:Developmental-Disabilities', NULL,-1),
(457, 3, 3, 'Health-and-Fitness:Diabetes', NULL,-1),
(458, 3, 3, 'Health-and-Fitness:Disability', NULL,-1),
(459, 3, 3, 'Health-and-Fitness:Diseases', NULL,-1),
(460, 3, 3, 'Health-and-Fitness:Diseases-Multiple-Sclerosis', NULL,-1),
(461, 3, 3, 'Health-and-Fitness:Diseases-STD''s', NULL,-1),
(462, 3, 3, 'Health-and-Fitness:Drug-Abuse', NULL,-1),
(463, 3, 3, 'Health-and-Fitness:Ears-Hearing', NULL,-1),
(464, 3, 3, 'Health-and-Fitness:Eating-Disorders', NULL,-1),
(465, 3, 3, 'Health-and-Fitness:Ergonomics', NULL,-1),
(466, 3, 3, 'Health-and-Fitness:Exercise', NULL,-1),
(467, 3, 3, 'Health-and-Fitness:Eyes-Vision', NULL,-1),
(468, 3, 3, 'Health-and-Fitness:Fitness-Equipment', NULL,-1),
(469, 3, 3, 'Health-and-Fitness:Hair-Loss', NULL,-1),
(470, 3, 3, 'Health-and-Fitness:Healing-Arts', NULL,-1),
(471, 3, 3, 'Health-and-Fitness:Healthcare-Systems', NULL,-1),
(472, 3, 3, 'Health-and-Fitness:Heart-Disease', NULL,-1),
(473, 3, 3, 'Health-and-Fitness:Home-Health-Care', NULL,-1),
(474, 3, 3, 'Health-and-Fitness:Hypertension', NULL,-1),
(475, 3, 3, 'Health-and-Fitness:Massage', NULL,-1),
(476, 3, 3, 'Health-and-Fitness:Medicine', NULL,-1),
(477, 3, 3, 'Health-and-Fitness:Meditation', NULL,-1),
(478, 3, 3, 'Health-and-Fitness:Men''s-Issues', NULL,-1),
(479, 3, 3, 'Health-and-Fitness:Mental-Health', NULL,-1),
(480, 3, 3, 'Health-and-Fitness:Mind-Body-Spirit', NULL,-1),
(481, 3, 3, 'Health-and-Fitness:Nutrition', NULL,-1),
(482, 3, 3, 'Health-and-Fitness:Obesity', NULL,-1),
(483, 3, 3, 'Health-and-Fitness:Pain-Management', NULL,-1),
(484, 3, 3, 'Health-and-Fitness:Physical-Therapy', NULL,-1),
(485, 3, 3, 'Health-and-Fitness:Popular-Diets', NULL,-1),
(486, 3, 3, 'Health-and-Fitness:Quit-Smoking', NULL,-1),
(487, 3, 3, 'Health-and-Fitness:Skin-Care', NULL,-1),
(488, 3, 3, 'Health-and-Fitness:Sleep-Snoring', NULL,-1),
(489, 3, 3, 'Health-and-Fitness:Speech-Pathology', NULL,-1),
(490, 3, 3, 'Health-and-Fitness:Supplements', NULL,-1),
(491, 3, 3, 'Health-and-Fitness:Weight-Loss', NULL,-1),
(492, 3, 3, 'Health-and-Fitness:Women''s-Issues', NULL,-1),
(493, 3, 3, 'Health-and-Fitness:Yoga', NULL,-1),
(494, 3, 3, 'Home-Based-Business', NULL,-1),
(495, 3, 3, 'Home-Based-Business:Network-Marketing', NULL,-1),
(496, 3, 3, 'Home-Improvement', NULL,-1),
(497, 3, 3, 'Home-Improvement:Appliances', NULL,-1),
(498, 3, 3, 'Home-Improvement:Audio-Video', NULL,-1),
(499, 3, 3, 'Home-Improvement:Bath-and-Shower', NULL,-1),
(500, 3, 3, 'Home-Improvement:Cabinets', NULL,-1),
(501, 3, 3, 'Home-Improvement:Cleaning-Tips-and-Tools', NULL,-1),
(502, 3, 3, 'Home-Improvement:Concrete', NULL,-1),
(503, 3, 3, 'Home-Improvement:DIY', NULL,-1),
(504, 3, 3, 'Home-Improvement:Doors', NULL,-1),
(505, 3, 3, 'Home-Improvement:Electrical', NULL,-1),
(506, 3, 3, 'Home-Improvement:Energy-Efficiency', NULL,-1),
(507, 3, 3, 'Home-Improvement:Feng-Shui', NULL,-1),
(508, 3, 3, 'Home-Improvement:Flooring', NULL,-1),
(509, 3, 3, 'Home-Improvement:Foundation', NULL,-1),
(510, 3, 3, 'Home-Improvement:Furniture', NULL,-1),
(511, 3, 3, 'Home-Improvement:Heating-and-Air-Conditioning', NULL,-1),
(512, 3, 3, 'Home-Improvement:House-Plans', NULL,-1),
(513, 3, 3, 'Home-Improvement:Interior-Design-and-Decorating', NULL,-1),
(514, 3, 3, 'Home-Improvement:Kitchen-Improvements', NULL,-1),
(515, 3, 3, 'Home-Improvement:Landscaping-Outdoor-Decorating', NULL,-1),
(516, 3, 3, 'Home-Improvement:Lighting', NULL,-1),
(517, 3, 3, 'Home-Improvement:New-Construction', NULL,-1),
(518, 3, 3, 'Home-Improvement:Painting', NULL,-1),
(519, 3, 3, 'Home-Improvement:Patio-Deck', NULL,-1),
(520, 3, 3, 'Home-Improvement:Pest-Control', NULL,-1),
(521, 3, 3, 'Home-Improvement:Plumbing', NULL,-1),
(522, 3, 3, 'Home-Improvement:Remodeling', NULL,-1),
(523, 3, 3, 'Home-Improvement:Roofing', NULL,-1),
(524, 3, 3, 'Home-Improvement:Security', NULL,-1),
(525, 3, 3, 'Home-Improvement:Stone-Brick', NULL,-1),
(526, 3, 3, 'Home-Improvement:Storage-Garage', NULL,-1),
(527, 3, 3, 'Home-Improvement:Swimming-Pools-Spas', NULL,-1),
(528, 3, 3, 'Home-Improvement:Tools-and-Equipment', NULL,-1),
(529, 3, 3, 'Home-Improvement:Windows', NULL,-1),
(530, 3, 3, 'Home-Improvement:Yard-Equipment', NULL,-1),
(531, 3, 3, 'Home-and-Family', NULL,-1),
(532, 3, 3, 'Home-and-Family:Babies-Toddler', NULL,-1),
(533, 3, 3, 'Home-and-Family:Baby-Boomer', NULL,-1),
(534, 3, 3, 'Home-and-Family:Crafts-Hobbies', NULL,-1),
(535, 3, 3, 'Home-and-Family:Crafts-Supplies', NULL,-1),
(536, 3, 3, 'Home-and-Family:Death-Dying', NULL,-1),
(537, 3, 3, 'Home-and-Family:Elder-Care', NULL,-1),
(538, 3, 3, 'Home-and-Family:Entertaining', NULL,-1),
(539, 3, 3, 'Home-and-Family:Fatherhood', NULL,-1),
(540, 3, 3, 'Home-and-Family:Gardening', NULL,-1),
(541, 3, 3, 'Home-and-Family:Grandparenting', NULL,-1),
(542, 3, 3, 'Home-and-Family:Holidays', NULL,-1),
(543, 3, 3, 'Home-and-Family:Motherhood', NULL,-1),
(544, 3, 3, 'Home-and-Family:Parenting', NULL,-1),
(545, 3, 3, 'Home-and-Family:Parties', NULL,-1),
(546, 3, 3, 'Home-and-Family:Pregnancy', NULL,-1),
(547, 3, 3, 'Home-and-Family:Retirement', NULL,-1),
(548, 3, 3, 'Home-and-Family:Scrapbooking', NULL,-1),
(549, 3, 3, 'Insurance', NULL,-1),
(550, 3, 3, 'Insurance:Agents-Marketers', NULL,-1),
(551, 3, 3, 'Insurance:Car-Auto', NULL,-1),
(552, 3, 3, 'Insurance:Commercial', NULL,-1),
(553, 3, 3, 'Insurance:Dental', NULL,-1),
(554, 3, 3, 'Insurance:Disability', NULL,-1),
(555, 3, 3, 'Insurance:Flood', NULL,-1),
(556, 3, 3, 'Insurance:Health', NULL,-1),
(557, 3, 3, 'Insurance:Home-Owners-Renters', NULL,-1),
(558, 3, 3, 'Insurance:Life-Annuities', NULL,-1),
(559, 3, 3, 'Insurance:Long-Term-Care', NULL,-1),
(560, 3, 3, 'Insurance:Medical-Billing', NULL,-1),
(561, 3, 3, 'Insurance:Personal-Property', NULL,-1),
(562, 3, 3, 'Insurance:Pet', NULL,-1),
(563, 3, 3, 'Insurance:RV-Motorcycle', NULL,-1),
(564, 3, 3, 'Insurance:Supplemental', NULL,-1),
(565, 3, 3, 'Insurance:Travel', NULL,-1),
(566, 3, 3, 'Insurance:Umbrella', NULL,-1),
(567, 3, 3, 'Insurance:Vision', NULL,-1),
(568, 3, 3, 'Insurance:Watercraft', NULL,-1),
(569, 3, 3, 'Insurance:Workers-Compensation', NULL,-1),
(570, 3, 3, 'Internet-and-Businesses-Online', NULL,-1),
(571, 3, 3, 'Internet-and-Businesses-Online:Affiliate-Revenue', NULL,-1),
(572, 3, 3, 'Internet-and-Businesses-Online:Auctions', NULL,-1),
(573, 3, 3, 'Internet-and-Businesses-Online:Audio-Streaming', NULL,-1),
(574, 3, 3, 'Internet-and-Businesses-Online:Autoresponders', NULL,-1),
(575, 3, 3, 'Internet-and-Businesses-Online:Banner-Advertising', NULL,-1),
(576, 3, 3, 'Internet-and-Businesses-Online:Blogging', NULL,-1),
(577, 3, 3, 'Internet-and-Businesses-Online:Domain-Names', NULL,-1),
(578, 3, 3, 'Internet-and-Businesses-Online:E-Books', NULL,-1),
(579, 3, 3, 'Internet-and-Businesses-Online:Ecommerce', NULL,-1),
(580, 3, 3, 'Internet-and-Businesses-Online:Email-Marketing', NULL,-1),
(581, 3, 3, 'Internet-and-Businesses-Online:Ezine-Publishing', NULL,-1),
(582, 3, 3, 'Internet-and-Businesses-Online:Forums', NULL,-1),
(583, 3, 3, 'Internet-and-Businesses-Online:Internet-Marketing', NULL,-1),
(584, 3, 3, 'Internet-and-Businesses-Online:Link-Popularity', NULL,-1),
(585, 3, 3, 'Internet-and-Businesses-Online:List-Building', NULL,-1),
(586, 3, 3, 'Internet-and-Businesses-Online:PPC-Advertising', NULL,-1),
(587, 3, 3, 'Internet-and-Businesses-Online:PPC-Publishing', NULL,-1),
(588, 3, 3, 'Internet-and-Businesses-Online:Paid-Surveys', NULL,-1),
(589, 3, 3, 'Internet-and-Businesses-Online:Podcasting', NULL,-1),
(590, 3, 3, 'Internet-and-Businesses-Online:Product-Creation', NULL,-1),
(591, 3, 3, 'Internet-and-Businesses-Online:Product-Launching', NULL,-1),
(592, 3, 3, 'Internet-and-Businesses-Online:RSS', NULL,-1),
(593, 3, 3, 'Internet-and-Businesses-Online:SEO', NULL,-1),
(594, 3, 3, 'Internet-and-Businesses-Online:Search-Engine-Marketing', NULL,-1),
(595, 3, 3, 'Internet-and-Businesses-Online:Security', NULL,-1),
(596, 3, 3, 'Internet-and-Businesses-Online:Site-Promotion', NULL,-1),
(597, 3, 3, 'Internet-and-Businesses-Online:Social-Bookmarking', NULL,-1),
(598, 3, 3, 'Internet-and-Businesses-Online:Social-Media', NULL,-1),
(599, 3, 3, 'Internet-and-Businesses-Online:Social-Networking', NULL,-1),
(600, 3, 3, 'Internet-and-Businesses-Online:Spam-Blocker', NULL,-1),
(601, 3, 3, 'Internet-and-Businesses-Online:Traffic-Building', NULL,-1),
(602, 3, 3, 'Internet-and-Businesses-Online:Video-Marketing', NULL,-1),
(603, 3, 3, 'Internet-and-Businesses-Online:Video-Streaming', NULL,-1),
(604, 3, 3, 'Internet-and-Businesses-Online:Web-Design', NULL,-1),
(605, 3, 3, 'Internet-and-Businesses-Online:Web-Development', NULL,-1),
(606, 3, 3, 'Internet-and-Businesses-Online:Web-Hosting', NULL,-1),
(607, 3, 3, 'Investing', NULL,-1),
(608, 3, 3, 'Investing:Day-Trading', NULL,-1),
(609, 3, 3, 'Investing:IRA-401k', NULL,-1),
(610, 3, 3, 'Investing:Mutual-Funds', NULL,-1),
(611, 3, 3, 'Investing:Retirement-Planning', NULL,-1),
(612, 3, 3, 'Investing:Stocks', NULL,-1),
(613, 3, 3, 'Kids-and-Teens', NULL,-1),
(614, 3, 3, 'Legal', NULL,-1),
(615, 3, 3, 'Legal:Copyright', NULL,-1),
(616, 3, 3, 'Legal:Corporations-LLC', NULL,-1),
(617, 3, 3, 'Legal:Criminal-Law', NULL,-1),
(618, 3, 3, 'Legal:Cyber-Law', NULL,-1),
(619, 3, 3, 'Legal:Employment-Law', NULL,-1),
(620, 3, 3, 'Legal:Identity-Theft', NULL,-1),
(621, 3, 3, 'Legal:Immigration', NULL,-1),
(622, 3, 3, 'Legal:Intellectual-Property', NULL,-1),
(623, 3, 3, 'Legal:Labor-Law', NULL,-1),
(624, 3, 3, 'Legal:Living-Will', NULL,-1),
(625, 3, 3, 'Legal:Medical-Malpractice', NULL,-1),
(626, 3, 3, 'Legal:National-State-Local', NULL,-1),
(627, 3, 3, 'Legal:Patents', NULL,-1),
(628, 3, 3, 'Legal:Personal-Injury', NULL,-1),
(629, 3, 3, 'Legal:Real-Estate-Law', NULL,-1),
(630, 3, 3, 'Legal:Regulatory-Compliance', NULL,-1),
(631, 3, 3, 'Legal:Trademarks', NULL,-1),
(632, 3, 3, 'News-and-Society', NULL,-1),
(633, 3, 3, 'News-and-Society:Crime', NULL,-1),
(634, 3, 3, 'News-and-Society:Economics', NULL,-1),
(635, 3, 3, 'News-and-Society:Energy', NULL,-1),
(636, 3, 3, 'News-and-Society:Environmental', NULL,-1),
(637, 3, 3, 'News-and-Society:International', NULL,-1),
(638, 3, 3, 'News-and-Society:Military', NULL,-1),
(639, 3, 3, 'News-and-Society:Politics', NULL,-1),
(640, 3, 3, 'News-and-Society:Pure-Opinion', NULL,-1),
(641, 3, 3, 'News-and-Society:Religion', NULL,-1),
(642, 3, 3, 'News-and-Society:Weather', NULL,-1),
(643, 3, 3, 'Pets', NULL,-1),
(644, 3, 3, 'Pets:Birds', NULL,-1),
(645, 3, 3, 'Pets:Cats', NULL,-1),
(646, 3, 3, 'Pets:Dogs', NULL,-1),
(647, 3, 3, 'Pets:Exotic', NULL,-1),
(648, 3, 3, 'Pets:Farm-Ranch', NULL,-1),
(649, 3, 3, 'Pets:Fish', NULL,-1),
(650, 3, 3, 'Pets:Horses', NULL,-1),
(651, 3, 3, 'Pets:Reptiles-Amphibians', NULL,-1),
(652, 3, 3, 'Real-Estate', NULL,-1),
(653, 3, 3, 'Real-Estate:Building-a-Home', NULL,-1),
(654, 3, 3, 'Real-Estate:Buying', NULL,-1),
(655, 3, 3, 'Real-Estate:Commercial-Construction', NULL,-1),
(656, 3, 3, 'Real-Estate:Commercial-Property', NULL,-1),
(657, 3, 3, 'Real-Estate:Condominiums', NULL,-1),
(658, 3, 3, 'Real-Estate:FSBO', NULL,-1),
(659, 3, 3, 'Real-Estate:Foreclosures', NULL,-1),
(660, 3, 3, 'Real-Estate:Green-Real-Estate', NULL,-1),
(661, 3, 3, 'Real-Estate:Home-Staging', NULL,-1),
(662, 3, 3, 'Real-Estate:Homes', NULL,-1),
(663, 3, 3, 'Real-Estate:Investing', NULL,-1),
(664, 3, 3, 'Real-Estate:Land', NULL,-1),
(665, 3, 3, 'Real-Estate:Leasing-Renting', NULL,-1),
(666, 3, 3, 'Real-Estate:Marketing', NULL,-1),
(667, 3, 3, 'Real-Estate:Mortgage-Refinance', NULL,-1),
(668, 3, 3, 'Real-Estate:Moving-Relocating', NULL,-1),
(669, 3, 3, 'Real-Estate:Property-Management', NULL,-1),
(670, 3, 3, 'Real-Estate:Selling', NULL,-1),
(671, 3, 3, 'Recreation-and-Sports', NULL,-1),
(672, 3, 3, 'Recreation-and-Sports:Archery', NULL,-1),
(673, 3, 3, 'Recreation-and-Sports:Auto-Racing', NULL,-1),
(674, 3, 3, 'Recreation-and-Sports:Baseball', NULL,-1),
(675, 3, 3, 'Recreation-and-Sports:Basketball', NULL,-1),
(676, 3, 3, 'Recreation-and-Sports:Billiards', NULL,-1),
(677, 3, 3, 'Recreation-and-Sports:Boating', NULL,-1),
(678, 3, 3, 'Recreation-and-Sports:Bodybuilding', NULL,-1),
(679, 3, 3, 'Recreation-and-Sports:Bowling', NULL,-1),
(680, 3, 3, 'Recreation-and-Sports:Boxing', NULL,-1),
(681, 3, 3, 'Recreation-and-Sports:Cheerleading', NULL,-1),
(682, 3, 3, 'Recreation-and-Sports:Climbing', NULL,-1),
(683, 3, 3, 'Recreation-and-Sports:Cricket', NULL,-1),
(684, 3, 3, 'Recreation-and-Sports:Cycling', NULL,-1),
(685, 3, 3, 'Recreation-and-Sports:Dancing', NULL,-1),
(686, 3, 3, 'Recreation-and-Sports:Equestrian', NULL,-1),
(687, 3, 3, 'Recreation-and-Sports:Extreme', NULL,-1),
(688, 3, 3, 'Recreation-and-Sports:Fantasy-Sports', NULL,-1),
(689, 3, 3, 'Recreation-and-Sports:Figure-Skating', NULL,-1),
(690, 3, 3, 'Recreation-and-Sports:Fish-Ponds', NULL,-1),
(691, 3, 3, 'Recreation-and-Sports:Fishing', NULL,-1),
(692, 3, 3, 'Recreation-and-Sports:Football', NULL,-1),
(693, 3, 3, 'Recreation-and-Sports:Golf', NULL,-1),
(694, 3, 3, 'Recreation-and-Sports:Gymnastics', NULL,-1),
(695, 3, 3, 'Recreation-and-Sports:Hockey', NULL,-1),
(696, 3, 3, 'Recreation-and-Sports:Horse-Racing', NULL,-1),
(697, 3, 3, 'Recreation-and-Sports:Hunting', NULL,-1),
(698, 3, 3, 'Recreation-and-Sports:Martial-Arts', NULL,-1),
(699, 3, 3, 'Recreation-and-Sports:Mountain-Biking', NULL,-1),
(700, 3, 3, 'Recreation-and-Sports:Olympics', NULL,-1),
(701, 3, 3, 'Recreation-and-Sports:Racquetball', NULL,-1),
(702, 3, 3, 'Recreation-and-Sports:Rodeo', NULL,-1),
(703, 3, 3, 'Recreation-and-Sports:Rugby', NULL,-1),
(704, 3, 3, 'Recreation-and-Sports:Running', NULL,-1),
(705, 3, 3, 'Recreation-and-Sports:Scuba-Diving', NULL,-1),
(706, 3, 3, 'Recreation-and-Sports:Skateboarding', NULL,-1),
(707, 3, 3, 'Recreation-and-Sports:Skiing', NULL,-1),
(708, 3, 3, 'Recreation-and-Sports:Snowboarding', NULL,-1),
(709, 3, 3, 'Recreation-and-Sports:Soccer', NULL,-1),
(710, 3, 3, 'Recreation-and-Sports:Sports-Apparel', NULL,-1),
(711, 3, 3, 'Recreation-and-Sports:Surfing', NULL,-1),
(712, 3, 3, 'Recreation-and-Sports:Swimming', NULL,-1),
(713, 3, 3, 'Recreation-and-Sports:Tennis', NULL,-1),
(714, 3, 3, 'Recreation-and-Sports:Track-and-Field', NULL,-1),
(715, 3, 3, 'Recreation-and-Sports:Triathlon', NULL,-1),
(716, 3, 3, 'Recreation-and-Sports:Volleyball', NULL,-1),
(719, 3, 3, 'Recreation-and-Sports:Wrestling', NULL,-1),
(720, 3, 3, 'Reference-and-Education', NULL,-1),
(721, 3, 3, 'Reference-and-Education:Astronomy', NULL,-1),
(722, 3, 3, 'Reference-and-Education:College-University', NULL,-1),
(723, 3, 3, 'Reference-and-Education:Financial-Aid', NULL,-1),
(724, 3, 3, 'Reference-and-Education:Future-Concepts', NULL,-1),
(725, 3, 3, 'Reference-and-Education:Home-Schooling', NULL,-1),
(726, 3, 3, 'Reference-and-Education:Languages', NULL,-1),
(727, 3, 3, 'Reference-and-Education:Nature', NULL,-1),
(728, 3, 3, 'Reference-and-Education:Online-Education', NULL,-1),
(729, 3, 3, 'Reference-and-Education:Paranormal', NULL,-1),
(730, 3, 3, 'Reference-and-Education:Psychic', NULL,-1),
(731, 3, 3, 'Reference-and-Education:Psychology', NULL,-1),
(732, 3, 3, 'Reference-and-Education:Science', NULL,-1),
(733, 3, 3, 'Reference-and-Education:Survival-and-Emergency', NULL,-1),
(734, 3, 3, 'Reference-and-Education:Vocational-Trade-Schools', NULL,-1),
(735, 3, 3, 'Reference-and-Education:Wildlife', NULL,-1),
(736, 3, 3, 'Relationships', NULL,-1),
(737, 3, 3, 'Relationships:Affairs', NULL,-1),
(738, 3, 3, 'Relationships:Anniversaries', NULL,-1),
(739, 3, 3, 'Relationships:Commitment', NULL,-1),
(740, 3, 3, 'Relationships:Communication', NULL,-1),
(741, 3, 3, 'Relationships:Conflict', NULL,-1),
(742, 3, 3, 'Relationships:Dating', NULL,-1),
(743, 3, 3, 'Relationships:Dating-for-Boomers', NULL,-1),
(744, 3, 3, 'Relationships:Divorce', NULL,-1),
(745, 3, 3, 'Relationships:Domestic-Violence', NULL,-1),
(746, 3, 3, 'Relationships:Enhancement', NULL,-1),
(747, 3, 3, 'Relationships:Friendship', NULL,-1),
(748, 3, 3, 'Relationships:Gay-Lesbian', NULL,-1),
(749, 3, 3, 'Relationships:Love', NULL,-1),
(750, 3, 3, 'Relationships:Marriage', NULL,-1),
(751, 3, 3, 'Relationships:Post-Divorce', NULL,-1),
(752, 3, 3, 'Relationships:Readiness', NULL,-1),
(753, 3, 3, 'Relationships:Sexuality', NULL,-1),
(754, 3, 3, 'Relationships:Singles', NULL,-1),
(755, 3, 3, 'Relationships:Wedding', NULL,-1),
(756, 3, 3, 'Self-Improvement', NULL,-1),
(757, 3, 3, 'Self-Improvement:Addictions', NULL,-1),
(758, 3, 3, 'Self-Improvement:Anger-Management', NULL,-1),
(759, 3, 3, 'Self-Improvement:Attraction', NULL,-1),
(760, 3, 3, 'Self-Improvement:Coaching', NULL,-1),
(761, 3, 3, 'Self-Improvement:Creativity', NULL,-1),
(762, 3, 3, 'Self-Improvement:Goal-Setting', NULL,-1),
(763, 3, 3, 'Self-Improvement:Grief-Loss', NULL,-1),
(764, 3, 3, 'Self-Improvement:Happiness', NULL,-1),
(765, 3, 3, 'Self-Improvement:Innovation', NULL,-1),
(766, 3, 3, 'Self-Improvement:Inspirational', NULL,-1),
(767, 3, 3, 'Self-Improvement:Leadership', NULL,-1),
(768, 3, 3, 'Self-Improvement:Motivation', NULL,-1),
(769, 3, 3, 'Self-Improvement:NLP-Hypnosis', NULL,-1),
(770, 3, 3, 'Self-Improvement:Organizing', NULL,-1),
(771, 3, 3, 'Self-Improvement:Positive-Attitude', NULL,-1),
(772, 3, 3, 'Self-Improvement:Self-Esteem', NULL,-1),
(773, 3, 3, 'Self-Improvement:Spirituality', NULL,-1),
(774, 3, 3, 'Self-Improvement:Stress-Management', NULL,-1),
(775, 3, 3, 'Self-Improvement:Success', NULL,-1),
(776, 3, 3, 'Self-Improvement:Techniques', NULL,-1),
(777, 3, 3, 'Self-Improvement:Time-Management', NULL,-1),
(778, 3, 3, 'Shopping-and-Product-Reviews', NULL,-1),
(779, 3, 3, 'Shopping-and-Product-Reviews:Book-Reviews', NULL,-1),
(780, 3, 3, 'Shopping-and-Product-Reviews:Electronics', NULL,-1),
(781, 3, 3, 'Shopping-and-Product-Reviews:Fashion-Style', NULL,-1),
(782, 3, 3, 'Shopping-and-Product-Reviews:Gifts', NULL,-1),
(783, 3, 3, 'Shopping-and-Product-Reviews:Internet-Marketing', NULL,-1),
(784, 3, 3, 'Shopping-and-Product-Reviews:Jewelry-Diamonds', NULL,-1),
(785, 3, 3, 'Shopping-and-Product-Reviews:Lingerie', NULL,-1),
(786, 3, 3, 'Travel-and-Leisure', NULL,-1),
(787, 3, 3, 'Travel-and-Leisure:Airline-Travel', NULL,-1),
(788, 3, 3, 'Travel-and-Leisure:Aviation-Airplanes', NULL,-1),
(789, 3, 3, 'Travel-and-Leisure:Bed-Breakfast-Inns', NULL,-1),
(790, 3, 3, 'Travel-and-Leisure:Budget-Travel', NULL,-1),
(791, 3, 3, 'Travel-and-Leisure:Camping', NULL,-1),
(792, 3, 3, 'Travel-and-Leisure:Car-Rentals', NULL,-1),
(793, 3, 3, 'Travel-and-Leisure:Charter-Jets', NULL,-1),
(794, 3, 3, 'Travel-and-Leisure:Cruise-Ship-Reviews', NULL,-1),
(795, 3, 3, 'Travel-and-Leisure:Cruising', NULL,-1),
(796, 3, 3, 'Travel-and-Leisure:Destination-Tips', NULL,-1),
(797, 3, 3, 'Travel-and-Leisure:First-Time-Cruising', NULL,-1),
(798, 3, 3, 'Travel-and-Leisure:Hotels-Accommodations', NULL,-1),
(799, 3, 3, 'Travel-and-Leisure:Limo-Rentals-Limousines', NULL,-1),
(800, 3, 3, 'Travel-and-Leisure:Luxury-Cruising', NULL,-1),
(801, 3, 3, 'Travel-and-Leisure:Outdoors', NULL,-1),
(802, 3, 3, 'Travel-and-Leisure:Pet-Friendly-Rentals', NULL,-1),
(803, 3, 3, 'Travel-and-Leisure:Sailing', NULL,-1),
(804, 3, 3, 'Travel-and-Leisure:Ski-Resorts', NULL,-1),
(805, 3, 3, 'Travel-and-Leisure:Timeshare', NULL,-1),
(806, 3, 3, 'Travel-and-Leisure:Vacation-Homes', NULL,-1),
(807, 3, 3, 'Travel-and-Leisure:Vacation-Rentals', NULL,-1),
(808, 3, 3, 'Women''s-Interests', NULL,-1),
(809, 3, 3, 'Women''s-Interests:Cosmetic-Surgery', NULL,-1),
(810, 3, 3, 'Women''s-Interests:Plus-Size', NULL,-1),
(811, 3, 3, 'Writing-and-Speaking', NULL,-1),
(812, 3, 3, 'Writing-and-Speaking:Article-Marketing', NULL,-1),
(813, 3, 3, 'Writing-and-Speaking:Book-Marketing', NULL,-1),
(814, 3, 3, 'Writing-and-Speaking:Copywriting', NULL,-1),
(815, 3, 3, 'Writing-and-Speaking:Public-Speaking', NULL,-1),
(816, 3, 3, 'Writing-and-Speaking:Teleseminars', NULL,-1),
(817, 3, 3, 'Writing-and-Speaking:Writing', NULL,-1),
(818, 3, 3, 'Writing-and-Speaking:Writing-Articles', NULL,-1)";

	$sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."directory`";
	$sql_data[]="CREATE TABLE `".$tableprefix."directory` (
  `id` int(10) NOT NULL auto_increment,
  `directory` varchar(100) collate utf8_unicode_ci default NULL,
  `type` varchar(50) collate utf8_unicode_ci default NULL,
  `user_id` int(15) NOT NULL,
  PRIMARY KEY  (`id`)
) ";
	$sql_data[] = "INSERT INTO `".$tableprefix."directory` VALUES(1, 'Article Dashboard', 'AD',-1)";
	$sql_data[] = "INSERT INTO `".$tableprefix."directory` VALUES(2, 'GoArticles', 'GA',-1)";
	$sql_data[] = "INSERT INTO `".$tableprefix."directory` VALUES(3, 'EzineArticles', 'EA',-1)";


	$sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."profile`";
	$sql_data[]="CREATE TABLE `".$tableprefix."profile` (
  `id` int(10) NOT NULL auto_increment,
  `profile_name` varchar(100) collate utf8_unicode_ci default NULL,
  `author` varchar(50) collate utf8_unicode_ci default NULL,
  `author_lname` varchar(50) collate utf8_unicode_ci default NULL,
  `biography` varchar(255) collate utf8_unicode_ci default NULL,
  `biography_html` varchar(255) collate utf8_unicode_ci default NULL,
  `comments` text collate utf8_unicode_ci,
  `date_created` date NOT NULL default '0000-00-00',
  `profile_id` int(50) default NULL,
  PRIMARY KEY  (`id`)
) ";
	
	$sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."submission`";
	$sql_data[]="CREATE TABLE `".$tableprefix."submission` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `summary` varchar(255) collate utf8_unicode_ci default NULL,
  `body` text collate utf8_unicode_ci,
  `keyword` text collate utf8_unicode_ci,
  `article_id` varchar(10) collate utf8_unicode_ci default NULL,
  `directory_id` varchar(50) collate utf8_unicode_ci default NULL,
  `url_id` varchar(50) collate utf8_unicode_ci default NULL,
  `category_id` varchar(50) collate utf8_unicode_ci default NULL,
  `profile_id` varchar(100) collate utf8_unicode_ci default NULL,
  `dir_type` varchar(50) collate utf8_unicode_ci default NULL,
  `start_date` date default NULL,
  `schedule` date default NULL,
  `isScheduled` char(1) collate utf8_unicode_ci default NULL,
  `isSubmit` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  `error` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  `log` text collate utf8_unicode_ci,
  `isProcess` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  `flag` char(1) collate utf8_unicode_ci NOT NULL default 'T',
  PRIMARY KEY  (`id`)
) ";
	
	$sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."url`";
	$sql_data[]="CREATE TABLE `".$tableprefix."url` (
  `id` int(10) NOT NULL auto_increment,
  `directory_id` int(10) default NULL,
  `url` varchar(100) collate utf8_unicode_ci default NULL,
  `dir_label` varchar(50) collate utf8_unicode_ci default NULL,
  `username` varchar(50) collate utf8_unicode_ci default NULL,
  `password` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ";
	
	$sql_data[] = "INSERT INTO `".$tableprefix."url` VALUES(1, 1, 'http://www.articledashboard.com/', '', '', '')";
	$sql_data[] = "INSERT INTO `".$tableprefix."url` VALUES(2, 2, 'http://www.goarticles.com/', '', '', '')";
	$sql_data[] = "INSERT INTO `".$tableprefix."url` VALUES(3, 3, 'http://members.ezinearticles.com/', '', '', '')";

	


	
/**********************************			***********************/
			$file = "config/config.php";
			$error_msg1="<br><div align='center'><a href='javascript:history.go(-1)'><< Back</a></div>";
			if(is_writable($file))
			{
				if(mysql_select_db($db_name,$install))
				{
					foreach($sql_data as $i=>$value)
					{
						$sql=$sql_data[$i];
					//echo "<LI>".$sql;
						$rs_user=mysql_query($sql,$install) or die("<table width='70%' border='1' align='center' bgcolor='#ffccff'><tr><td align='center'><font color='#FFFFFF'><b>Installation Error</b></font></td></tr><tr><td><font color='red'><b>OOps there was some error <br>".mysql_error()."<li>$sql".$error_msg1."</b></font></td></tr></table>");
						if($rs_user==false)
						{
							$msg=die("<table width='70%' border='1' align='center' bgcolor='#ffccff'><tr><td align='center'><font color='#FFFFFF'><b>Installation Error</b></font></td></tr><tr><td><font color='red'><b>Error: Database Does not Exist. Create Database First.".$error_msg1."</b></font></td></tr></table>");					
						} 
					} 
				}
				//Writing the data in the Config file
				mysql_close($install);
				$fp = @fopen("config/config.php","w");
				

$txt = '<?php
	define("SITE_TITLE","'.$site_name.'");
	define("DB_SERVER_NAME","'.$host_name.'");
	define("DB_USERNAME","'.$user_name.'");
	define("DB_PASSWORD","'.$password.'");
	define("DB_NAME","'.$db_name.'");
	define("TABLE_PREFIX","'.$tableprefix.'");
	define("SERVER_PATH","'.$site_path.'");
	define("ROOTPATH","'.$root_path.'");
	
	define("SESSION_PREFIX","ASM_SESS_");

	define("RECORD_PER_PAGE","10");
	define("ROWS_PER_PAGE","10");
	define("PAGE_LINKS","10");

	
    $sign=" > ";
	
?>';
//error_reporting(~E_NOTICE & ~E_STRICT);
			fputs($fp,$txt,strlen($txt));
		
			fclose($fp);
			
		}
							
			

		
				header("Location: install.step2.php"); 
				$iid = "installed";
	
			
//=============================================================================================		
	}
	else
	{
?>
<?php
require_once("header.php");
?>
<link rel="stylesheet" type="text/css" href="stylesheets/install.css"> 
<script language="javascript">
function trim(value)
{
	var temp = value;
	var obj = /^(\s*)([\W\w]*)(\b\s*$)/;
	if (obj.test(temp)) { temp = temp.replace(obj, '$2'); }
	var obj = / +/g;
	temp = temp.replace(obj, " ");
	if (temp == " ") { temp = ""; }
	return temp;
}

function checkform(form)
{
		var mess = "";

		if (trim(form.site_name.value)=="") mess += "- Site name should be entered \n"
		if (trim(form.site_url.value)=="") mess += "- Site URL should be entered \n"
		if (trim(form.server_name.value)=="") mess += "- Server name should be entered \n"
		if (trim(form.database_name.value)=="") mess += "- Database name should be entered \n"		
		if (trim(form.user_name.value)=="") mess += "- Username should be entered \n"
//		if (trim(form.password.value)=="") mess += "- Password should be entered \n"

	if (mess.length>0)
		{
				mess = "The Following error(s) : \n------------------------------------\n"+mess
		alert(mess)
		return false;
		}
} 
</script>
</head>

<body>

<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>

    <td align="center"><form name="form1" method="post" action="" onSubmit="return checkform(this)">
  <table width="50%"  border="0" cellpadding="5" cellspacing="0" class="maintable">
	<?php if((isset($_POST['install_db'])) && ($_POST['install_db']=="form1")){ ?>
    <tr>
      <td colspan="2"><?php echo $msg;?></td>
    </tr>
	<?php } ?>
    <tr align="left">
      <td colspan="2"><h3>Welcome to the installation of Article Submssion Module : </h3></td>
    </tr>
    <tr align="left">
      <td colspan="2"><h3>Important Notice </h3></td>
    </tr>
    <tr align="left">
		<?php
			$ok_to_install=true;
		?>
      <td colspan="2"><p>        Please make sure that the following folders and files have write permission</p>
        <ul>
         
		  <?php
			checkForWriteable();
		  ?>
          <br>
          <br>		  
		  <?php
			clearstatcache(); ?>
          <br>
          </ul>
        <p>After that is done, please enter the MySQL details in the text boxes below.<br>
          </p>
        </td>
    </tr>
	<?php 	if(!$ok_to_install){ ?>
    <tr>
      <td colspan="2" align="center" class="datagridred"> <b>Please give write permissions to the files and folders mentioned above before proceeding.<br />
	  After you have given write permissions, please refresh this page.

	  If everything is proper, you will see a form where you will have to enter the MySQL database details.<br><br><br>	  </td>
    </tr>
	<?php }else{  ?>

    <tr>
      <td colspan="2" align="left"><h3>MySql Database Information </h3></td>
    </tr>
    <TR >
      <TD align="right" nowrap> 	Site Name :</TD>
      <TD><INPUT name="site_name" type="text" id="site_name" size="35" readonly="readonly" value="Article Submission Module">&nbsp;<sup>*</sup>
      &nbsp;</TD>
    </TR>
	<TR>
      <TD align="right" nowrap>Site URL:</TD>
      <TD><INPUT name="site_url" type="text" id="site_url" size="35" value="<?php echo  "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/"?>">&nbsp;<sup>*</sup></TD>
    </TR>
<?php
	$base_arr=explode(".php",$_SERVER['PHP_SELF']);
	$root_path=$_SERVER['DOCUMENT_ROOT'].dirname($base_arr[0])."/";
?>	
	<TR>
      <TD align="right" nowrap>Site Root Path:</TD>
      <TD><INPUT name="root_path" type="text" id="root_path" size="35" value="<?php echo $root_path?>">&nbsp;<sup>*</sup></TD>
    </TR>		
    <tr>
      <td align="right">Database Server:</td>
      <td><input name="server_name" type="text" id="server_name" size="35">&nbsp;<sup>*</sup></td>
    </tr>
    <tr>
      <td align="right">Database Name:</td>
      <td><input name="database_name" type="text" id="database_name" size="35">&nbsp;<sup>*</sup></td>
    </tr>
    <tr>
      <td align="right">Database User:</td>
      <td><input name="user_name" type="text" id="user_name" size="35">&nbsp;<sup>*</sup></td>
    </tr>
    <tr>
      <td align="right">Database Password:</td>
      <td><input name="password" type="password" id="password" size="35">&nbsp;<sup>*</sup></td>
    </tr>
    <TR class="pagemaintable">
      <TD align="right" nowrap>Database prefix:</TD>
      <TD><input name="tableprefix" type="text" id="tableprefix" value="" size="35">
      </TD>
    </TR>
	

       <tr>
      <td colspan="2" align="center"><input type="submit" name="Submit" value="Install DB and Continue to Settings."></td>
    </tr>
    <tr>
      <td colspan="2" align="center"></td>
    </tr>
  </table>
<input name="install_db" type="hidden" value="form1">
</form></td>
  </tr>
  <?php } //if ok to install ?>
</table>

</body>
</html>


<?php
}

?>