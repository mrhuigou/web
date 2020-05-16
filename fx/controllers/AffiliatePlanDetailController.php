<?php
namespace fx\controllers;

use api\models\V1\AffiliatePlan;
use api\models\V1\AffiliatePlanDetail;
use api\models\V1\Page;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class AffiliatePlanDetailController extends Controller
{
    public function actionIndex(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
        }

        if(!$plan_id = Yii::$app->request->get("plan_id")){
            $position = Yii::$app->request->get("position");
            if($affiliatePlan=AffiliatePlan::find()->where(['and',"position='".$position."'",'date_start<=NOW()','date_end>=NOW()','status=1'])->one()){
                $plan_id = $affiliatePlan->affiliate_plan_id;
            }
        }
        $plan = AffiliatePlan::findOne($plan_id);

        if(!empty($plan)){
            return $this->render("index",['plan'=>$plan]);
        }else{
            throw new NotFoundHttpException("没有找到该页面！");
        }
    }

    public function actionDefaultProduct(){
        $status = 1;
        $plan_type = "DEFAULT";
        $plan_status = \Yii::$app->request->post('plan_status');
        $data = [];
        if(!$plan_status){
            //获取正在进行的方案
            $affiliatePlan=AffiliatePlan::find()->where(['and',"type='".$plan_type."'",'date_start<=NOW()','date_end>=NOW()','status=1'])->one();
        }else{
            $affiliatePlan=AffiliatePlan::find()->where(['and',"type='".$plan_type."'",'date_start>=NOW()','date_end>=NOW()','status=1'])->one();
        }

        if($affiliatePlan){
            //获取所有的商品
            $details =  AffiliatePlanDetail::find()->where(['status' => 1, 'affiliate_plan_id' => $affiliatePlan->affiliate_plan_id])->orderBy('priority asc')->all();
        }

        if($details){
            $count = 0;
            foreach($details as $key=>$detail){

                //测试-------------------------------------
                $ad_detail_product = $detail->product;
                if(!$ad_detail_product){
                    continue;
                }
                //对商品图进行处理
                $imagelist = '';
                $images = $detail->product->productBase->imagelist;
                if($images){
                    foreach ($images as $value_image){
                        if(empty($imagelist)){
                            $imagelist = $value_image;
                        }
                    }
                }

                $data[$count] = [
                    'product_code'=>$detail->product_code,
                    'affiliate_plan_id'=>$detail->affiliate_plan_id,
                    'count'=>$detail->getQty($detail->affiliate_plan_id),
                    'title'=>$detail->title,
                    'item_id'=>$ad_detail_product->product_base_id,
                    'item_code'=>$ad_detail_product->product_base_code,
                    'name' => $ad_detail_product->description->name,
                    'meta_description' => $detail->title,
                    'image' => Image::resize(($detail->image_url?:$imagelist)?:'',320,320),
                    'sale_price' => $detail->price,
                    'vip_price' => $ad_detail_product->vip_price,
                    'cur_price' => $detail->getPrice(),
                    'stock'=>$ad_detail_product->getStockCount(0,$detail->affiliate_plan_id),
                    'beintoinv'=>$ad_detail_product->beintoinv,//判断是否下架 1上架
                    'url'=>Url::to(['product/index','shop_code'=>$ad_detail_product->store_code,'product_code'=>$ad_detail_product->product_code,'affiliate_plan_id'=>$detail->affiliate_plan_id]),
                ];
                $count ++ ;

            }
        }
        $data = ['status' => $status, 'data' => $data,];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
}