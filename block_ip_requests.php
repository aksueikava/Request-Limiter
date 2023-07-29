<?php
$blocklistFile = 'blocklist.txt';
$maxRequests = 100;

$ip = $_SERVER['REMOTE_ADDR'];

$blocklist = file($blocklistFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if (in_array($ip, $blocklist)) {
    die('Ваш IP-адрес заблокирован.');
}

$requests = 0;
$logFile = 'log.txt';
if (file_exists($logFile)) {
    $log = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($log as $line) {
        if (strpos($line, $ip) !== false) {
            $requests++;
        }
    }
}

if ($requests >= $maxRequests) {
    file_put_contents($blocklistFile, $ip . PHP_EOL, FILE_APPEND | LOCK_EX);
    
    die('Ваш IP-адрес заблокирован из-за превышения лимита запросов.');
}

file_put_contents($logFile, date('[Y-m-d H:i:s]') . ' ' . $ip . PHP_EOL, FILE_APPEND | LOCK_EX);

?>
