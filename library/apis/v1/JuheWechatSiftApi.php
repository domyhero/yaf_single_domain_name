<?php
/**
 * 聚合微信精选接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\WeChatSift;

class JuheWechatSiftApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $tel  = $this->getString('tel');
        $page = $this->getInt('page', 1);
        $list = WeChatSift::query($$page, 20);
        $result = [
            'list' => $list,
            'page' => $page
        ];
        $this->render(0, 'success', $result);
    }

}