<?php
namespace common\component\Redis;
use Yii;
/**
 * 基于redis的分布式锁
 *
 */


class DistKey {

    //锁的超时时间
    const TIMEOUT = 20;

    const SLEEP = 100000;

    /*
     * 当前锁的过期时间
     * @var int
     */
    protected static $expire;

    public static function getRedis()
    {
        return  Yii::$app->redis;
    }

    /**
     * 获得锁，如果锁被占用，阻塞，直到获得锁或者超时
     *
     * 如果$timeout参数为0，则立即返回锁。
     *
     * @param  string    $key
     * @param  int        $timeout    过期时间
     * @return boolean    成功，true；失败，false
     */
    public static function lock($key, $timeout = null){
        if(!$key)
        {
            return false;
        }

        $start = time();

        $redis = self::getRedis();

        do{
            self::$expire = self::timeout();

            if($acquired = ($redis->setnx("Lock:{$key}", self::$expire)))
            {
                break;
            }

            if($acquired = (self::recover($key)))
            {
                break;
            }
            if($timeout === 0)
            {
                //如果超时时间为0，即为没有超时时间
                break;
            }

            usleep(self::SLEEP); //微秒

        } while(!is_numeric($timeout) || time() < $start + $timeout);

        if(!$acquired)
        {
            //超时
            return false;
        }

        return true;
    }

    /**
     * 释放锁
     */
    public static function release($key){
        if(!$key)
        {
            return false;
        }

        $redis = self::getRedis();

        // Only release the lock if it hasn't expired
        if(self::$expire > time())
        {
            $redis->del("Lock:{$key}");
        }
    }


    protected static function timeout(){
        return (int) (time() + self::TIMEOUT + 1);
    }

    /**
     * @return bool   该锁是否可用
     */
    protected static function recover($key){

        $redis = self::getRedis();

        if(($lockTimeout = $redis->get("Lock:{$key}")) > time())
        {
            //锁还没有过期
            return false;
        }

        $timeout = self::timeout();
        $currentTimeout = $redis->getset("Lock:{$key}", $timeout);

        if($currentTimeout != $lockTimeout)
        {
            return false;
        }

        self::$expire = $timeout;
        return true;
    }
}

?>