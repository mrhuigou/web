<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace frontend\modules\club\widgets;
use yii\bootstrap\Widget;
class Menu extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render('menu');
    }
}