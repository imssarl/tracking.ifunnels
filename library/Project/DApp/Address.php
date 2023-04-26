<?php

class Project_DApp_Address extends Core_Data_Storage
{
    protected $_table  = 'dapp_address';
    protected $_fields = ['id', 'address', 'added'];

    private $_withAddress   = false;
    private $_withoutExist  = false;
    private $_withProjectId = false;

    public static function install()
    {
        Core_Sql::setExec("DROP TABLE IF EXISTS `dapp_address`");

        Core_Sql::setExec(
            "CREATE TABLE `dapp_address` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`address` VARCHAR(255) NULL DEFAULT NULL,
				`added` INT(11) NULL DEFAULT NULL,
				UNIQUE INDEX `id` (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;"
        );

        Core_Sql::setExec("DROP TABLE IF EXISTS `dapp_address_project`");

        Core_Sql::setExec(
            "CREATE TABLE `dapp_address_project` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`address_id` VARCHAR(255) NULL DEFAULT NULL,
				`project_id` INT(11) NULL DEFAULT NULL,
				UNIQUE INDEX `id` (`id`)
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;"
        );
    }

    public function withAddress($address)
    {
        $this->_withAddress = $address;
        return $this;
    }

    public function isExistData($_isExistData)
    {
        $this->_isExistData = $_isExistData;
        return $this;
    }

    public function withProjectId($project_id)
    {
        $this->_withProjectId = $project_id;
        return $this;
    }

    protected function assemblyQuery()
    {
        parent::assemblyQuery();

        if ($this->_withAddress) {
            $this->_crawler->set_where('d.address IN (' . Core_Sql::fixInjection($this->_withAddress) . ')');
        }

        if ($this->_isExistData) {
            $_columns = array_keys($this->_isExistData[0]);

            $_sQuery = array_map(function ($_column) {
                $values = Core_Sql::fixInjection(array_unique(array_column($this->_isExistData, $_column)));
                return "d.`$_column` IN ($values)";
            }, $_columns);

            $this->_crawler->set_where(join(' AND ', $_sQuery));
        }

        if ($this->_withProjectId) {
            $this->_crawler->set_from('LEFT JOIN dapp_address_project dap ON d.id = dap.address_id');
            $this->_crawler->set_where('dap.project_id IN (' . Core_Sql::fixInjection($this->_withProjectId) . ')');
        }
    }

    protected function init()
    {
        parent::init();

        $this->_withAddress   = false;
        $this->_isExistData   = false;
        $this->_withProjectId = false;
    }

    public function addToProject($project_id, $address_list)
    {
        $crawler = new Core_Sql_Qcrawler();

        $crawler->set_select('dap.`id`, dap.`address_id`');
        $crawler->set_from('`dapp_address_project` dap');
        $crawler->set_where('dap.`address_id` IN (' . Core_Sql::fixInjection($address_list) . ') AND project_id = ' . $project_id);

        $diff         = Core_Sql::getKeyVal($crawler->get_result_full());
        $address_list = array_diff($address_list, $diff);

        if (!empty($address_list)) {
            $address_list = array_map(function ($address_id) use ($project_id) {
                return ['project_id' => $project_id, 'address_id' => $address_id];
            }, $address_list);

            Core_Sql::setMassInsert('dapp_address_project', $address_list);
        }
    }
}
