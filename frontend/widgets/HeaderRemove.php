<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:46
 */
namespace frontend\widgets;

class HeaderRemove extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }
    public function run(){
        return $this->render('header-remove');
    }
}