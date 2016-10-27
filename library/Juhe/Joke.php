<?php
/**
 * 笑话。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class Joke extends Base {

    /**
     * 获取笑话列表。
     * @param int $page 当前页码。
     * @param int $pagesize 每页显示条数。
     * @param string $sort 类型，desc:指定时间之前发布的，asc:指定时间之后发布的
     * @param int $time 时间戳（10位），如：1418816972
     * @return string
     */
    public static function getList($page = 1, $pagesize = 20, $sort = 'desc', $time = 0) {
        $time = $_SERVER['REQUEST_TIME'];
        $url = "http://japi.juhe.cn/joke/content/list.from";
        $params = [
            'pagesize' => $pagesize,
            'sort'     => $sort,
            'page'     => $page,
            'time'     => $time,
            'key'      => 'c04b2d908aee2fa3d403c046f923d105'
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result'];
            } else {
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }

    /**
     * 获取趣图列表。
     * @param int $page 当前页码。
     * @param int $pagesize 每页显示条数。
     * @param string $sort 类型，desc:指定时间之前发布的，asc:指定时间之后发布的
     * @param int $time 时间戳（10位），如：1418816972
     * @return string
     */
    public static function getImgList($page = 1, $pagesize = 20, $sort = 'desc', $time = 0) {
        $time = $_SERVER['REQUEST_TIME'];
        $url  = "http://japi.juhe.cn/joke/img/list.from";
        $params = [
            'pagesize' => $pagesize,
            'sort'     => $sort,
            'page'     => $page,
            'time'     => $time,
            'key'      => 'c04b2d908aee2fa3d403c046f923d105'
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result'];
            } else {
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }

    /**
     * 随机获取趣图列表。
     * @param string $type 类型(pic:趣图,不传默认为笑话)
     * @return string
     */
    public static function getRandomJoke($type) {
        $url  = "http://v.juhe.cn/joke/randJoke.php";
        $params = [
            'type' => $type,
            'key'  => 'c04b2d908aee2fa3d403c046f923d105'
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result'];
            } else {
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }
}