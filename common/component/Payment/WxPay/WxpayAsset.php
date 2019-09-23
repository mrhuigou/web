<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/2
 * Time: 15:56
 */
namespace common\component\Payment\WxPay;


use yii\web\AssetBundle;

class WxpayAsset extends AssetBundle
{
    public $js = [
        'js/qrcode.js',
    ];

    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}