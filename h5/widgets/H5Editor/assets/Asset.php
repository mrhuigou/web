<?php
/**
 * Class Asset
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:35
 */

namespace h5\widgets\H5Editor\assets;


use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@h5/widgets/H5Editor/assets';

    /**
     * @inheritdoc
     */
    public $css = [
       'css/H5Editor.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/h5editor.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
