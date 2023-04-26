<?php
chdir( dirname(__FILE__) );
chdir( '../../' );
set_time_limit(0);
ignore_user_abort(true);
require_once 'inc_config.php'; // set defined params - depercated!!!
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
Project_Wpress_Connector_Upgrade::getInstance()->runAsService()->upgradeBlogs();
?>