<?php

namespace common\extensions\widgets\fileapi;

use yii\web\AssetBundle;

/**
 * Single upload asset bundle.
 */
class SingleAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
	public $sourcePath = '@common/extensions/widgets/fileapi/assets';

    /**
     * @inheritdoc
     */
	public $css = [
	    'css/single.css'
	];

    /**
     * @inheritdoc
     */
	public $depends = [
		'common\extensions\widgets\fileapi\Asset'
	];
}
