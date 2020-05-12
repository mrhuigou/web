<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/22
 * Time: 14:10
 */
namespace api\controllers\mall\v1;
use api\models\V1\AdvertiseDetail;
use api\models\V1\AffiliatePlan;
use api\models\V1\AffiliatePlanDetail;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;
use \yii\rest\Controller;
use yii\web\BadRequestHttpException;

class AffiliatePlanController extends Controller {

    public function actionIndex()
    {
        $status = 1;
        $message = "";
        $data = [];
        try {
            if ($code = \Yii::$app->request->get('code')) {
                /*获取滚动banner*/
                $affiliatePlan = new AffiliatePlan();

                $details = $affiliatePlan->getAffiliatePlanDetailByPositionCode($code);

            } else {
                throw new BadRequestHttpException('错误请求', '1001');
            }
            if($wx_xcx = Yii::$app->request->get('wx_xcx',0)){
                //$wx_xcx = 1 表示该请求源自小程序客户端
                Yii::$app->session->set('source_from_agent_wx_xcx',true);
                if ($details) {
                    $count = 0;
                    foreach ($details as $key => $detail) {
                        if(!strpos($detail->link_url,'site/go-to')){
                            //$data 的键值必须0，1,2，3,4如此递增
                            $data[$count] = [
                                'title' => $detail->title,
                                'image' => Image::resize($detail->source_url, $detail->width, $detail->height),
//                                'url'=> Url::to($detail->link_url)
                                'url'=> $detail->link_url ? Url::to($detail->link_url):(Url::to(['product/index','shop_code'=>$detail->store_code,'product_code'=>$detail->product_code]))
                            ];
                            $count++;
                        }
                    }
                }
            }else{
                Yii::$app->session->remove('source_from_agent_wx_xcx');
                if ($details) {
                    foreach ($details as $key => $detail) {
                        //$data 的键值必须0，1,2，3,4如此递增
                        $data[$key] = [
                            'title' => $detail->name,
                            'image' => Image::resize($detail->source_url),
                            'url'=> Url::to(['affiliate-plan-detail/index','plan_id'=>$detail->affiliate_plan_id])
                        ];

                    }
                }
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


    public function actionProduct(){
        $status = 1;
        $message = "";
        $data = [];
        try {
            $code = \Yii::$app->request->get('code');
            if(!$code){//通过位置编码获取 正在进行的方案code
                $position = \Yii::$app->request->get('position');
                if($affiliatePlan=AffiliatePlan::find()->where(['and',"position='".$position."'",'date_start<=NOW()','date_end>=NOW()','status=1'])->one()){
                    $code = $affiliatePlan->code;
                }
            }
            if ($code) {//方案code
                $plan = AffiliatePlan::findOne(['code'=>$code]);
                if($plan->status && $plan->planType->status){
                    //获取所有的商品
                    $details =  AffiliatePlanDetail::find()->where(['status' => 1, 'affiliate_plan_id' => $plan->affiliate_plan_id])->orderBy('priority asc')->all();
                }
            } else {
                throw new BadRequestHttpException('错误请求', '1001');
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
        } catch (\Exception $e) {
            $status = 0;
            $message = $e->getMessage();
        }
        $data = ['status' => $status, 'data' => $data, 'promotion'=>['title'=>$plan->name,'timestamp'=>strtotime($plan->date_end)-time()], 'message' => $message];
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