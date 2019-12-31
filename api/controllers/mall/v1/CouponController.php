<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/22
 * Time: 14:10
 */
namespace api\controllers\mall\v1;
use api\models\V1\AdvertiseDetail;
use api\models\V1\AdvertiseRelated;
use api\models\V1\AttributeDescription;
use api\models\V1\CouponRules;
use api\models\V1\CouponRulesDetail;
use api\models\V1\ProductBase;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;
use \yii\rest\Controller;
use yii\web\BadRequestHttpException;

class CouponController extends Controller {
    public function actionIndex(){
        $status = 1;
        $message = "";
        $data = [];
        $i=0;
        try {
            $item_id=Yii::$app->request->get('item_id');
            $coupon_rules_id=Yii::$app->request->get('coupon_rules_id');
            if(!$coupon_rules_id){
                $coupon_rules_id = 4;
            }
            if($coupon_rules=CouponRules::findOne(['coupon_rules_id'=>$coupon_rules_id])){
                $coupon_info=CouponRulesDetail::find()->where(['coupon_rules_id'=>$coupon_rules->coupon_rules_id])->all();
//                $coupon_info=CouponRulesDetail::find()->where(['coupon_rules_id'=>$coupon_rules->coupon_rules_id])->andWhere(['and',"date_start<='".date('Y-m-d H:i:s',time())."'","date_end>='".date('Y-m-d H:i:s',time())."'",])->orderBy("sort_order asc,advertise_detail_id asc")->all();

//                var_dump($coupon_info);die;
                if($coupon_info){

                    foreach($coupon_info as $ad_detail){
//                       var_dump($ad_detail);die;
                        if(!$ad_detail->img_url){
                            continue;
                        }
                        $i++;
                        if($i>=9){
                            break;
                        }
                        $data[] = [
                            'item_id'=>$ad_detail->coupon_rules_detail_id,
                            'img'=>$ad_detail->img_url,
//                            'name' => $ad_detail->product->description->name,
//                            'meta_description' => $ad_detail->product->description->meta_description,
//                            'image' => Image::resize($ad_detail->product->image,200,200),
//                            'sale_price' => $ad_detail->product->price,
//                            'vip_price' => $ad_detail->product->vip_price,
//                            'cur_price' => $ad_detail->product->getPrice(),
//                            'stock'=>$ad_detail->product->getStockCount(),
//                            'life'=>'',
//                            'url'=>Url::to(['product/index','shop_code'=>$ad_detail->product->store_code,'product_code'=>$ad_detail->product->product_code])//'/'.$ad_detail->product->store_code."-".$ad_detail->product->product_code.".html"
                        ];

                    }
                }
            }else{

            }
        } catch (\Exception $e) {
            $status = 0;
            $message = $e->getMessage();
        }
        $data = ['status' => $status, 'data' => $data, 'message' => $message];
        if (Yii::$app->request->get('callback')) {
            Yii::$app->getResponse()->format = "jsonp";
            return [
                'data' => $data,
                'callback' => \Yii::$app->request->get('callback')
            ];
        } else {
            Yii::$app->getResponse()->format = "json";
            return ['data' => $data];
        }
    }
}