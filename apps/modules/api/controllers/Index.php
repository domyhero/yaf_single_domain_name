<?php
/**
 * APP接口默认controller。
 * @author winerQin
 * @date 2016-10-14
 */

use apis\ApiFactory;

class IndexController extends \common\controllers\Api {

    /**
     * API入口。
     */
    public function indexAction() {
        header("Access-Control-Allow-Origin: *");
        try {
            $body = file_get_contents('php://input'); // 取请求中body的JSON内容。
            $body_params = json_decode($body, true);  // 解析JSON为数组参数。
            $api  = ApiFactory::factory($body_params);
            $data = $api->renderJson();
            header('Content-Type:application/json;charset=UTF-8');
            echo $data;
        } catch (\Exception $e) {
            header('Content-Type:application/json;charset=UTF-8');
            $data = [
                'errcode' => $e->getCode(),
                'errmsg'  => $e->getMessage()
            ];
            echo json_encode($data);
        }
        $this->end();
    }
}