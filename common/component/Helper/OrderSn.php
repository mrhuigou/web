<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/5/6
 * Time: 16:36
 */
namespace common\component\Helper;
class OrderSn
{

    public static function generateNumber()
    {
        $orderSn = date('ymd'). substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }


}