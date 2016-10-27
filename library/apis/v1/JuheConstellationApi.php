<?php
/**
 * 聚合星座运势接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Constellation;
class JuheConstellationApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $cons_name = $this->getString('cons_name');
        $type = $this->getString('type');
        $result = Constellation::query($cons_name, $type);
        $this->render(0, 'success', $result);
    }

}