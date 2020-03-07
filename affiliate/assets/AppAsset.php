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
//        'assets/stylesheet/mobile.min.css?v=20200219',
        'assets/stylesheet/swiper.min.css?v=20170724',
        'assets/script/layer.m/need/layer.css?v=20171216'
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

        'assets/script/swiper.jquery.min.js?v=20170613',
        'assets/script/jquery-extend.js',
        'assets/script/template7.js',
        'assets/script/infinite.js',
        'assets/script/popup.js',
        'assets/script/toast.js',
        'assets/script/modal.js',
        'assets/script/notification.js',
        'assets/script/jquery_mask.js',
        'assets/script/jquery.followit.js?v=20170101',
        'assets/script/jquery.tabslet.min.js?v=20170101',
        'assets/script/jquery.scrollLoading.js?v=20170101',
        'assets/script/ipop.js?v=20170101',
        'assets/script/page.js?v=20200307',
        'assets/script/layer.m/layer.m.js?v=20170101',
        'assets/script/scrollfix.js?v=20170101',
        'assets/script/jquery.nav.js?v=20170101',
        'assets/script/jquery.cookie.js?v=20170101',
        'assets/js/template.js?v=20180129',
        'assets/script/breakingnews.js?v=170401',
        'assets/script/TT.js?v=20170829',
        'assets/fonts/iconfont.js',
        'assets/js/trace.js?v=20170615',
        'assets/js/ad.js?v=20170926',
        'assets/js/qrcode.js?v=20170613'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
