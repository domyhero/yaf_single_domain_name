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
class GuessService extends BaseService {

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
     * @return boolean
     */
    public static function addGuess($admin_id, $title, $image_url, $options_data, $deadline) {
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
        foreach ($options_data as $item) {
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
            'deadline'     => $deadline,
            'status'       => 1,
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
     * @return boolean
     */
    public static function editGuess($admin_id, $guess_id, $title, $image_url, $options_data, $deadline) {
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
        foreach ($options_data as $item) {
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
            'deadline'     => $deadline,
            'status'       => 1,
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
}