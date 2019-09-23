<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/14
 * Time: 14:48
 */
namespace backend\widgets;
use yii\bootstrap\Widget;
class Customizer extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render('Customizer');
    }
}