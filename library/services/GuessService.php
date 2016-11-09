<?php
/**
 * 竞猜活动管理。
 * @author winerQin
 * @date 2016-11-01
 */

namespace services;

use common\YCore;
use winer\Validator;
use models\GmGuess;
use models\DbBase;
use models\User;
use common\YUrl;
use models\GmGuessRecord;
class guessservice extends BaseService {

    /**
     * 选项。
     * @var array
     */
    public static $options = [
        'A' => 'A选项',
        'B' => 'B选项',
        'C' => 'C选项',
        'D' => 'D选项',
        'E' => 'E选项'
    ];

    /**
     * 投注金币等级。
     * @var array
     */
    public static $gold_level = [
        100,
        200,
        500
    ];

    /**
     * 获取竞猜详情。
     * @param number $guess_id 竞猜ID。
     * @return array
     */
    public static function getAdminGuessDetail($guess_id) {
        $where = [
            'guess_id' => $guess_id,
            'status'   => 1
        ];
        $columns = [
            'guess_id', 'title', 'image_url', 'option_data', 'deadline', 'is_open', 'open_result'
        ];
        $guess_model = new GmGuess();
        $guess_info  = $guess_model->fetchOne($columns, $where);
        if (empty($guess_info)) {
            YCore::exception(-1, '竞猜活动不存在');
        }
        $guess_info['deadline']    = YCore::format_timestamp($guess_info['deadline']);
        $guess_info['option_data'] = json_decode($guess_info['option_data'], true);
        return $guess_info;
    }

    /**
     * 获取用户竞猜记录。
     * @param number $user_id 用户ID。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getUserGuessRecordList($user_id, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_guess_record ';
        $columns = ' guess_id, user_id, bet_gold, is_prize, prize_money, created_time ';
        $where   = ' WHERE user_id = :user_id AND status = :status ';
        $params  = [
            ':user_id' => $user_id,
            ':status'  => 1
        ];
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        $guess_model = new GmGuess();
        $guesss = [];
        foreach ($list as $key => $item) {
            if (isset($guesss[$item['guess_id']])) {
                $guess_info = $guesss[$item['guess_id']];
            } else {
                $guess_info = $guess_model->fetchOne([], ['guess_id' => $item['guess_id']]);
                $guesss[$item['guess_id']] = $guess_info;
            }
            $item['title']    = $guess_info['title'];
            $item['deadline'] = YCore::format_timestamp($item['deadline']);
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

    /**
     * 用户获取当前竞猜活动。
     */
    public static function getUserGuessList($page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_guess ';
        $columns = ' guess_id, title, image_url, deadline, option_data ';
        $where   = ' WHERE status = :status ';
        $params  = [
            ':status' => 1
        ];
        $order_by = ' ORDER BY guess_id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $key => $item) {
            $item['deadline'] = YCore::format_timestamp($item['deadline']);
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

    /**
     * 管理后台获取竞猜活动参与记录。
     * @param number $guess_id 竞猜ID。
     * @param string $username 用户账号。
     * @param string $mobilephone 用户手机号。
     * @param number $is_prize 是否中奖。-1不限、1是、0否。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminGuessRecordList($guess_id = -1, $username = '', $mobilephone = '', $is_prize = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_guess_record ';
        $columns = ' guess_id, user_id, bet_gold, is_prize, prize_money, created_time ';
        $where   = ' WHERE status = :status ';
        $params  = [
            ':status' => 1
        ];
        if ($guess_id != -1) {
            $where .= ' AND guess_id = :guess_id ';
            $params[':guess_id'] = $guess_id;
        }
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
        if ($is_prize !=  -1) {
            $where .= ' AND is_prize = :is_prize ';
            $params[':is_prize'] = $is_prize;
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        $users = [];
        foreach ($list as $key => $item) {
            if (isset($users[$item['user_id']])) {
                $userinfo = $users[$item['user_id']];
            } else {
                $userinfo = $user_model->fetchOne([], ['user_id' => $item['user_id']]);
                $users[$item['user_id']] = $userinfo;
            }
            $item['username']     = $userinfo['username'];
            $item['mobilephone']  = $userinfo['mobilephone'];
            $item['created_time'] = YCore::format_timestamp($item['created_time']);
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

    /**
     * 管理后台获取竞猜活动列表。
     * @param string $title 活动标题。
     * @param string $start_time 创建时间开始。
     * @param string $end_time 创建时间截止。
     * @param number $is_open 是否开奖。
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminGuessList($title = '', $start_time = '', $end_time = '', $is_open = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_guess ';
        $columns = ' guess_id, title, image_url, deadline, is_open, open_result, total_people, prize_people, total_bet_gold, total_prize_gold, modified_time, created_time ';
        $where   = ' WHERE status = :status ';
        $params  = [
            ':status' => 1
        ];
        if (strlen($title) > 0) {
            $where .= ' AND title LIKE :title ';
            $params[':title'] = "%{$title}%";
        }
        if (strlen($start_time) > 0) {
            $where .= ' AND created_time >= :start_time ';
            $params[':start_time'] = strtotime($start_time);
        }
        if (strlen($end_time) > 0) {
            $where .= ' AND created_time <= :end_time ';
            $params[':end_time'] = $end_time;
        }
        if ($is_open != -1) {
            $where .= ' AND is_open = :is_open ';
            $params[':is_open'] = $is_open;
        }
        $order_by = ' ORDER BY guess_id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $key => $item) {
            $item['image_url']     = YUrl::filePath($item['image_url']);
            $item['deadline']      = YCore::format_timestamp($item['deadline']);
            $item['modified_time'] = YCore::format_timestamp($item['modified_time']);
            $item['created_time']  = YCore::format_timestamp($item['created_time']);
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

    /**
     * 添加竞猜活动。
     * -- Example start --
     * $options_data = [
     *      [
     *          'op_title' => '选项标题',
     *          'op_odds'  => '选项赔率'
     *      ],
     *      [
     *          'op_title' => '选项标题',
     *          'op_odds'  => '选项赔率'
     *      ],
     *      ......
     * ];
     * -- Example end --
     * @param number $admin_id 管理员ID。
     * @param string $title 竞猜标题。
     * @param string $image_url 竞猜关联图片。
     * @param array $options_data 竞猜选项数据。
     * @param string $deadline 活动参与截止日期。
     * @param string $open_result 开奖结果。
     * @return boolean
     */
    public static function addGuess($admin_id, $title, $image_url, $options_data, $deadline, $open_result = '') {
        if (strlen($title) === 0) {
            YCore::exception(-1, '竞猜标题必须填写');
        }
        if (!Validator::is_len($title, 1, 255, true)) {
            YCore::exception(-1, '竞猜活动标题必须1至255个字符 ');
        }
        if (strlen($image_url) === 0) {
            YCore::exception(-1, '竞猜活动图片必须上传');
        }
        if (!Validator::is_len($image_url, 1, 100, true)) {
            YCore::exception(-1, '竞猜活动图片链接过长 ');
        }
        if (strlen($deadline) === 0) {
            YCore::exception(-1, '活动参与截止日期必须填写');
        }
        if (!Validator::is_date($deadline)) {
            YCore::exception(-1, '活动参与截止日期格式不正确');
        }
        if (empty($options_data)) {
            YCore::exception(-1, '竞猜活动选项必须设置');
        }
        if (strlen($open_result) > 0) {
            $open_result = strtoupper($open_result);
            if (!array_key_exists($open_result, self::$options)) {
                YCore::exception(-1, '竞猜结果数据异常');
            }
        } else {
            $open_result = '';
        }
        foreach ($options_data as $opk => $item) {
            if (strlen($item['op_title']) === 0 && strlen($item['op_odds']) === 0) {
                continue;
            }
            if (!isset($item['op_title']) || strlen($item['op_title']) === 0) {
                YCore::exception(-1, '选项标题必须填写');
            }
            if (!Validator::is_len($item['op_title'], 1, 20, true)) {
                YCore::exception(-1, '选项标题不能超过20个字符');
            }
            if (!isset($item['op_odds'])) {
                YCore::exception(-1, '选项赔率必须设置');
            }
            if (!Validator::is_float($item['op_odds'])) {
                YCore::exception(-1, '选项赔率必须是小数');
            }
        }
        $data = [
            'title'        => $title,
            'image_url'    => $image_url,
            'option_data'  => json_encode($options_data),
            'deadline'     => strtotime($deadline),
            'status'       => 1,
            'open_result'  => $open_result,
            'created_by'   => $admin_id,
            'created_time' => $_SERVER['REQUEST_TIME']
        ];
        $guess_model = new GmGuess();
        $ok = $guess_model->insert($data);
        if (!$ok) {
            YCore::exception(-1, '添加失败');
        }
        return true;
    }

    /**
     * 编辑竞猜活动。
     * -- Example start --
     * $options_data = [
     *      [
     *          'op_title' => '选项标题',
     *          'op_odds'  => '选项赔率'
     *      ],
     *      [
     *          'op_title' => '选项标题',
     *          'op_odds'  => '选项赔率'
     *      ],
     *      ......
     * ];
     * -- Example end --
     * @param number $admin_id 管理员ID。
     * @param number $guess_id 竞猜活动ID。
     * @param string $title 竞猜标题。
     * @param string $image_url 竞猜关联图片。
     * @param array $options_data 竞猜选项数据。
     * @param string $deadline 活动参与截止日期。
     * @param string $open_result 开奖结果。
     * @return boolean
     */
    public static function editGuess($admin_id, $guess_id, $title, $image_url, $options_data, $deadline, $open_result = '') {
        if (strlen($title) === 0) {
            YCore::exception(-1, '竞猜标题必须填写');
        }
        if (!Validator::is_len($title, 1, 255, true)) {
            YCore::exception(-1, '竞猜活动标题必须1至255个字符 ');
        }
        if (strlen($image_url) === 0) {
            YCore::exception(-1, '竞猜活动图片必须上传');
        }
        if (!Validator::is_len($image_url, 1, 100, true)) {
            YCore::exception(-1, '竞猜活动图片链接过长 ');
        }
        if (strlen($deadline) === 0) {
            YCore::exception(-1, '活动参与截止日期必须填写');
        }
        if (!Validator::is_date($deadline)) {
            YCore::exception(-1, '活动参与截止日期格式不正确');
        }
        if (empty($options_data)) {
            YCore::exception(-1, '竞猜活动选项必须设置');
        }
        if (strlen($open_result) > 0) {
            $open_result = strtoupper($open_result);
            if (!array_key_exists($open_result, self::$options)) {
                YCore::exception(-1, '竞猜结果数据异常');
            }
        } else {
            $open_result = '';
        }
        foreach ($options_data as $item) {
            if (strlen($item['op_title']) === 0 && strlen($item['op_odds']) === 0) {
                continue;
            }
            if (!isset($item['op_title']) || strlen($item['op_title']) === 0) {
                YCore::exception(-1, '选项标题必须填写');
            }
            if (!Validator::is_len($item['op_title'], 1, 20, true)) {
                YCore::exception(-1, '选项标题不能超过20个字符');
            }
            if (!isset($item['op_odds'])) {
                YCore::exception(-1, '选项赔率必须设置');
            }
            if (!Validator::is_float($item['op_odds'])) {
                YCore::exception(-1, '选项赔率必须是小数');
            }
        }
        $where = [
            'guess_id' => $guess_id,
            'status' => 1
        ];
        $guess_model = new GmGuess();
        $guess_info  = $guess_model->fetchOne([], $where);
        if (empty($guess_info)) {
            YCore::exception(-1, '竞猜活动不存在');
        }
        $data = [
            'title'         => $title,
            'image_url'     => $image_url,
            'option_data'   => json_encode($options_data),
            'deadline'      => strtotime($deadline),
            'status'        => 1,
            'open_result'   => $open_result,
            'modified_by'   => $admin_id,
            'modified_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $guess_model->update($data, $where);
        if (!$ok) {
            YCore::exception(-1, '修改失败');
        }
        return true;
    }

    /**
     * 删除竞猜活动。
     * @param number $admin_id 管理员ID。
     * @param number $guess_id 竞猜活动ID。
     * @return boolean
     */
    public static function deleteGuess($admin_id, $guess_id) {
        $where = [
            'guess_id' => $guess_id,
            'status'   => 1
        ];
        $guess_model = new GmGuess();
        $guess_info  = $guess_model->fetchOne([], $where);
        if (empty($guess_info)) {
            YCore::exception(-1, '竞猜活动不存在');
        }
        $data = [
            'status'        => 2,
            'modified_by'   => $admin_id,
            'modified_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $guess_model->update($data, $where);
        if (!$ok) {
            YCore::exception(-1, '删除失败');
        }
        return true;
    }

    /**
     * 用户竞猜。
     * @param number $user_id 用户ID。
     * @param number $guess_id 竞猜活动ID。
     * @param string $option_index 用户选择的选项。
     * @param unknown $bet_gold 投注金币。
     * @return number 用户当前操作之后剩余的金币。
     */
    public static function startDo($user_id, $guess_id, $option_index, $bet_gold) {
        if (!in_array($bet_gold, self::$gold_level)) {
            YCore::exception(-1, '投注金币数错误');
        }
        $guess_model  = new GmGuess();
        $guess_detail = $guess_model->fetchOne([], ['guess_id' => $guess_id, 'status' => 1]);
        if (empty($guess_detail)) {
            YCore::exception(-1, '活动不存在');
        }
        if ($guess_detail['deadline'] < $_SERVER['REQUEST_TIME']) {
            YCore::exception(-1, '活动已经结束');
        }
        $default_db = new DbBase();
        $default_db->beginTransaction();
        try {
            $user_gold = GoldService::goldConsume($user_id, $bet_gold, GoldService::CONSUME_TYPE_CUT, 'guess_cut');
        } catch (\Exception $e) {
            $default_db->rollBack();
            YCore::exception($e->getCode(), $e->getMessage());
        }
        $guess_record_model = new GmGuessRecord();
        $data = [
            'guess_id'     => $guess_id,
            'user_id'      => $user_id,
            'bet_gold'     => $bet_gold,
            'prize_status' => 0,
            'status'       => 1,
            'created_time' => $_SERVER['REQUEST_TIME']
        ];
        $ok = $guess_record_model->insert($data);
        if (!$ok) {
            $default_db->rollBack();
            YCore::exception(-1, '投注失败');
        }
        $default_db->commit();
        return $user_gold;
    }

    /**
     * 统计竞猜相关数据。
     * @param number $guess_id 竞猜ID。
     * @return boolean
     */
    public static function stats($guess_id) {

    }
}