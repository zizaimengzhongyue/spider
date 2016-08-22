<?php
require_once("utils.php");

$config = require_once("config.php");

$db = get_db_connection($config);

while(true) {

	$res = $db->query("SELECT nickname FROM users WHERE checked=0");

	if ($res->rowCount() == 0) break;

	while($row = $res->fetch()) {

		$nickname = $row["nickname"];

		//todo 获取用户信息
		$url = "https://segmentfault.com/u/{$nickname}";

		$content = @file_get_contents($url);
		if (!$content) {
			$db->exec("UPDATE users SET checked=1 WHERE nickname='{$nickname}'");
			continue;
		}

		$data             = [];
		$data["nickname"] = $nickname;

		preg_match("#<a class=\"profile__rank-btn\" href=\"/u/{$data["nickname"]}/rank\"><span class=\"h4\">([0-9]+)#", $content, $out);
		$data["rank"] = isset($out[1]) ? $out[1] : 0;

		preg_match("#<span class=\"profile__city\">([\\x{4e00}-\\x{9fa5}]{1,15})#u", $content, $out);
		$data["city"] = isset($out[1]) ? $out[1] : "";

		preg_match("#<span class=\"profile__school\">([\\x{4e00}-\\x{9fa5}]{1,15})#u", $content, $out);
		$data["school"] = isset($out[1]) ? $out[1] : "";

		$db->exec("UPDATE users SET rank={$data["rank"]}, city='{$data["city"]}', school='{$data["school"]}', checked=1 WHERE nickname='{$nickname}'");

		//todo 获取用户粉丝
		$url = "https://segmentfault.com/u/{$nickname}/users/followed";

		$content = @file_get_contents($url);
		if (!$content) continue;
		preg_match_all("#([a-zA-Z0-9]*)\\\">[\\x{4e00}-\\x{9fa5}_a-zA-Z0-9]*</a><div class=\"profile-following--followed\">#u", $content, $out);
		foreach ($out[1] as $v) {
			$db->exec("INSERT INTO users(nickname,checked) VALUE('$v',0) ON DUPLICATE KEY UPDATE nickname=VALUES(nickname)");
		}

		//todo 获取用户关注的人
		$url = "https://segmentfault.com/u/{$nickname}/users/following";

		$content = @file_get_contents($url);
		preg_match_all("#([a-zA-Z0-9]*)\\\">[\\x{4e00}-\\x{9fa5}_a-zA-Z0-9]*</a><div class=\"profile-following--followed\">#u", $content, $out);
		foreach ($out[1] as $v) {
			$db->exec("INSERT INTO users(nickname,checked) VALUE('$v',0) ON DUPLICATE KEY UPDATE nickname=VALUES(nickname)");
		}
	}
}