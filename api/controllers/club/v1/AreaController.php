<?php

namespace api\controllers\club\v1;

use api\models\V1\District;
use api\models\V1\Zone;
use common\models\City;

class AreaController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionGetallzone(){
        $results = Zone::find()->asArray()->all();

        return $results;
    }
    public function actionGetallcity(){
        $zone_id = \Yii::$app->request->post("zone_id");
        $results = City::find()->where(['zone_id'=>$zone_id])->asArray()->all();
        return $results;
    }
    public function actionGetalldistrict(){
        $city_id = \Yii::$app->request->post("city_id");
        $results = City::find()->where(['city_id'=>$city_id])->asArray()->all();
        $results = District::find()->asArray()->all();
        return $results;
    }
}
