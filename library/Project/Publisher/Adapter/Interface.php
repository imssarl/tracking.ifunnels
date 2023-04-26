<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Publisher_Adapter
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 02.02.2010
 * @version 0.1
 */


/**
 * publisher adapter interface
 *
 * @category Project
 * @package Project_Publisher
 * @copyright Copyright (c) 2005-2010, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
interface Project_Publisher_Adapter_Interface {

	public static function getInstance();
	/**
	 * Posting content to site
	 * @abstract
	 * @return bool
	 */
	public function post();

	/**
	 * Set content
	 * @abstract
	 * @param  $data
	 * @return object $this
	 */
	public function setContent( &$data );

	/**
	 * Set site for posting
	 * @abstract
	 * @param  $intId
	 * @return object $this
	 */
	public function setSite( $intId );

	/**
	 * Get posting result
	 * @abstract
	 * @return array
	 */
	public function getPublicateResult();

}
?>