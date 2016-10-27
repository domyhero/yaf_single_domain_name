<?php
/**
 * 聚合来电显示接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\CallDisplay;
class JuheCallDisplayApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $tel = $this->getString('tel');
        $result = CallDisplay::query($tel);
        $this->render(0, 'success', $result);
    }

}