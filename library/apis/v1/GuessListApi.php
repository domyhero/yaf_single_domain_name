<?php
/**
 * 竞猜活动列表接口。
 * @author winerQin
 * @date 2016-11-09
 */

namespace apis\v1;

use apis\BaseApi;
use services\GuessService;

class GuessListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $page = $this->getInt('page', 1);
        $list = GuessService::getUserGuessList($page, 20);
        $this->render(0, 'success', $list);
    }
}