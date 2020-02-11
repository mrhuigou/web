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
use api\models\V1\ProductBase;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;
use \yii\rest\Controller;
use yii\web\BadRequestHttpException;

class ProductController extends Controller {
	public function actionIndex(){
		$status = 1;
		$message = "";
		$data = [];
		$i=0;
		try {
			$item_id=Yii::$app->request->get('item_id');
//			$advertise = new AdvertiseDetail();
//			$details = $advertise->getAdvertiserDetailByPositionCode('DE-XQTY-DES1');
//			if($details){
//				foreach($details as $ad_detail){
//					if(!$ad_detail->product){
//						continue;
//					}
//
//					$data[] = [
//						'item_id'=>$ad_detail->product->product_base_id,
//						'item_code'=>$ad_detail->product->product_base_code,
//						'name' => $ad_detail->product->description->name,
//						'meta_description' => $ad_detail->product->description->meta_description,
//						'image' => Image::resize($ad_detail->product->image,200,200),
//						'sale_price' => $ad_detail->product->price,
//						'vip_price' => $ad_detail->product->vip_price,
//						'cur_price' => $ad_detail->product->getPrice(),
//						'stock'=>$ad_detail->product->getStockCount(),
//						'life'=>'',
//						'url'=>'/'.$ad_detail->product->store_code."-".$ad_detail->product->product_code.".html"
//					];
//				}
//			}else{
				if($Ads=AdvertiseRelated::findOne(['product_base_id'=>$item_id,'status'=>1])){
					if($Ads->advertise && $Ads->advertise->status){
						$ad_details=AdvertiseDetail::find()->where(['advertise_id'=>$Ads->advertise->advertise_id,'status'=>1])->andWhere(['and',"date_start<='".date('Y-m-d H:i:s',time())."'","date_end>='".date('Y-m-d H:i:s',time())."'",])->orderBy("sort_order asc,advertise_detail_id asc")->all();
						if($ad_details){
							foreach($ad_details as $ad_detail){
								if(!$ad_detail->product){
									continue;
								}
								$i++;
								if($i>=9){
									break;
								}
								$data[] = [
									'item_id'=>$ad_detail->product->product_base_id,
									'item_code'=>$ad_detail->product->product_base_code,
									'name' => $ad_detail->product->description->name,
									'meta_description' => $ad_detail->product->description->meta_description,
									'image' => Image::resize($ad_detail->product->image,200,200),
									'sale_price' => $ad_detail->product->price,
									'vip_price' => $ad_detail->product->vip_price,
									'cur_price' => $ad_detail->product->getPrice(),
									'stock'=>$ad_detail->product->getStockCount(),
									'life'=>'',
									'url'=>Url::to(['product/index','shop_code'=>$ad_detail->product->store_code,'product_code'=>$ad_detail->product->product_code])//'/'.$ad_detail->product->store_code."-".$ad_detail->product->product_code.".html"
								];

							}
						}
					}
				}
				if( ($model=ProductBase::findOne(['product_base_id'=>$item_id]))){
                    if($model){
                        if($model->manufacturer_id == 0  && $model->category_id ==0){

                        }else{
//                            $relation=ProductBase::find()->where(['or',"manufacturer_id='".$model->manufacturer_id."'","category_id='".$model->category_id."'"])->andWhere("product_base_id<>'".$model->manufacturer_id."'")->all();
                            $relation=ProductBase::find()->where(['manufacturer_id'=>$model->manufacturer_id,'category_id'=>$model->category_id])->andWhere("product_base_id<>'".$model->manufacturer_id."'")->all();
                            if($relation){
                                foreach ($relation as $detail){
                                    if(!$detail->getOnline_status()){
                                        continue;
                                    }
                                    if(!$detail->getStockCount()){
                                        continue;
                                    }
                                    $i++;
                                    if($i>=9){
                                        break;
                                    }
                                    $data[] = [
                                        'item_id'=>$detail->product_base_id,
                                        'item_code'=>$detail->product_base_code,
                                        'name' => $detail->description->name,
                                        'meta_description' => $detail->description->meta_description,
                                        'image' => Image::resize($detail->getDefaultImage(),200,200),
                                        'sale_price' => $detail->sale_price,
                                        'vip_price' => $detail->vip_price,
                                        'cur_price' => $detail->getPrice(),
                                        'stock'=>$detail->getStockCount(),
                                        'life'=>$detail->life,
                                        'url'=>Url::to(['product/index','shop_code'=>$detail->store_code,'product_code'=>$detail->product_base_code])//'/'.$detail->store_code."-".$detail->product_base_code.".html"

                                    ];
                                }
                            }
                        }
                    }
				}
			//}
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