<?php
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
error_reporting(0);

/**
 * Get real ip from $_SERVER
 * @return String
 * 
 */
function getRealIp()
{
	$ip = false;
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip) {
			array_unshift($ips, $ip);
			$ip = FALSE;
		}
		for ($i = 0; $i < count($ips); $i++) {
			if (!preg_match("^(10|172\.16|192\.168)\.", $ips[$i])) {
				$ip = $ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * Check Empty or Isset
 * @param mixed ...$a
 * @return bool
 * 
 */
function hasEmpty(...$a)
{
	foreach ($a as $key => $val) {
		if (empty($val) || $val == null || $val == []) return true;
	}
	return false;
}

/**
 * Get Address By IP
 * @param string $ip
 * @param bealoon
 * @return String
 * 
 */
function getLocation($ip = false)
{
	$ip = !$ip ? getRealIp() : $ip;
	$s = file_get_contents("http://whois.pconline.com.cn/ip.jsp?ip={$ip}", true);
	$encode = mb_detect_encoding($s, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
	$s = mb_convert_encoding($s, 'UTF-8', $encode);
	$s = str_replace(PHP_EOL, '', $s);
	$s = str_replace("\r", '', $s);
	return $s;
}

/**
 * 
 * Motd for PC(Java) Minecraft
 * 
 */

include_once './lib/MinecraftServerStatus.php'; //include the class
$status = new MinecraftServerStatus(); // call the class

$response = $status->getStatus('localhost'); // call the function

if ($response) {
	$real = gethostbyname($ip);
	$array = [
		'code' => 200,
		'status' => 'online',
		'ip' => $response['hostname'],
		'real' => $real,
		'location' => getLocation($real),
		'port' => $port,
		'motd' => $response['motd'],
		'version' => $response['version'],
		'online' => $response['players'],
		'max' => $response['maxplayers'],
		'delay' => $response['ping']
	];
} else {
	if (!hasEmpty($_REQUEST['ip'], $_REQUEST['port'])) {
		$ip = $_REQUEST['ip'];
		$port = $_REQUEST['port'];
		$t1 = microtime(true);
		if ($handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr, 2)) {
			stream_set_timeout($handle, 2);
			fwrite($handle, hex2bin('0100000000240D12D300FFFF00FEFEFEFEFDFDFDFD12345678') . "\n");
			$result = strstr(fread($handle, 1024), "MCPE");
			fclose($handle);
			$data = explode(";", $result);
			$data['1'] = preg_replace("/§[a-z A-Z 0-9]{1}/s", '', $data['1']);
			if (!hasEmpty($data, $data['1'])) {
				$t2 = microtime(true);
				$real = gethostbyname($ip);
				$array = [
					'code' => 200,
					'status' => 'online',
					'ip' => $ip,
					'real' => $real,
					'location' => getLocation($real),
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
				$array = [
					'code' => 203,
					'status' => 'offline'
				];
			}
		} else {
			$array = [
				'code' => 202,
				'status' => 'offline'
			];
		}
	} else {
		$array = [
			'code' => 201,
			'status' => 'offline'
		];
	}
}

exit(json_encode($array));
