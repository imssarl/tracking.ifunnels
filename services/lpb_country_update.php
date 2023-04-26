<?php
set_time_limit(0);
ignore_user_abort(true);
error_reporting(E_ALL);
ini_set('display_errors', '1');

chdir( dirname(__FILE__) );
chdir( '../' );
require_once './library/WorkHorse.php'; // starter
WorkHorse::shell();
$_start=microtime(true);


$_ips = Core_Sql::getAssoc('SELECT id, ip FROM lpb_subscribers c WHERE c.country_id=0 LIMIT 0,100');
$_update=0;
foreach( $_ips as $value ){
	if( ip2long($value['ip']) ){
		$_countryId = Core_Sql::getCell('SELECT country_id FROM getip_countries2ip WHERE ip_start <= ' . sprintf("%u\n", ip2long($value['ip'])) . ' AND ' . sprintf("%u\n", ip2long($value['ip'])) . ' <= ip_end');
		Core_Sql::setExec('UPDATE `lpb_subscribers` SET country_id = '.$_countryId.' WHERE id = '.$value['id']);
		$_update++;
		// тут ещё апдейтер
		if( microtime(true)-$_start > 20 ){
			break;
		}
	}else{
		echo '<br/>empty lpb_subscribers id:'.@$value['id'].' ip:'.@$value['ip'];
	}
}


$_start=microtime(true)-$_start;
echo '<br/>Update '.$_update.' values => '.$_start.' sec.';
if( $_update != 0 ){
	header("Refresh:0");
}else{
	$_start=microtime(true);
	$_ips = Core_Sql::getAssoc('SELECT id, ip FROM lpb_conversions WHERE country_id=0 LIMIT 0,100');
	echo '<br/>lpb_conversions count:'.count( $_ips ); 
	$_update=0;
	foreach( $_ips as $value ){
		//echo( '<br/>'.$value['ip'] );
		if( ip2long($value['ip']) ){
			$_countryId = Core_Sql::getCell('SELECT country_id FROM getip_countries2ip WHERE ip_start >= ' . ip2long($value['ip']) . ' AND ' . ip2long($value['ip']) . ' <= ip_end');
			Core_Sql::setExec('UPDATE `lpb_conversions` SET country_id = '.$_countryId.' WHERE id = '.$value['id']);
			$_update++;
			// тут ещё апдейтер
			if( microtime(true)-$_start > 20 ){
				break;
			}
		}else{
			echo '<br/>empty lpb_conversions id:'.@$value['id'].' ip:'.@$value['ip'];
		}
	}
	$_start=microtime(true)-$_start;
	echo '<br/>Update '.$_update.' values => '.$_start.' sec.';
	if( count( $_ips ) > 0 ){
		header("Refresh:0");
	}
}

/*
"
UPDATE `lpb_subscribers` c
LEFT JOIN `getip_countries2ip` d ON
	d.ip_start >= c.ip AND c.ip <= d.ip_end
SET
	c.country_id = d.country_id
WHERE c.id = 1


UPDATE `lpb_conversions` c
LEFT JOIN `getip_countries2ip` d ON
	d.ip_start >= c.ip AND c.ip <= d.ip_end
SET
	c.country_id = d.country_id
WHERE c.id = 1


"*/
?>