<?php
/**
 * 文章详情接口。
 * @author winerQin
 * @date 2016-10-27
 */
namespace apis\v1;

use apis\BaseApi;
use services\NewsService;

class NewsDetailApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $news_id = $this->getInt('news_id');
        $detail  = NewsService::getNewsDetail($news_id, true);
        $this->render(0, 'success', $detail);
    }

}