<?php

class Project_Pagebuilder_Quiz {

    protected $_table  = 'pb_quiz_';
    protected $_fields = array('id', 'pb_site_id', 'pb_page_id', 'quiz_id', 'quiz_answer_index', 'ip', 'country_id', 'added');

    public function __construct($_uid = false) {
        if ($_uid !== false) {
            $this->_table = $this->_table . $_uid;
        }
        $this->install($_uid);
    }

    public static function install($_uid = false) {
        Core_Sql::getInstance();
        try {
            Core_Sql::setConnectToServer('lpb.tracker');
            Core_Sql::setExec("CREATE TABLE IF NOT EXISTS `pb_quiz_" . $_uid . "` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`pb_site_id` INT(11) NULL DEFAULT NULL,
				`pb_page_id` INT(11) NULL DEFAULT NULL,
                `quiz_id` VARCHAR(255) NULL DEFAULT NULL,
				`quiz_answer_index` INT(11) NULL DEFAULT NULL,
				`ip` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
				`country_id` INT(4) NOT NULL DEFAULT '0',
				`added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
				UNIQUE INDEX `id` (`id`)
			)
			COLLATE='utf8_general_ci'
            ENGINE=InnoDB");
            
            Core_Sql::renewalConnectFromCashe();
        } catch (Exception $e) {
            Core_Sql::renewalConnectFromCashe();
        }
    }

    public function setEntered($_mix = array()) {
        $this->_data = is_object($_mix) ? $_mix : new Core_Data($_mix);
        return $this;
    }

    public function set() {
        $this->_data->setFilter();

        // Set time of create
        $this->_data->setElement('added', time());
    
        // Getting of user IP
        $ip = null;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $this->_data->setElement('ip', $ip);

        if (!empty($this->_data->filtered['ip'])) {
            $this->_data->setElement('country_id', Core_Sql::getCell('SELECT country_id FROM getip_countries2ip WHERE ip_start <= ' . sprintf("%u\n", ip2long($this->_data->filtered['ip'])) . ' AND ' . sprintf("%u\n", ip2long($this->_data->filtered['ip'])) . ' <= ip_end'));
        }

        try {
            Core_Sql::setConnectToServer('lpb.tracker');
            $this->_data->setElement('id', Core_Sql::setInsertUpdate($this->_table, $this->_data->setMask($this->_fields)->getValid()));
            Core_Sql::renewalConnectFromCashe();
        } catch (Exception $e) {
            Core_Sql::renewalConnectFromCashe();
            return $this;
        }
    }
}
