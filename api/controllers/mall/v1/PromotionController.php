<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/7/22
 * Time: 14:10
 */
namespace api\controllers\mall\v1;
use api\models\V1\Product;
use api\models\V1\Promotion;
use api\models\V1\PromotionDetail;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;
use \yii\rest\Controller;
use yii\web\BadRequestHttpException;

class PromotionController extends Controller {
	public function actionIndex()
	{
		$status = 1;
		$message = "";
		$data = [];
		try {
			if ($code = \Yii::$app->request->get('code')) {
				$promotion=Promotion::findOne(['code'=>trim($code)]);
				$details = PromotionDetail::find()->where(['and', "promotion_id='" .($promotion?$promotion->promotion_id:0)."'", "begin_date<='" . date('Y-m-d H:i:s', time()) . "'", "end_date>='" . date('Y-m-d H:i:s', time()) . "'", 'status=1'])->orderBy('priority desc,promotion_detail_id asc')->all();
			} else {
				throw new BadRequestHttpException('错误请求', '1001');
			}
            if($wx_xcx = Yii::$app->request->get('wx_xcx',0)) {
                //$wx_xcx = 1 表示该请求源自小程序客户端
                Yii::$app->session->set('source_from_agent_wx_xcx', true);
            }else{
                Yii::$app->session->remove('source_from_agent_wx_xcx');
            }
			if ($details) {
				foreach ($details as $key => $detail) {
					$data[$key] = [
						'item_id'=>$detail->product->product_base_id,
						'name' => $detail->product->description->name,
						'meta_description' => $detail->product->description->meta_description,
						'image' => Image::resize($detail->product->image, 500, 500),
						'sale_price' => $detail->product->price,
						'vip_price' => $detail->product->vip_price,
						'cur_price' => $detail->getCurPrice(),
						'stock'=>$detail->product->getStockCount(),
						'product_date'=>$detail->product->productBase->bedisplaylife && $detail->product->productDate  ? $detail->product->productDate:"",
						'life'=>$detail->product->productBase->bedisplaylife?$detail->product->productBase->life:" ",
						'url'=>Url::to(['product/index','shop_code'=>$detail->product->store_code,'product_code'=>$detail->product->product_code])//'/'.$detail->product->store_code."-".$detail->product->product_code.".html"
					];
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
	public function actionSubject()
	{
		$status = 1;
		$message = "";
		$data = [];
		try {
			if ($subject = \Yii::$app->request->get('subject')) {
				$promotion=Promotion::find()->where(['and',"subject='".strtoupper($subject)."'",'date_start<=NOW()','date_end>=NOW()','status=1'])->one();
				$details = PromotionDetail::find()->where(['and', "promotion_id='" .($promotion?$promotion->promotion_id:0)."'", "begin_date<='" . date('Y-m-d H:i:s', time()) . "'", "end_date>='" . date('Y-m-d H:i:s', time()) . "'", 'status=1'])->orderBy('priority asc,promotion_detail_id asc')->all();
			} else {
				throw new BadRequestHttpException('错误请求', '1001');
			}
            if($wx_xcx = Yii::$app->request->get('wx_xcx',0)) {
                //$wx_xcx = 1 表示该请求源自小程序客户端
                Yii::$app->session->set('source_from_agent_wx_xcx', true);
            }else{
                Yii::$app->session->remove('source_from_agent_wx_xcx');
            }
			if ($details) {
				foreach ($details as $key => $detail) {
				    $stock = $detail->product->getStockCount();
				    if($stock > 0){
                        $promotion_detail_image = '';
                        if($detail->product->promotions){
//                            foreach ($detail->product->promotions as $key => &$promotion){
//                                $promotion_detail_image = $promotion->promotion_detail_image;
//                            }
                            $promotion_detail_image = $detail->product->promotions[0]->promotion_detail_image;
                        }
                        //------------------------促销方案描述---------------------


                        $data[] = [
                            'item_id'=>$detail->product->product_base_id,
                            'name' => $detail->product->description->name,
                            'meta_description' => $detail->product->description->meta_description,
                            'image' => $promotion_detail_image?Image::resize($promotion_detail_image, 500, 500): Image::resize($detail->product->image, 500, 500),
//                            'image' => Image::resize($detail->product->image, 500, 500),
                            'sale_price' => $detail->product->price,
                            'vip_price' => $detail->product->vip_price,
                            'cur_price' => $detail->getCurPrice(),
                            'stock'=>$stock,
                            'product_date'=>$detail->product->productBase->bedisplaylife && $detail->product->productDate  ?$detail->product->productDate:"",
                            'life'=>$detail->product->productBase->bedisplaylife?$detail->product->productBase->life:" ",
                            'url'=>Url::to(['product/index','shop_code'=>$detail->product->store_code,'product_code'=>$detail->product->product_code]),//'/'.$detail->product->store_code."-".$detail->product->product_code.".html"
                        ];
                    }

				}
			}
		} catch (\Exception $e) {
			$status = 0;
			$message = $e->getMessage();
		}
		$data = ['status' => $status, 'data' => $data, 'promotion'=>['title'=>$promotion->name,'timestamp'=>strtotime($promotion->date_end)-time()],'message' => $message];
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
	public function actionProduct()
	{
		$status = 1;
		$message = "";
		$data = [];
		try {
			if ($product_code = Yii::$app->request->get('product_code')) {
				$details=Product::findAll(['product_code'=>$product_code]);
			} else {
				throw new BadRequestHttpException('错误请求', '1001');
			}
            if($wx_xcx = Yii::$app->request->get('wx_xcx',0)) {
                //$wx_xcx = 1 表示该请求源自小程序客户端
                Yii::$app->session->set('source_from_agent_wx_xcx', true);
            }else{
                Yii::$app->session->remove('source_from_agent_wx_xcx');
            }
			if ($details) {
				foreach ($details as $key => $detail) {

                    //过滤 已经下架的商品
//                    if($detail->beintoinv != 1){
//                        continue;
//                    }

				    //------------------------促销方案描述---------------------
                    $promotion_detail_title = '';
                    $promotion_detail_image = '';
				    if($detail->Promotions){
				        foreach ($detail->Promotions as $promotion){
                            $promotion_detail_title = '[促]'.$promotion->promotion_detail_title;
                            $promotion_detail_image = $promotion->promotion_detail_image;
                        }
                    }
                    //------------------------促销方案描述---------------------
                    //------------------------优惠券描述---------------------
                    $coupon_title = '';
				    if($detail->productBase->coupon){
				        foreach ($detail->productBase->coupon as $coupon){
                                $coupon_title = '[券]'.$coupon->comment;
                        }
                    }
                    //------------------------优惠券描述---------------------

					$data[$key] = [
						'item_id'=>$detail->product_base_id,
						'product_code'=>$detail->product_code,
						'name' => $detail->description->name,
						'meta_description' => $promotion_detail_title.$coupon_title.$detail->description->meta_description,
						'image' => $promotion_detail_image?Image::resize($promotion_detail_image, 500, 500):($detail->image?Image::resize($detail->image, 500, 500):Image::resize($detail->productBase->image, 500, 500)),
						'sale_price' => $detail->price,
						'vip_price' => $detail->vip_price,
						'cur_price' => $detail->getPrice(),
						'stock'=>$detail->getStockCount(),
						'product_date'=>$detail->productBase->bedisplaylife && $detail->productDate ? $detail->productDate:"",
						'life'=>$detail->productBase->bedisplaylife?$detail->productBase->life:" ",
						//'url'=>'/'.$detail->store_code."-".$detail->product_code.".html"
                        'url'=> Url::to(['product/index','shop_code'=>$detail->store_code,'product_code'=>$detail->product_code]),
					];
				}
				# 交换键和值
				$sortRule = array_flip($product_code);
				# 自定义排序
				usort($data,function($preRow,$nextRow) use($sortRule){
					$preRuleVal = isset($sortRule[$preRow['product_code']])?$sortRule[$preRow['product_code']]:0;
					$nextRuleVal = isset($sortRule[$nextRow['product_code']])?$sortRule[$nextRow['product_code']]:0;
					return $preRuleVal - $nextRuleVal;
				});
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