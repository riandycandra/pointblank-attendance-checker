<?php

include_once "vendor/autoload.php";

$list = explode(PHP_EOL, file_get_contents('list.txt'));
ob_start();

foreach ($list as $account) {
	$exp = explode(":", $account);
	$username = $exp[0];
	$password = $exp[1];
	
	$pb = new PointBlank($username, $password);
	if ($pb->login()) {
		if ($pb->getAttendance()) {
			print_message("{$username}\t\t => today attended", 0);
		} else {
			print_message("{$username}\t\t => today IS NOT attended", 2);
		}
	} else {
		print_message("{$username}\t\t => FAILED LOGIN", 1);
	}
	ob_flush();
	flush();
	unset($pb);
}
ob_end_flush();
