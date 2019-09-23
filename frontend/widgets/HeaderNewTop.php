<?php
namespace frontend\widgets;
class HeaderNewTop extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }

    public function run(){

        return $this->render('header-new-top');

    }
}