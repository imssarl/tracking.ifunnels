<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Wpress
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 24.03.2010
 * @version 1.0
 */


/**
 * Wpress system notification events cntroller
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */


class Project_Wpress_Notification {

	/**
	 * сообщение всем пользователям о новой версии WP;
	 *
	 * @return void
	 */
	public static function newWordpressVersion() {
		Project_Wpress_Connector_Upgrade::getInstance()->getCurVersion( $latestVersion );
		Zend_Registry::get( 'objUser' )->withGroups( array( 'Unlimited', 'Blog Fusion' ) )->getList( $_arrUsers );
		$_arrTo=array();
		foreach( $_arrUsers as $v ) {
			$_arrTo[]=array( 'email'=>$v['email'], 'name'=>$v['nickname'] );
		}
		Core_Mailer::getInstance()
			->setVariables( array(
				'version'=>$latestVersion,
			) )
			->setTemplate( 'wpress_get_new_version' )
			->setSubject( 'Blog Fusion: '.$latestVersion.' WordPress Version Released' )
			->setPeopleTo( $_arrTo )
			->setPeopleFrom( 'support@creativenichemanager.info' )
			->sendOneToMany();
	}

	/**
	 * отправка писем пользователям о результатах обновления блогов
	 *
	 * @param array $mixData
	 * @return void
	 */
	public static function result2Users( &$_arrInfo ) {
		Project_Wpress_Connector_Upgrade::getInstance()->getCurVersion( $latestVersion );
		Zend_Registry::get( 'objUser' )->withParentId( array_keys( $_arrInfo ) )->getList( $_arrUsers );
		foreach( $_arrInfo as $_intUserId=>$arrBlogs ) {
			foreach( $_arrUsers as $k=>$v ) {
				if ( $v['parent_id']==$_intUserId ) {
					$_arrUser=$v;
					unSet( $_arrUsers[$k] );
					break;
				}
			}
			if ( empty( $_arrUser ) ) {
				continue;
			}
			Core_Mailer::getInstance()
				->setVariables( array(
					'version'=>$latestVersion,
					'arrBlogs'=>$arrBlogs,
					'name'=>$_arrUser['nickname'],
				) )
				->setTemplate( 'wpress_update_blogs' )
				->setSubject( 'Blog Fusion: ugrade blogs to '.$latestVersion.' WordPress Version' )
				->setPeopleTo( array( 'email'=>$_arrUser['email'], 'name'=>$_arrUser['nickname'] ) )
				->setPeopleFrom( 'support@creativenichemanager.info' )
				->sendOneToMany();
		}
	}

	/**
	 * Письмо вледельцу при создании блога.
	 *
	 * @param object $_data Core_Data instance
	 * @return void
	 */
	public static function createWP( Core_Data $_data ){
		if ( empty( $_data->filtered['admin_email'] ) ){
			return false;
		}
		$_uri=Zend_Uri_Http::factory( $_data->filtered['url'] );
		Core_Mailer::getInstance()
			->setVariables( array(
				'blogUrl'=>$_data->filtered['url'],
				'userLogin'=>$_data->filtered['dashboad_username'],
				'userPassword'=>$_data->filtered['dashboad_password'],
			) )
			->setTemplate( 'wpress_create_new' )
			->setSubject( 'New WordPress Site' )
			->setPeopleTo( array( 'email'=>$_data->filtered['admin_email'], 'name'=>$_data->filtered['dashboad_username'] ) )
			->setPeopleFrom( array('email'=>'wordpress@'.$_uri->getHost(), 'name'=>'WordPress') )
			->sendOneToMany();
	}
}
?>