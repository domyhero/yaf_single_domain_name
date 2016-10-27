<?php
/**
 * 聚合笑话随机列表接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Joke;
class JuheJokeRandomListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $type = $this->getString('type', '');
        $list = Joke::getRandomJoke($type);
        $data = [
            'list' => $list,
        ];
        $this->render(0, 'success', $data);
    }
}