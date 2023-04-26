<?php

class Project_Conversionpixel extends Core_Data_Storage {
	protected $_table='lpb_conversionpixel';
	protected $_fields=array('id', 'squeeze_id', 'flg_pixeltype', 'ip', 'country_id', 'added');
	private $_flgPixeltype = array('view' => 1, 'lead' => 2, 'sale' => 3);

	public function beforeSet(){
		$this->_data->setFilter( array( 'clear' ) );
		$this->_data->setElements(
			array(
				'flg_pixeltype' => $this->_flgPixeltype[$this->_data->filtered['param']],
				'ip' => self::getUserIp()
			)
		);
		$this->_data->setElement( 'country_id', Core_Sql::getCell('SELECT country_id FROM getip_countries2ip WHERE ip_start <= ' . sprintf("%u\n", ip2long($this->_data->filtered['ip'])) . ' AND ' . sprintf("%u\n", ip2long($this->_data->filtered['ip'])) . ' <= ip_end') );
		return true;
	}

	public static function getUserIp(){
		if (!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip=$_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$ip=$_SERVER["REMOTE_ADDR"];
		}
		return $ip;
    }

	public function install(){
		Core_Sql::setExec('CREATE TABLE IF NOT EXISTS `lpb_conversionpixel` (
  			`id` int(11) NOT NULL AUTO_INCREMENT,
  			`squeeze_id` int(11) NOT NULL,
  			`flg_pixeltype` int(11) NOT NULL DEFAULT "0",
			`ip` varchar(255) NOT NULL DEFAULT "",
  			`country_id` int(11) NOT NULL DEFAULT "0",
  			`added` int(11) NOT NULL,
  			PRIMARY KEY (`id`)
			) 
			ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;'
		);
	}
}

?>