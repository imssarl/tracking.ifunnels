<?php

class Project_DApp_Signature extends Core_Data_Storage
{
    protected $_table = 'dapp_signature';
    protected $_fields = ['id', 'signature', 'added'];

    public static function install()
    {
        Core_Sql::setExec("DROP TABLE IF EXISTS `dapp_signature`");

        Core_Sql::setExec(
            "CREATE TABLE `dapp_signature` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`signature` VARCHAR(255) NULL DEFAULT NULL,
				`added` INT(11) NULL DEFAULT NULL,
				UNIQUE INDEX `id` (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;"
        );
    }

    public function beforeSet()
    {
        $this->_data->setFilter(['clear']);

        if (empty($this->_data->filtered['signature'])) {
            return Core_Data_Errors::getInstance()->setError("Enter field Signature");
        }

        return true;
    }
}