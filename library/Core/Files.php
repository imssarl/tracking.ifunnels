<?php
/**
 * WorkHorse Framework
 *
 * @category Project
 * @package Project_Wpress
 * @license http://opensource.org/licenses/ MIT License
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @author Rodion Konnov <kindzadza@mail.ru>
 * @date 26.03.2010
 * @version 0.5
 */


/**
 * File system methods
 *
 * @category Project
 * @package Project_Wpress
 * @copyright Copyright (c) 2005-2010, Rodion Konnov
 * @license http://opensource.org/licenses/ MIT License
 */
class Core_Files {

	public static $fileInfo=array( 'filenameOnly'=>1, 'withSize'=>2, 'withFrendlySize'=>3, 'withMTime'=>4 );

	/**
	 * Рекурсивное сканирование директорий
	 *
	 * @param string  $arrRes - список поддиректорий с файлами и файлов в текущей директории
	 * @param string  $_strDir - директория
	 * @param integer  $_intInfo - детализация информации о файлах. см Core_Files::$fileInfo
	 * @return boolean
	 */
	public static function dirScan( &$arrRes, $_strDir='', $_intInfo=1 ) {
		if ( empty( $_strDir ) ) {
			return false;
		}
		$arrRes=array();
		foreach( new RecursiveIteratorIterator( 
				new RecursiveDirectoryIterator( $_strDir, RecursiveDirectoryIterator::KEY_AS_PATHNAME ), 
				RecursiveIteratorIterator::SELF_FIRST ) as $directory => $info ) {
			// учитываем только читаемые файлы но не ссылки
			if ( $info->isLink()||!$info->isReadable() ) {
				continue;
			}
			if ( substr_count( $info->getPathname(), '.svn' )>0 ) { // это как-бы фильтр TODO!!! 08.07.2009
				continue;
			}
			if ( $info->isDir() ) {
				if ( !isSet( $arrRes[$info->getPathname()] ) ) {
					$arrRes[$info->getPathname()]=array();
				}
			}
			if ($info->isFile()) {
				// надо как-то гибче сделать и добавить все возможности DirectoryIterator TODO!!! 25.10.2010
				switch( $_intInfo ) {
					case self::$fileInfo['filenameOnly']:
						$arrRes[$info->getPath()][]=$info->getFilename();
					break;
					case self::$fileInfo['withSize']:
						$arrRes[$info->getPath()][]=array(
							'name'=>$info->getFilename(),
							'size'=>$info->getSize(),
						);
					break;
					case self::$fileInfo['withFrendlySize']:
						$arrRes[$info->getPath()][]=array(
							'name'=>$info->getFilename(),
							'size'=>$info->getSize(),
							'frendly_size'=>Core_Files::byteToFrendly($info->getSize())
						);
					break;
					case self::$fileInfo['withMTime']:
						$arrRes[$info->getPath()][]=array(
							'name'=>$info->getFilename(),
							'size'=>$info->getSize(),
							'frendly_size'=>Core_Files::byteToFrendly( $info->getSize() ),
							'date'=>$info->getMTime(),
						);
					break;
				}
			}
		}
		return !empty( $arrRes );
	}

	/**
	 * Перевод байтов в человеко-понятную форму
	 * это вынести в плагин смарти TODO!!! 25.10.2010
	 *
	 * @param integer $_intSize - размер в байтах
	 * @return string
	 */
	public static function byteToFrendly( $_intSize=0 ) {
		if ( $_intSize<1024 ) {
			$_strSize=$_intSize.' Byte';
		} elseif ( $_intSize<1024*1024 ) {
			$_strSize=((int)($_intSize/1024)).' Kb';
		} else {
			$_strSize=((int)(($_intSize/1024)*100/1024)/100).' Mb';
		}
		return $_strSize;
	}

	/**
	 * Получить расширение файла
	 *
	 * @param string  $_str - путь с файлом
	 * @return string
	 */
	public static function getExtension( $_str='' ) {
		return pathinfo( $_str, PATHINFO_EXTENSION );
	}

	/**
	 * Получить имя файла
	 *
	 * @param string  $_str - путь с файлом
	 * @return string
	 */
	public static function getFileName( $_str='' ) {
		if ( version_compare( phpversion(), "5.2.0", "<" ) ) {
			$_arr=pathinfo( $_str );
			return ( empty( $_arr['extension'] )? $_arr['basename']:substr($_arr['basename'],0,strlen($_arr['basename'])-strlen($_arr['extension'])-1) );
		}
		return pathinfo( $_str, PATHINFO_FILENAME );
	}

	/**
	 * Получить имя файла с расширением
	 *
	 * @param string  $_str - путь с файлом
	 * @return string
	 */
	public static function getBaseName( $_str='' ) {
		return pathinfo( $_str, PATHINFO_BASENAME );
	}

	/**
	 * Получить путь до файла
	 *
	 * @param string  $_str - путь с файлом
	 * @return string
	 */
	public static function getDirName( $_str='' ) {
		if ( is_dir( $_str ) ) { // если путь без файла pathinfo вернёт без последней категории!!
			return $_str;
		}
		return pathinfo( $_str, PATHINFO_DIRNAME );
	}

	/**
	 * Получить массив со всеми каталогами, корень имеет самый большой индекс
	 *
	 * @param string  $_str - путь с файлом или просто путь
	 * @return array
	 */
	public static function getDirsOfPath( $_str='' ) {
		$_obj=Core_String::getInstance( self::getDirName( $_str ) );
		return array_reverse( $_obj->separate( (DIRECTORY_SEPARATOR=='/'?'\/':'\\\\') ) );
	}

	/**
	 * удаление файлов
	 *
	 * @param mixed $_mix in (array или string)
	 * @return boolean
	 */
	public static function rmFile( $_mix=array() ) {
		if ( is_array( $_mix ) ) {
			$_arrErr=array_map( array( 'Core_Files', 'rmFile' ), $_mix );
			if ( !in_array( false, $_arrErr ) ) {
				return true;
			}
		}
		if ( is_string( $_mix )&&is_file( $_mix ) ) {
			return unlink( $_mix );
		}
		return false;
	}

	/**
	 * рекурсивное удаление файлов, директорий
	 *
	 * @param mixed $_mix in (array или string)
	 * @return boolean
	 */
	public static function rmDir( $_mix=array() ) {
		if ( is_array( $_mix ) ) {
			$_arrErr=array_map( array( 'Core_Files', 'rmDir' ), $_mix );
			if ( !in_array( false, $_arrErr ) ) {
				return true;
			}
		}
		if ( is_string( $_mix )&&is_dir( $_mix ) ) {
			if ( self::dirScan( $_arr, $_mix ) ) {
				$_arr=array_reverse( $_arr );
				foreach( $_arr as $_strDir=>$_arrFiles ) {
					foreach( $_arrFiles as $_strFile ) {
						self::rmFile( $_strDir.DIRECTORY_SEPARATOR.$_strFile );
					}
					@rmdir( $_strDir );
				}
			}
			@rmdir( $_mix );
			return true;
		}
		return false;
	}

	/**
	 * получение содиржимого файла
	 *
	 * @param string $strContent out
	 * @param string $_strFile in
	 * @return boolean
	 */
	public static function getContent( &$strContent , $_strFile='' ) {
		if ( empty( $_strFile ) ) {
			return false;
		}
		$strContent=@file_get_contents( $_strFile );
		return $strContent!==false;
	}

	/**
	 * запись содержимого в файл
	 *
	 * @param string $strContent in
	 * @param string $_strFile in
	 * @return boolean
	 */
	public static function setContent( &$strContent, $_strFile='' ) {
		if ( empty( $_strFile ) ) {
			return false;
		}
		$_intBytes=@file_put_contents( $_strFile, $strContent );
		return $_intBytes!==false;
	}
	
	/**
	 * дописываем содержимого в файл
	 *
	 * @param string $strContent in
	 * @param string $_strFile in
	 * @return boolean
	 */
	public static function addContent( &$strContent, $_strFile='' ) {
		if ( empty( $_strFile ) ) {
			return false;
		}
		$_intBytes=@file_put_contents( $_strFile, $strContent, FILE_APPEND | LOCK_EX );
		return $_intBytes!==false;
	}

	/**
	 * массовое создание файлов в директории
	 *
	 * @param array $arrFiles in array( 'fileName1'=>content, ... )
	 * @param string $strDest in папка в которой создавать файлы
	 * @return boolean
	 */
	public static function setContentMass( &$arrFiles, $strDest='' ) {
		if ( empty( $strDest )||empty( $arrFiles )||!is_array( $arrFiles ) ) {
			return false;
		}
		foreach( $arrFiles as $k=>$v ) {
			if ( !self::setContent( $v, $strDest.$k ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * приходящие данные добавляются в файл сообщений
	 * на вермя добавления файл блокируется
	 *
	 * @param mixed $_mix in (array или string)
	 * @return boolean
	 */
	public static function devMess( $_mix=array() ) {
		if ( empty( $_mix ) ) {
			$_mix='empty data come to devMess';
		} elseif ( is_array( $_mix ) ) {
			$_mix=print_r( $_mix, true );
		}
		$_strHead=str_repeat( '-=', 25 ).' added on '.date( 'c' );
		$_intBytes=@file_put_contents( 
			Zend_Registry::get( 'config' )->path->relative->root.'debmes.txt', 
			$_strHead.str_repeat( PHP_EOL, 2 ).$_mix.str_repeat( PHP_EOL, 2 ), 
			FILE_APPEND | LOCK_EX );
		return $_intBytes!==false;
	}
}
?>