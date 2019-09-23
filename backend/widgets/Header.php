<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace backend\widgets;
use yii\bootstrap\Widget;
class Header extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render('Header');
    }
}
