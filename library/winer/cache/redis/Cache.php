<?php
/**
 * Redis缓存。
 * @author winerQin
 * @date 2016-09-11
 */

namespace winer\cache\redis;

class Cache {

    /**
     * 当前对象。
     * @var winer\cache\redis
     */
    protected $client = null;

    public function __construct() {
        $client_name = 'winer_cache_redis';
        if (\Yaf\Registry::has($client_name)) {
            $this->client = \Yaf\Registry::get($client_name);
        } else {
            $this->client = $this->connect();
            \Yaf\Registry::set($client_name, $this->client);
        }
    }

    protected function connect() {
        $ok = \Yaf\Registry::has('redis');
        if ($ok) {
            return \Yaf\Registry::get('redis');
        } else {
            $config      = \Yaf\Registry::get('config');
            $redis_host  = $config->database->redis->host;
            $redis_port  = $config->database->redis->port;
            $redis_pwd   = $config->database->redis->pwd;
            $redis_index = $config->database->redis->index;
            $redis = new \Redis();
            $redis->connect($redis_host, $redis_port);
            $redis->auth($redis_pwd);
            $redis->select($redis_index);
            \Yaf\Registry::set('redis', $redis);
            return $redis;
        }
    }

    /**
     * 获取缓存。
     * @param string $cache_key 缓存KEY。
     * @return string|array|boolean
     */
    public function get($cache_key) {
        $cache_data = $this->client->get($cache_key);
        return $cache_data ? json_decode($cache_data, true) : false;
    }

    /**
     * 写缓存。
     * @param string $cache_key
     * @param string|array $cache_data
     */
    public function set($cache_key, $cache_data) {
        return $this->client->set($cache_key, json_encode($cache_data));
    }

    /**
     * 删除缓存。
     * @param string $cache_key
     * @return boolean
     */
    public function delete($cache_key) {
        return $this->client->del($cache_key);
    }
}