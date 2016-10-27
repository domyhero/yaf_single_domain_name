<?php
/**
 * 聚合汽车站查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Bus;
class JuheBusSearchApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $station = $this->getString('station');
        $result  = Bus::query($station);
        $this->render(0, 'success', $result);
    }

}