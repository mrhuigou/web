<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace h5\widgets;
use yii\bootstrap\Widget;
use yii\web\Cookie;

class Affiliate extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        if($code=\Yii::$app->request->get('affiliate_code')){
            if($model=\api\models\V1\Affiliate::findOne(['code'=>$code])){
                \Yii::$app->session->set('affiliate_id',$model->affiliate_id);
            }
        }
        if(!\Yii::$app->user->isGuest && !\Yii::$app->session->get('affiliate_id') ){
            \Yii::$app->session->set('affiliate_id',\Yii::$app->user->identity->affiliate_id);
        }
    }
}
