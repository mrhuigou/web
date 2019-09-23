<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace h5\widgets;
use yii\bootstrap\Widget;
use yii\helpers\Url;

class Source extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        if(\Yii::$app->request->get('s_sid')){
            if(\Yii::$app->request->get('s_sid')=="1"){
                \Yii::$app->session->set('source_from',"DP0268");
                \Yii::$app->session->set('home_url',Url::to(['/shop/index','shop_code'=>'DP0268','s_sid'=>1]));
            }
        }else{
            \Yii::$app->session->set('source_from',"H5");
        }
    }
}
