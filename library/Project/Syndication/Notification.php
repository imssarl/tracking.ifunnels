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
 * Syndication system notification events cntroller
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */


class Project_Syndication_Notification {

	private static function getAllData( &$arrPrj, &$arrUser, $_intPrjId ) {
		if ( empty( $_intPrjId ) ) {
			return false;
		}
		$_prj=new Project_Syndication();
		if ( !$_prj->getOnlyProject( $arrPrj, $_intPrjId ) ) {
			return false;
		}
		$_intId=Zend_Registry::get( 'objUser' )->getIdByParent( $arrPrj['user_id'] );
		if ( !Zend_Registry::get( 'objUser' )->getProfileById( $arrUser, $_intId ) ) {
			return false;
		}
		$_content=new Project_Syndication_Content( $_intPrjId );
		$_content->getList( $arrPrj['content'] );
		return true;
	}

	public static function statusRejected( $_intPrjId=0 ) {
		if ( !self::getAllData( $arrUser, $arrPrj, $_intPrjId ) ) {
			return;
		}
		Core_Mailer::getInstance()
			->setVariables( array(
				'arrUser'=>$arrUser,
				'arrPrj'=>$arrPrj,
				'arrStat'=>array_flip( Project_Syndication_Content::$stat ),
			) )
			->setTemplate( 'syndication_project_rejected' )
			->setSubject( 'Content Syndication "'.$arrPrj['title'].'" Project Rejected' )
			->setPeopleTo( array( 'email'=>$arrUser['email'], 'name'=>$arrUser['nickname'] ) )
			->setPeopleFrom( 'support@creativenichemanager.info' )
			->sendOneToMany();
	}

	public static function statusApproved( $_intPrjId=0 ) {
		if ( !self::getAllData( $arrUser, $arrPrj, $_intPrjId ) ) {
			return;
		}
		Core_Mailer::getInstance()
			->setVariables( array(
				'arrUser'=>$arrUser,
				'arrPrj'=>$arrPrj,
				'arrStat'=>array_flip( Project_Syndication_Content::$stat ),
			) )
			->setTemplate( 'syndication_project_approved' )
			->setSubject( 'Content Syndication "'.$arrPrj['title'].'" Project Rejected' )
			->setPeopleTo( array( 'email'=>$arrUser['email'], 'name'=>$arrUser['nickname'] ) )
			->setPeopleFrom( 'support@creativenichemanager.info' )
			->sendOneToMany();
	}
}
?>