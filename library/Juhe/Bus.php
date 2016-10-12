<?php
/**
 * 长途汽车信息。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class Bus extends Base {

    /**
     * 汽车站信息查询。
     * @param string $station 城市名称，如:北京
     * @return string
     */
    public static function query($station) {
        $time = $_SERVER['REQUEST_TIME'];
        $url = "http://op.juhe.cn/onebox/bus/query";
        $params = [
            'station' => $station,
            'key'     => 'eb93607b12cad2889fe50dbcf650b1a0',
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result'];
            } else {
                // echo $result['error_code'].":".$result['reason'];
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }

    /**
     * 汽车站到站查询。
     * @param string $from 	出发城市，如：上海
     * @param string $to 到达城市，如:苏州
     * @return string
     */
    public static function queryAb($from, $to) {
        $time = $_SERVER['REQUEST_TIME'];
        $url = "http://op.juhe.cn/onebox/bus/query_ab";
        $params = [
            'from' => $from,
            'to'   => $to,
            'key'  => 'eb93607b12cad2889fe50dbcf650b1a0',
        ];
        $paramstring = http_build_query($params);
        $content = self::juhecurl($url, $paramstring);
        $result  = json_decode($content, true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['result'];
            } else {
                // echo $result['error_code'].":".$result['reason'];
                YCore::exception(-1, '服务器繁忙,请稍候重试');
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }
}