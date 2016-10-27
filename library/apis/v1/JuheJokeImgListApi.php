<?php
/**
 * 聚合趣图笑话列表接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Joke;
class JuheJokeImgListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $page = $this->getInt('page', 1);
        $time = $this->getInt('time', 0);
        $list = Joke::getImgList($page, 20, 'desc', $time);
        $data = [
            'list' => $list,
            'page' => $page + 1, // 下一页的页码。
            'time' => $time
        ];
        $this->render(0, 'success', $data);
    }
}