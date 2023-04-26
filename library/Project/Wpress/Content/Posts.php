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
 * Blog posts remote management
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2009-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
class Project_Wpress_Content_Posts extends Project_Wpress_Content_Abstract {

	public function __construct() {
		$this->setTable( 'bf_ext_posts' )
			->setFields( array( 'id', 'ext_id', 'blog_id', 'title', 'content', 'tags', 'added' ) )
			->setDefaultOrder();		
	}
	
	public function getList( &$mixRes ){
		$_crawler=new Core_Sql_Qcrawler();
		$_crawler->set_select( 'c.*' );
		$_crawler->set_select('(SELECT COUNT(*) FROM bf_ext_comments as com WHERE com.ext_post_id = c.ext_id AND com.blog_id = c.blog_id) as comments');
		$_crawler->set_from( $this->table.' c' );
		$_crawler->set_where( 'c.blog_id='.Core_Sql::fixInjection( $this->blog->filtered['id'] ) );
		if( !empty( $this->_byTitle ) ){
			$_crawler->set_where('c.title='.Core_Sql::fixInjection( $this->_byTitle ) );
		}
		if ( !empty( $this->_withCategory ) ) {
			$ext_post_ids = Core_Sql::getField('SELECT ext_post_id FROM bf_ext_post2cat WHERE ext_cat_id = '.Core_Sql::fixInjection( $this->_withCategory ));
			if (!empty($ext_post_ids)) {
				$_crawler->set_where('c.ext_id IN ('. join(',',$ext_post_ids) .')' );
			}
		}
		if ( !empty( $this->_withIds ) ) {
			$_crawler->set_where( 'c.ext_id IN ('.Core_Sql::fixInjection( $this->_withIds ).')' );
		}		
		$_crawler->set_order_sort( $this->_withOrder );
		if ( !empty( $this->_withPagging ) ) {
			$this->_withPagging['rowtotal']=Core_Sql::getCell( $_crawler->get_result_counter( $_strTmp ) );
			$_crawler->set_paging( $this->_withPagging )->get_sql( $_strSql, $this->_paging );
		} elseif ( !$this->_onlyCount ) {
			$_crawler->get_result_full( $_strSql );
		}//p($_strSql);
		if ( $this->_onlyIds ) {
			$mixRes=Core_Sql::getField( $_strSql );
		} elseif ( $this->_onlyCount ) {
			$mixRes=Core_Sql::getCell( $_crawler->get_result_counter() );
		} elseif ( $this->_onlyOne||!empty( $this->_byTitle ) ) {
			$mixRes=Core_Sql::getRecord( $_strSql );
		} else {
			if (!empty($this->_withCategory) && empty($ext_post_ids)) {
				return !empty( $mixRes );	 
			}
			$mixRes=Core_Sql::getAssoc( $_strSql );
			if ( $this->_withCategories ) {
				foreach ( $mixRes as &$i ) {
					$i['categories'] =  Core_Sql::getField( 'SELECT ext_cat_id FROM bf_ext_post2cat WHERE blog_id = '.$i['blog_id'].' AND ext_post_id = '. $i['ext_id'] );	
				}
			}		
		}   
		$this->init();
		return !empty( $mixRes );		
	}
	
	public function get( &$arrRes, $_intId=0 ) {}
	

	public function set() {
		// проверяем данные на ошибки
		$this->data->setFilter( array( 'stripslashes', 'trim', 'clear' ));
		foreach( $this->data->filtered as $v ) {
			if ( empty( $v['title'] ) ) {
				$this->errors[$v['id']]=true;
			}
		}
		if ( !empty( $this->errors ) ) {
			return false;
		}
		// добавление/изменение/удаление на серваке
		$_export=new Project_Wpress_Connector_Export( $this->blog );
		if ( !$_export->post( $this ) ) {
			$_export->getErrors( $this->_errors['export'] );
			return false;
		}
		$this->setToDb( $this->data );
		return true;
	}

	// используется как при импорте категорий так и при управлении категориями
	public function setToDb( Core_Data $obj ) {
		if (empty($obj->filtered)) {
			$obj->setFilter(); // при иморте сделаем ещё раз
		}
		foreach( $obj->filtered as $v ) {
			if ( !empty( $v['del'] ) ) {
				if ( !empty( $v['id'] ) ) {
					$arrDel[]=$v['id'];
				}
				continue;
			}
			if ( empty( $v['id'] ) ) {
				unSet( $v['id'] );
				$v['blog_id']=$this->blog->filtered['id'];
				$v['added']=time();
			}
			if ( !empty( $v['catIds'] ) ) {
				Project_Wpress_Content_Category::category2post( $this->blog->filtered['id'], $v );
			}
			$obj->setMask( $this->fields );
			Core_Sql::setInsertUpdate( $this->table, $obj->getValidCurrent( $v ) );
		}
		$this->del( $arrDel );
	}

	public function del( $_mixId=array() ) {
		if ( empty( $_mixId ) ) {
			return false;
		}
		Core_Sql::setExec( 'DELETE p,c,com FROM '.$this->table.' as p LEFT JOIN bf_ext_comments as com ON p.ext_id = com.ext_post_id LEFT JOIN bf_ext_post2cat as c ON p.ext_id = c.ext_post_id WHERE p.id IN('.Core_Sql::fixInjection( $_mixId ).') AND p.blog_id="'.$this->blog->filtered['id'].'"' );
		return true;
	}
}
?>