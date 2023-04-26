<?php
class Project_Updater_Sql {

	// /services/updater.php?method=10112021
	public static function update10112021() {
		Project_DApp_Project::install();
		Project_DApp_Address::install();
	}

	// /services/updater.php?method=06102020
	public static function update06102020() {
		Project_TestAB::install();
		Project_TestAB_View::install();
		Project_TestAB_Goal::install();
	}

	public static function update01032019(){
		Project_Pagebuilder_GoogleUTM::install();
	}

	public static function updateUtmCreate(){
		Project_Squeeze_GoogleUTM::install();
	}
	
	public static function updateTrafficEndFlag(){
		Core_Sql::setExec("ALTER TABLE traffic_campaigns ADD COLUMN `flg_end` INT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;");
	}

	public static function updateInstallSqueezeRestrictions(){
		Project_Squeeze_Restrictions::install();
	}

	public static function updateInstallTxtNation(){
		Project_Billing_Txtnation::install();
	}
	
	public static function updateCcsServices(){
		Core_Sql::setExec("ALTER TABLE `billing_aggregator` ADD COLUMN `services` INT(1) NOT NULL DEFAULT '0' AFTER `id`;");
	}
	
	public static function updateTrafficLockingUser(){
		Core_Sql::setExec("ALTER TABLE `lpb_subscribers` ADD COLUMN `uid` INT(11) NULL DEFAULT NULL AFTER `squeeze_id`;;");
	}

	public static function updateTrafficLocking(){
		Core_Sql::setExec("CREATE TABLE `traffic_locking` (
			`user_id` INT(11) NOT NULL AUTO_INCREMENT,
			`campaign_id` INT(11) NULL DEFAULT NULL,
			PRIMARY KEY (`user_id`, `campaign_id`)
		)
		COLLATE='utf8_general_ci'
		ENGINE=InnoDB;");
	}
	
	public static function updateAddTrafficClicks(){
		Core_Sql::setExec("CREATE TABLE `traffic_credits` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`credits` INT(11) NULL DEFAULT NULL,
			UNIQUE INDEX `id` (`id`)
		)
		COLLATE='utf8_general_ci'
		ENGINE=InnoDB;");
		Core_Sql::setExec("CREATE TABLE `traffic_campaigns` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`category_id` INT(11) NULL DEFAULT NULL,
			`url` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
			`credits` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
			`user_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`edited` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			UNIQUE INDEX `id` (`id`)
		);");
		Core_Sql::setExec("CREATE TABLE `traffic_subscribers` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`referer` INT(11) NULL DEFAULT NULL,
			`campaign_id` INT(11) NULL DEFAULT NULL,
			`ip` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
			`added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			UNIQUE INDEX `id` (`id`)
		)
		COLLATE='utf8_general_ci'
		ENGINE=InnoDB;");
	}

	public static function updateAddSqueezeClicks(){
		Core_Sql::setExec("CREATE TABLE `lpb_conversions` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`squeeze_id` INT(11) NULL DEFAULT NULL,
			`ip` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
			`added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			UNIQUE INDEX `id` (`id`)
		);");
	}

	public static function updateCcsOxigen8(){
	//	Core_Sql::setExec('ALTER TABLE `billing_aggregator` CHANGE COLUMN `status` `status` VARCHAR(24) NULL DEFAULT NULL AFTER `aggregator`;');
		Core_Sql::setExec("CREATE TABLE `billing_oxigen8` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`clientid` VARCHAR(255) NULL DEFAULT NULL,
			`service` VARCHAR(32) NULL DEFAULT NULL,
			`transactionid` VARCHAR(20) NULL DEFAULT NULL,
			`added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			UNIQUE INDEX `id` (`id`)
		)
		COLLATE='utf8_general_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=55;
		");
	}

	public static function updateCcsValues(){
	//	Core_Sql::setExec('ALTER TABLE `billing_aggregator` CHANGE COLUMN `status` `status` VARCHAR(24) NULL DEFAULT NULL AFTER `aggregator`;');
		Core_Sql::setExec('ALTER TABLE `billing_aggregator` CHANGE COLUMN `transactionid` `transactionid` INT(19) NULL DEFAULT NULL AFTER `service`;');
	}

	public static function updateContentVideo(){
		Core_Sql::setExec('ALTER TABLE content_video CHANGE embed_code body TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL');
	}


	
	
	/**
	 * Добавление в БД таблицы content_setting
	 */
	public static function updateToTask456(){

		Core_Sql::setExec("CREATE TABLE `content_setting` (`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,`user_id` INT(11) UNSIGNED NOT NULL,`flg_source` TINYINT(1) UNSIGNED NOT NULL,`settings` TEXT NULL,PRIMARY KEY (`id`))COLLATE='utf8_general_ci' ENGINE=MyISAM ROW_FORMAT=DEFAULT AUTO_INCREMENT=0");
	}


	/**
	 * зачистка от ненужных значений
	 */
	public static function updateCleanerClicbankTypesCategorys() {
		Core_Sql::setExec( "TRUNCATE `lng_storage`" );
		Core_Sql::setExec( "DELETE FROM `category_types` WHERE `title`='Clickbank'");
		Core_Sql::setExec( "DROP TABLE `category_clickbank_tree`" );
	}
	/**
	 * добавление added, edited to clickbank
	 */
	public static function updateClickbank() {
		Core_Sql::setExec( "ALTER TABLE content_clickbank ADD edited INT UNSIGNED NOT NULL DEFAULT '0',ADD added INT UNSIGNED NOT NULL DEFAULT '0'" );
	}
	
	/**
	 * добавление дэфолтного значения для поля tags.
	 */
	public static function updatePubTable() {
		Core_Sql::setExec( "ALTER TABLE `pub_project` CHANGE COLUMN `tags` `tags` TEXT NULL DEFAULT NULL" );
		Core_Sql::setExec( "ALTER TABLE `pub_project`  CHANGE COLUMN `keywords_random` `keywords_random` INT(10) UNSIGNED NOT NULL DEFAULT '0',  CHANGE COLUMN `keywords_first` `keywords_first` INT(10) UNSIGNED NOT NULL DEFAULT '0';" );

	}
	
	/**
	 * два новых поля для системы категорий, для поддержки в них мультиязычности
	 */
	public static function updateCategoryTypeTable() {
		$fields=Core_Sql::getField( 'DESCRIBE category_types' );
		if ( !in_array( 'flg_multilng', $fields ) ){
			Core_Sql::setExec( 'ALTER TABLE category_types ADD flg_multilng tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER flg_typelink' );
		}
		if ( !in_array( 'flg_deflng', $fields ) ){
			Core_Sql::setExec( 'ALTER TABLE category_types ADD flg_deflng tinyint(2) unsigned NOT NULL DEFAULT 0 AFTER flg_multilng' );
		}
	}

	/**
	 * добавление дэфолтного значения для поля.
	 */
	public static function updateCnbTable() {
		Core_Sql::setExec( "ALTER TABLE `es_cnb` CHANGE `parent_id` `parent_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'" );
	}

	/**
	 * два новых поля для системы артиклов дата добавления и редактирования в юникстайм
	 */
	public static function updateArticlesTableDatas() {
		$fields=Core_Sql::getField( 'DESCRIBE hct_am_article' );
		if ( !in_array( 'edited', $fields ) ){
			Core_Sql::setExec( 'ALTER TABLE hct_am_article ADD edited INT(11) UNSIGNED NOT NULL DEFAULT "0" AFTER user_id' );
		}
		if ( !in_array( 'added', $fields ) ){
			Core_Sql::setExec( 'ALTER TABLE hct_am_article ADD added INT(11) UNSIGNED NOT NULL DEFAULT "0" AFTER edited' );
		}
	}

	/**
	 * добавляет все сайты системы в синдикейшн
	 * нужно выполнять один раз до релиза синдикейшена
	 */
	public static function updateDBallSiteToSyndication() {
		foreach( Project_Syndication::$tables as $k=>$v ) {
			if ( $k=='badwords' ) {
				continue;
			}
			Core_Sql::setExec( 'TRUNCATE TABLE '.$v );
		}
		$_arrSql=array();
		foreach( Project_Sites::$tables as $k=>$v ) {
			$_arrSql[]='(SELECT user_id, id, '.$k.' FROM '.$v.')';
		}
		$_strSql='INSERT INTO '.Project_Syndication::$tables['sites'].' (user_id,site_id,flg_type) SELECT * FROM ('.join( ' UNION ', $_arrSql ).') tmp';
		Core_Sql::setExec( $_strSql );
		$indexes=Core_Sql::getAssoc( 'SHOW INDEXES FROM '.Project_Syndication::$tables['sites'] );
		foreach( $indexes as $v ) {
			if ( in_array( $v['Key_name'], array( 's_idx', 't_idx', 'u_idx' ) ) ) {
				return;
			}
		}
		Core_Sql::setExec( 'ALTER TABLE '.Project_Syndication::$tables['sites'].' ADD INDEX `s_idx` (`site_id`),  ADD INDEX `t_idx` (`flg_type`),  ADD INDEX `u_idx` (`user_id`)' );
	}

	/**
	 * добавить поле catedit во все 5 таблиц содержащих сайты
	 *
	 */
	public static function updateDBsite_tables392() {
		foreach( Project_Sites::$tables as $table ) {
			$fields=Core_Sql::getAssoc( 'DESCRIBE '.$table );
			$_flg=$_flg2=false;
			foreach( $fields as $v ) {
				if ( $v['Field']=='catedit' ) {
					$_flg=true;
				}
				if ( $v['Field']=='user_id'&&empty( $v['Key'] ) ) {
					$_flg2=true;
				}
			}
			if ( $_flg2 ) {
				Core_Sql::setExec( 'ALTER TABLE '.$table.' ADD INDEX `u_idx` (`user_id`),  ADD INDEX `c_idx` (`category_id`)' );
			}
			if ( $_flg ) {
				continue;
			}
			Core_Sql::setExec( 'ALTER TABLE '.$table.' ADD catedit INT(11) UNSIGNED NOT NULL DEFAULT "0" AFTER added' );
		}
		$indexes=Core_Sql::getAssoc( 'SHOW INDEXES FROM bf_theme2blog_link' );
		foreach( $indexes as $v ) {
			if ( in_array( $v['Key_name'], array( 'b_idx', 't_idx' ) ) ) {
				return;
			}
		}
		Core_Sql::setExec( 'ALTER TABLE `bf_theme2blog_link`  ADD INDEX `b_idx` (`blog_id`),  ADD INDEX `t_idx` (`theme_id`)' );
	}

	/**
	 * обновить поле flg_type для проектов блогфюжен. переход на типы сайтов Project_Sites
	 *
	 */
	public static function updateDBpub_project(){
			Core_Sql::setExec('ALTER TABLE pub_project DROP flg_generate , DROP keywords_random , DROP keywords_first');
		$fields=Core_Sql::getField( 'DESCRIBE pub_project' );
		if ( !in_array( 'keywords_first', $fields ) ){
			Core_Sql::setExec('ALTER TABLE pub_project ADD flg_generate tinyint(1) unsigned NOT NULL DEFAULT 0  AFTER flg_schedule');
			Core_Sql::setExec('ALTER TABLE pub_project ADD keywords_random INT unsigned NOT NULL AFTER flg_generate, ADD keywords_first INT unsigned NOT NULL AFTER keywords_random');
			Core_Sql::setExec('ALTER TABLE pub_schedule ADD keyword VARCHAR( 255 ) NOT NULL');
			Core_Sql::setExec("ALTER TABLE pub_schedule CHANGE blog_id site_id INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'");
			Core_Sql::setExec("ALTER TABLE pub_rsscache CHANGE blog_id site_id INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'");
			Core_Sql::setExec("ALTER TABLE pub_rssblogs CHANGE blog_id site_id INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'");
			Core_Sql::setExec('UPDATE pub_project SET flg_type=5 WHERE flg_type=1');
		}		
	}

	/**
	 * Добавление статей в nvsb
	 *
	 */
	public static function updateDBes_nvsb(){
		$fields=Core_Sql::getField( 'DESCRIBE es_nvsb' );
		if ( !in_array( 'flg_articles', $fields ) ){
			Core_Sql::setExec('ALTER TABLE es_nvsb ADD flg_articles tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER flg_damas');
		}		
	}

	/**
	 * замена старых типов сайтов на новые из Ptoject_Sites
	 *
	 */
	public static function updateSiteType_articles_links(){
		Core_Sql::setExec('UPDATE hct_articles_links SET site_type='.Project_Sites::NCSB.' WHERE site_type='.Project_Articles_Links::Type_NCSB );
		Core_Sql::setExec('UPDATE hct_articles_links SET site_type='.Project_Sites::PSB.' WHERE site_type='.Project_Articles_Links::Type_PSB  );
	}
	
	/**
	 * добавление поля flg_update и added (BlogFusion) Спринт [bf ncsb psb fixes & continue develop syndication feature (20 Apr — 03 May)]
	 *
	 * @param Core_Updater $obj
	 * @return void
	 */
	public static function updateDBbf_blog2update2history308( Core_Updater $obj ) {
		$fields=Core_Sql::getField( 'DESCRIBE bf_blog2update' );
		if ( !in_array( 'flg_update', $fields ) ){
			Core_Sql::setExec('ALTER TABLE bf_blog2update ADD flg_update tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER updater_id');
		}
		if ( !in_array('added', $fields) ) {
			Core_Sql::setExec('ALTER TABLE bf_blog2update ADD added int(11) unsigned NOT NULL DEFAULT 0 AFTER flg_update');
		}
	}

	/**
	 * добавление поля category_id в NCSB. Спринт [psb refactoring & update blogfusion (01 Apr — 14 Apr)]
	 *
	 * @param Core_Updater $obj
	 * @return void
	 */
	public static function updateDBhct_ncsbsites2sprint52106( Core_Updater $obj  ) {
		$fields=Core_Sql::getField("DESCRIBE hct_ncsbsites");
		if ( !in_array( 'category_id', $fields ) ){
			Core_Sql::setExec('ALTER TABLE hct_ncsbsites ADD category_id int(11) unsigned NOT NULL DEFAULT 0 AFTER id');
		}
		if ( !in_array('flg_damas', $fields) ) {
			Core_Sql::setExec('ALTER TABLE hct_ncsbsites ADD flg_damas tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER temp_id');
			Core_Sql::setExec('UPDATE hct_ncsbsites SET flg_damas=IF(damas_type="split",2,IF(damas_type="single",1,0))');
			Core_Sql::setExec( 'ALTER TABLE hct_ncsbsites DROP COLUMN damas_type' );
		}
		if ( in_array( 'source_type', $fields ) ) {
			Core_Sql::setExec( 'ALTER TABLE hct_ncsbsites DROP COLUMN source_type' );
		}
	}

	/**
	 * Добавление тэгов в публикацию постов через Content Publishing. Спринт [psb refactoring & update blogfusion (01 Apr — 14 Apr)]
	 *
	 * @param Core_Updater $obj
	 * @return void
	 */
	public static function updateDBpub_project2sprint52106( Core_Updater $obj  ) {
		$fields=Core_Sql::getField("DESCRIBE pub_project");
		if (!in_array('tags', $fields) ){		
			Core_Sql::setExec('ALTER TABLE pub_project ADD tags TEXT NOT NULL ');
		}
	}
	
	/**
	 * добавление фичи для BlogFusion похожей на прежний master blog 
	 * для подстановки настроек при создании нового блога
	 *
	 * @param Core_Updater $obj
	 * @return void
	 */
	public static function updateUpdateNewBlogFusionTable( Core_Updater $obj ) {
		$fields=Core_Sql::getField("DESCRIBE bf_blogs");
		if (!in_array('flg_settings', $fields) ){
			Core_Sql::setExec( 'ALTER TABLE bf_blogs ADD COLUMN flg_settings tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER flg_status' );
		}
	}

	/**
	 * добавление поля flg_status в новый блогфьюжн
	 *
	 * @param Core_Updater $obj
	 * @return void
	 */
	public static function updateNewBlogFusionField( Core_Updater $obj ) {
		Core_Sql::setExec( 'ALTER TABLE bf_blogs ADD COLUMN flg_status tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER flg_type' );
	}

	/**
	 * Изменение связки с таблицей u_users (было связано по id будет по parent_id (ethnicashe id))
	 *
	 * @param Core_Updater $obj
	 * @return void
	 */
	public static function updateVideoManager( Core_Updater $obj ) {
		Core_Sql::setExec( 'UPDATE cnm_vm_item SET user_id=(SELECT parent_id FROM u_users WHERE id=user_id)' );
		Core_Sql::setExec( 'UPDATE tc_categories SET user_id=(SELECT parent_id FROM u_users WHERE id=user_id) WHERE user_id>0' );
	}

	/**
	 * Удаление дублированых аккаунтов (если человек менял пароль в ethnicashe то ему создавало новый аккаунт)
	 * select GROUP_CONCAT(id SEPARATOR ' - '), nickname, count(*) test, parent_id from u_users group by parent_id HAVING test>1 ORDER BY test;
	 *
	 * @param Core_Updater $obj
	 * @return void
	 */
	public static function updateUusers( Core_Updater $obj ) {
		$_arrIds=array( '321', '314', '249', '355', '186', '475', '376', '270', '147', '102', '322', '239', '236', '492', '367', '103', '204', '296', '484', '403', '415', '499', '416', '411' );
		Core_Sql::setExec( '
			DELETE u, group_link
			FROM u_users u
			LEFT JOIN u_link group_link ON group_link.user_id=u.id
			WHERE u.id IN("'.join( '", "', $_arrIds ).'")
		' );
	}

	/**
	 * Добавление поля mod_type в таблицу hct_affiliate_compaign для совместимости Affiliate Profit Booster и Covert Conversion Pro
	 *
	 * @param Core_Updater $obj
	 * @return unknown
	 */
	public static function updateDbHctAffiliateCompaign( Core_Updater $obj ){
		$fields=Core_Sql::getField("DESCRIBE hct_affiliate_compaign");
		if (in_array('mod_type', $fields) ){
			$obj->logger->info( 'This field already exist' );
			return false;
		}
		$sql="ALTER TABLE hct_affiliate_compaign ADD mod_type SET( 'cpp', 'affiliate' ) NOT NULL DEFAULT 'affiliate'";
		if ( Core_Sql::setExec($sql) ) {
			$obj->logger->info( 'Update successfully' );
		}
	}
	/**
	 * Добавление поля cloaked в hct_ccp_trackingpages для модуля Covert Conversion Pro 
	 *
	 * @param Core_Updater $obj
	 * @return unknown
	 */
	public static function updateDbHctCcpTrackingpages( Core_Updater $obj ){
		$fields=Core_Sql::getField("DESCRIBE hct_ccp_trackingpages");
		if (in_array('cloaked', $fields) ){
			$obj->logger->info( 'This field already exist' );
			return false;
		}
		$sql="ALTER TABLE hct_ccp_trackingpages ADD cloaked VARCHAR( 255 ) NOT NULL , ADD title VARCHAR( 255 ) NOT NULL , ADD keywords TEXT NOT NULL ";
		if ( Core_Sql::setExec($sql) ) {
			$obj->logger->info( 'Update successfully' );
		}
	}
		
	/**
	 * Обновление таблицы hct_spots для совместимости с новыми опшинсами
	 *
	 * @param Core_Updater $obj
	 */
	public static function updateDbHctSpots( Core_Updater $obj ){
		$fields=Core_Sql::getField("DESCRIBE hct_spots");
		if (!in_array('spot_id', $fields) ){
			$sql="ALTER TABLE hct_spots ADD spot_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ";
			Core_Sql::setExec($sql);
		}
		if (!in_array('spot_video', $fields) ){
			$sql="ALTER TABLE hct_spots ADD spot_video INT NOT NULL DEFAULT '0'";
			Core_Sql::setExec($sql);
		}	
				
		if (!in_array('spot_video_title', $fields) ){
			$sql="ALTER TABLE hct_spots ADD spot_video_title INT NOT NULL DEFAULT '0'";
			Core_Sql::setExec($sql);
		}	
		if (!in_array('spot_position', $fields) ){
			$sql="ALTER TABLE hct_spots ADD spot_position VARCHAR( 255 ) NOT NULL ";
			Core_Sql::setExec($sql);
		}	
		$sql="DELETE FROM hct_spots WHERE site_id > (SELECT id FROM hct_ncsbsites ORDER BY id DESC LIMIT 1) AND site_type='ncsb' ";
		Core_Sql::setExec($sql);
	}
	
	/**
	 * Перенос данных из старых опшинсов в новые.
	 *
	 */
	public static function updateDbHctSpotsLink () {
		$arrData=Core_Sql::getAssoc("SELECT spot_id,spot_saved_selections,spot_snippets FROM hct_spots");
		$arSnippets=array();
		foreach ($arrData as $value) {
			$tempSnippetsId=explode(",",$value['spot_snippets']);
			$snippetsId=array();
			foreach ($tempSnippetsId as $i) {
				if ($i && $i != 1) {
					$snippetsId[]=intval(Project_Options_Encode::decode($i));
				}
			}
			foreach ($snippetsId as $id)
			$arSnippets[]=array(
				"link_spot_id"=> $value['spot_id'],
				"link_spot_type"=> "snippets",
				"link_data_id"	=>	$id
			);
		}
		if (count($arSnippets))
		Core_sql::setMassInsert("hct_spots_link",$arSnippets);
		foreach ($arrData as $value) {
			$tempSavedSelectionsId=explode(",",$value['spot_saved_selections']);
			$SavedSelectionsId=array();
			foreach ($tempSavedSelectionsId as $i) {
				if ($i && $i != 1) {
					$SavedSelectionsId[]=intval(Project_Options_Encode::decode($i));
				}
			}
			foreach ($SavedSelectionsId as $id)
			$arSavedSelections[]=array(
				"link_spot_id"=> $value['spot_id'],
				"link_spot_type"=> "savedselections",
				"link_data_id"	=>	$id
			);
		}
		if (count($arSavedSelections))
		Core_sql::setMassInsert("hct_spots_link",$arSavedSelections);			
	}

	/**
	 * Обновление БД 02.10.2009
	 *
	 */
	public function updateToSprint5642(){
		/**
		 * 
		 * 2009_09_08_09_27_43
		 */
		Core_Sql::setExec("DROP TABLE IF EXISTS hct_articles_links");
		Core_Sql::setExec("CREATE TABLE `hct_articles_links` (`site_id` int(11) NOT NULL,`article_id` int(11) NOT NULL, `site_type` int(10) NOT NULL DEFAULT '1') ENGINE=MyISAM DEFAULT CHARSET=utf8");
		
		/**
		 * 2009_08_03_17_25_57.sql
		 */
		Core_Sql::setExec("DROP TABLE IF EXISTS hct_spots_link");
		Core_Sql::setExec("CREATE TABLE `hct_spots_link` (`link_spot_id` int(11) NOT NULL, `link_spot_type` varchar(20) NOT NULL, `link_data_id` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}
	
	/**
	 * Обновление таб. hct_snippet_parts для добавления функции Reset CSS 
	 *  а так же возможности сохранения типа ввода данных TEXT or HTML
	 *
	 */
	public function updateDBHctSnippetParts(Core_Updater $obj) {
		$obj->logger->info('Start');
//		Core_Sql::setExec("ALTER TABLE `hct_snippet_parts` ADD `reset_css` INT NOT NULL DEFAULT '0'");	// отработал 
//		Core_Sql::setExec("ALTER TABLE `hct_dams_adcampaigns` ADD `reset_css` INT NOT NULL DEFAULT '0';"); // отработал 
		
		$fields=Core_Sql::getField("DESCRIBE hct_snippet_parts");
		if (!in_array('inputmode', $fields) ){
			Core_Sql::setExec("ALTER TABLE `hct_snippet_parts` ADD `inputmode` SET( 'text', 'html' ) NOT NULL DEFAULT 'text'");
		}	
			
		$obj->logger->info('End');
		
	}
}
?>