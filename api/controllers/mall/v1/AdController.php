<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/22
 * Time: 14:10
 */
namespace api\controllers\mall\v1;
use api\models\V1\AdvertiseDetail;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;
use \yii\rest\Controller;
use yii\web\BadRequestHttpException;

class AdController extends Controller {
	public function actionIndex()
	{
		$status = 1;
		$message = "";
		$data = [];
		try {
			if ($code = \Yii::$app->request->get('code')) {
				/*获取滚动banner*/
				$advertise = new AdvertiseDetail();
				$details = $advertise->getAdvertiserDetailByPositionCode($code);
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
                                'url'=> Url::to($detail->link_url)
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
                            'title' => $detail->title,
                            'image' => Image::resize($detail->source_url, $detail->width, $detail->height),
                            'url'=> Url::to($detail->link_url)
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
			if ($code = \Yii::$app->request->get('code')) {
				/*获取滚动banner*/
				$advertise = new AdvertiseDetail();
				$details = $advertise->getAdvertiserDetailByPositionCode($code);
			} else {
				throw new BadRequestHttpException('错误请求', '1001');
			}
            if($wx_xcx = Yii::$app->request->get('wx_xcx',0)) {
                //$wx_xcx = 1 表示该请求源自小程序客户端
                Yii::$app->session->set('source_from_agent_wx_xcx', true);
            }else{
                Yii::$app->session->remove('source_from_agent_wx_xcx');
            }
			if($details){
			    $count = 0;
				foreach($details as $key=>$ad_detail){
					if(!$ad_detail->product){
						continue;
					}
					$data[$count] = [
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
						'url'=>Url::to(['product/index','shop_code'=>$ad_detail->product->store_code,'product_code'=>$ad_detail->product->product_code]),//'/'.$ad_detail->product->store_code."-".$ad_detail->product->product_code.".html"
					];
                    $count ++ ;
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

}