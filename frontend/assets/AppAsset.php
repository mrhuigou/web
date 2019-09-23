<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

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
      'assets/stylesheets/global.css?v=201700926',
      'assets/stylesheets/layout.css?v=201700926',
      'assets/stylesheets/modules.css?v=201700926',
      'assets/stylesheets/icommon.css?v=20170926',
      'assets/stylesheets/page.css?v=201700927'
    ];
    public $js = [
      'assets/script/jquery-migrate-1.2.1.js?v=201600926',
      'https://s.tbcdn.cn/??s/kissy/1.3.0/seed-min.js',
      'assets/script/jquery.SuperSlide.2.1.1.js?v=201600926',
      'assets/script/jquery.lazyload.min.js?v=201600926',
      'assets/script/ipop.js?v=201600926',
      'assets/script/page.js?v=201600926',
      'assets/script/jquery.cityselect.js?v=201600926',
        'assets/script/smartFloat.js?v=201600926',
       'assets/script/ipage.js?v=201600926',
      'assets/script/jquery.scrollLoading.js?v=201600926',
      'assets/script/layer/layer.js?v=201600926',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
