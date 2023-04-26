<?php

class Project_TestAB_View extends Core_Data_Storage
{
    protected $_table  = 'testab_pages_view';
    protected $_fields = ['id', 'pageid', 'current_option', 'visitor_ip', 'added'];

    private $_withPageId        = false;
    private $_withCurrentOption = false;
    private $_toSelectField     = false;
    private $_withVisitorIP     = false;

    /**
     * Create table on database
     *
     * @return void
     */
    public static function install()
    {
        Core_Sql::getInstance();
        try {
            Core_Sql::setConnectToServer('lpb.tracker');
            Core_Sql::setExec('DROP TABLE IF EXISTS `testab_pages_view`');

            Core_Sql::setExec("CREATE TABLE `testab_pages_view` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `pageid` INT(11) NULL DEFAULT NULL,
            `current_option` VARCHAR(5) NULL DEFAULT NULL,
            `visitor_ip` VARCHAR(50) NULL DEFAULT NULL,
            `added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
            UNIQUE INDEX `id` (`id`)
        );");
            Core_Sql::renewalConnectFromCashe();
        } catch (Exception $e) {
            Core_Sql::renewalConnectFromCashe();
        }
    }

    public function withPageId($pageid)
    {
        $this->_withPageId = $pageid;
        return $this;
    }

    public function withCurrentOption($option)
    {
        $this->_withCurrentOption = $option;
        return $this;
    }

    public function withVisitorIP($ip)
    {
        $this->_withVisitorIP = $ip;
        return $this;
    }

    public function toSelectField($fields)
    {
        $this->_toSelectField = $fields;
        return $this;
    }

    public function beforeSet()
    {
        $this->_data->setFilter('clear');
        return true;
    }

    public function set()
    {
        try {
            Core_Sql::setConnectToServer('lpb.tracker');
            parent::set();
            Core_Sql::renewalConnectFromCashe();
        } catch (Exception $e) {
            Core_Sql::renewalConnectFromCashe();
            return false;
        }

        return true;
    }

    /**
     * Builder for query
     *
     * @return void
     */
    protected function assemblyQuery()
    {
        parent::assemblyQuery();

        if ($this->_withPageId) {
            $this->_crawler->set_where('d.pageid = ' . Core_Sql::fixInjection($this->_withPageId));
        }

        if ($this->_withCurrentOption) {
            $this->_crawler->set_where('d.current_option = ' . Core_Sql::fixInjection($this->_withCurrentOption));
        }

        if ($this->_withVisitorIP) {
            $this->_crawler->set_where('d.visitor_ip = ' . Core_Sql::fixInjection($this->_withVisitorIP));
        }

        if ($this->_toSelectField) {
            $this->_crawler->clean_select();
            $this->_crawler->set_select(join(', ', $this->_toSelectField));
        }
    }

    protected function init()
    {
        $this->_withPageId        = false;
        $this->_withCurrentOption = false;
        $this->_toSelectField     = false;
        $this->_withVisitorIP     = false;
        parent::init();
    }

    public function getList(&$mixRes)
    {
        try {
            Core_Sql::setConnectToServer('lpb.tracker');
            parent::getList($mixRes);
            Core_Sql::renewalConnectFromCashe();
        } catch (Exception $e) {
            Core_Sql::renewalConnectFromCashe();
        }

        return $this;
    }

    /**
     * Add new record with stats
     *
     * @param [int] $pageid
     * @param [string] $current_option
     * @return boolean
     */
    public static function addStat($pageid, $current_option)
    {
        $ip = null;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        if (empty($pageid)) {
            return Core_Data_Errors::getInstance()->setError('Empty param [pageid]');
        }

        if (empty($current_option)) {
            return Core_Data_Errors::getInstance()->setError('Empty param [current_option]');
        }

        $insts = new self();
        $insts
            ->withPageId($pageid)
            ->withVisitorIP($ip)
            ->withCurrentOption($current_option)
            ->onlyCount()
            ->getList($statData);

        if ($statData === '0') {
            return $insts
                ->setEntered(['pageid' => $pageid, 'current_option' => $current_option, 'visitor_ip' => $ip])
                ->set();
        }

        return true;
    }

    /**
     * Return array with data of count view each variant at total views
     *
     * @param [string|int] $pageid
     * @param [array] $tests
     * @return array
     */
    public static function getViews($pageid, $tests)
    {
        $tests = array_map(function ($test) use ($pageid) {
            return sprintf("(SELECT COUNT(id) FROM testab_pages_view WHERE pageid = $pageid AND current_option = '$test') as '$test'");
        }, $tests);

        $result = null;

        try {
            Core_Sql::setConnectToServer('lpb.tracker');
            $total  = intval(Core_Sql::getCell("SELECT COUNT(id) FROM testab_pages_view WHERE pageid = $pageid"));
            $result = Core_Sql::getRecord("SELECT " . join(",", $tests));

            Core_Sql::renewalConnectFromCashe();
        } catch (Exception $e) {
            Core_Sql::renewalConnectFromCashe();
        }

        if (!empty($result)) {
            $result = array_map(function ($count) use ($total) {
                return $total > 0 ? (intval($count) * 100 / $total) : 0;
            }, $result);
        }

        return $result;
    }
}
