<?php
use services\GuessBallService;
/**
 * 命令行运行。
 * -- 1、通过在此文件可以加载所有的类库以及model。
 * -- 2、使用：直接在当前文件目录下执行命令：php cli_run.php
 *
 * @author winerQin
 *         @date 2016-07-10
 */

$environ = 'dev';

// 微秒。
define('MICROTIME', microtime());

// -- 取当前目录名称 --
$pwd = trim(__DIR__, DIRECTORY_SEPARATOR);
$arr_pwd = explode(DIRECTORY_SEPARATOR, $pwd);
$app_name = array_pop($arr_pwd);
define('APP_NAME', $app_name);
define("APP_PATH", realpath(dirname(__FILE__) . '/../'));
$app = new \Yaf\Application(APP_PATH . "/conf/application.ini", $environ);

// 注册配置到全局环境。
$config = \Yaf\Application::app()->getConfig();
\Yaf\Registry::set("config", $config);
date_default_timezone_set($config->get('timezone'));

// 调用要执行的程序。
$app->execute('cli_run');

/**
 * 业务区。
 */
function cli_run() {
    //GuessBallService::systemOpenReward();
}