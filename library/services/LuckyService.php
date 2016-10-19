<?php
/**
 * 抽奖业务封装。
 * @author winerQin
 * @date 2016-10-19
 */

namespace services;

use common\YCore;
use winer\Validator;
use models\DbBase;
use models\GmLuckyGoods;
use models\User;
class LuckyService extends BaseService {

    const GOODS_TYPE_JB = 'jb'; // 金币。
    const GOODS_TYPE_QB = 'qb'; // Q币。
    const GOODS_TYPE_HF = 'hf'; // 话费。
    const GOODS_TYPE_SW = 'sw'; // 实物。
    const GOODS_TYPE_NO = 'no'; // 未中奖。

    /**
     * 商品类型。
     * @var array
     */
    public static $goods_type_dict = [
        'jb' => '金币',
        'qb' => 'Q币',
        'hf' => '话费',
        'sw' => '实物',
        'no' => '未中奖'
    ];

    /**
     * 设置抽奖奖品。
     * -- Example start --
     * $goods = [
     *      [
     *          'goods_name' => '奖品名称',
     *          'day_max'    => '每天中奖最大次数。0代表不限制',
     *          'min_range'  => '随机最小概率值',
     *          'max_range'  => '随机最大概率值',
     *          'goods_type' => '商品类型',
     *          'image_url'  => '奖品图片'
     *      ],
     *      ......
     * ];
     * -- Example end --
     * @param number $admin_id 管理员ID。
     * @param array $goods 奖品列表。奖品格子只有九个。也就是说奖品也只能设置九个。
     * @return boolean
     */
    public static function setLuckyGoods($admin_id, $goods) {
        if (count($goods) === 9) {
            YCore::exception(-1, '奖品必须9个');
        }
        $db = new DbBase();
        $db->beginTransaction();
        $db->rawExec('TRUNCATE TABLE gm_lucky_goods');
        foreach ($goods as $item) {
            if (!Validator::is_len($item['goods_name'], 1, 50, true)) {
                $db->rollBack();
                YCore::exception(-1, '奖品名称长度不能大于50个字符');
            }
            if (!Validator::is_number_between($item['day_max'], 0, 1000000)) {
                $db->rollBack();
                YCore::exception(-1, '奖品每天的中奖最大次数不能超过1000000次');
            }
            if (!Validator::is_number_between($item['min_range'], 1, 1000000)) {
                $db->rollBack();
                YCore::exception(-1, '随机最小概率值不能超过100000');
            }
            if (!Validator::is_number_between($item['max_range'], 1, 1000000)) {
                $db->rollBack();
                YCore::exception(-1, '随机最大概率值不能超过100000');
            }
            if (!array_key_exists($item['goods_type'], self::$goods_type_dict)) {
                $db->rollBack();
                YCore::exception(-1, '商品类型不正确');
            }
            if (strlen($item['image_url']) === 0) {
                $db->rollBack();
                YCore::exception(-1, '奖品图片必须设置');
            }
            if (!Validator::is_len($item['image_url'], 1, 100, true)) {
                $db->rollBack();
                YCore::exception(-1, '图片长度不能超过100个字符');
            }
            $item['created_by']   = $admin_id;
            $item['created_time'] = $_SERVER['REQUEST_TIME'];
            $lucky_goods_model = new GmLuckyGoods();
            $ok = $lucky_goods_model->insert($item);
            if (!$ok) {
                $db->rollBack();
                YCore::exception(-1, '设置失败');
            }
        }
        $db->commit();
        return true;
    }

    /**
     * 用户发起抽奖。
     * @param number $user_id 用户ID。
     * @return array
     */
    public static function startDoLucky($user_id) {
        $lucky_goods_model = new GmLuckyGoods();
        $lucky_goods_list  = $lucky_goods_model->fetchAll();
        $rand_value = mt_rand(1, 100000);
        $prize_info = []; // 保存抽中的奖品信息。
        foreach ($lucky_goods_list as $item) {
            if ($rand_value >= $item['min_range'] && $rand_value <= $item['max_range']) {
                $prize_info = $item;
            }
        }
        if ($prize_info['goods_type'] == self::GOODS_TYPE_NO) {
            return [
                'goods_name' => '未中奖',
                'goods_type' => self::GOODS_TYPE_NO
            ];
        }
        $lucky_goods_time_key = "lucky_goods_time_{$prize_info['id']}";
        $cache_key = "lucky_goods_{$prize_info['id']}";
        $cache_db  = YCore::getCache();
        $cache_val = $cache_db->get($cache_key);
        if ($cache_val === false) {
            $cache_db->set($lucky_goods_time_key, $_SERVER['REQUEST_TIME']);
            $cache_db->set($cache_key, 1);
            return [
                'goods_name' => $prize_info['goods_name'],
                'goods_type' => $prize_info['goods_type']
            ];
        } else {
            $last_end_time = strtotime(date('Y-m-d 00:00:00', $_SERVER['REQUEST_TIME']));
            $lucky_goods_time = $cache_db->get($lucky_goods_time);
            if ($lucky_goods_time > $last_end_time) { // 当天。
                if ($cache_val >= $prize_info['day_max']) { // 超过了奖品当天允许抽中的数量。
                    return [
                        'goods_name' => '未中奖',
                        'goods_type' => self::GOODS_TYPE_NO
                    ];
                } else {
                    $cache_db->set($cache_key, $cache_val+1);
                    $cache_db->set($lucky_goods_time_key, $_SERVER['REQUEST_TIME']);
                    return [
                        'goods_name' => $prize_info['goods_name'],
                        'goods_type' => $prize_info['goods_type']
                    ];
                }
            } else { // 昨天。
                $cache_db->set($cache_key, 1);
                $cache_db->set($lucky_goods_time_key, $_SERVER['REQUEST_TIME']);
                return [
                    'goods_name' => $prize_info['goods_name'],
                    'goods_type' => $prize_info['goods_type']
                ];
            }
        }
    }

    /**
     * 管理员获取用户中奖记录。
     * @param string $username 用户名。
     * @param string $mobilephone 手机号码。
     * @param string $goods_name 奖品名称。
     * @param string $goods_type 奖品类型。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminLuckyPrizeList($username = '', $mobilephone = '', $goods_name = '', $goods_type = '', $page = 1, $count = 20) {
        $offset  = self::getPaginationOffset($page, $count);
        $columns = ' * ';
        $where   = ' WHERE status = :status';
        $params  = [
            ':status' => 1
        ];
        $user_model = new User();
        if (strlen($username) === 0) {
            $userinfo = $user_model->fetchOne([], ['username' => $username]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        }
        if (strlen($mobilephone) === 0) {
            $userinfo = $user_model->fetchOne([], ['mobilephone' => $mobilephone, 'status' => 1]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        }
        if (strlen($goods_name) !== 0) {
            $where .= ' AND goods_name LIKE :goods_name ';
            $params[':goods_name'] = "%{$goods_name}%";
        }
        if (strlen($goods_type) !== 0) {
            $where .= ' AND goods_type = :goods_type ';
            $params[':goods_type'] = $goods_type;
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count FROM gm_lucky_prize {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} FROM gm_lucky_prize {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        $userinfos = [];
        foreach ($list as $k => $v) {
            if (isset($userinfos[$v['user_id']])) {
                $v['username']    = $userinfos[$v['user_id']]['username'];
                $v['mobilephone'] = $userinfos[$v['user_id']]['mobilephone'];
            } else {
                $userinfo = $user_model->fetchOne([], ['user_id' => $v['user_id']]);
                $v['username']    = $userinfo ? $userinfo['username'] : '';
                $v['mobilephone'] = $userinfo ? $userinfo['mobilephone'] : '';
                $userinfos[$v['user_id']] = $userinfo;
            }
            $v['created_time'] = YCore::format_timestamp($v['created_time']);
            $list[$k] = $v;
        }
        $result = [
            'list'   => $list,
            'total'  => $total,
            'page'   => $page,
            'count'  => $count,
            'isnext' => self::IsHasNextPage($total, $page, $count)
        ];
        return $result;
    }

    /**
     * 获取用户中奖记录。
     * @param number $user_id 用户ID。
     * @param string $goods_name 奖品名称。
     * @param string $goods_type 奖品类型。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getUserLuckyPrizeList($user_id, $goods_name = '', $goods_type = '', $page = 1, $count = 20) {
        $offset  = self::getPaginationOffset($page, $count);
        $columns = ' * ';
        $where   = ' WHERE user_id = :user_id AND status = :status';
        $params  = [
            ':status'  => 1,
            ':user_id' => $user_id
        ];
        if (strlen($goods_name) !== 0) {
            $where .= ' AND goods_name LIKE :goods_name ';
            $params[':goods_name'] = "%{$goods_name}%";
        }
        if (strlen($goods_type) !== 0) {
            $where .= ' AND goods_type = :goods_type ';
            $params[':goods_type'] = $goods_type;
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count FROM gm_lucky_prize {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} FROM gm_lucky_prize {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $k => $v) {
            $v['created_time'] = YCore::format_timestamp($v['created_time']);
            $list[$k] = $v;
        }
        $result = [
            'list'   => $list,
            'total'  => $total,
            'page'   => $page,
            'count'  => $count,
            'isnext' => self::IsHasNextPage($total, $page, $count)
        ];
        return $result;
    }

    /**
     * 获取最新中奖记录。
     * @param number $count 要取的记录条数。
     * @return array
     */
    public static function getNewestKuckyPrizeList($count = 20) {
        $page    = 1;
        $offset  = self::getPaginationOffset($page, $count);
        $columns = ' * ';
        $where   = ' WHERE status = :status AND goods_type != :goods_type ';
        $params  = [
            ':status'     => 1,
            ':goods_type' => self::GOODS_TYPE_NO
        ];
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count FROM gm_lucky_prize {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} FROM gm_lucky_prize {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        $userinfos = [];
        $user_model = new User();
        foreach ($list as $k => $v) {
            if (isset($userinfos[$v['user_id']])) {
                $v['username'] = $userinfos[$v['user_id']]['username'];
            } else {
                $userinfo = $user_model->fetchOne([], ['user_id' => $v['user_id']]);
                $v['username'] = $userinfo ? $userinfo['username'] : '';
                $userinfos[$v['user_id']] = $userinfo;
            }
            $v['created_time'] = YCore::format_timestamp($v['created_time']);
            $list[$k] = $v;
        }
        $result = [
            'list'   => $list,
            'total'  => $total,
            'page'   => $page,
            'count'  => $count,
            'isnext' => self::IsHasNextPage($total, $page, $count)
        ];
        return $result;
    }
}