<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/14
 * Time: 14:48
 */
namespace backend\widgets;
use api\models\V1\Node;
use common\component\Helper\Helper;
use yii\bootstrap\Widget;
class MainMenu extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render('MainMenu');
    }
}