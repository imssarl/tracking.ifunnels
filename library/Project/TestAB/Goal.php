<?php

class Project_TestAB_Goal extends Core_Data_Storage
{

    const GOAL_LEAD = 1, GOAL_REGISTRATION = 2, GOAL_SALE = 3;

    protected $_table  = 'testab_pages_goal';
    protected $_fields = ['id', 'pageid', 'goal_type', 'option', 'visitor_ip', 'added'];

    private $_withPageId    = false;
    private $_withOption    = false;
    private $_withVisitorIP = false;
    private $_withGoalType  = false;
    private $_toSelectField = false;

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
            Core_Sql::setExec('DROP TABLE IF EXISTS `testab_pages_goal`');

            Core_Sql::setExec("CREATE TABLE `testab_pages_goal` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `pageid` INT(11) NULL DEFAULT NULL,
            `goal_type` TINYINT(2) NULL DEFAULT NULL,
            `option` VARCHAR(5) NULL DEFAULT NULL,
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

    public function withOption($option)
    {
        $this->_withOption = $option;
        return $this;
    }

    public function withVisitorIP($ip)
    {
        $this->_withVisitorIP = $ip;
        return $this;
    }

    public function withGoalType($types)
    {
        $this->_withGoalType = $types;
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

        if ($this->_withOption) {
            $this->_crawler->set_where('d.option = ' . Core_Sql::fixInjection($this->_withOption));
        }

        if ($this->_withVisitorIP) {
            $this->_crawler->set_where('d.visitor_ip = ' . Core_Sql::fixInjection($this->_withVisitorIP));
        }

        if ($this->_withGoalType) {
            $this->_crawler->set_where('d.goal_type IN (' . Core_Sql::fixInjection($this->_withGoalType) . ')');
        }

        if ($this->_toSelectField) {
            $this->_crawler->clean_select();
            $this->_crawler->set_select(join(', ', $this->_toSelectField));
        }
    }

    protected function init()
    {
        $this->_withPageId    = false;
        $this->_withOption    = false;
        $this->_withVisitorIP = false;
        $this->_withGoalType  = false;
        $this->_toSelectField = false;
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
    public static function addStat()
    {
        $ip = null;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $input = json_decode(file_get_contents('php://input'));

        if ($input === false) {
            return false;
        }

        if (empty($input->pageid) || empty($input->goals)) {
            return false;
        }

        $goals = explode(',', $input->goals);

        $instsView = new Project_TestAB_View();
        $instsView
            ->withPageId($input->pageid)
            ->withVisitorIP($ip)
            ->onlyOne()
            ->getList($viewData);

        $pageid = $input->pageid;
        $option = $viewData['current_option'];

        $insts = new self();

        $goals = array_map(function ($goal) use ($pageid, $ip, $option) {
            return ['pageid' => $pageid, 'visitor_ip' => $ip, 'option' => $option, 'goal_type' => $goal];
        }, $goals);

        $insts
            ->withPageId($pageid)
            ->withVisitorIP($ip)
            ->withGoalType(explode(',', $input->goals))
            ->withOption($option)
            ->getList($goalsData);

        $goals = array_udiff($goals, $goalsData, function ($a, $b) {
            if (strcmp($a['pageid'], $b['pageid']) !== 0 && strcmp($a['visitor_ip'], $b['visitor_ip']) !== 0 && strcmp($a['option'], $b['option']) !== 0 && strcmp($a['goal_type'], $b['goal_type']) !== 0) {
                return -1;
            }

            return 0;
        });

        if (!empty($goals)) {
            $insts->setEntered($goals)->setMass();
        }

        return true;
    }
}
