<?php
/**
 * 聚合新华字典接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\XinHuaDict;
class JuheXinhuaApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $word = $this->getString('word');
        $result = XinHuaDict::query($word);
        $this->render(0, 'success', $result);
    }

}