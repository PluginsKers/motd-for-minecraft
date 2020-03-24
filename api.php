<?php
require_once('common.php');
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
error_reporting(0);
$t1 = microtime(true);
if (!empty($_REQUEST['ip']) && !empty($_REQUEST['port'])) {
    if ($handle = stream_socket_client("udp://{$_REQUEST['ip']}:{$_REQUEST['port']}", $errno, $errstr, 2)) {
        fwrite($handle, hex2bin('0100000000240D12D300FFFF00FEFEFEFEFDFDFDFD12345678') . "\n");
        $result = strstr(fread($handle, 1024), "MCPE");
        fclose($handle);
        $data = explode(";", $result);
        $data['1'] = preg_replace("/§[a-z A-Z 0-9]{1}/s", '', $data['1']);
        if (!empty($data['1'])) {
        	$t2 = microtime(true);
            $array = [
                'status' => 'online',
                'ip' => $_REQUEST['ip'],
                'port' => $_REQUEST['port'],
                'motd' => $data['1'],
                'agreement' => $data['2'],
                'version' => $data['3'],
                'online' => $data['4'],
                'max' => $data['5'],
                'gamemode' => $data['8'],
                'delay' => round($t2 - $t1, 3) * 1000
            ];
            $host = gethostbyname($_REQUEST['ip']);
        	if (!mysqli_num_rows($DB->query("SELECT * FROM `list` WHERE `host` = '{$host}' and `port` = '{$_REQUEST['port']}'"))) {
        		$DB->query("INSERT INTO `list` (`id`, `host`, `ip`, `port`, `date`) VALUES (NULL, '{$host}', '{$_REQUEST['ip']}', '{$_REQUEST['port']}', CURRENT_TIMESTAMP)");
        	} else {
        		if (!mysqli_num_rows($DB->query("SELECT * FROM `list` WHERE `host` = '{$host}' and `ip` = '{$_REQUEST['ip']}' and `port` = '{$_REQUEST['port']}'"))) {
	        		$DB->query("UPDATE `list` SET `ip`='{$_REQUEST['ip']}' WHERE `host`='{$host}' and `port` = '{$_REQUEST['port']}' LIMIT 1");
	        	} else {
					
	        	}
        	}
            //$DB->query("INSERT INTO `query` (`id`, `ip`, `port`, `date`) VALUES (NULL, '{$_REQUEST['ip']}', '{$_REQUEST['port']}', CURRENT_TIMESTAMP)");
        } else {
            $array = [
                'status' => 'offline'
            ];
        }
    } else {
        $array = [
            'status' => 'offline'
        ];
    }
    exit(json_encode($array));
} else {
	exit(json_encode(['status' => 'offline']));
}