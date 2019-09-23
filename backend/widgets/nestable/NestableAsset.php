<?php

/**
 * @copyright Copyright &copy; Arno Slatius 2015
 * @package yii2-nestable
 * @version 1.0
 */

namespace backend\widgets\nestable;
use yii\web\AssetBundle;
/**
 * Nestable bundle for \slatiusa\nestable\Sortable
 *
 * @author Arno Slatius <a.slatius@gmail.com>
 * @since 1.0
 */
class NestableAsset extends AssetBundle {
    public $css=[
        'jquery.nestable.css'
    ];
    public $js = [
        'jquery.nestable.js',
    ];
    public $depends = ['backend\assets\AppAsset'];
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }

}