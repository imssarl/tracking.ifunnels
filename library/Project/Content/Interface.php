<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Syndication
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2011, web2innovation
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 12.04.2011
 * @version 0.7
 */


/**
 * интерфейс коннектора к разным типам данных
 *
 * @category Project
 * @package Project_Syndication
 * @copyright Copyright (c) 2005-2011, web2innovation
 * @license http://opensource.org/licenses/ MIT License
 */
interface Project_Content_Interface {

	/**
	* Выбирает список контента
	* id, title, text присутствует в данных в любом случае
	*
	* @param mixed $mixRes отдаёт в виде array( array( id, title, text ), array )
	* @return boolean
	*/
	public function getList( &$mixRes );

	/**
	* Подсобный метод для формирования запроса
	* $_obj->withId( $_arrIds )->getContent( $mixRes )
	*
	* @param array $_arrIds - ids нужного контента
	* @return object
	*/
	public function withIds( $_arrIds=array() );

	/**
	* В случаях когда надо получить контент без
	* учёта принадлежности какому-либо пользователю
	*
	* @return object
	*/
	public static function getInstance();

	/**
	* Фильтр для списка контента
	* $_obj->setFilter( $_GET['arrFlt'] )->getList( $mixRes )
	*
	* @param array $_arrFilter - поля и значения фильтра
	* @return object
	*/
	public function setFilter( $_arrFilter=array() );

	/**
	* Получение массива для генерации постраничной навигации
	*
	* @param array $arrRes
	* @return object
	*/
	public function getPaging( &$arrRes ) ;

	/**
	* Ранее установленный фильтр для использования в шаблоне
	*
	* @param array $arrRes
	* @return object
	*/
	public function getFilter( &$arrRes ) ;

	/**
	 * Сколько контента вернуть
	 *
	 * @param  $_intLimit
	 * @return object
	 */
	public function setLimited( $_intLimit );

	/**
	 * Счетчик контента запощеного в проект от начала. Используется для внешних источников, те которые не име
	 *
	 * @param  $_intCounter
	 * @return object
	 */
	public function setCounter( $_intCounter );
}
?>