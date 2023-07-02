<?php 
include_once "vendor/autoload.php";

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

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

	public function getProfile(): string
	{
		$response = $this->client->request('GET', 'https://www.pointblank.id/game/profile');

		$dom = new Dom;
		$dom->loadStr($response->getBody()->getContents());

		$rank = $dom->find('.level')[0]->getChildren()[0]->text;

		$rank_img = $dom->find('.my_level')[0]->getChildren()[0]->getAttribute('src');
		$rank_img = "https://www.pointblank.id" . $rank_img;

		return "RANK : {$rank}\t{$rank_img}";
	}

}