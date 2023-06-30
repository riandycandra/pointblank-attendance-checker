<?php

include_once "vendor/autoload.php";

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class PointBlank
{

	public $username;
	public $password;
	public $client;

	public function __construct(string $username, string $password)
	{
		$this->client = new Client(
			[
				'defaults' => [
					'verify' => false
				],
				'cookies' => true
			]
		);

		$this->username = $username;
		$this->password = $password;
	}

	public function login()
	{

		$response = $this->client->request('POST', 'https://www.pointblank.id/login/process', [
			'form_params' => [
				"loginFail" =>	"0",
				"userid" =>	$this->username,
				"password" =>	$this->password,
			]
		]);

		$dom = new Dom;
		$dom->loadStr($response->getBody()->getContents());

		try {

			$isSuccess = $dom->find(".my_account_btn");

			return (count($isSuccess) >= 1);
			
		} catch (Exception $e) {

			return false;

		}

	}

	public function getAttendance()
	{
		$response = $this->client->request('GET', 'https://www.pointblank.id/game/attendance');

		$dom = new Dom;
		$dom->loadStr($response->getBody()->getContents());

		try {

			$isAttend = $dom->find(".today")[0]->find('.txt')[0]->getTag()->getAttribute('class')->getValue();

			return $isAttend == 'txt attend';

		} catch	(Exception $e) {

			return false;

		}
	}

}

$list = explode(PHP_EOL, file_get_contents('list.txt'));
ob_start();

// Create a new instance of the ConsoleOutput
$output = new ConsoleOutput();

// Create a new style for the color
$errorStyle = new OutputFormatterStyle('red', 'black', ['bold', 'blink']);
$successStyle = new OutputFormatterStyle('green', 'black', ['bold', 'blink']);

// Apply the style to the output
$output->getFormatter()->setStyle('error', $errorStyle);
$output->getFormatter()->setStyle('success', $successStyle);

foreach ($list as $account) {
	$exp = explode(":", $account);
	$username = $exp[0];
	$password = $exp[1];
	
	$pb = new PointBlank($username, $password);
	if ($pb->login()) {
		if ($pb->getAttendance()) {
			$output->writeLn("<success>{$username}\t\t => today attended</success>");
		} else {
			$output->writeLn("<error>{$username}\t\t => today IS NOT attended</error>");
		}
	} else {
		$output->writeLn("<error>{$username}\t\t => FAILED LOGIN</error>");
	}
	ob_flush();
	flush();
	unset($pb);
}
ob_end_flush();
