<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace affiliate\assets;

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
        'assets/plugins/font-awesome/css/font-awesome.min.css',
        'assets/plugins/uniform/css/uniform.default.css',
    ];
    public $js = [
        'assets/plugins/jquery-migrate-1.2.1.min.js',
        'assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
        'assets/plugins/bootstrap/js/bootstrap.min.js',
        'assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js',
        'assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'assets/plugins/jquery.blockui.min.js',
        'assets/plugins/jquery.cokie.min.js',
        'assets/plugins/uniform/jquery.uniform.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
