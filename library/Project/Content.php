<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Content
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 30.03.2011
 * @version 2.0
 */

/**
 * Content функционал
 *
 * @category Project
 * @package Project_Content
 * @copyright Copyright (c) 2009-2011, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Content {

	/**
	* типы контент источников по содержимому - видео, статьи, товары, остальное (например Keywords)
	* @var const
	*/
	const VIDEO=1, ARTICLE=2, GOODS=3, OTHERS=4;

/*
User's Content:
- Articles
- Videos
- Keywords
Pure Content:
- Articles
- Videos
- RSS
- PLR Articles
- Yahoo answers
Monetized Content:
- Amazon
- Clickbank
- Ebay
- Commision Junction
- LinkShare
- ShopZilla
*/
	public static $source=array(
		'User\'s Content'=>array(
			array( 'type'=>Project_Content::ARTICLE, 'flg_source'=>1, 'title'=>'Articles', 'label'=>'articles', 'class'=>'Project_Content_Adapter_Articles', 'availability'=>array( Project_Sites::BF, Project_Sites::CNB ) ),
			array( 'type'=>Project_Content::VIDEO, 'flg_source'=>2, 'title'=>'Videos', 'label'=>'videos', 'class'=>'Project_Content_Adapter_Videos', 'availability'=>array( Project_Sites::BF ) ),
			array( 'type'=>Project_Content::OTHERS, 'flg_source'=>3, 'title'=>'Keywords', 'label'=>'keywords', 'class'=>'Project_Content_Adapter_Keywords', 'availability'=>array( Project_Sites::CNB ) ),
		),
		'Pure Content'=>array(
			array( 'flg_source'=>4, 'title'=>'Articles', 'label'=>'purearticles', 'class'=>'Project_Content_Adapter_Purearticles' ),
			array( 'flg_source'=>5, 'title'=>'Videos', 'label'=>'purevideos', 'class'=>'Project_Content_Adapter_Purevideos' ),
			array( 'type'=>Project_Content::ARTICLE, 'flg_source'=>6, 'title'=>'RSS', 'label'=>'rss', 'class'=>'Project_Content_Adapter_RSS', 'availability'=>array( Project_Sites::BF ) ),
			array( 'flg_source'=>7, 'title'=>'PLR Articles', 'label'=>'plr', 'class'=>'Project_Content_Adapter_Plr' ),
			array( 'flg_source'=>8, 'title'=>'Yahoo answers', 'label'=>'yahooanswers', 'class'=>'Project_Content_Adapter_Yahoo' ),
		),
		'Monetized Content'=>array(
			array( 'flg_source'=>9, 'title'=>'Amazon', 'label'=>'amazon', 'class'=>'Project_Content_Adapter_Amazon' ),
			array( 'type'=>Project_Content::GOODS, 'flg_source'=>10, 'title'=>'Clickbank', 'label'=>'clickbank', 'class'=>'Project_Content_Adapter_Clickbank', 'availability'=>array( Project_Sites::BF, Project_Sites::CNB ) ),
			array( 'flg_source'=>11, 'title'=>'Ebay', 'label'=>'ebay', 'class'=>'Project_Content_Adapter_Ebay' ),
			array( 'flg_source'=>12, 'title'=>'Commision Junction', 'label'=>'cj', 'class'=>'Project_Content_Adapter_Keywords' ),
			array( 'flg_source'=>13, 'title'=>'LinkShare', 'label'=>'linkshare', 'class'=>'Project_Content_Adapter_Linkshare' ),
			array( 'flg_source'=>14, 'title'=>'ShopZilla', 'label'=>'shopzilla', 'class'=>'Project_Content_Adapter_Shopzilla' ),
		),
	);

	public static function toLabelArray() {
		$arrRes=array();
		foreach( Project_Content::$source as $k=>$type ) {
			foreach( $type as $source ) {
				$arrRes[$source['label']]=$source;
			}
		}
		return $arrRes;
	}

	public static function toOptgroupSelect( $_intSiteType=0 ) {
		$arrRes=array();
		foreach( Project_Content::$source as $k=>$type ) {
			$arrRes[$k]=array();
			foreach( $type as $source ) {
				if ( !empty( $_intSiteType )&&!in_array( $_intSiteType, $source['availability'] ) ) {
					continue;
				}
				$arrRes[$k][$source['flg_source']]=$source['title'];
			}
		}
		return $arrRes;
	}

	public static function factory( $_intId=0 ) {
		if ( empty( $_intId ) ) {
			throw new Exception( Core_Errors::DEV.'|Project_Content::factory( $_intId=0 ) - empty source id' );
		}
		foreach( Project_Content::$source as $type ) {
			foreach( $type as $source ) {
				if ( $_intId==$source['flg_source'] ) {
					return new $source['class'];
				}
			}
		}
		throw new Exception( Core_Errors::DEV.'|unknown source type' );
	}
}
?>