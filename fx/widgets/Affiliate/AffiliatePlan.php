<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace fx\widgets\Affiliate;
use api\models\V1\AffiliatePlanDetail;
use api\models\V1\AffiliatePlanType;
use yii\bootstrap\Widget;

class AffiliatePlan extends Widget{
    public $plan_type_code = 'DEFAULT';
    public $products = [];
    public $plan_code;
    public function init()
    {
        //获取分销方案code编码
        if(!$this->plan_code){
            if ($model = AffiliatePlanType::findOne(['code' => $this->plan_type_code, 'status' => 1])) {
                $plans = \api\models\V1\AffiliatePlan::find()->where(['type'=>$model->code,'status'=>1])->andWhere(['and','date_start < NOW()','date_end > NOW()'])->orderBy('date_start asc,date_end desc,affiliate_plan_id desc')->all();
                if(!empty($plans)){
                    $plan_code = $plans[0]->code;
                    //正在进行的方案
                    $affiliate_plan = \api\models\V1\AffiliatePlan::find()->where(['status' => 1,'code'=>$plan_code])->andWhere(['<', 'date_start', date('Y-m-d H:i:s')])->andWhere(['>', 'date_end', date('Y-m-d H:i:s')])->one();
                    if ($affiliate_plan) {
                        if ($model = AffiliatePlanType::findOne(['code' => $affiliate_plan->type, 'status' => 1])) {
                            $this->products = AffiliatePlanDetail::find()->where(['status' => 1, 'affiliate_plan_id' => $affiliate_plan->affiliate_plan_id])->orderBy('priority asc')->all();
                        }
                    }
                }
            }
        }
        parent::init();
    }
    public function run()
    {
        return $this->render('affiliate-plan',['products'=>$this->products]);
    }
}