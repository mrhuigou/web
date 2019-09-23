<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\assets;
use yii\web\AssetBundle;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'assets/stylesheets/global.css',
//        'assets/stylesheets/layout.css',
//        'assets/stylesheets/modules.css',
//        'assets/stylesheets/page.css',
//        'assets/stylesheets/detail.css',
//        'assets/stylesheets/suggest.css'
    ];
    public $js = [
//        'assets/script/jquery-migrate-1.2.1.js',
//        'http://g.tbcdn.cn/kissy/k/1.3.2/seed.js',
//        'assets/script/jquery.SuperSlide.2.1.1.js',
//        'assets/script/jquery.lazyload.min.js',
//        'assets/script/page.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
