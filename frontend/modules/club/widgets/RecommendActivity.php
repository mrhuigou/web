<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace frontend\modules\club\widgets;
use api\models\V1\ClubActivity;
use yii\bootstrap\Widget;
class RecommendActivity extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $model=ClubActivity::find()->where(['is_recommend'=>1,'status'=>1])->orderBy('creat_at desc')->limit(3)->all();
        if($model){
            return $this->render('recommend-activity',['model'=>$model]);
        }
    }
}