<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\modules\club\assets;

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
      'assets/stylesheets/global.css',
      'assets/stylesheets/layout2.css',
      'assets/stylesheets/modules.css',
      'assets/stylesheets/icommon.css',
      'assets/stylesheets/page.css',
      'assets/stylesheets/jquery-ui-1.11.4.css',
      'assets/stylesheets/life.css'
    ];
    public $js = [
    //  'assets/script/jquery-migrate-1.2.1.js',
      'assets/script/jquery.SuperSlide.2.1.1.js',
      'assets/script/jquery.lazyload.min.js',
      'assets/script/jquery.qrcode.min.js',
      'assets/script/ipop.js',
      'assets/script/page.js',
      'assets/script/jquery-ui-1.11.4.js',
      'assets/script/jquery.scrollLoading.js',
      'assets/script/layer/layer.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
