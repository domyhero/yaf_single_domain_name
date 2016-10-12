<?php
/**
 * 身份证。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;

class IDCard extends Base {

    /**
     * 身份证信息查询。
     * @param string $cardno 身份证号码。
     * @return array
     */
    public static function getIDCardInfo($cardno) {
        $url = "http://apis.juhe.cn/idcard/index";
        $params = [
            'dtype'  => 'json',                                 // 需要查询的IP地址或域名
            'cardno' => $cardno,                                // 需要转换成的类型。0：简体 1：繁体  2：火星文
            'key'    => '855366926196ed6e4549fac00b3abd42',     // 应用APPKEY(应用详细页查询)
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
     * 身份证信息泄漏查询。
     * @param string $cardno 身份证号码。
     * @return array
     */
    public static function isLeak($cardno) {
        $url = "http://apis.juhe.cn/idcard/leak";
        $params = [
            'dtype'  => 'json',                                 // 需要查询的IP地址或域名
            'cardno' => $cardno,                                // 需要转换成的类型。0：简体 1：繁体  2：火星文
            'key'    => '855366926196ed6e4549fac00b3abd42',     // 应用APPKEY(应用详细页查询)
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
     * 身份证信息泄漏查询。
     * @param string $cardno 身份证号码。
     * @return array
     */
    public static function isLoss($cardno) {
        $url = "http://apis.juhe.cn/idcard/loss";
        $params = [
            'dtype'  => 'json',                                 // 需要查询的IP地址或域名
            'cardno' => $cardno,                                // 需要转换成的类型。0：简体 1：繁体  2：火星文
            'key'    => '855366926196ed6e4549fac00b3abd42',     // 应用APPKEY(应用详细页查询)
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