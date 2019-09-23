<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace h5\widgets;
use yii\bootstrap\Widget;
use \api\models\V1\ClubUserInvite;

class Invite extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        if($code=\Yii::$app->request->get('invite_code')){
            if($model=ClubUserInvite::findOne(['code'=>$code])){
                if($model->customer_id!==\Yii::$app->user->getId()){
                    \Yii::$app->session->set('invite_code',$model->code);
                }
            }
        }
    }
}