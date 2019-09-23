<?php

namespace common\extensions\widgets\fileapi;

use yii\web\AssetBundle;

/**
 * Multiple upload asset bundle.
 */
class MultipleAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
	public $sourcePath = '@common/extensions/widgets/fileapi/assets';

    /**
     * @inheritdoc
     */
	public $css = [
	    'css/multiple.css'
	];

    /**
     * @inheritdoc
     */
	public $depends = [
		'common\extensions\widgets\fileapi\Asset'
	];
}
