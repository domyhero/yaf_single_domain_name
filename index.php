<?php
/**
 * 前台入口文件。
 * -- 1、入口文件会判断当前域名去加载配置文件。
 * @author winerQin
 * @date 2016-09-07
 */

// 微秒。
define('MICROTIME', microtime());

$evn_name = 'dev';
if ($_SERVER['HTTP_HOST'] == 'phper.applinzi.com') {
    $evn_name = 'product';
}

// -- 取当前目录名称 --
$pwd = trim(__DIR__, DIRECTORY_SEPARATOR);
$arr_pwd = explode(DIRECTORY_SEPARATOR, $pwd);

define("APP_PATH", realpath(dirname(__FILE__)));

$app = new \Yaf\Application(APP_PATH . "/apps/conf/application.ini", $evn_name);
$app->bootstrap()->run();