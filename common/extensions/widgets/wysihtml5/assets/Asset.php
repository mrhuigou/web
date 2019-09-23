<?php
/**
 * Class Asset
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:35
 */

namespace common\extensions\widgets\wysihtml5\assets;


use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@common/extensions/widgets/wysihtml5/assets';

    /**
     * @inheritdoc
     */
    public $css = [
       'css/simditor.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        // 'js/wysihtml5x-toolbar.min.js',
        // 'js/handlebars.runtime.min.js',
        // 'js/bootstrap3-wysihtml5.min.js',
        'js/wysihtml-toolbar.min.js',
        'js/advanced_and_extended.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
