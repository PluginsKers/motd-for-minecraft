<?php

namespace Kers;

class Utils
{
	public function getRealIp()
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

	public function hasEmpty(...$a)
	{
		foreach ($a as $key => $val) {
			if (empty($val) || $val == null || $val == []) return true;
		}
		return false;
	}

	public function getLocation($ip = false)
	{
		$ip = !$ip ? $this->getRealIp() : $ip;
		$s = file_get_contents("http://whois.pconline.com.cn/ip.jsp?ip={$ip}", true);
		$encode = mb_detect_encoding($s, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
		$s = mb_convert_encoding($s, 'UTF-8', $encode);
		$s = str_replace(PHP_EOL, '', $s);
		$s = str_replace("\r", '', $s);
		return $s;
	}
}
