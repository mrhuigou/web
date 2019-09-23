<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:46
 */
namespace frontend\widgets;
use api\models\V1\CategoryDisplay;

class Header extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }
    public function run(){
        $category_arrays = CategoryDisplay::find()->where(['parent_id'=>'501'])->orderBy('sort_order')->all();

        return $this->render('header',['category_arrays'=> $category_arrays]);
    }
}