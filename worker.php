<?php
require_once("utils.php");

$config = require_once("config.php");
$db     = get_db_connection($config);
$db->exec("INSERT INTO users(`nickname`) VALUE ('yeyushengfan') ON DUPLICATE KEY UPDATE checked=0");
$db = null;

$file = __DIR__ . "/spider.php";
$cmd  = "php $file";

exec($cmd);