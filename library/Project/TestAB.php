<?php

class Project_TestAB extends Core_Data_Storage
{
    const DEFAULT_OPTION = '#';

    protected $_table  = 'testab_pages';
    protected $_fields = ['id', 'pageid', 'access_options', 'current_option', 'days', 'visitors', 'auto_optimize', 'weight', 'added'];

    private $_withPageId = false;

    /**
     * Create table on database
     *
     * @return void
     */
    public static function install()
    {
        Core_Sql::setExec('DROP TABLE IF EXISTS `testab_pages`');

        Core_Sql::setExec("CREATE TABLE `testab_pages` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `pageid` INT(11) NULL DEFAULT NULL,
            `access_options` TEXT NULL DEFAULT NULL,
            `current_option` VARCHAR(5) NULL DEFAULT NULL,
            `days` INT(11) NULL DEFAULT NULL,
            `visitors` INT(11) NULL DEFAULT NULL,
            `auto_optimize` TINYINT(1) NULL DEFAULT 0,
            `added` INT(11) UNSIGNED NOT NULL DEFAULT '0',
            UNIQUE INDEX `id` (`id`)
        );");
    }

    public function beforeSet()
    {
        $this->_data->setFilter('clear');

        if (!empty($this->_data->filtered['access_options'])) {
            $this->_data->setElement('access_options', json_encode($this->_data->filtered['access_options']));
        }

        $this->_data->setElement('weight', json_encode($this->_data->filtered['weight']));

        return true;
    }

    /**
     * Setter WithPageId
     *
     * @param [int] $pageid
     * @return $this
     */
    public function withPageId($pageid)
    {
        $this->_withPageId = $pageid;
        return $this;
    }

    /**
     * Builder for query
     *
     * @return void
     */
    protected function assemblyQuery()
    {
        parent::assemblyQuery();

        if ($this->_withPageId !== false) {
            $this->_crawler->set_where('d.pageid = ' . Core_Sql::fixInjection($this->_withPageId));
        }
    }

    protected function init()
    {
        $this->_withPageId = false;
        parent::init();
    }

    public function getList(&$mixRes)
    {
        parent::getList($mixRes);

        if (array_key_exists('0', $mixRes)) {
            foreach ($mixRes as &$item) {
                $item['access_options'] = json_decode($item['access_options'], true);
                $item['weight']         = json_decode($item['weight'], true);
            }
        } else {
            $mixRes['access_options'] = json_decode($mixRes['access_options'], true);
            $mixRes['weight']         = json_decode($mixRes['weight'], true);
        }

        return $this;
    }

    /**
     * Return option name for view
     *
     * @param [int] $pageid
     * @return string
     */
    public static function getCurrentOption($pageid)
    {
        if (empty($pageid)) {
            return self::DEFAULT_OPTION;
        }

        $inst = new self();

        $inst
            ->withPageId($pageid)
            ->onlyOne()
            ->getList($testData);

        $current_option = null;
        $key            = array_search($testData['current_option'], $testData['access_options']);

        if (empty($testData['weight'])) {
            if (array_key_exists($key + 1, $testData['access_options'])) {
                $key += 1;
            } else {
                reset($testData['access_options']);
                $key = key($testData['access_options']);
            }
        } else {
            // Фильтрация вариантов с weight > 0
            $weights = array_filter($testData['weight'], function ($variant) {
                return !empty($variant) || $variant > '0';
            });

            $views = Project_TestAB_View::getViews($pageid, array_keys($weights));

            // Если доступен только один вариант, фильтрация не требуется
            if (count($weights) > 1) {
                $weights = array_filter($weights, function ($weight, $variant) use ($views) {
                    return $views[$variant] < $weight;
                }, ARRAY_FILTER_USE_BOTH);
    
                asort($weights, SORT_ASC | SORT_NUMERIC);
            }

            // Проверка количества вариантов, возможен вариант когда показ текущих перевалит количество
            // указанное в weight
            if (count($weights) > 0) {
                $key = array_keys($weights);
                $key = array_search(array_shift($key), $testData['access_options']);
            }
        }

        $current_option             = $testData['current_option'];
        $testData['current_option'] = $testData['access_options'][$key];
        $flg_limit                  = false;

        // Limit of days
        if (!empty($testData['days'])) {
            if ($testData['added'] + 24 * 60 * 60 * intval($testData['days']) < time()) {
                $flg_limit = true;
            }
        }

        // Limit of visitors
        if (!empty($testData['visitors'])) {
            $view = new Project_TestAB_View();
            $view
                ->withPageId($pageid)
                ->onlyCount()
                ->getList($visitorCount);

            if (intval($visitorCount) > intval($testData['visitors'])) {
                $flg_limit = true;
            }
        }

        // Auto Optimize
        if ($flg_limit && !empty($testData['auto_optimize']) && $testData['auto_optimize'] === '1') {
            return self::maxConversion($pageid);
        } elseif ($flg_limit) {
            return self::DEFAULT_OPTION;
        }

        $inst
            ->setEntered($testData)
            ->set();

        return $current_option;
    }

    /**
     * Return option name with max conversion
     *
     * @param [int] $pageid
     * @return string
     */
    public static function maxConversion($pageid)
    {
        $view = new Project_TestAB_View();
        $view
            ->withPageId($pageid)
            ->keyRecordForm()
            ->toSelectField(['d.current_option', 'count(*) as count'])
            ->withGroup('d.current_option')
            ->getList($viewsData);

        $goal = new Project_TestAB_Goal();
        $goal
            ->withPageId($pageid)
            ->keyRecordForm()
            ->toSelectField(['d.option', 'count(*) as count'])
            ->withGroup('d.option')
            ->getList($goalsData);

        $conversion = [];
        foreach ($viewsData as $item) {
            $current              = $item['current_option'];
            $conversion[$current] = 0;

            if (isset($goalsData[$current])) {
                $conversion[$current] = intval($goalsData[$current]['count']) / intval($item['count']);
            }
        }

        $max = array_keys($conversion, max($conversion));

        return array_shift($max);
    }

}
