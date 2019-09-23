<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace h5\widgets;
use yii\bootstrap\Widget;

class News extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $model=\api\models\V1\News::find()->where(['status'=>1])->orderBy('sort_order asc')->limit(10)->all();
        if($model){
            return $this->render('news',['model'=>$model]);
        }

    }
}