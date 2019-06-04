<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Common;

use Think\Cache;

/**
 * Description of Redis
 *
 * @author Administrator
 */
class Redis {

    private static $instance;
    private $prefix;
    private $redis;

    private function __construct() {
        $this->prefix = C('SHORT_CODE');
        $this->redis = Cache::getInstance('Redis');
    }

    private function __clone() {
        
    }

    /**
     * Description:静态方法，单例访问统一入口 
     * @return Singleton：返回应用中的唯一对象实例 
     */
    public static function GetInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 处理KEY名称  不存在前缀自动加前缀。
     * @param string $key KEY名称
     */
    public function getKeyName($key) {
        $isprefix = $this->redis->EXISTS($key);
        if ($isprefix === false || $isprefix * 1 == 0) {
            $key = $this->prefix . $key;
        }
        return $key;
    }

    /**
     * 通过key获取数据
     * @param string $key KEY名称
     */
    public function get($key) {
        $key = $this->getKeyName($key);
        $value = $this->redis->get($key);
        $jsonData = json_decode($value, true);
        return ($jsonData === null) ? $value : $jsonData; //检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
    }

    /**
     * 同时获取多个值
     * @param ayyay $keyArray 获key数值
     */
    public function gets($keyArray) {
        if (is_array($keyArray)) {
            foreach ($keyArray as $key => $value) {
                $keyArray[$key] = $this->getKeyName($value);
            }
            return $this->redis->mget($keyArray);
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * 获取所有key名，不是值
     */
    public function getAll($key) {
        if (!is_null($key) && !empty($key)) {
            $redis = $this->redis->keys("*" . $key . "*");
        } else {
            $redis = $this->redis->keys("*");
        }
        return $redis;
    }

    /**
     * 获取所有keys名，不是值
     */
    public function getKeys($key) {
        if (!is_null($key) && !empty($key)) {
            $redis = $this->redis->keys($this->prefix . $key . "*");
        } else {
            return false;
        }
        return $redis;
    }

    /**
     * 获取所有keys名，不是值
     */
    public function getKeysLogin($key) {
        if (!is_null($key) && !empty($key)) {
            $this->redis->select(0);
            $redis = $this->redis->keys($this->prefix."APP_Login*".$key);
        } else {
            return false;
        }
        return $redis;
    }

    /**
     * 设置值  构建一个字符串
     * @param string $key KEY名称
     * @param string $value  设置值
     * @param int $timeOut 时间  0表示无过期时间
     */
    public function set($key, $value, $timeOut = 0) {
        $key = $this->getKeyName($key);
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if (is_int($timeOut) && $timeOut) {
            $result = $this->redis->setex($key, $timeOut, $value);
        } else {
            $result = $this->redis->set($key, $value);
        }
        return $result;
    }

    /**
     * 设置值 构建一个字符串（Key存在将不写入值，不存在则写入值）永不过期。
     * @param string $key KEY名称
     * @param type $value 设置值
     */
    public function setnx($key, $value) {
        $key = $this->getKeyName($key);
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        return $this->redis->setnx($key, $value);
    }
 

    /**
     * 为给定 key 设置生存时间，当 key 过期时(生存时间为 0 )，它会被自动删除。
     * @param type $key  
     * @param type $ttl  时间
     * @return type
     */
    public function expire($key, $ttl) {
        $key = $this->getKeyName($key);
        return $this->redis->expire($key, $ttl);
    }

    /**
     * 获取剩余时间
     * @param type $key
     * @return type
     */
    public function getTTL($key) {
        $key = $this->getKeyName($key);
        return $this->redis->TTL($key);
    }

    /**
     * 获取类型
     * @param type $key
     * @return type
     */
    public function getType($key) {
        $key = $this->getKeyName($key);
        return $this->redis->TYPE($key);
    }

    /**
     * 获取redis key类型
     * @param type $key
     * @return string
     */
    public function getTypeName($key) {
        $key = $this->getKeyName($key);
        $arr = array("none(key不存在)", "string(字符串)", "list(列表)", "set(集合)", "zset(有序集)", "hash(哈希表)");
        $result["type"] = $this->redis->TYPE($key);
        $result["name"] = $arr[$this->redis->TYPE($key)];
        return $result;
    }

    /**
     * 构建一个集合(无序集合)
     * @param string $key 集合Y名称
     * @param string|array $value  值
     */
    public function sadd($key, $value) {
        $key = $this->getKeyName($key);
        if (is_array($value)) {
            array_unshift($value, $key);
            return call_user_func_array(array($this->redis, 'sAdd'), $value);
        } else {
            return $this->redis->sAdd($key, $value);
        }
    }

    /**
     * 从一个无序集合中移除一个值。
     * @param string $key 集合Y名称
     * @param string|array $value  值
     */
    public function srem($key, $value) {
        $key = $this->getKeyName($key);
        if (is_array($value)) {
            array_unshift($value, $key);
            return call_user_func_array(array($this->redis, 'sRem'), $value);
        } else {
            return $this->redis->sRem($key, $value);
        }
    }

    /**
     * 获取一个集合中元素的数量
     * @param string $key 集合名称
     */
    public function scard($key, $value) {
        $key = $this->getKeyName($key);
        return $this->redis->sCard($key);
    }

    /**
     * 判断成员元素是否是集合的成员。 
     * @param type $key
     * @param type $value
     * @return type 如果成员元素是集合的成员，返回 1 。 如果成员元素不是集合的成员，或 key 不存在，返回 0 。 
     */
    public function sismember($key, $value) {
        $key = $this->getKeyName($key);
        return $this->redis->sIsMember($key, $value);
    }

    /**
     * 构建一个集合(有序集合)
     * @param string $key 集合名称
     * @param string|array $value  值
     */
    public function zadd($key, $value) {
        $key = $this->getKeyName($key);
        if (is_array($value)) {
            array_unshift($value, $key);
            return call_user_func_array(array($this->redis, 'zAdd'), $value);
        } else {
            return $this->redis->zAdd($key, $value);
        }
    }

    /**
     * 获取一个有序集合中元素的数量
     * @param string $key 集合名称
     */
    public function zcard($key, $value) {
        $key = $this->getKeyName($key);
        return $this->redis->zCard($key);
    }

    /**
     * 添加一个VALUE到HASH中。如果VALUE已经存在于HASH中，则返回FALSE。
     * @param string $tableName  表名字key
     * @param string $field       字段名
     * @param sting $value          值
     */
    public function hset($tableName, $field, $value) {
        $tableName = $this->getKeyName($tableName);
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        return $this->redis->hSet($tableName, $field, $value);
    }

    /**
     * 取得HASH中的VALUE，如何HASH不存在，或者KEY不存在返回FLASE。
     * @param type $tableName 表名字key
     * @param type $field 字段名
     */
    public function hget($tableName, $field) {
        $tableName = $this->getKeyName($tableName);
        $value = $this->redis->hGet($tableName, $field);
        $jsonData = json_decode($value, true);
        return ($jsonData === null) ? $value : $jsonData;
    }

    /**
     * 取得HASH表的长度。
     * @param type $tableName 表名字key
     */
    public function hlen($tableName) {
        $tableName = $this->getKeyName($tableName);
        return $this->redis->hLen($tableName);
    }

    /**
     * 从HASH表删除指定的元素。
     * @param type $tableName 表名字key
     * @param type $field 字段名
     */
    public function hdel($tableName, $field) {
        $tableName = $this->getKeyName($tableName);
        return $this->redis->hDel($tableName, $field);
    }

    /**
     * 取得HASH表中的KEYS，以数组形式返回。
     * @param type $tableName
     */
    public function hkeys($tableName) {
        $tableName = $this->getKeyName($tableName);
        return $this->redis->hKeys($tableName);
    }

    /**
     * 取得HASH表中所有的VALUE，以数组形式返回。
     * @param type $tableName
     */
    public function hvals($tableName) {
        $tableName = $this->getKeyName($tableName);
        return $this->redis->hVals($tableName);
    }

    /**
     * 取得整个HASH表的信息，返回一个以KEY为索引VALUE为内容的数组。
     * @param type $tableName
     */
    public function hGetAll($tableName) {
        $tableName = $this->getKeyName($tableName);
        return $this->redis->hGetAll($tableName);
    }

    /**
     * 验证HASH表中是否存在指定的KEY-VALUE
     * @param type $tableName 表名字key
     * @param type $field 字段名
     */
    public function hExists($tableName, $field) {
        $tableName = $this->getKeyName($tableName);
        return $this->redis->hExists($tableName, $field);
    }

    /**
     * 构建一个栈(先进后出) 入栈
     * @param sting $key KEY名称
     * @param string $value 值
     */
    public function stackPush($key, $value) {
        $key = $this->getKeyName($key);
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        return $this->redis->lPush($key, $value);
    }

    /**
     * 出栈
     * @param sting $key KEY名称
     */
    public function stackPop($key) {
        $key = $this->getKeyName($key);
        $value = $this->redis->lPop($key);
        $jsonData = json_decode($value, true);
        return ($jsonData === null) ? $value : $jsonData;
    }

    /**
     * 构建一个队列(先进先出) 入队
     * @param sting $key KEY名称
     * @param string $value 值
     * @param string $num 入队次数
     */
    public function queuePush($key, $value, $num = 1) {
        $key = $this->getKeyName($key);
        if (empty($num) || $num * 1 < 1)
            $num = 1;
        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        $result = false;
        for ($i = 0; $i < $num; $i++) {
            $result = $this->redis->rPush($key, $value);
        }
        return $result;
    }

    /**
     * 获取队列指定位置的值，不出队。
     * @param sting $key KEY名称
     */
    public function getQueuePos($key, $pos = 0) {
        $key = $this->getKeyName($key);
        $value = $this->redis->lGet($key, $pos);
        $jsonData = json_decode($value, true);
        return ($jsonData === null) ? $value : $jsonData;
    }

    /**
     * 出队
     * @param sting $key KEY名称
     */
    public function queuePop($key) {
        $key = $this->getKeyName($key);
        $value = $this->redis->lPop($key);
        $jsonData = json_decode($value, true);
        return ($jsonData === null) ? $value : $jsonData;
    }

    /**
     * 获取列表长度（队列和栈）
     * @param type $key KEY名称
     * @return type
     */
    public function listLen($key) {
        $key = $this->getKeyName($key);
        return $this->redis->lLen($key);
    }

    /**
     * 获取所有列表数据
     * @param sting $key KEY名称
     */
    public function lranges($key) {
        $key = $this->getKeyName($key);
        $tail = $this->redis->lLen($key);
        if ($tail * 1 > 0)
            $tail-=1;
        return $this->redis->lrange($key, 0, $tail);
    }

    /**
     * 删除一条数据key
     * @param string $key 删除KEY的名称
     */
    public function del($key) {
        $key = $this->getKeyName($key);
        return $this->redis->delete($key);
    }

    /**
     * 同时删除多个key数据
     * @param array $keyArray KEY集合
     */
    public function dels($keyArray) {
        if (is_array($keyArray)) {
            foreach ($keyArray as $key => $value) {
                $keyArray[$key] = $this->getKeyName($value);
            }
            return $this->redis->del($keyArray);
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key, $value = 0) {
        $key = $this->getKeyName($key);
        if ($value == 0) {
            return $this->redis->incr($key);
        } else {
            return $this->redis->incrByFloat($key, $value);
        }
    }

    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key, $value = 0) {
        $key = $this->getKeyName($key);
        if ($value == 0) {
            return $this->redis->decr($key);
        } else {
            return $this->redis->decrBy($key, $value);
        }
    }

    /**
     * 判断key是否存在
     * @param string $key KEY名称
     */
    public function isExists($key) {
        $key = $this->getKeyName($key);
        return $this->redis->exists($key);
    }

    /**
     * 重命名- 当且仅当newkey不存在时，将key改为newkey ，当newkey存在时候会报错哦RENAME   
     *  和 rename不一样，它是直接更新（存在的值也会直接更新）
     * @param string $Key KEY名称
     * @param string $newKey 新key名称
     */
    public function updateName($key, $newKey) {
        $key = $this->getKeyName($key);
        $newKey = $this->getKeyName($newKey);
        return $this->redis->RENAMENX($key, $newKey);
    }

    /**
     * 获取KEY存储的值类型
     * none(key不存在) int(0)  string(字符串) int(1)   list(列表) int(3)  set(集合) int(2)   zset(有序集) int(4)    hash(哈希表) int(5)
     * @param string $key KEY名称
     */
    public function dataType($key) {
        $key = $this->getKeyName($key);
        return $this->redis->type($key);
    }

    /**
     * 清空数据
     */
    public function flushAll() {
        return $this->redis->flushAll();
    }

    /**
     * 返回redis对象
     * 拿着这个对象就可以直接调用redis自身方法
     * eg:$redis->redisOtherMethods()->keys('*a*') 
     */
    public function redisOtherMethods() {
        return $this->redis;
    }

    public function select($num) {
       return $this->redis->select($num);
    }

}
