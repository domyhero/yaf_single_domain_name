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
     * 身份证实名认证。
     * @param string $realname 姓名。
     * @param string $idcard 身份证号码。
     * @return array
     */
    public static function isRealname($realname, $idcard) {
        $url = "http://op.juhe.cn/idcard/query";
        $params = [
            'realname' => $realname,
            'idcard'   => $idcard,
            'key'      => 'f7380a91a125b1ce5c04b22096d0713b'
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
     * 身份证信息查询。
     * @param string $cardno 身份证号码。
     * @return array
     */
    public static function getIDCardInfo($cardno) {
        $url = "http://apis.juhe.cn/idcard/index";
        $params = [
            'dtype'  => 'json',
            'cardno' => $cardno,
            'key'    => '855366926196ed6e4549fac00b3abd42'
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
     * 身份证信息泄漏查询。
     * @param string $cardno 身份证号码。
     * @return array
     */
    public static function isLeak($cardno) {
        $url = "http://apis.juhe.cn/idcard/leak";
        $params = [
            'dtype'  => 'json',
            'cardno' => $cardno,
            'key'    => '855366926196ed6e4549fac00b3abd42'
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
     * 身份证信息泄漏查询。
     * @param string $cardno 身份证号码。
     * @return array
     */
    public static function isLoss($cardno) {
        $url = "http://apis.juhe.cn/idcard/loss";
        $params = [
            'dtype'  => 'json',
            'cardno' => $cardno,
            'key'    => '855366926196ed6e4549fac00b3abd42'
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