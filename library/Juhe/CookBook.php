<?php
/**
 * 菜谱大全。
 * @author winerQin
 * @date 2106-10-12
 */

namespace Juhe;

use common\YCore;
class CookBook extends Base {

    /**
     * 菜谱查询。
     * @param string $menu 要查询的菜谱名。
     * @param int $pn 数据返回起始下标。
     * @param int $rn 数据返回条数，最大30。
     * @param int $albums albums字段类型，1字符串，默认数组。
     * @return array
     */
    public static function query($menu, $pn, $rn, $albums = 0) {
        $url = "http://apis.juhe.cn/cook/query.php";
        $params = [
            'menu'   => $menu,
            'dtype'  => 'json',
            'pn'     => $pn,
            'rn'     => $rn,
            'albums' => $albums,
            'key'    => 'd4f40600c00f766decf0ba54332e3325'
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
     * 菜谱分类标签。
     * @param int $parentid 父分类ID。
     * @return array
     */
    public static function category($parentid = 0) {
        $url = "http://apis.juhe.cn/cook/category";
        $params = [
            'parentid' => $parentid,
            'dtype'    => 'json',
            'key'      => 'd4f40600c00f766decf0ba54332e3325'
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
     * 按菜谱标签检索菜谱。
     * @param int $cid 标签ID。
     * @param int $pn 数据返回起始下标。
     * @param int $rn 数据返回条数，最大30。
     * @param int $albums albums字段类型，1字符串，默认数组。
     * @return array
     */
    public static function byCategoryQuery($cid, $pn, $rn) {
        $url = "http://apis.juhe.cn/cook/index";
        $params = [
            'cid'    => $cid,
            'dtype'  => 'json',
            'pn'     => $pn,
            'rn'     => $rn,
            'format' => '',
            'key'    => 'd4f40600c00f766decf0ba54332e3325'
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
     * 按菜谱ID查看详情。
     * @param int $id 标签ID。
     * @return array
     */
    public static function getCookDetail($id) {
        $url = "http://apis.juhe.cn/cook/queryid";
        $params = [
            'id'    => $id,
            'dtype' => 'json',
            'key'   => 'd4f40600c00f766decf0ba54332e3325'
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