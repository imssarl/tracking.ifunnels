<?php
	session_start();
	require_once("config/config.php");	
	require_once("classes/settings.class.php");	
	$host_name= DB_SERVER_NAME;			
	$site_name="E-Commerce Suite";
	$user_name=DB_USERNAME;
	$password=DB_PASSWORD;
	$db_name=DB_NAME;
	$tableprefix=TABLE_PREFIX."psf_";
	$site_path=SERVER_PATH."psf/";
	$root_path=ROOT_PATH."psf/";
	
	require_once("classes/database.class.php");
	$ms_db = new Database();
	$ms_db->openDB();

	$settings_obj = new Settings();
	
	$ct_admin_data = $settings_obj->getSettings();
?>

<?php require_once("header.php"); ?>

<title>

<?php echo SITE_TITLE; ?>

</title>

<script language="javascript">

</script>

<?php require_once("top.php"); ?>
<?php require_once("left.php"); ?>

<?php 


$call=checkForWriteable();

if($ok_to_install===false)
{
	echo "<table width='70%' border='1' align='center' bgcolor='#DDDDDD'><tr><td align='center'><font color='red'><b><p> Please set the write permission for the above folder</b></P></font></td></tr></table><p>&nbsp;</P><p>&nbsp;</P></P><p>&nbsp;</P></P><p>&nbsp;</P>";
}
else
{

        $sql_data[]="DROP TABLE IF EXISTS  `".$tableprefix."admin_settings_tb`";
        $sql_data[]="CREATE TABLE `".$tableprefix."admin_settings_tb` (
        `id` int(6) NOT NULL auto_increment,
        `username` varchar(255) NOT NULL default '',
        `password` varchar(255) NOT NULL default '',
        `email_address` varchar(255) NOT NULL default '',
      `snippet_part_1` int(3) NOT NULL default '0',
        `snippet_part_2` int(3) NOT NULL default '0',
        `snippet_part_3` int(3) NOT NULL default '0',
        rows_per_page  int(5) NOT NULL default '0',
        page_links  int(5) NOT NULL default '0',
		`user_id` int(15) NOT NULL,
        PRIMARY KEY  (`id`)
      )";

        $sql_data[]="INSERT INTO `".$tableprefix."admin_settings_tb` (`id`, `username`, `password`, `email_address` ,snippet_part_1,     snippet_part_2, snippet_part_3, rows_per_page, page_links,user_id) VALUES (1, 'admin', '', 'admin@admin.com', 0, 0, 0, 20, 10,-1)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."price_packages_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."price_packages_tb (
			id int(11) NOT NULL auto_increment,
			name varchar(255) default NULL,
			benefits text NOT NULL,
			charge_duration varchar(100) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."ads_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."ads_tb (
			id int(11) NOT NULL auto_increment,
			ads_name varchar(255) NOT NULL default '',
			product_id int(11) NOT NULL default '0',
			ads_summary text,
			status char(1) NOT NULL default '',
			default_val char(1) NOT NULL default 'n',
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."affiliates_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."affiliates_tb (
			id int(11) NOT NULL auto_increment,
			aff_name varchar(255) default NULL,
			aff_email varchar(255) default NULL,
			aff_pass varchar(50) default NULL,
			country_id int(11) default NULL,
			join_dt date default NULL,
			join_ip varchar(16) default NULL,
			status char(1) default '1',
			remarks text default NULL,
			l1_sale int(11) default NULL,
			l2_sale int(11) default NULL,
			l3_sale int(11) default NULL,
			l1_comm double(15,2) default NULL,
			l2_comm double(15,2) default NULL,
			l3_comm double(15,2) default NULL,
			paypal_email varchar(255) default NULL,
			parent_aff_id int(11) default NULL,
			self_customer_id int(11) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			//$sql_data[]="INSERT INTO ".$tableprefix."affiliates_tb (id,aff_name,aff_email,aff_pass,status,paypal_email,parent_aff_id,self_customer_id) VALUES (1,'Admin','admin@admin.com','admin','A','admin@admin.com',0)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."articles_categories_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."articles_categories_tb (
			id int(11) NOT NULL auto_increment,
			blog_cat_name varchar(255) default NULL,
			status char(1) default NULL,
			description text default NULL,
			show_to_affiliate char(1) default NULL,
			show_to_customer char(1) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."articles_refer_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."articles_refer_tb (
			id int(15) NOT NULL auto_increment,
			blog_id int(15) NOT NULL,
			category_id int(15) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."articles_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."articles_tb (
			id int(11) NOT NULL auto_increment,
			matter text NOT NULL,
			status char(1) NOT NULL COMMENT 'A for active, I for inactive',
			title varchar(255) NOT NULL,
			date date NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."broadcast_message_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."broadcast_message_tb (
			id int(11) NOT NULL auto_increment,
			campaign_id int(11) default NULL,
			content_type char(1) default NULL,
			subject varchar(255) default NULL,
			messages text default NULL,
			last_subsid int(11) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."campaign_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."campaign_tb (
			id int(11) NOT NULL auto_increment,
			campaign_name varchar(255) NOT NULL,
			product_id int(11) default NULL,
			optintype char(1) NOT NULL,
			redirectpage1 varchar(255) NOT NULL,
			mailtype char(1) NOT NULL,
			subject varchar(255) NOT NULL,
			matter text NOT NULL,
			redirectpage2 varchar(255) NOT NULL,
			subscribe_through_mail char(1) NOT NULL,
			subscribe_subject varchar(255) NOT NULL,
			pop_server varchar(100) NOT NULL,
			username varchar(255) NOT NULL,
			password varchar(255) NOT NULL,
			unsubscribe_through_mail varchar(255) NOT NULL,
			unsub_subject varchar(255) NOT NULL,
			status char(1) NOT NULL,
			from_name varchar(255) NOT NULL,
			from_email varchar(255) NOT NULL,
			reply_address varchar(255) NOT NULL,
			comments text NOT NULL,
			campaign_date date NOT NULL,
			prod_camp char(1) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."client_email_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."client_email_tb (
			id int(11) NOT NULL auto_increment,
			product_id int(11) NOT NULL,
			license_id int(11) NOT NULL,
			subject text NOT NULL,
			message text NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."config_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."config_tb (
			id int(11) NOT NULL auto_increment,
			username varchar(255) default NULL,
			password varchar(255) default NULL,
			query_string_vb varchar(255) default NULL,
			cookie_vb varchar(255) default NULL,
			cookie_lt int(11) default '0',
			ad_email varchar(255) default NULL,
			mail_type char(1) default NULL,
			sendmail_path varchar(255) default NULL,
			req_authenticate char(1) default NULL,
			smtp_username varchar(255) default NULL,
			smtp_password varchar(255) default NULL,
			lock_period int(11) default '0',
			link_path varchar(255) default NULL,
			smtp_server_info varchar(255) default NULL,
			smtp_server_port varchar(255) default NULL,
			cust_link varchar(255) default NULL,
			license_tb varchar(255) default NULL,
			license_id varchar(255) default NULL,
			license_name varchar(255) default NULL,
			total_friends int(11) default NULL,
			tell_a_friend_matter text,
			tell_a_friend_subject varchar(255) default NULL,
			include_friend_comment char(1) default NULL,
			bcc_email varchar(50) default NULL,
			from_name varchar(50) default NULL,
			commision_depth int(11) default NULL,
			affiliate_registration_option int(11) default NULL,
			use_human_verification_for_order VARCHAR( 3 ) NULL DEFAULT 'yes',
			earn_affiliate_income VARCHAR( 3 ) NULL DEFAULT 'no',
			affiliate_id INT default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."country_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."country_tb (
			id int(11) NOT NULL auto_increment,
			country varchar(255) NOT NULL default '',
			isocode varchar(5) NOT NULL default '',
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (1, 'India', 'IN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (2, 'United States', 'US')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (3, 'Australia', 'AU')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (4, 'Afghanistan', 'AF')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (5, 'Albania', 'AL')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (6, 'Algeria', 'DZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (7, 'Andorra', 'AD')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (8, 'Angola', 'AO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (9, 'Anguilla', 'AI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (10, 'Antigua and', 'AG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (11, 'Argentina', 'AR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (12, 'Armenia', 'AM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (13, 'Aruba', 'AW')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (14, 'Ascension', 'AC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (16, 'Austria', 'AT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (17, 'Azerbaijan', 'AZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (18, 'Bahamas', 'BS')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (19, 'Bahrain', 'BH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (20, 'Bangladesh', 'BD')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (21, 'Barbados', 'BB')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (22, 'Barbuda', 'AG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (23, 'Belarus', 'BY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (24, 'Belgium', 'BE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (25, 'Belize', 'BZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (26, 'Benin', 'BJ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (27, 'Bermuda', 'BM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (28, 'Bhutan', 'BT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (29, 'Bolivia', 'BO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (30, 'Bosnia-Herzegovina', 'BA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (31, 'Botswana', 'BW')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (32, 'Brazil', 'BR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (33, 'British', 'IO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (34, 'Brunei Darussalam', 'BN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (35, 'Bulgaria', 'BG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (36, 'Burkina Faso', 'BF')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (37, 'Burma', 'MM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (38, 'Burundi', 'BI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (39, 'Cambodia', 'KH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (40, 'Cameroon', 'CM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (41, 'Canada', 'CA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (42, 'Cape Verde', 'CV')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (43, 'Cayman Islands', 'KY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (44, 'Central African Republic', 'CF')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (45, 'Chad', 'TD')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (46, 'Chile', 'CL')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (47, 'China', 'CN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (48, 'Colombia', 'CO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (49, 'Comoros', 'KM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (50, 'Congo (Democratic Republic of the)', 'CG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (51, 'Costa Rica', 'CR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (52, 'Cote d lvoire (Ivory Coast)', '')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (53, 'Croatia', 'HR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (54, 'Cuba', 'CU')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (55, 'Cyprus', 'CY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (56, 'Czech Republic', 'CZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (57, 'Denmark', 'DK')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (58, 'Djibouti', 'DJ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (59, 'Dominica', 'DM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (60, 'Dominican Republic', 'DO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (61, 'Ecuador', 'EC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (62, 'Egypt', 'EG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (63, 'El Salvador', 'ISV')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (64, 'Equatorial Guinea', 'GQ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (65, 'Eritrea', 'ER')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (66, 'Estonia', 'EE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (67, 'Ethiopia', 'ET')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (68, 'Falkland Islands', 'FK')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (69, 'Faroe Islands', 'FO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (70, 'Fiji', 'FJ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (71, 'Finland', 'FI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (72, 'France', 'FR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (73, 'French Guiana', 'GF')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (74, 'French Polynesia', 'PF')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (75, 'Gabon', 'GA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (76, 'Gambia', 'GM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (77, 'Georgia (Republic of)', 'GE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (78, 'Germany', 'DE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (79, 'Ghana', 'GH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (80, 'Gibraltar', 'GI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (81, 'Great Britain & N. Ireland', 'GB')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (82, 'Greece', 'GR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (83, 'Greenland', 'GL')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (84, 'Grenada', 'GD')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (85, 'Guadeloupe', 'GP')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (86, 'Guatemala', 'GT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (87, 'Guinea', 'GN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (88, 'Guinea-Bissau', 'GW')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (89, 'Guyana', 'GY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (90, 'Haiti', 'HT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (91, 'Honduras', 'HN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (92, 'Hong Kong', 'HK')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (93, 'Hungary', 'HU')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (94, 'Iceland', 'IS')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (96, 'Indonesia', 'ID')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (97, 'Iran', 'IR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (98, 'Iraq', 'Q')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (99, 'Ireland', 'IE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (100, 'Israel', 'IL')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (101, 'Italy', 'T')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (102, 'Jamaica', 'JM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (103, 'Japan', 'JP')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (104, 'Jordan', 'JO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (105, 'Kazakhstan', 'KZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (106, 'Kenya', 'KE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (107, 'Kiribati', 'KI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (108, 'Kuwait', 'KW')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (109, 'Kyrgyzstan', 'KG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (110, 'Laos', 'LA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (111, 'Latvia', 'LV')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (112, 'Lebanon', 'LB')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (113, 'Lesotho', 'LS')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (114, 'Liberia', 'LR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (115, 'Libya', 'LY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (116, 'Liechtenstein', 'LI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (117, 'Lithuania', 'LT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (118, 'Luxembourg', 'LU')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (119, 'Macao', 'MC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (120, 'Macedonia (Republic of)', 'MK')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (121, 'Madagascar', 'MG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (122, 'Malawi', 'MW')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (123, 'Malaysia', 'MY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (124, 'Maldives', 'MV')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (125, 'Mali', 'ML')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (126, 'Malta', 'MT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (127, 'Martinique', 'MQ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (128, 'Mauritania', 'MR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (129, 'Mauritius', 'MU')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (130, 'Mexico', 'MX')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (131, 'Moldova', 'MD')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (132, 'Mongolia', 'MN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (133, 'Montserrat', 'MS')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (134, 'Morocco', 'MA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (135, 'Mozambique', 'MZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (136, 'Namibia', 'NA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (137, 'Nauru', 'NR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (138, 'Nepal', 'NP')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (139, 'Netherlands', 'NL')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (140, 'Netherlands Antilles', 'NA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (141, 'New Caledonia', 'NC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (142, 'New Zealand', 'NZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (143, 'Nicaragua', 'NI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (144, 'Niger', 'NE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (145, 'Nigeria', 'NG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (146, 'North Korea', 'KP')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (147, 'Norway', 'NO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (148, 'Oman', 'OM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (149, 'Pakistan', 'PK')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (150, 'Panama', 'PA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (151, 'Papua New Guinea', 'PG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (152, 'Paraguay', 'PY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (153, 'Peru', 'PE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (154, 'Philippines', 'PH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (155, 'Pitcairn Island', 'PN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (156, 'Poland', 'PL')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (157, 'Portugal', 'PT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (158, 'Qatar', 'QA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (159, 'Reunion', 'RE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (160, 'Romania', 'RO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (161, 'Russia', 'RU')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (162, 'Rwanda', 'RW')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (163, 'San Marino', 'ISM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (164, 'Sao Tome and Principe', 'IST')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (165, 'Saudi Arabia', 'ISA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (166, 'Senegal', 'ISN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (167, 'Serbia-Montenegro', '''')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (168, 'Seychelles', 'ISC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (169, 'Sierra Leone', 'ISL')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (170, 'Singapore', 'ISG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (171, 'Slovak Republic', 'ISK')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (172, 'Slovenia', 'ISI')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (173, 'Solomon Islands', 'ISB')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (174, 'Somalia', 'ISO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (175, 'South Africa', 'ZA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (176, 'South Korea', 'KR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (177, 'Spain', 'ES')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (178, 'Sri Lanka', 'LK')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (179, 'St. Christopher and Nevis', '')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (180, 'St. Helena', 'ISH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (181, 'St. Lucia', 'LC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (182, 'St. Pierre and Miquelon', 'PM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (183, 'St. Vincent and the Grenadines', 'VC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (184, 'Sudan', 'ISD')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (185, 'Suriname', 'ISR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (186, 'Swaziland', 'ISZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (187, 'Sweden', 'ISE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (188, 'Switzerland', 'CH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (189, 'Syrian Arab Republic', '')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (190, 'Taiwan', 'TW')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (191, 'Tajikistan', 'TJ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (192, 'Tanzania', 'TZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (193, 'Thailand', 'TH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (194, 'Togo', 'TG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (195, 'Tonga', 'TO')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (196, 'Trinidad and Tobago', 'TT')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (197, 'Tristan da Cunha', 'ISH')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (198, 'Tunisia', 'TN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (199, 'Turkey', 'TR')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (200, 'Turkmenistan', 'TM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (201, 'Turks and Caicos Islands', 'TC')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (202, 'Tuvalu', 'TV')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (203, 'Uganda', 'UG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (204, 'Ukraine', 'UA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (205, 'United Arab Emirates', 'AE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (206, 'Uruguay', 'UY')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (207, 'US Possession', '')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (208, 'USA', 'GOV')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (209, 'Uzbekistan', 'UZ')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (210, 'Vanuatu', 'VU')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (211, 'Vatican City', 'VA')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (212, 'Venezuela', 'VE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (213, 'Vietnam', 'VN')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (214, 'Virgin Islands', 'VG')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (215, 'Wallis and Futuna Islands', 'WF')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (216, 'Western Samoa', 'WS')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (217, 'Yemen', 'YE')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (218, 'Zambia', 'ZM')";
			$sql_data[]="INSERT INTO ".$tableprefix."country_tb VALUES (219, 'Zimbabwe', 'ZW')";
			
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."customer_payment_details_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."customer_payment_details_tb (
			id int(11) NOT NULL auto_increment,
			order_id int(11) NOT NULL,
			amount double NOT NULL,
			paid_date datetime NOT NULL,
			transaction_id varchar(255) NOT NULL,
			payment_status char(1) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."customer_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."customer_tb (
			id int(11) NOT NULL auto_increment,
			cust_email varchar(255) default NULL,
			password varchar(255) default NULL,
			cust_name varchar(255) default NULL,
			address text,
			city varchar(255) default NULL,
			state varchar(255) default NULL,
			country int(11) default NULL,
			zipcode int(11) default NULL,
			contact_no varchar(255) default NULL,
			fax_no varchar(255) default NULL,
			status char(1) NOT NULL,
			ip_address varchar(255) default NULL,
			session_id varchar(255) default NULL,
			join_date datetime default NULL,
			unsubs_date datetime default NULL,
			last_login datetime default NULL,
			self_aff_id int(11) default NULL,
			fraud_probability varchar(3) default NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."faqs_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."faqs_tb (
			id int(11) NOT NULL auto_increment,
			question varchar(255) default NULL,
			answer text default NULL,
			status char(1) default NULL COMMENT 'A for active, I for inactive',
			show_to_affiliate char(1) default NULL,
			show_to_customer char(1) default NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."first_subscription_order_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."first_subscription_order_tb (
			id int(11) NOT NULL auto_increment,
			order_id int(11) NOT NULL,
			subs_period char(1) NOT NULL,
			subs_terms char(1) NOT NULL,
			terms_value int(11) default NULL,
			due_date date NOT NULL,
			subscription_status char(1) NOT NULL COMMENT 'R =Running, C=Completed, A=Cancelled',
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."form_builder_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."form_builder_tb (
			id int(11) NOT NULL auto_increment,
			campaign_id int(11) NOT NULL,
			field_name varchar(255) NOT NULL,
			field_value varchar(255) default NULL,
			field_type char(1) NOT NULL,
			required char(1) NOT NULL,
			status char(1) NOT NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."forum_category_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."forum_category_tb (
			forum_category_id int(11) NOT NULL auto_increment,
			forum_category_name varchar(255) default NULL,
			forum_category_description text default NULL,
			forum_category_status char(1) default NULL,
			show_to_affiliate char(1) default NULL,
			show_to_customer char(1) default NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (forum_category_id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."forum_post_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."forum_post_tb (
			forum_post_id int(11) NOT NULL auto_increment,
			forum_topic_id int(11) default NULL,
			forum_user_id int(11) default NULL,
			forum_post_message text default NULL,
			forum_post_date datetime default NULL,
			forum_post_status char(1) default NULL,
			user_type VARCHAR( 10 ) NULL,
			PRIMARY KEY  (forum_post_id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."forum_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."forum_tb (
			forum_id int(11) NOT NULL auto_increment,
			forum_name varchar(255) NOT NULL,
			forum_category_id int(11) NOT NULL,
			forum_description text,
			forum_start_date date default NULL,
			forum_last_post datetime default NULL,
			forum_status char(1) default NULL,
			forum_no_of_post int(11) default NULL,
			forum_no_of_topic int(11) default NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (forum_id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."forum_topic_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."forum_topic_tb (
			forum_topic_id int(11) NOT NULL auto_increment,
			forum_id int(11) NOT NULL,
			forum_user_id int(11) NOT NULL,
			forum_topic_name varchar(255) NOT NULL,
			forum_topic_description text NOT NULL,
			forum_topic_date datetime NOT NULL,
			topic_status char(1) NOT NULL,
			forum_topic_read int(6) NOT NULL,
			user_type VARCHAR( 10 ) NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (forum_topic_id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."mail_setting_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."mail_setting_tb (
			id int(11) NOT NULL auto_increment,
			name varchar(50) default NULL,
			logical_name varchar(255) NOT NULL,
			subject varchar(255) default NULL,
			matter text,
			content_type char(1) default NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			

			$mail_message=addslashes("&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name], &lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Thank you for the order of [product_product_name]. &lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;The product will be delivered as soon as the payment is confirmed.&lt;br /&gt;If you have not made the payment yet, please complete the payment process.&lt;br /&gt;&lt;br /&gt;Here is a summary of the order: &lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Product Price: [product_product_price]&lt;br /&gt;Order Amount: [order_amount]&lt;br /&gt;Your Comments: [order_cust_comment]&lt;br /&gt;&lt;br /&gt;You can login to the client area using the following details:&lt;br /&gt;Login URL: [customer_login_url]&lt;br /&gt;Email Id: [customer_cust_email]&lt;br /&gt;Password: [customer_password]&lt;br /&gt;&lt;br /&gt;In the client's area you can read the Frequently Asked Questions,&amp;nbsp;get links&amp;nbsp;for&amp;nbsp;product downloads, discuss in the forum with other clients etc.&lt;br /&gt;&lt;br /&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;&lt;/p&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (1, 'order','Order Mail to Customer','Thank you for the order [customer_cust_name]','".$mail_message."', 'H',-1)";
			
			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name], &lt;br /&gt;&lt;br /&gt;Thank you for the order of [product_product_name]. &lt;br /&gt;The product will be delivered as soon as the payment is confirmed by us.&lt;br /&gt;If you have not made the payment yet, then&amp;nbsp;please complete the payment process.&lt;br /&gt;&lt;br /&gt;Here is a summary of the order: &lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Subscription Amount: [product_subs_price]&lt;br /&gt;Billed every [product_terms_value] [product_subs_terms]&lt;br /&gt;This subscription will bill for: [product_subs_period] [product_subs_terms]&lt;br /&gt;Your Comments: [order_cust_comment]&lt;br /&gt;&lt;br /&gt;After the payment is confirmed, you can login to the client area using the following details:&lt;br /&gt;Login URL: [customer_login_url]&lt;br /&gt;Email Id: [customer_cust_email]&lt;br /&gt;Password: [customer_password]&lt;br /&gt;In the client's area, you can read the FAQs, Knowledge Base, get the download link etc&lt;br /&gt;&lt;br /&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (2, 'subscription order', 'Subscription Order Mail to Customer','Thank you for the order [customer_cust_name]','".$mail_message."', 'H',-1)";
			
			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name], &lt;br /&gt;&lt;br /&gt;This is to inform you that we have not received any payment for [product_product_name] during this subscription cycle. &lt;br /&gt;&lt;br /&gt;This payment was due on [order_next_subscription_date].&lt;br /&gt;&lt;br /&gt;Please note that if the payment is not received, the service might get interrupted. &lt;br /&gt;&lt;br /&gt;Here is a summary of the order: &lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Subscription Amount: [product_subs_price]&lt;br /&gt;Billed every [product_terms_value] [product_subs_terms]&lt;br /&gt;This subscription will bill for: [product_subs_period] [product_subs_terms]&lt;br /&gt;Your Comments: [order_cust_comment]&lt;br /&gt;&lt;br /&gt;You can login to the client area using the following details:&lt;br /&gt;Login URL: [customer_login_url]&lt;br /&gt;Email Id: [customer_cust_email]&lt;br /&gt;Password: [customer_password]&lt;br /&gt;&lt;br /&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (3, 'subscription non payment', 'Subscription Non Payment Mail to Customer','Possible service interruption notice for [product_product_name]','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name],&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Your subscription for [product_product_name] has been cancelled successfully.&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;&lt;/p&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (4, 'subscription cancel', 'Subscription Cancel Mail to Customer','Your subscription for [product_product_name] has been cancelled.','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [affiliate_aff_name],&lt;br /&gt;&lt;br /&gt;You have just earned a new commission of&amp;nbsp; [affiliate_commision]&lt;br /&gt;&lt;br /&gt;Here are the details:&lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Order Value: [order_amount]&lt;br /&gt;Order Date: [order_order_completed_date]&lt;br /&gt;Your Commission: [affiliate_commision]&lt;br /&gt;Commission Release Date: [affiliate_locked_date]&lt;br /&gt;&lt;br /&gt;Please login to the affiliate area at&amp;nbsp;[affiliate_login_url] to read the details. &lt;br /&gt;&lt;br /&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (5, 'commision gain mail to affiliate', 'Commision Gain Mail to Affiliate','You have earned [affiliate_commision] for [product_product_name].','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;New commission request notification &lt;br /&gt;&lt;br /&gt;Amount: [payrequest_amount]&lt;br /&gt;Affiliate: [affiliate_aff_name]&lt;br /&gt;Email: [affiliate_aff_email]&lt;br /&gt;Affiliate Id: [affiliate_id]&lt;br /&gt;Request Comments: [payrequest_comments] &lt;br /&gt;&lt;br /&gt;Please login to the admin area at&amp;nbsp;[administrator_login_url] to process this request. &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (6, 'commision request mail to administrator', 'Commision Request Mail to Administrator','Commission of [payrequest_amount] requested by [affiliate_aff_name]','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [affiliate_aff_name], &lt;br /&gt;A payment of [paid_amount] has been sent to you.&lt;br /&gt;&lt;br /&gt;Here are the details: &lt;br /&gt;Amount: [paid_amount]&lt;br /&gt;Date Paid: [paid_dt_paid]&lt;br /&gt;Payment Details: [paid_details]&lt;br /&gt;Transaction Id: [paid_paypal_txn_id]&lt;br /&gt;&lt;br /&gt;Please login to the affiliate area at [affiliate_login_url] to view the details.&lt;br /&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (7, 'commision received mail to affiliate', 'Commision Received Mail to Affiliate','Your commission of [paid_amount] has been released.','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name], &lt;br /&gt;&lt;br /&gt;Thank you for the order of [product_product_name].&lt;br /&gt;&lt;br /&gt;Please use the following information to make a payment of [order_amount] &lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;[order_comment] &lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;&lt;/p&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (8, 'mail for cheque payment', 'Mail For Cheque/DD Payment','Instructions for payments for [product_product_name]','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;A new order has been placed for [product_product_name]. &lt;br /&gt;&lt;br /&gt;Here are the details of the order:&lt;br /&gt;Customer: [customer_cust_name]&lt;br /&gt;Email: [customer_cust_email]&lt;br /&gt;Contact No: [customer_contact_no]&lt;br /&gt;Country: [customer_country]&lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Amount: [order_amount]&lt;br /&gt;Comments: [order_cust_comment]&lt;br /&gt;Payment Gateway: [order_payment_gateway]&lt;br /&gt;&lt;br /&gt;Please login to the admin area at&amp;nbsp;[administrator_login_url] to view the details. &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (9, 'order mail to merchant', 'Order Mail to Administrator','New order for [product_product_name]','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name], &lt;br /&gt;&lt;br /&gt;A new post has been posted at the forum: [forum_forum_name] &lt;br /&gt;Topic: [topic_topic_name]&lt;br /&gt;&lt;br /&gt;[topic_description] &lt;br /&gt;&lt;br /&gt;You can read more about it at [post_url] &lt;br /&gt;&lt;br /&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (10, 'forum_mail_to_user', 'Forum Mail to User','New Reply: [forum_forum_name]','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [administrator_username],&lt;br /&gt;&lt;br /&gt;Forum: [forum_forum_name]&lt;br /&gt;Topic: [topic_topic_name]&lt;br /&gt;Post URL: [post_url]&lt;br /&gt;Customer: [customer_cust_name]&lt;br /&gt;Email: [customer_cust_email] &lt;br /&gt;&lt;br /&gt;[topic_description] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (11, 'forum_mail_to_admin', 'Forum Mail to Administrator', 'New post: [forum_forum_name]','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;p&gt;Dear [administrator_username], &lt;/p&gt;&lt;p&gt;A new ticket was raised with the following details:&lt;br /&gt;Subject: [ticket_subject]&lt;br /&gt;Date: [ticket_message_date]&lt;br /&gt;URL: [ticket_url]&lt;br /&gt;Message: [ticket_message] &lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Amount: [order_amount]&lt;br /&gt;Order Id: [order_id]&lt;br /&gt;Order Date:[order_order_completed_date]&lt;br /&gt;Customer: [customer_cust_name]&amp;nbsp;&lt;br /&gt;Contact No: [customer_contact_no]&lt;br /&gt;Email: [customer_cust_email]&lt;br /&gt;&lt;br /&gt;Please login to the admin area at [administrator_login_url]&amp;nbsp;to respond to this ticket. &lt;/p&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (12, 'ticket_open_mail', 'Ticket Mail to Administrator','New ticket raised for [product_product_name]','".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name], &lt;br /&gt;&lt;br /&gt;[administrator_username] has replied to your support request.&lt;br /&gt;-------------------------------------------&lt;br /&gt;[ticket_message]&lt;br /&gt;-------------------------------------------&lt;br /&gt;You can read more about it [ticket_url]&lt;br /&gt;&lt;br /&gt;Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (13, 'ticket_mail_to_customer', 'Ticket Mail to Customer','Re: [ticket_subject]','".$mail_message."','H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [affiliate_aff_name], &lt;br /&gt;&lt;br /&gt;A client has requested a refund for [product_product_name]. &lt;br /&gt;&lt;br /&gt;The order details are:&lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Customer: [customer_cust_name]&lt;br /&gt;Customer Email: [customer_cust_email]&lt;br /&gt;Product: [product_product_name]&lt;br /&gt;Amount: [order_amount]&lt;br /&gt;Payment Confirmed on: [order_order_completed_date]&lt;br /&gt;Your Commission: [affiliate_commision]&lt;br /&gt;&lt;br /&gt;Your commission of [affiliate_commision] for this sale has been cancelled.&lt;br /&gt;&lt;br /&gt;You can login to the affiliate area at [affiliate_login_url]&amp;nbsp;to view the details of this transaction. Regards&lt;br /&gt;[administrator_username] &lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (14, 'refund_mail_to_affiliate', 'Product Refund Mail to Affiliate', 'A client has requested a refund', '".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [affiliate_aff_email]&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Thank You for joining our affiliate program.&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;We have an extensive affiliate control panel for you where you can get a list of products to promote, monitor your referrals, track your commissions and withdrawls etc.&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;You can login to the affiliate area with the following details:&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Email ID : [affiliate_aff_email]&lt;br /&gt;Password : [affiliate_aff_pass]&lt;br /&gt;Login URL : [affiliate_login_url]&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;br /&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;For your reference, your affiliate id is: [affiliate_id]&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Wishing you all the luck for your success&lt;/font&gt;&lt;/p&gt;&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Regards&lt;br /&gt;[administrator_username]&lt;/font&gt;&lt;/p&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (15, 'welcome_mail_to_affiliate', 'Welcome Mail to Affiliate', 'Welcome [affiliate_aff_name] as our affiliate.', '".$mail_message."', 'H',-1)";

			$mail_message=addslashes("Dear [administrator_name]&lt;br /&gt;&lt;br /&gt;Your account details are:&lt;br /&gt;&lt;br /&gt;Name : [administrator_name]&lt;br /&gt;Email :[administrator_ad_email]&lt;br /&gt;Password : [administrator_password]&lt;br /&gt;User Name : [administrator_username]&lt;br /&gt;&lt;br /&gt;Login URL : [administrator_login_url]");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (16, 'forgot_password_admin', 'Forgot Password Mail to Administrator', 'Password reminder for admin', '".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [affiliate_aff_name]&lt;br /&gt;&lt;br /&gt;You can login to the affiliate area with the following details:&lt;br /&gt;&lt;br /&gt;Id : [affiliate_id]&lt;br /&gt;Email : [affiliate_aff_email]&lt;br /&gt;Password : [affiliate_aff_pass]&lt;br /&gt;Name : [affiliate_aff_name]&lt;br /&gt;Login URL : [affiliate_login_url]&lt;br /&gt;&lt;br /&gt;Regards&lt;br /&gt;[administrator_username]&lt;/font&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (17, 'forgot_password_affiliate', 'Forgot Password Mail to Affiliate', 'Password reminder for affiliate area for [affiliate_aff_name]', '".$mail_message."', 'H',-1)";

			$mail_message=addslashes("&lt;p&gt;&lt;font face=&quot;Arial, Helvetica, sans-serif&quot; size=&quot;2&quot;&gt;Dear [customer_cust_name],&lt;br /&gt;&lt;br /&gt;Here is the login details for the client's area&lt;br /&gt;&lt;br /&gt;Email : [customer_cust_email]&lt;br /&gt;Password : [customer_password]&lt;br /&gt;Name : [customer_cust_name]&lt;br /&gt;Contact No : [customer_contact_no]&lt;br /&gt;&lt;br /&gt;You can login with this detail at [customer_login_url]&lt;br /&gt;&lt;br /&gt;Regards,&lt;br /&gt;[administrator_username]&lt;br /&gt;&lt;/font&gt;&lt;/p&gt;");
			$sql_data[]="INSERT INTO ".$tableprefix."mail_setting_tb VALUES (18, 'forgot_password_customer', 'Forgot Password Mail to Customer', 'Password reminder for [customer_cust_name]', '".$mail_message."', 'H',-1)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."messages_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."messages_tb (
			id int(11) NOT NULL auto_increment,
			campaign_id int(11) NOT NULL,
			subject varchar(255) NOT NULL,
			matter varchar(255) NOT NULL,
			content_type char(1) NOT NULL,
			time_period char(1) NOT NULL,
			time_value int(11) NOT NULL,
			serial_no int(11) NOT NULL,
			status char(1) NOT NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."notification_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."notification_tb (
			id int(11) NOT NULL auto_increment,
			forum_topic_id int(11) NOT NULL,
			forum_user_id int(11) NOT NULL,
			notify char(1) NOT NULL,
			user_type VARCHAR( 10 ) NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."paid_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."paid_tb (
			id int(11) NOT NULL auto_increment,
			dt_paid date NOT NULL default '0000-00-00',
			paypal_txn_id varchar(50) NOT NULL default '',
			details text NOT NULL,
			amount double(15,2) NOT NULL default '0.00',
			request_id int(11) default NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."payment_gateway_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."payment_gateway_tb (
			id int(11) NOT NULL auto_increment,
			paypal_business_id varchar(255) default NULL,
			paypal_ord_status char(1) default NULL,
			paypal_status char(1) default NULL,
			paydotcom_secret_code varchar(50) default NULL,
			paydotcom_ord_status char(1) default NULL,
			paydotcom_status char(1) default NULL,
			twoco_vender_act_no varchar(255) default NULL,
			twoco_secure_word varchar(255) default NULL,
			twoco_ord_status char(1) default NULL,
			twoco_status char(1) default NULL,
			offline_status char(1) default NULL,
			order_text varchar(255) default NULL COMMENT 'text should be displayed on link',
			order_display_text text default NULL COMMENT 'text should be displayed on thanks page',
			paypal_caption VARCHAR( 100 ) default NULL,
			twocheckout_caption VARCHAR( 100 ) default NULL,
			paydotcom_caption VARCHAR( 100 ) default NULL,
			paypal_demo VARCHAR( 3 ) default NULL,
			twoco_demo VARCHAR( 3 ) default NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."payrequest_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."payrequest_tb (
			id int(11) NOT NULL auto_increment,
			aff_id int(11) NOT NULL default '0',
			amount double(15,2) NOT NULL default '0.00',
			request_dt date NOT NULL default '0000-00-00',
			comments text,
			status char(1) NOT NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."popup_settings_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."popup_settings_tb (
			popup_id int(11) NOT NULL auto_increment,
			popup_matter text,
			popup_subject varchar(255) default NULL,
			popup_width int(11) default NULL,
			popup_height int(11) default NULL,
			popup_left int(11) default NULL,
			popup_top int(11) default NULL,
			popup_top_move int(11) default NULL,
			popup_top_move_time int(11) default NULL,
			popup_move_upto int(11) default NULL,
			status char(1) NOT NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (popup_id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."product_payment_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."product_payment_tb (
			id int(11) NOT NULL auto_increment,
			comment text,
			cust_comment text,
			cust_id varchar(11) default NULL,
			prod_id varchar(11) default NULL,
			amount double default '0',
			sales_date datetime default NULL,
			payment_gateway char(1) default NULL COMMENT 'P for Paypal C for ToCheckOut O for Pay.com D for DimandDraft and Cheque',
			transaction_id varchar(255) default NULL,
			payment_status char(1) default NULL,
			order_status char(1) default NULL,
			order_completed_date datetime default NULL,
			prod_download_url varchar(255) default NULL,
			download_type char(1) default NULL,
			download_value int(11) default '0',
			total_downloads int(11) default '0',
			payment_for char(1) default NULL COMMENT 'N for New Order, S for subscription',
			parent_order_id int(11) default NULL,
			unique_key varchar(255) default NULL,
			subscription_start_date date default NULL,
			subscription_end_date date default NULL,
			next_subscription_date date default NULL,
			subscription_cancel_date date default NULL,
			affiliate_id int(11) default NULL,
			l1_comm double(15,2) default NULL,
			l2_comm double(15,2) default NULL,
			l3_comm double(15,2) default NULL,
			commision_amount double(15,2) default NULL,
			locked_date date default NULL,
			commision_date date default NULL,
			discount_id int(11) default NULL,
			discount_amount double(15,2) default NULL,
			referrals_id INT NULL,
			integration_key INT NULL,
			user_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";

			$sql_data[]="INSERT INTO ".$tableprefix."product_payment_tb ( id , comment , cust_comment , cust_id , prod_id , amount , sales_date , payment_gateway , transaction_id , payment_status , order_status , order_completed_date , prod_download_url , download_type , download_value , total_downloads , payment_for , parent_order_id , unique_key , subscription_start_date , subscription_end_date , next_subscription_date , subscription_cancel_date , affiliate_id , l1_comm , l2_comm , l3_comm , commision_amount , locked_date , commision_date , discount_id , discount_amount,user_id )VALUES ('1', 'Demo order', 'Demo order', '1', NULL , '0', '2007-04-23 13:30:01', 'P', 'Demo_Trans_1001', 'C', 'C', '2007-04-23 13:30:01', 'Test.zip', 'H', '10', '0', 'N', '1', 'aasssddff', NULL , NULL , NULL , NULL , '1', NULL , NULL , NULL , NULL , NULL , NULL , NULL , NULL,-1)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."products_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."products_tb (
			id int(11) NOT NULL auto_increment,
			product_name varchar(255) default NULL,
			product_summary text,
			product_url varchar(255) default NULL,
			free_product_url text,
			l1_sale int(11) default '0',
			l2_sale int(11) default '0',
			l3_sale int(11) default '0',
			l1_comm double(15,2) default '0.00',
			l2_comm double(15,2) default '0.00',
			l3_comm double(15,2) default '0.00',
			status char(1) default 'a',
			prod_date date default NULL,
			hot char(1) default NULL,
			product_price double default NULL,
			product_image varchar(255) default NULL,
			pd_2checkout_product_id varchar(255) default NULL,
			pd_2checkout_product_id_type int(11) default NULL,
			pd_2cout_url varchar(255) default NULL,
			pd_paypal_url varchar(255) default NULL,
			pd_downloadable char(1) default NULL,
			pd_download_url varchar(255) default NULL,
			pd_download_type char(1) default NULL,
			pd_download_value int(11) default NULL,
			subs_product char(1) default NULL,
			subs_price double(15,2) default NULL,
			subs_period char(1) default NULL,
			subs_terms char(1) default NULL,
			terms_value int(11) default NULL,
			paydotcom_prod_id varchar(50) default NULL,
			send_download_mail char(1) default NULL,
			content_type char(1) default NULL,
			subject varchar(255) default NULL,
			message text,
			group_id int(11) default NULL,
			use_affiliate char(3) default NULL,
			`condition` text,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."promotion_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."promotion_tb (
			id int(11) NOT NULL auto_increment,
			prod_id int(11) default NULL,
			comment text,
			date date NOT NULL,
			content_type char(1) default NULL,
			subject text,
			message text,
			affall CHAR( 1 ) NULL ,
			prod_list TEXT NULL ,
			maxaff_id INT NULL ,
			maxcust_id INT NULL ,
			message_status CHAR( 1 ) NULL,
			last_exe_time DATETIME NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."promoware_config_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."promoware_config_tb (
			id int(11) NOT NULL auto_increment,
			product_id int(11) NOT NULL,
			req_referrals int(11) default NULL,
			unique_email char(1) default NULL,
			ref_content_type char(1) default NULL,
			ref_subject varchar(255) default NULL,
			ref_message text,
			member_content_type char(1) default NULL,
			member_subject varchar(255) default NULL,
			member_message text,
			total_members int(11) default NULL,
			total_referrals int(11) default NULL,
			promoware_caption varchar(255) default NULL,
			visit_required char(1) default NULL,
			min_referrals int(11) default NULL,
			submit_caption VARCHAR( 255 ) NULL,
			referral_name_caption VARCHAR( 255 ) NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."promoware_member_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."promoware_member_tb (
			id int(11) NOT NULL auto_increment,
			promoware_id int(11) NOT NULL,
			name varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			ip_address varchar(255) NOT NULL,
			referral_url varchar(255) NOT NULL,
			landing_url varchar(255) NOT NULL,
			date date NOT NULL,
			mail_sent char(1) default 'N',
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."promoware_referrals_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."promoware_referrals_tb (
			id int(11) NOT NULL auto_increment,
			promoware_id int(11) NOT NULL,
			member_id int(11) NOT NULL,
			email varchar(255) NOT NULL,
			verified char(1) default 'N',
			varification_code varchar(100) default NULL,
			ref_name VARCHAR( 255 ) NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."referrals_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."referrals_tb (
			id int(11) NOT NULL auto_increment,
			referral_url varchar(255) NOT NULL,
			ip_address varchar(255) NOT NULL,
			referral_date datetime NOT NULL,
			aff_id int(11) NOT NULL,
			ad_id int(11) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."sales";
			$sql_data[]="CREATE TABLE ".$tableprefix."sales (
			id int(11) NOT NULL auto_increment,
			aff_id int(11) NOT NULL default '0',
			prod_id int(11) NOT NULL default '0',
			sale_dt date NOT NULL default '0000-00-00',
			comm_amount double(15,2) NOT NULL default '0.00',
			locked_till_dt date NOT NULL default '0000-00-00',
			pay_id varchar(255) default NULL,
			remarks text NOT NULL,
			sales_no int(11) NOT NULL,
			status char(1) NOT NULL,
			sale_amount double(15,2) NOT NULL,
			sale_type char(1) NOT NULL,
			l1_sales int(11) NOT NULL,
			l2_sales int(11) NOT NULL,
			l1_commamt double(15,2) NOT NULL,
			l2_commamt double(15,2) NOT NULL,
			l3_commamt double(15,2) NOT NULL,
			license_id int(11) NOT NULL,
			trans_info varchar(255) NOT NULL,
			client_name varchar(255) NOT NULL,
			client_email varchar(255) NOT NULL,
			no_of_copy int(11) NOT NULL,
			client_status char(1) NOT NULL,
			sale_id int(11) default NULL,
			refund_date date default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."subscribers_details_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."subscribers_details_tb (
			id int(11) NOT NULL auto_increment,
			subs_id int(11) NOT NULL,
			field_id int(11) NOT NULL,
			field_value varchar(255) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."subscribers_messages_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."subscribers_messages_tb (
			id int(11) NOT NULL auto_increment,
			subs_id int(11) NOT NULL,
			msg_id int(11) NOT NULL,
			delivered_date datetime NOT NULL,
			deliver_status char(1) NOT NULL,
			open_status char(1) NOT NULL,
			bounced_status char(1) NOT NULL,
			open_date datetime NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			//check with subscribers table piyush 21108144
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."subscribers_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."subscribers_tb (
			id int(11) NOT NULL auto_increment,
			campaign_id int(11) NOT NULL,
			name varchar(255) NOT NULL,
			emailid varchar(255) NOT NULL,
			subs_date datetime NOT NULL,
			status char(1) NOT NULL,
			unique_key varchar(255) NOT NULL,
			unsubs_date datetime default NULL,
			mail_type char(1) NOT NULL,
			user_id int(11) default NULL,
			muser_id int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."test_cases_details_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."test_cases_details_tb (
			id int(11) NOT NULL auto_increment,
			test_case_id int(11) NOT NULL,
			action char(1) NOT NULL,
			landing_url varchar(255) NOT NULL,
			action_url varchar(255) NOT NULL,
			ip_address varchar(255) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."test_cases_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."test_cases_tb (
			id int(11) NOT NULL auto_increment,
			group_id int(11) NOT NULL,
			test_case_name varchar(255) NOT NULL,
			code text NOT NULL,
			status char(1) NOT NULL,
			total_impressions int(11) NOT NULL,
			total_actions int(11) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."test_groups_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."test_groups_tb (
			id int(11) NOT NULL auto_increment,
			group_name varchar(255) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."ticket";
			$sql_data[]="CREATE TABLE ".$tableprefix."ticket (
			id int(11) NOT NULL auto_increment,
			order_id int(11) NOT NULL,
			subject varchar(255) NOT NULL,
			priority char(1) NOT NULL,
			start_date date NOT NULL,
			turn char(1) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."ticket_details";
			$sql_data[]="CREATE TABLE ".$tableprefix."ticket_details (
			id int(11) NOT NULL auto_increment,
			ticket_id int(11) NOT NULL,
			message varchar(255) NOT NULL,
			message_date date NOT NULL,
			message_by char(1) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."visitors_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."visitors_tb (
			id int(11) NOT NULL auto_increment,
			test_case_id int(11) NOT NULL,
			show_date datetime NOT NULL,
			ip_address varchar(100) NOT NULL,
			action char(1) default 'N',
			order_id int(11) default NULL,
			show_url varchar(255) default NULL,
			referrer_url varchar(255) default NULL,
			action_url varchar(255) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."visits_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."visits_tb (
			id int(11) NOT NULL auto_increment,
			aff_id int(11) NOT NULL default '0',
			dt datetime NOT NULL default '0000-00-00 00:00:00',
			ip varchar(20) NOT NULL default '',
			ref_url varchar(255) default NULL,
			count int(11) default '1',
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS demo_tb";
			$sql_data[]="CREATE TABLE demo_tb (
			id int(11) NOT NULL auto_increment,
			field_name varchar(255) NOT NULL,
			field_value varchar(255) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."product_group_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."product_group_tb (
			id int(11) NOT NULL auto_increment,
			name varchar(50) NOT NULL,
			description varchar(255) NOT NULL,
			status char(1) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="INSERT INTO ".$tableprefix."product_group_tb VALUES (1, 'Default', 'This is default group','A',-1)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."affiliate_commision_slab_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."affiliate_commision_slab_tb (
			id int(11) NOT NULL auto_increment,
			affiliate_id int(11) default NULL,
			l1_comm double(15,2) default NULL,
			l2_comm double(15,2) default NULL,
			l3_comm double(15,2) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."commision_slab_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."commision_slab_tb (
			id int(11) NOT NULL auto_increment,
			product_id int(11) default NULL,
			l1_comm double(15,2) default NULL,
			l2_comm double(15,2) default NULL,
			l3_comm double(15,2) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."news_announcement_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."news_announcement_tb (
			id int(11) NOT NULL auto_increment,
			announcement varchar(255) NOT NULL,
			from_date date default NULL,
			till_date date default NULL,
			status char(1) NOT NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."discount_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."discount_tb (
			id int(11) NOT NULL auto_increment,
			code varchar(50) default NULL,
			prod_id int(11) default NULL,
			from_date date default NULL,
			to_date date default NULL,
			max_order int(11) default NULL,
			type char(1) default NULL COMMENT 'F for fix, P for percentage',
			discount double(10,2) default NULL,
			status text,
			discount_type char(1) default NULL,
			reduce_unit double(10,2) default NULL,
			reduce_type char(1) default NULL,
			reduce_after int(11) default NULL,
			reduce_after_type char(1) default NULL,
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."commision_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."commision_tb (
			id int(11) NOT NULL auto_increment,
			affiliate_id int(11) NOT NULL,
			order_id int(11) default NULL,
			commision double(15,2) default NULL,
			commision_date date default NULL,
			refunded char(1) default NULL,
			locked_date date default NULL,
			is_varified char(1) default 'N',
			`user_id` int(15) NOT NULL,
			PRIMARY KEY  (id)
			)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."orderpage_config_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."orderpage_config_tb (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			header_caption VARCHAR( 100 ) NULL ,
			header_bg_image VARCHAR( 255 ) NULL ,
			header_bg_color VARCHAR( 50 ) NULL ,
			table_head_color VARCHAR( 50 ) NULL ,
			table_border_color VARCHAR( 50 ) NULL ,
			footer_html VARCHAR( 255 ) NULL,
			header_caption_color VARCHAR( 50 ) NULL ,
			table_head_text_color VARCHAR( 50 ) NULL ,
			page_bg_color VARCHAR( 50 ) NULL ,
			page_text_color VARCHAR( 255 ) NULL,
			thank_you_message text NULL,
			language_file VARCHAR( 255 ) NULL,
			`user_id` int(15) NOT NULL
			)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."tellafriend_config_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."tellafriend_config_tb (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			total_friends INT NULL ,
			tell_a_friend_subject TEXT NULL ,
			tell_a_friend_matter TEXT NULL ,
			include_friend_comment CHAR( 1 ) NULL,
			`user_id` int(15) NOT NULL
			)";

			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."reward_commission_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."reward_commission_tb (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			affiliate_id INT NULL ,
			hits INT NULL ,
			user_id int(15) NOT NULL,
			reward_flag INT NULL
			)";
			
			$sql_data[]="DROP TABLE IF EXISTS ".$tableprefix."reward_details_tb";
			$sql_data[]="CREATE TABLE ".$tableprefix."reward_details_tb (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			affiliate_id INT NULL ,
			order_id INT NULL,
			user_id int(15) NOT NULL
			)";

	
/**********************************			***********************/
			$file = "psf/config/config.php";
			$error_msg1="<br><div align='center'><a href='javascript:history.go(-1)'><< Back</a></div>";
//echo $user_name;
$install=mysql_pconnect($host_name,$user_name,$password) or die("<table width='70%' border='1' align='center' bgcolor='#ffccff'><tr><td align='center'><font color='#FFFFFF'><b>Installation Error</b></font></td></tr><tr><td><font color='red'><b>".mysql_error().$error_msg.$error_msg1."</b></font></td></tr></table>");
//print_r($sql_data);
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
				
				
				$sql = "UPDATE `".TABLE_PREFIX."addon_tbl` set `status`='A' where keyword='psf'";
				mysql_query($sql,$install);
				
				
				

				
				$handle = @fopen("psf/config/config.php","w");
							
				

				$txt = '<?php
					define("WEBTITLE","'.$site_name.'");
					define("HOSTNAME_AFFDB","'.$host_name.'");
					define("USERNAME_AFFDB","'.$user_name.'");
					define("PASSWORD_AFFDB","'.$password.'");
					define("DATABASE_AFFDB","'.$db_name.'");
					define("TABLE_PREFIX","'.$tableprefix.'");
					define("SERVER_NAME","'.$site_path.'");
					define("BASEPATH","'.$site_path.'");
					define("ROOTPATH","'.$root_path.'");
					define("RECORD_PER_PAGE","50");
					define("SESSION_PREFIX","PSF_SESS_");
					define("MSESSION_PREFIX","CP_SESS_");
					define("VERSION","1.0");
					define("RELEASED_DATE","02-15-2007");
					define("ARTICLES_DATE_FORMAT","Y-m-d");
					define("ARTICLES_CATEGORY_POSITION","RIGHT");
					define("CURRENCY_TYPE","&#036;");
					define("CURRENCY_SYMBOL","USD");
					define("DATE_SEPERATOR","-");
					define("DATE_FORMAT","d M Y");
					define("DATE_TIME_FORMAT","d M Y h:i A");
					define("APPLICATION_PATH","'.$root_path.'");
					define("NO_OF_CAMPAIGN_MAILS","50");
					define("IS_FREE","NO");
					define("PROD_NAME","E-Commorce");
					define("CT_VERSION","1.0");
					define("PSF_VERSION","1.0");
					define("PROMO_URL","http://www.productsaleframework.com");
					define("OUTGOING_MESSAGE","Powered by Free Product Sale Framework from Kalptaru Infotech Ltd. http://www.ProductSaleFramework.com");
					//used in paypal ipn where as VERIFIED flag is required or not for updateing order
					define("USE_VERIFIED","YES");
               define("OUTER_CSS_PATH","'.SERVER_PATH.'");
				?>';
				//error_reporting(~E_NOTICE & ~E_STRICT);
					fputs($handle,$txt,strlen($txt));
					fclose($handle);
					
					require_once("psf/config/config.php");

							
					$sql="INSERT INTO ".$tableprefix."config_tb (id,username,password,ad_email,affiliate_registration_option,commision_depth,query_string_vb,cookie_vb,cookie_lt,lock_period,link_path,user_id) VALUES(1,'".$ct_admin_data['username']."','".$ct_admin_data['password']."','".$ct_admin_data['email_address']."','2','1','aff_id','aff_cookie',31536000,30,'".$site_path."',-1)";		
					mysql_query($sql,$install);


					$sql="insert into ".$tableprefix."customer_tb (id,cust_email,password,cust_name,self_aff_id,status,user_id)values(1,'".$ct_admin_data['email_address']."','".$ct_admin_data['password']."','".$ct_admin_data['username']."',1,'A',-1)";
					mysql_query($sql,$install);
										
										
					$sql="INSERT INTO ".$tableprefix."affiliates_tb (id,aff_name,aff_email,aff_pass,status,paypal_email,parent_aff_id,self_customer_id,user_id) VALUES(1,'".$ct_admin_data['username']."','".$ct_admin_data['email_address']."','".$ct_admin_data['password']."','A','".$ct_admin_data['email_address']."',0,1,-1)";
					mysql_query($sql,$install);

	
					mysql_close($install);							
							
							
					echo "<table width='70%' border='1'
align='center' bgcolor='#DDDDDD'><tr><td align='center'><font color='red'><b><p>
We have successfully merged E-Commerce Suite into your script. Now you can use
E-Commerce Script from the following URL</b></P></font><p><a 
href='".$site_path."admin/index.php'>".$site_path."</a></P></td></tr></table>";
			}
							
			
}	//PHP code will come here

function checkForWriteable()
{
	global $ok_to_install;
	$ok_config = false;
	$ok_temp_data = false;
	$ok_trackingpages = false;
	
	echo "<table width='70%' border='0' align='center' cellspacing='5' style='padding-left:30px;'><tr><td align='left'><li>All the folders are in the psf folder at the root<br>(permission should be set to 0707 or above for this folder)</td></tr>";


	if(!is_writable("psf/config"))
	{
		if (!@chmod("psf/config", 0707))
		{
			echo "<tr><td align='left'><li><b><font color='red'>Current Status: Folder psf/config is not writable </font></b></td></tr>";
			$ok_to_install=false; 
			$ok_config = false;
		}
	}
	else
	{
		$ok_config = true;
	}


	
	if ($ok_config === true)
	{
	$file = "psf/config/config.php";
	$somecontent = "<tr><td align='left'><li>This is the Config File. You have not completed the installation process complete. Complete it first.</td></tr>";
	if ($handle = @fopen($file, "w"))
		{
				if (!@fwrite($handle, $somecontent))
				{
						echo "<tr><td align='left'><li><b><font color='red'>Current Status: Not Able To Write to Config File</font></b></td></tr>";
					$ok_to_install=false; 
				}
				else
					{
				echo "<tr><td align='left'><li><b><font color='blue'>Current Status: Folder psf/Config Ok</font></b></td></tr>";
				}
		}
		else
		{
		echo "<tr><td align='left'><li><b><font color='red'>Current Status: Not Able To Create Temp File</font></b></td></tr>";
		$ok_to_install=false; 
		}

	}
	echo "</table><P>&nbsp;</P><P>&nbsp;</P>";
}

?>


	
	

<?php require_once("right.php"); ?>

<?php require_once("bottom.php"); ?>

