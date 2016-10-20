<?php
/**
 * API测试。
 * @author winerQin
 * @date 2016-10-20
 */

use common\YUrl;

class ApiController extends \common\controllers\Guest {

    /**
     * 首页。
     */
    public function indexAction() {
        echo $this->testLotteryActivityListApi();
        $this->end();
    }

    public function testLotteryActivityListApi() {
        $data = [
            'method' => 'lottery.activity.list',
            'v'      => 1,
            'page'   => 1
        ];
        return $this->curl(json_encode($data));
    }

    public function testUserEditPwdApi() {
        $data = [
            'method'  => 'user.edit.pwd',
            'v'       => 1,
            'token'   => '2ed8BAlUAgVSBQYEUwEDVARUBApTBFQEUlUDAFBVOVRSVgACDFNWDQdXVFVQC1NSCAABBFQBAwdWAVVaBQIGPlVSDwIAUQYHVAQ\/Bg',
            'old_pwd' => '654321',
            'new_pwd' => '123456'
        ];
        return $this->curl(json_encode($data));
    }

    public function testUserDetailApi() {
        $data = [
            'method' => 'user.detail',
            'v'      => 1,
            'token'  => '2ed8BAlUAgVSBQYEUwEDVARUBApTBFQEUlUDAFBVOVRSVgACDFNWDQdXVFVQC1NSCAABBFQBAwdWAVVaBQIGPlVSDwIAUQYHVAQ\/Bg'
        ];
        return $this->curl(json_encode($data));
    }

    public function testLuckyStartDoApi() {
        $data = [
            'method' => 'lucky.start.do',
            'v'      => 1,
            'token'  => '2ed8BAlUAgVSBQYEUwEDVARUBApTBFQEUlUDAFBVOVRSVgACDFNWDQdXVFVQC1NSCAABBFQBAwdWAVVaBQIGPlVSDwIAUQYHVAQ\/Bg'
        ];
        return $this->curl(json_encode($data));
    }

    public function testLuckNewestPrizeListApi() {
        $data = [
            'method' => 'lucky.newest.prize.list',
            'v'      => 1,
        ];
        return $this->curl(json_encode($data));
    }

    public function testUserLoginApi() {
        $data = [
            'method'   => 'user.login',
            'v'        => 1,
            'username' => 'phpqinsir',
            'password' => '123456'
        ];
        return $this->curl(json_encode($data));
    }

    public function testUserLuckyPrizeList() {
        $data = [
            'method'     => 'user.lucky.prize.list',
            'v'          => 1,
            'token'      => '2ed8BAlUAgVSBQYEUwEDVARUBApTBFQEUlUDAFBVOVRSVgACDFNWDQdXVFVQC1NSCAABBFQBAwdWAVVaBQIGPlVSDwIAUQYHVAQ\/Bg',
            'goods_name' => '',
            'goods_type' => '',
            'page'       => 1
        ];
        return $this->curl(json_encode($data));
    }

    public function testLuckyGoodsListApi() {
        $data = [
            'method' => 'lucky.goods.list',
            'v'      => 1
        ];
        return $this->curl(json_encode($data));
    }

    private function curl($data) {
        $url = YUrl::getDomainName() . '/api/index/index';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}