<?php
namespace frontend\widgets;
use api\models\V1\CategoryDisplay;

class HeaderStore extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }
    public function run(){
        $category_display = CategoryDisplay::find()->where(['parent_id'=>0,'status'=>1])->all();
        foreach($category_display as $cd){
            //print_r($cd->child);
        }
        return $this->render('header-store');
    }
}