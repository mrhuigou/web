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
//				if($code == "H5-0F-AD"){//首页爆品
//                    $advertise_detail = AdvertiseDetail::find()->where(["and", "date_start<'" . date('Y-m-d H:i:s') . "'", "date_end>'" . date('Y-m-d H:i:s') . "'", 'status=1'])->andWhere(['advertise_position_code' => $code])->orderBy("sort_order ASC, position_priority ASC")->all();
//                    $details = '[
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""},
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""}
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""}
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""}
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""}
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""}
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""}
//                        {"title":"01","source_url":"group1/M00/06/A2/wKgB7l4AW3KAC56WAABlWzmn_b4235.jpg","link_url":"https://m.mrhuigou.com/DP0001-508140.html","width":"","height":""}
//                        ]';
//                    $details = json_decode($details);
//                }else{
                    $details = $advertise->getAdvertiserDetailByPositionCode($code);
//                }


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
                            'title' => $detail->title,
                            'image' => Image::resize(($detail->source_url?:$detail->product->image)?:$detail->product->productBase->image, $detail->width, $detail->height),
//                            'url'=> Url::to($detail->link_url),
                            'url'=> $detail->link_url ? Url::to($detail->link_url):(Url::to(['product/index','shop_code'=>$detail->store_code,'product_code'=>$detail->product_code]))

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

					//过滤 已经下架的商品
//                    if($ad_detail->product->beintoinv != 1){
//					    continue;
//                    }

                    //------------------------促销方案描述---------------------
                    $promotion_detail_title = '';
                    $promotion_detail_image = '';
                    if($ad_detail->product->promotions){
                        foreach ($ad_detail->product->promotions as $promotion){
                            $promotion_detail_title = '[促]'.$promotion->promotion_detail_title;
                            $promotion_detail_image = $promotion->promotion_detail_image;
                        }
                    }
                    //------------------------促销方案描述---------------------
                    //------------------------优惠券描述---------------------
                    $coupon_title = '';
                    if($ad_detail->product->productBase->coupon){
                        foreach ($ad_detail->product->productBase->coupon as $coupon){
                            $coupon_title = '[券]'.$coupon->comment;
                        }
                    }
                    //------------------------优惠券描述---------------------

					$data[$count] = [
						'item_id'=>$ad_detail->product->product_base_id,
						'item_code'=>$ad_detail->product->product_base_code,
						'name' => $ad_detail->product->description->name,
						'meta_description' => $promotion_detail_title.$coupon_title.$ad_detail->product->description->meta_description,
//						'image' => Image::resize(($ad_detail->source_url ?: $promotion_detail_image )? :$ad_detail->product->image,320,320),
						'image' => Image::resize($promotion_detail_image ? :$ad_detail->product->image,320,320),
						'ad_image' => Image::resize($ad_detail->source_url ?: $promotion_detail_image),
                        'sale_price' => $ad_detail->product->price,
						'vip_price' => $ad_detail->product->vip_price,
						'cur_price' => $ad_detail->product->getPrice(),
						'stock'=>$ad_detail->product->getStockCount(),
						'beintoinv'=>$ad_detail->product->beintoinv,//判断是否下架 1上架
                        'product_date'=>$ad_detail->product->productBase->bedisplaylife && $ad_detail->product->productDate ? $ad_detail->product->productDate:"",
                        'life'=>$ad_detail->product->productBase->bedisplaylife?$ad_detail->product->productBase->life:" ",
						'url'=>$ad_detail->link_url ?:(Url::to(['product/index','shop_code'=>$ad_detail->product->store_code,'product_code'=>$ad_detail->product->product_code])),//'/'.$ad_detail->product->store_code."-".$ad_detail->product->product_code.".html"
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