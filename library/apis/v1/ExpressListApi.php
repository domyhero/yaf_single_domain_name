<?php
/**
 * 快递列表接口。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use common\YCore;
class ExpressListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $express_list = YCore::dict('logistics_list');
        $this->render(0, 'success', $express_list);
    }
}