<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:46
 */
namespace frontend\widgets;
use api\models\V1\CategoryDisplay;

class HeaderNew extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }
    public function run(){
        return $this->render('header-new');
    }
}