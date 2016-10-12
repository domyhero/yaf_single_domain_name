<?php
/**
 * 问答机器人。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class Robot extends Base {

    /**
     * 向机器人发起问答。
     * @param string $info 发送给机器人的内容。
     * @param string $loc 地点，如北京中关村。
     * @param string $userid 1~32位，此userid针对您自己的每一个用户，用于上下文的关联。
     * @return string
     */
    public static function ask($info, $loc = '', $userid = '') {
        if (mb_strlen($info, 'UTF-8') > 30) {
            YCore::exception(-1, '您的问题太长了');
        }
        $url = "http://op.juhe.cn/robot/index";
        $params = [
            'info'   => $info,
            'userid' => $userid,
            'loc'    => $loc,
            'key'    => 'fb013675d34b7be1358a86a8c69f9ece'
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
     * 机器人回答内容的类型含义。
     * @return string
     */
    public static function code() {
        $url = "http://op.juhe.cn/robot/code";
        $params = [
            'dtype' => 'json',
            'key'   => 'fb013675d34b7be1358a86a8c69f9ece'
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