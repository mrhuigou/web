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
class WeekdayActivity extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $model=ClubActivity::find()->where(['is_recommend'=>0,'status'=>1])->orderBy('click_count desc')->limit(5)->all();
        if($model){
            return $this->render('weekday-activity',['model'=>$model]);
        }
    }
}