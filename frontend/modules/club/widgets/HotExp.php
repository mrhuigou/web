<?php
namespace frontend\modules\club\widgets;
use api\models\V1\ClubExperience;
use yii\bootstrap\Widget;
class HotExp extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $model = ClubExperience::find()->where(['examine_id'=>1])->orderBy('total_click desc')->limit(5)->all();
        if($model){
            return $this->render('hot-exp',['model'=>$model]);
        }
    }
}