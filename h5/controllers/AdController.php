<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/29
 * Time: 10:13
 */
namespace h5\controllers;
use api\models\V1\AdvertiseDetail;
use api\models\V1\AdvertiseToCategory;
use api\models\V1\Category;
use api\models\V1\Order;
use api\models\V1\ProductBase;
use yii\base\ErrorException;

class AdController extends \yii\web\Controller {
	public function actionAdCode($code="")
	{
		try {
			if(\Yii::$app->session->get('ad_pop_flag')){
				throw new ErrorException("没有任何数据");
			}
			$advertise = new AdvertiseDetail();
			if($code){
				$focus_position = [$code];
			}else{
				$focus_position = ['H5-TC-DES1'];
			}
			if(\Yii::$app->request->get("nocache")){
                $no_cache = \Yii::$app->request->get("nocache");
            }else{
                $no_cache = 0;
            }
			if($res= $advertise->getAdvertiserDetailByPositionCode($focus_position)) {
                foreach (array_slice($res, 0, 1) as $value) {
                    $ad_flag_key = 'Ad_flag_' . md5(serialize($focus_position));
                    if($no_cache){
                        $data = [
                            'status' => 1,
                            'image' => \common\component\image\Image::resize($value->source_url),
                            'href' => \yii\helpers\Url::to($value->link_url, true),
                        ];
                    }else{
                        if ( (time() - intval(\Yii::$app->session->get($ad_flag_key, 0))) > 3600) {
                            $data = [
                                'status' => 1,
                                'image' => \common\component\image\Image::resize($value->source_url),
                                'href' => \yii\helpers\Url::to($value->link_url, true),
                            ];
                            \Yii::$app->session->set($ad_flag_key, time());
                        } else {
                            throw new ErrorException("没有任何数据");
                        }
                    }

                }
            }else{
				throw new ErrorException("没有任何数据");
			}
		} catch (ErrorException $e) {
		$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	public function actionBaseCode($code="")
	{
		try {
			if(\Yii::$app->session->get('ad_pop_flag')){
				throw new ErrorException("没有任何数据");
			}
			$sub_query=ProductBase::find()->select("category_id")->where(['product_base_code'=>$code]);
			$Query = new \yii\db\Query();
			$Query->select(["c.`code`","c.parentcode","(SELECT tmp.parentcode from jr_category tmp where tmp.`code`=c.parentcode) as rootcode"])->from(['c'=>Category::tableName()])->where(['category_id'=>$sub_query]);
			$in_arr=[];
			if($code_arr=$Query->one()){
				foreach ($code_arr as $value){
					$in_arr[]=$value;
				}
			}else{
				throw new ErrorException("没有任何数据");
			}
			$model=AdvertiseToCategory::find()->where(['category_code'=>$in_arr,'status'=>1])->orderBy('advertise_position_code desc')->one();
			if($model && $model->advertise_position_code){
				$focus_position = [$model->advertise_position_code];
			}else{
				$focus_position = ['H5-TC-DES1'];
			}
			$advertise = new AdvertiseDetail();
			if($res= $advertise->getAdvertiserDetailByPositionCode($focus_position)){
				foreach (array_slice($res,0,1) as $value){
					$ad_flag_key='Ad_flag_'.md5(serialize($focus_position));
					if((time()-intval(\Yii::$app->session->get($ad_flag_key,0)))>3600){
						$data=[
							'status'=>1,
							'image'=>\common\component\image\Image::resize($value->source_url),
							'href'=>\yii\helpers\Url::to($value->link_url, true),
						];
						\Yii::$app->session->set($ad_flag_key,time());
					}else{
						throw new ErrorException("没有任何数据");
					}
				}
			}else{
				throw new ErrorException("没有任何数据");
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
}