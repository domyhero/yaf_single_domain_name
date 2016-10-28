<?php
/**
 * 金币相关操作封装。
 * @author winerQin
 * @date 2016-07-02
 */

namespace services;

use models\GmGold;
use common\YCore;
use models\GmGoldConsume;
use models\DbBase;
use models\User;
use winer\Validator;
class GoldService extends BaseService {

    // 消费类型。
    const CONSUME_TYPE_ADD = 1; // 增加。
    const CONSUME_TYPE_CUT = 2; // 扣减。

    // 消费类型字典。
    public static $consume_type_dict = [
        1 => '增加',
        2 => '扣减'
    ];

    // 消费编码。
    const CONSUME_ORDER_PAY = 'order.pay'; // 订单支付。

    /**
     * 金币消费明细[增加/扣除]。
     *
     * @param number $user_id 用户ID。
     * @param number $gold 金币数量。
     * @param number $consume_type 消费类型。1增加、2扣减。
     * @param number $consume_code 消费编码。
     * @return boolean
     */
    public static function goldConsume($user_id, $gold, $consume_type, $consume_code) {
        $gold_model = new GmGold();
        $user_gold_info = $gold_model->fetchOne([], ['user_id' => $user_id]);
        if ($consume_type == self::CONSUME_TYPE_ADD) {
            if (empty($user_gold_info)) {
                $data = [
                    'user_id'      => $user_id,
                    'gold'         => $gold,
                    'created_time' => $_SERVER['REQUEST_TIME']
                ];
                $ok = $gold_model->insert($data);
                if (!$ok) {
                    YCore::exception(- 1, '服务器繁忙,请稍候重试');
                }
            } else {
                $data = [
                    'v'             => $user_gold_info['v'] + 1,
                    'gold'          => $user_gold_info['gold'] + $gold,
                    'modified_time' => $_SERVER['REQUEST_TIME']
                ];
                $where = [
                    'user_id' => $user_id,
                    'v'       => $user_gold_info['v']
                ];
                $ok = $gold_model->update($data, $where);
                if (!$ok) {
                    YCore::exception(- 1, '服务器繁忙,请稍候重试');
                }
            }
        } else if ($consume_type == self::CONSUME_TYPE_CUT) {
            if (empty($user_gold_info) || $user_gold_info['gold'] < $gold) {
                YCore::exception(- 1, '金币数量不足');
            }
            $data = [
                'v'             => $user_gold_info['v'] + 1,
                'gold'          => $user_gold_info['gold'] - $gold,
                'modified_time' => $_SERVER['REQUEST_TIME']
            ];
            $where = [
                'user_id' => $user_id,
                'v'       => $user_gold_info['v']
            ];
            $ok = $gold_model->update($data, $where);
            if (!$ok) {
                YCore::exception(- 1, '服务器繁忙,请稍候重试');
            }
        }
        $gold_consume_model = new GmGoldConsume();
        $data = [
            'user_id'      => $user_id,
            'consume_type' => $consume_type,
            'consume_code' => $consume_code,
            'gold'         => $gold,
            'created_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $gold_consume_model->insert($data);
        if (!$ok) {
            YCore::exception(- 1, '服务器繁忙,请稍候重试');
        }
        return true;
    }

    /**
     * 获取金币消费记录。
     *
     * @param number $user_id 用户ID。
     * @param number $consume_type 消费类型：1增加、2扣减。
     * @param string $start_time 开始时间。
     * @param string $end_time 截止时间。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getUserGoldConsume($user_id = -1, $consume_type = -1, $start_time = '', $end_time = '', $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM ms_gold_consume ';
        $columns = ' * ';
        $where   = ' WHERE 1 ';
        $params  = [];
        if ($user_id != - 1) {
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $user_id;
        }
        if ($consume_type != - 1) {
            $where .= ' AND consume_type = :consume_type ';
            $params[':consume_type'] = $consume_type;
        }
        if (strlen($start_time) > 0) {
            if (!Validator::is_date($start_time)) {
                YCore::exception(-1, '查询时间格式不正确');
            }
            $where .= ' AND created_time <= :start_time ';
            $params[':start_time'] = strtotime($start_time);
        }
        if (strlen($end_time) > 0) {
            if (!Validator::is_date($end_time)) {
                YCore::exception(-1, '查询时间格式不正确');
            }
            $where .= ' AND created_time >= :end_time ';
            $params[':end_time'] = strtotime($end_time);
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total  = $count_data ? $count_data['count'] : 0;
        $sql    = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list   = $default_db->rawQuery($sql, $params)->rawFetchAll();
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
     * 获取金币消费记录。
     *
     * @param string $username 用户账号。
     * @param string $mobilephone
     * @param number $consume_type 消费类型：1增加、2扣减
     * @param string $start_time 开始时间。
     * @param string $end_time 截止时间。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminGoldConsume($username = '', $mobilephone = '', $start_time = '', $end_time = '', $consume_type = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_gold_consume ';
        $columns = ' * ';
        $where   = ' WHERE 1 ';
        $params  = [];
        $user_model = new User();
        if (strlen($username) > 0) {
            $userinfo = $user_model->fetchOne([], ['username' => $username]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        } else if (strlen($mobilephone) > 0) {
            $userinfo = $user_model->fetchOne([], ['mobilephone' => $mobilephone]);
            $where .= ' AND user_id = :user_id ';
            $params[':user_id'] = $userinfo ? $userinfo['user_id'] : 0;
        }
        if (strlen($start_time) > 0) {
            if (!Validator::is_date($start_time)) {
                YCore::exception(-1, '查询时间格式不正确');
            }
            $where .= ' AND created_time <= :start_time ';
            $params[':start_time'] = strtotime($start_time);
        }
        if (strlen($end_time) > 0) {
            if (!Validator::is_date($end_time)) {
                YCore::exception(-1, '查询时间格式不正确');
            }
            $where .= ' AND created_time >= :end_time ';
            $params[':end_time'] = strtotime($end_time);
        }
        if ($consume_type != -1) {
            $where .= ' AND consume_type = :consume_type ';
            $params[':consume_type'] = $consume_type;
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        $game_gold_consume_code_dict = YCore::dict('game_gold_consume_code');
        $users = [];
        foreach ($list as $key => $item) {
            if (isset($users[$item['user_id']])) {
                $userinfo = $users[$item['user_id']];
            } else {
                $userinfo = $user_model->fetchOne([], ['user_id' => $item['user_id']]);
                $users[$item['user_id']] = $userinfo;
            }
            $item['consume_type_label'] = self::$consume_type_dict[$item['consume_type']];
            $item['consume_code_label'] = $game_gold_consume_code_dict[$item['consume_code']];
            $item['username']           = $userinfo['username'];
            $item['mobilephone']        = $userinfo['mobilephone'];
            $item['email']              = $userinfo['email'];
            $item['created_time']       = YCore::format_timestamp($item['created_time']);
            $list[$key] = $item;
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