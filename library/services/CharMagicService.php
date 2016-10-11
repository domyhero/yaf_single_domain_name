<?php
/**
 * 文字魔法封装。
 * @author winerQin
 * @date 2016-10-11
 */

namespace services;

use common\YCore;
class CharMagicService extends BaseService {

    /**
     * 文字转换。
     * @param string $str 文字。
     * @param int $out_type 转换类型：0转简体、1转繁体、2转火星文、3转密文。
     * @return string
     */
    public static function convert($str, $out_type) {
        if (strlen($str) === 0) {
            YCore::exception(-1, '请输入要转换的内容');
        }
        if (mb_strlen($str, 'UTF-8') > 250) {
            YCore::exception(-1, '被转换内容不允许超过250个字符');
        }
        switch ($out_type) {
            case 0:
            case 1:
            case 2:
                return self::requestJuheApi($str, $out_type);
                break;
            case 3:
                return self::toMarsChar($str);
                break;
            default:
                YCore::exception(-1, '非法转换类型');
                break;
        }
    }

    /**
     * 调用聚合接口完成转换。
     * @param string $str 转换前的文字。
     * @param int $juhe_type 转换类型。0转简体、1转繁体、2转火星文
     * @return string
     */
    private static function requestJuheApi($str, $juhe_type) {
        $url = "http://japi.juhe.cn/charconvert/change.from";
        $params = [
            'ip'   => $_SERVER['SERVER_ADDR'],              // 需要查询的IP地址或域名
            'text' => $str,                                 // 需要转换字符串
            'type' => $juhe_type,                           // 需要转换成的类型。0：简体 1：繁体  2：火星文
            'key'  => 'bcfc17e7a4ca1a1abebcaf7dd8691c3b',   // 应用APPKEY(应用详细页查询)
        ];
        $paramstring = http_build_query($params);
        $content = YCore::juhecurl($url, $paramstring);
        $result  = json_decode($content,true);
        if($result){
            if ($result['error_code'] == '0') {
                return $result['outstr'];
            } else {
                echo $result['error_code'].":".$result['reason'];
            }
        } else {
            YCore::exception(-1, '服务器繁忙,请稍候重试');
        }
    }

    /**
     * 转换为火星文。
     * @param string $str 转换前的文字。
     * @return string
     */
    public static function toMarsChar($str) {
        return '';
    }
}