<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets;
use api\models\V1\Keyword;

class SearchBar extends \yii\bootstrap\Widget{
    public function init()
    {
        parent::init();
    }

    public function run(){
        $model=Keyword::find()->where(['status'=>1])->orderBy('weight desc')->all();
        return $this->render('search-bar',['keyword'=>$model]);

    }
}