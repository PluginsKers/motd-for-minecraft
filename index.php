<?php

namespace Kers;

use Kers\Utils;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;

require __DIR__ . '/utils.class.php';

require __DIR__ . '/src/MinecraftQuery.php';
require __DIR__ . '/src/MinecraftQueryException.php';

$Utils = new Utils();

header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
error_reporting(0);

$array = [
	'code' => 201,
	'status' => 'offine',
	'ip' => 'N/A',
	'real' => 'N/A',
	'location' => 'N/A',
	'port' => 'N/A',
	'motd' => 'N/A',
	'agreement' => 'N/A',
	'version' => 'N/A',
	'online' => 0,
	'max' => 0,
	'gamemode' => 'N/A',
	'delay' => 'N/A'
];



if (!$Utils->hasEmpty($_REQUEST['ip'], $_REQUEST['port'])) {
	$ip = $_REQUEST['ip'];
	$port = $_REQUEST['port'];
	if (!isset($_REQUEST['java'])) {


		// Edit this ->
		define('MQ_SERVER_ADDR', $_REQUEST['ip']);
		define('MQ_SERVER_PORT', $_REQUEST['port']);
		define('MQ_TIMEOUT', 1);
		// Edit this <-

		// Display everything in browser, because some people can't look in logs for errors
		Error_Reporting(E_ALL | E_STRICT);
		Ini_Set('display_errors', true);

		$Timer = MicroTime(true);

		$Query = new MinecraftQuery();

		try {
			$Query->Connect(MQ_SERVER_ADDR, MQ_SERVER_PORT, MQ_TIMEOUT);
		} catch (MinecraftQueryException $e) {
			$array['code'] = 201;
			$Exception = $e;
		}

		$Timer = Number_Format(MicroTime(true) - $Timer, 4, '.', '');
	} else {
		$t1 = microtime(true);
		if ($handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr, 2)) {
			stream_set_timeout($handle, 2);
			fwrite($handle, hex2bin('0100000000240D12D300FFFF00FEFEFEFEFDFDFDFD12345678') . "\n");
			$result = strstr(fread($handle, 1024), "MCPE");
			fclose($handle);
			$data = explode(";", $result);
			$data['1'] = preg_replace("/§[a-z A-Z 0-9]{1}/s", '', $data['1']);
			if (!$Utils->hasEmpty($data, $data['1'])) {
				$t2 = microtime(true);
				$real = gethostbyname($ip);
				$array = [
					'code' => 200,
					'status' => 'online',
					'ip' => $ip,
					'real' => $real,
					'location' => $Utils->getLocation($real),
					'port' => $port,
					'motd' => $data['1'],
					'agreement' => $data['2'],
					'version' => $data['3'],
					'online' => $data['4'],
					'max' => $data['5'],
					'gamemode' => $data['8'],
					'delay' => round($t2 - $t1, 3) * 1000
				];
			} else {
				$array['code'] = 203;
			}
		} else {
			$array['code'] = 202;
		}
	}
} else {
	$array['code'] = 201;
}

exit(json_encode($array));
