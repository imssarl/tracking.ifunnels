<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");
###############################################################
# cPanel Email Account Creator 1.3
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
############################################################### 
# You can pass following parameters in calling URL. They will
# override those specified below.
# user - new email user
# pass - password
# domain - email domain
# quota - email quota, Mb
# Example: cpemail.php?user=newuser&pass=password&quota=50
############################################################### 

// Antispam image
// Show CAPTCHA - true, do not show - false
// In case you set this to true, you may want to update settings in the antispam.php
// Also when using this feature, fonts must exist on your system.
// By default antispam.php is setup to use arial.ttf
// For details see http://www.zubrag.com/scripts/antispam-image-generator.php
$antispam = true; 

// cPanel info
$cpuser = 'imsllc'; // cPanel username
$cppass = 'kalptaru'; // cPanel password
$cpdomain = 'mytestserver1.info'; // cPanel domain or IP
$cpskin = 'x';  // cPanel skin. Mostly x or x2. 
// See following URL to know how to determine your cPanel skin
// http://www.zubrag.com/articles/determine-cpanel-skin.php

// Default email info for new email accounts
// These will only be used if not passed via URL
//$epass = 'hispassword'; // email password
//$edomain = 'mysite.com'; // email domain (usually same as cPanel domain above)
//$equota = 20; // amount of space in megabytes

############################################################### 
# END OF SETTINGS
############################################################### 

function getVar($name, $def = '') {
  if (isset($_REQUEST[$name]))
    return $_REQUEST[$name];
  else 
    return $def;
}

// check if overrides passed
$euser = getVar('', 'Ra');
$epass = getVar('pass', $epass);
$edomain = getVar('domain', $edomain);
$equota = getVar('quota', $equota);

$msg = '';

if (!empty($euser))
while(true) {


  // Create email account
  $f = fopen ("http://$cpuser:$cppass@$cpdomain:2082/frontend/$cpskin/index.html", "r");
  if (!$f) {
    $msg = 'Cannot login. Possible reasons: Either username/ Pass is wrong or "fopen" function not allowed on your server, PHP is running in SAFE mode';
    break;
  }

  $msg = "<h2>Email account {$euser}@{$edomain} created.</h2>";

  // Check result
  while (!feof ($f)) {
    $line = fgets ($f, 1024);
    if (ereg ("cPanel Version", $line, $out)) {
		echo "<pre/>";
	   print_r( $out);

     // $msg = "<h2>Email account {$euser}@{$edomain} already exists.</h2>";
     // break;
    }
	echo $line;
  }

  @fclose($f);

  break;

}
die($msg);
?>
<html>
<head><title>cPanel Email Account Creator</title></head>
<body>
<?php echo '<div style="color:red">'.$msg.'</div>'; ?>
<h1>cPanel Email Account Creator</h1>
<form name="frmEmail" method="post">
<table width="400" border="0">
<tr><td>Username:</td><td><input name="user" size="20" value="<?php echo htmlentities($euser); ?>" /></td></tr>
<tr><td>Password:</td><td><input name="pass" size="20" type="password" /></td></tr>
<?php if ($antispam) { ?>
<tr><td><img src="antispam.php" alt="CAPTCHA" /></td><td><input name="anti_spam_code" size="20" /></td></tr>
<?php } ?>
<tr><td colspan="2" align="center"><hr /><input name="submit" type="submit" value="Create Email Account" /></td></tr>
</table>
</form>
</body>
</html>