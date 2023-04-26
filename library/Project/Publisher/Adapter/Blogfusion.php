<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Publisher_Adapter
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 23.03.2011
 * @version 2.0
 */


/**
 * Posting data on WordPress
 *
 * @category Project
 * @package Project_Publisher_Adapter
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */

class Project_Publisher_Adapter_Blogfusion implements Project_Publisher_Adapter_Interface {

	private static $_instance=NULL;
	private $_content = false;
	private $_siteId=false;
	private $_result=array();

	public static function getInstance(){
		if( self::$_instance == NULL ){
			self::$_instance= new Project_Publisher_Adapter_Blogfusion();
		}
		return self::$_instance;
	}
	
	public function setContent( &$data ){
		$this->_content=&$data;
		return $this;
	}

	public function setSite( $intId ){
		$this->_siteId=$intId;
		return $this;
	}

	public function post(){
		$_wp = new Project_Wpress_Content_Posts();
		foreach($this->_content as $_item ){
			$arrContent[]=array(
				'title'=> $_item['title'],
				'content' => $_item['body'],
				'catIds' => ( $_item['ext_category_id'] ) ? array( $_item['ext_category_id'] ) : array(),
				'time' => date('Y-m-d H:i:s',( ( !empty($_item['start']) )? $_item['start']:time() ) ),
				'tags'=> (( !empty($_item['tags']) )?$_item['tags']:'')
			);
		}
		$_wp->setBlogById( $this->_siteId );
		$_wp->setData( $arrContent );
		p($this->_siteId);
		$_bool=$_wp->set();
		$this->_result=$_wp->data;
		return $_bool;
	}

	public function getPublicateResult(){
		foreach( $this->_result as &$_item ){
			$_item['body']=$_item['content'];
		}
		return $this->_result;
	}
}
?>