<?php
/**
 * 聚合天气接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Weather;
class JuheWeatherApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $city_name = $this->getString('city_name', '');
        $result = Weather::query($city_name);
        $this->render(0, 'success', $result);
    }
}