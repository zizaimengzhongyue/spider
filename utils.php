<?php
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ALL);
set_error_handler("error_handler");

function error_handler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) {
		return;
	}

	switch ($errno) {
		case E_ERROR:
			$message = "On line {$errline} in file {$errfile}: $errstr";
			$level   = "Error";
			break;
		case E_WARNING:
			$message = "On line {$errline} in file {$errfile}: $errstr";
			$level   = "Warning";
			break;
		case E_NOTICE:
			$message = "On line {$errline} in file {$errfile}: $errstr";
			$level   = "Notice";
			break;
		case E_DEPRECATED:
			$message = "On line {$errline} in file {$errfile}: $errstr";
			$level   = "Deprecated";
			break;
		default:
			$message = "Error on line {$errline} in file {$errfile}: $errstr";
			$level   = "Undefined";
	}

	logx($message, $level);
	exit;
}

function logx($message, $level = "Error")
{
	$dir = __DIR__ . "/log/";
	if (!is_dir($dir)) {
		mkdir($dir);
		chmod($dir, "0777");
	}
	$log_name = $dir . "/" . date("Y-m-d", time());
	$time     = date("Y-m-d H:i:s", time());

	if (is_array($message)) {
		$message = json_encode($message, JSON_UNESCAPED_UNICODE);
	}
	$message = "[" . $time . "]" . ": " . $level . " " . $message . "\r\n";

	$handle = fopen($log_name, "a+");
	fwrite($handle, $message);
	fclose($handle);
}

function get_db_connection($db_config)
{
	$dsn = "mysql:dbname={$db_config['name']};host={$db_config['host']}";
	$db  = new PDO($dsn, $db_config['username'], $db_config['password']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$db->exec("SET names utf8");
	return $db;
}