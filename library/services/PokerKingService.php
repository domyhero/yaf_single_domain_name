<?php
/**
 * 扑克王游戏。
 * -- 1、每局游戏有五张牌。3张普通牌,1张小王,1张大王。
 * -- 2、抽到普通牌输掉押注金币。
 * -- 3、抽到小王返还两部押注金币。
 * -- 4、抽到大王返还三倍押注金币。
 * -- 5、押注金币为固定三个档位：100、500、1000。
 * @author winerQin
 * @date 2016-11-04
 */

namespace services;

use common\YCore;
use models\GmPokerKingRecord;
use models\User;
use models\DbBase;
class PokerKingService extends BaseService {

    /**
     * 金币押注档次。
     * @var array
     */
    public static $money_level = [
        100,
        500,
        1000
    ];

    /**
     * 扑克牌。
     * @var array
     */
    private static $pokers = [
        '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'
    ];

    /**
     * 五张扑克牌初始形式。
     * -- 数字4、5分别代表小王、大王。
     * @var array
     */
    private static $five_pokers_init = [
        1, 2, 3, 4, 5
    ];

    /**
     * 用户获取扑克王游戏翻牌记录。
     * @param number $user_id 用户ID。
     * @param number $poker_type 用户翻到的牌的类型：1大王、2小王、3普通牌。
     * @param number $is_prize 是否中奖:0-否、1-是
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getUserPokerKingRecordList($user_id, $poker_type = -1, $is_prize = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_poker_king_record ';
        $columns = ' bet_gold, is_prize, prize_money, poker, pokers, poker_type, created_time ';
        $where   = ' WHERE user_id = :user_id AND status = :status ';
        $params  = [
            ':user_id' => $user_id,
            ':status'  => 1
        ];
        if ($is_prize !=  -1) {
            $where .= ' AND is_prize = :is_prize ';
            $params[':is_prize'] = $is_prize;
        }
        if ($poker_type !=  -1) {
            $where .= ' AND poker_type = :poker_type ';
            $params[':poker_type'] = $poker_type;
        }
        $order_by = ' ORDER BY id DESC ';
        $sql = "SELECT COUNT(1) AS count {$from_table} {$where}";
        $default_db = new DbBase();
        $count_data = $default_db->rawQuery($sql, $params)->rawFetchOne();
        $total = $count_data ? $count_data['count'] : 0;
        $sql   = "SELECT {$columns} {$from_table} {$where} {$order_by} LIMIT {$offset},{$count}";
        $list  = $default_db->rawQuery($sql, $params)->rawFetchAll();
        foreach ($list as $key => $item) {
            $item['pokers']       = json_decode($item['pokers'], true);
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
     * 管理后台获取扑克王游戏翻牌记录。
     * @param string $username 用户名。
     * @param string $mobilephone 用户手机号。
     * @param number $poker_type 用户翻到的牌的类型：1大王、2小王、3普通牌。
     * @param number $is_prize 是否中奖:0-否、1-是
     * @param number $page 当前页码。
     * @param number $count 每页显示条数。
     * @return array
     */
    public static function getAdminPokerKingRecordList($username = '', $mobilephone = '', $poker_type = -1, $is_prize = -1, $page = 1, $count = 20) {
        $offset = self::getPaginationOffset($page, $count);
        $from_table = ' FROM gm_poker_king_record ';
        $columns = ' user_id, bet_gold, is_prize, prize_money, poker, pokers, poker_type, created_time ';
        $where   = ' WHERE status = :status ';
        $params  = [
            ':status' => 1
        ];
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
        if ($poker_type !=  -1) {
            $where .= ' AND poker_type = :poker_type ';
            $params[':poker_type'] = $poker_type;
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
            $item['pokers']       = json_decode($item['pokers'], true);
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
     * 翻牌。
     * @param number $user_id 用户ID。
     * @param number $money 翻牌挡次。
     * @param number $poker_index 用户翻牌位置。
     * @return array
     */
    public static function startDo($user_id, $money, $poker_index) {
        if (!in_array($money, self::$money_level)) {
            YCore::exception(-1, '押注金币错误');
        }
        GoldService::goldConsume($user_id, $money, GoldService::CONSUME_TYPE_CUT, 'poker_king_cut');
        if (!in_array($poker_index, self::$five_pokers_init)) {
            YCore::exception(-1, '翻牌位位置错误');
        }
        $poker_index = $poker_index - 1;
        $random_pokers = self::randomPoker();
        $poker = $random_pokers[$poker_index];
        switch ($poker) {
            case 'KingMax':
                $is_win     = 1;            // 是否抽中。
                $poker_type = 1;            // 抽中的牌的类型。1大王、2小王、3普通牌。
                $gold       = $money * 2;   // 奖励金币。
                GoldService::goldConsume($user_id, $gold, GoldService::CONSUME_TYPE_ADD, 'poker_king_add');
                break;
            case 'KingMin':
                $is_win     = 1;            // 是否抽中。
                $poker_type = 2;            // 抽中的牌的类型。1大王、2小王、3普通牌。
                $gold       = $money * 1.5; // 奖励金币。
                GoldService::goldConsume($user_id, $gold, GoldService::CONSUME_TYPE_ADD, 'poker_king_add');
                break;
            default:
                $is_win     = 0;            // 是否抽中。
                $poker_type = 3;            // 抽中的牌的类型。1大王、2小王、3普通牌。
                $gold       = 0;            // 奖励金币。
                break;
        }
        self::wirteLog($user_id, $money, $is_win, $gold, $poker, $random_pokers, $poker_type);
        return [
            'is_win'     => $is_win,
            'poker_type' => $poker_type,
            'poker'      => $poker,
            'gold'       => $gold
        ];
    }

    /**
     * 记录翻牌记录。
     * @param number $user_id 用户ID。
     * @param number $bet_gold 押注金币。
     * @param number $is_prize 是否中奖：是否中奖:0-否、1-是。
     * @param number $prize_money 中奖金币数量。
     * @param string $poker 用户翻到的牌。
     * @param array $pokers 系统给出的牌。
     * @param number $poker_type 用户翻到的牌的类型：1大王、2小王、3普通牌。
     * @return boolean
     */
    private static function wirteLog($user_id, $bet_gold, $is_prize, $prize_money, $poker, $pokers, $poker_type) {
        $data = [
            'user_id'      => $user_id,
            'bet_gold'     => $bet_gold,
            'is_prize'     => $is_prize,
            'prize_money'  => $prize_money,
            'poker'        => $poker,
            'pokers'       => json_encode($pokers),
            'poker_type'   => $poker_type,
            'status'       => 1,
            'created_time' => $_SERVER['REQUEST_TIME']
        ];
        $poker_king_record_model = new GmPokerKingRecord();
        $ok = $poker_king_record_model->insert($data);
        if (!$ok) {
            YCore::exception(-1, '系统异常');
        }
        return true;
    }

    /**
     * 随机五张牌。
     * @return array
     */
    private static function randomPoker() {
        shuffle(self::$pokers);
        shuffle(self::$five_pokers_init);
        $one_key  = array_search(1, self::$five_pokers_init);
        $two_key  = array_search(2, self::$five_pokers_init);
        $thr_key  = array_search(3, self::$five_pokers_init);
        $four_key = array_search(4, self::$five_pokers_init);
        $five_key = array_search(5, self::$five_pokers_init);
        self::$five_pokers_init[$one_key]  = self::$pokers[0];
        self::$five_pokers_init[$two_key]  = self::$pokers[1];
        self::$five_pokers_init[$one_key]  = self::$pokers[2];
        self::$five_pokers_init[$four_key] = 'KingMin';
        self::$five_pokers_init[$five_key] = 'KingMax';
        return self::$five_pokers_init;
    }
}