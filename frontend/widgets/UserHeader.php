<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets;
class UserHeader extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }

    public function run(){

        return $this->render('user-header');

    }
}