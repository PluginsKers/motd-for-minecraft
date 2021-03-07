<?php
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
error_reporting(0);

function getAddr($ip) {
    $s = file_get_contents("http://whois.pconline.com.cn/ip.jsp?ip={$ip}", true);
    $encode = mb_detect_encoding($s, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
    $s = mb_convert_encoding($s, 'UTF-8', $encode);
    $s = str_replace(PHP_EOL, '', $s);
    $s = str_replace("\r", '', $s);
    return $s;
}

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
            $real = gethostbyname($_REQUEST['ip']);
            $array = [
                'status' => 'online',
                'ip' => $_REQUEST['ip'],
                'real' => $real,
                'location' => getAddr($real),
                'port' => $_REQUEST['port'],
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