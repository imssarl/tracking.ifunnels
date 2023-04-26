<?php

class Project_DApp_Project extends Core_Data_Storage
{
    protected $_table  = 'dapp_project';
    protected $_fields = ['id', 'name', 'signature', 'added', 'edited'];

    private $_withCountAccount = false;

    public static function install()
    {
        Core_Sql::setExec("DROP TABLE IF EXISTS `dapp_project`");

        Core_Sql::setExec(
            "CREATE TABLE `dapp_project` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(255) NULL DEFAULT NULL,
				`signature` VARCHAR(40) NULL DEFAULT NULL,
				`added` INT(11) NULL DEFAULT NULL,
				`edited` INT(11) NULL DEFAULT NULL,
				UNIQUE INDEX `id` (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;"
        );
    }

    public function withCountAccount()
    {
        $this->_withCountAccount = true;
        return $this;
    }

    protected function beforeSet()
    {
        $this->_data->setFilter(['clear', 'trim']);

        if (empty($this->_data->filtered['signature'])) {
            $this->_data->setElement('signature', bin2hex(openssl_random_pseudo_bytes(30)));
        }

        if (empty($this->_data->filtered['name'])) {
            $this->_data->setElement('name', 'Unnamed Project');
        }

        return true;
    }

    protected function assemblyQuery()
    {
        parent::assemblyQuery();

        if ($this->_withCountAccount) {
            $this->_crawler->set_select('(SELECT COUNT(a.id) FROM dapp_address_project a WHERE a.project_id = d.id) as total');
        }
    }

    protected function init()
    {
        parent::init();

        $this->_withCountAccount = false;
    }

    public function del()
    {
        Core_Sql::setExec('DELETE FROM dapp_address_project WHERE project_id IN (' . Core_Sql::fixInjection($this->_withIds) . ')');
        return parent::del();
    }

    public static function getSignature($pid, $address)
    {
        if (empty($pid) || empty($address)) {
            return false;
        }

        $crawler = new Core_Sql_Qcrawler();

        $crawler->set_select('p.signature');
        $crawler->set_from('dapp_project AS p');
        $crawler->set_from('LEFT JOIN dapp_address_project AS ap ON p.id = ap.project_id');
        $crawler->set_from('LEFT JOIN dapp_address AS a ON ap.address_id = a.id');
        $crawler->set_where("a.address = '$address' AND MD5(p.id) = '$pid'");

        $signature = Core_Sql::getCell($crawler->get_result_full());

        return !empty($signature) ? $signature : false;
    }
}
