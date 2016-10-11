<?php
/**
 * 历史上的今天。
 * @author winerQin
 * @date 2016-10-11
 */

namespace Juhe;

use common\YCore;
class TodayHistory extends Base {

    /**
     * 查看历史事件列表。
     * @param string $date 日期。月/日。
     * @return string
     */
    public static function queryEvent($date) {
        $url = "http://v.juhe.cn/todayOnhistory/queryEvent.php";
        $params = [
            'date' => $date,
            'key'  => '4f94d3d13359135a7c863b89ed445def',
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
     * 查看历史事件详情。
     * @param string $event_id 历史事情ID。
     * @return string
     */
    public static function queryDetail($event_id) {
        $url = "http://v.juhe.cn/todayOnhistory/queryDetail.php";
        $params = [
            'e_id' => $event_id,
            'key'  => '4f94d3d13359135a7c863b89ed445def',
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