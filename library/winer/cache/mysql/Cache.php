<?php
/**
 * MySQL缓存。
 * 将缓存存储到MySQL。
 * @author winerQin
 * @date 2016-09-11
 */

namespace winer\cache\mysql;

use models\DbBase;
class Cache {

    /**
     * 当前对象。
     * @var winer\cache\mysql
     */
    protected $client = null;

    public function __construct() {
        $client_name = 'winer_cache_mysql';
        if (\Yaf_Registry::has($client_name)) {
            $this->client = \Yaf_Registry::get($client_name);
        } else {
            $this->client = $this->connect();
            \Yaf_Registry::set($client_name, $this->client);
        }
    }

    /**
     * 连接数据库。
     */
    protected function connect() {
        return new DbBase('default');
    }

    /**
     * 获取缓存。
     * @param string $cache_key 缓存KEY。
     * @return string|array|boolean
     */
    public function get($cache_key) {
        $db_link = $this->client->getDbLink();
        $sql = 'SELECT * FROM ms_cache WHERE cache_key = :cache_key';
        $sth = $db_link->prepare($sql);
        $sth->bindParam(':cache_key', $cache_key, \PDO::PARAM_STR);
        $sth->execute();
        $cache_data = $sth->fetch();
        if ($cache_data !== FALSE && (($cache_data['cache_expire'] == 0) || ($cache_data['cache_expire'] > $_SERVER['REQUEST_TIME']))) {
            return json_decode($cache_data['cache_data'], true);
        } else {
            return false;
        }
    }

    /**
     * 写缓存。
     * @param string $cache_key 缓存KEY。
     * @param string|array $cache_data 缓存数据。
     * @param number $cache_time 缓存时间。0代表永久生效。
     * @return boolean
     */
    public function set($cache_key, $cache_data, $cache_time = 0) {
        $sql = 'REPLACE INTO ms_cache (cache_key, cache_expire, cache_data) VALUES(:cache_key, :cache_expire, :cache_data)';
        $cache_time = $cache_time <= 0 ? 0 : $_SERVER['REQUEST_TIME'] + $cache_time;
        $cache_data_json = json_encode($cache_data);
        $db_link = $this->client->getDbLink();
        $sth = $db_link->prepare($sql);
        $sth->bindParam(':cache_key', $cache_key, \PDO::PARAM_STR);
        $sth->bindParam(':cache_expire', $cache_time, \PDO::PARAM_INT);
        $sth->bindParam(':cache_data', $cache_data_json, \PDO::PARAM_STR);
        return $sth->execute();
    }

    /**
     * 删除缓存。
     * @param string $cache_key
     * @return boolean
     */
    public function delete($cache_key) {
        $sql = 'DELETE FROM ms_cache WHERE cache_key = :cache_key';
        $db_link = $this->client->getDbLink();
        $sth = $db_link->prepare($sql);
        $sth->bindParam(':cache_key', $cache_key, \PDO::PARAM_STR);
        $sth->execute();
        return true;
    }

}