<?php
$_CONFIG = array(
    "host" => "localhost",
    "port" => "3306",
    "username" => "u",
    "password" => "p",
    "dbname" => "localhost",
);

$DB = new mysqli($_CONFIG['host'], $_CONFIG['username'], $_CONFIG['password'], $_CONFIG['dbname'], $_CONFIG['port']);
if ($DB->connect_error) {
    die("连接失败: " . $DB->connect_error);
}

/**
 * 
 * 安全过滤
 * 违规访问或提交拦截
 * @author PluginsKers
 * 
 */
$getFilter = "\\'|iframe|input|<(.*)[^\f\n\r\t\v]>|(.*(@|\\|`|#|!(.*?[^\f\n\r\t\v])!|\*|&|.*(while|eval|system).*\(.*\)).*)|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$postFilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|iframe|input|<(.*)[^\f\n\r\t\v]>|(.*(@|\\|`|#|!(.*?[^\f\n\r\t\v])!|\*|&|.*(while|eval|system).*\(.*\)).*)|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$cookieFilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
foreach ($_POST as $itemFilter => $stringFilter) {
    $stringFilterFilter = addslashes($stringFilter);
    if ($stringFilterFilter != $stringFilter or preg_match("/" . $postFilter . "/is", $stringFilter) != 0) exit();
}
foreach ($_GET as $itemFilter => $stringFilter) {
    $stringFilterFilter = addslashes($stringFilter);
    if ($stringFilterFilter != $stringFilter or preg_match("/" . $getFilter . "/is", $stringFilter) != 0) exit();
}
foreach ($_COOKIE as $itemFilter => $stringFilter) {
    $stringFilterFilter = addslashes($stringFilter);
    if ($stringFilterFilter != $stringFilter or preg_match("/" . $cookieFilter . "/is", $stringFilter) != 0) exit();
}