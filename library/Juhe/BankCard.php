<?php
/**
 * 银行卡实名认证。
 * @author winerQin
 * @date 2016-10-12
 */

namespace Juhe;

use common\YCore;
class BankCard extends Base {

    /**
     * 银行卡二元素检测。
     * @param string $realname 真实姓名。
     * @param string $bankcard 银行卡号。
     * @return array
     */
    public static function secoendVerify($realname, $bankcard) {
        $url = "http://v.juhe.cn/verifybankcard/query";
        $params = [
            'realname' => $realname,
            'bankcard' => $bankcard,
            'key'      => 'e4cf64173916d87652e3f6f8cb1a8225'
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
     * 银行卡二元素检测。
     * @param string $realname 真实姓名。
     * @param string $bankcard 银行卡号。
     * @param string $idcard 身份证号码。
     * @return array
     */
    public static function thirdVerify($realname, $bankcard, $idcard) {
        $url = "http://v.juhe.cn/verifybankcard3/query";
        $params = [
            'idcard'   => $idcard,
            'realname' => $realname,
            'bankcard' => $bankcard,
            'key'      => 'e4cf64173916d87652e3f6f8cb1a8225'
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
     * 银行卡四元素检测。
     * @param string $realname 真实姓名。
     * @param string $bankcard 银行卡号。
     * @param string $mobile 手机号码。
     * @param string $idcard 身份证号码。
     * @return array
     */
    public static function fourVerify($realname, $bankcard, $mobile, $idcard) {
        $url = "http://v.juhe.cn/verifybankcard4/query";
        $params = [
            'idcard'   => $idcard,
            'mobile'   => $mobile,
            'realname' => $realname,
            'bankcard' => $bankcard,
            'key'      => '71cad5b3ee2bb43731dfa9a72b08590f'
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