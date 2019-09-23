<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 15:07
 */
namespace api\controllers\mall\v1;
use api\models\V1\Category;
use api\models\V1\ProductBase;
use common\component\image\Image;
use Yii;
use yii\helpers\Url;
use \yii\rest\Controller;
use yii\web\BadRequestHttpException;
class CategoryController extends Controller {
	public function actionIndex(){
		$status = 1;
		$message = "";
		$data = [];
		try {
			if ($code = \Yii::$app->request->get('code')) {
				$code_arr=explode(",",$code);
				$sub_query=Category::find()->select('category_id')->where(['like','code',$code_arr]);
				$details=ProductBase::find()->where(['category_id'=>$sub_query,'beintoinv'=>1])->limit(50)->all();
			} else {
				throw new BadRequestHttpException('错误请求', '1001');
			}
			if($details){
				foreach($details as $key=>$value){
					if(!$value->getOnline_status()){
						continue;
					}
					$data[$key] = [
						'item_id'=>$value->product_base_id,
						'item_code'=>$value->product_base_code,
						'name' => $value->description->name,
						'meta_description' => $value->description->meta_description,
						'image' => Image::resize($value->getDefaultImage(),200,200),
						'sale_price' => $value->getSale_price(),
						'vip_price' => $value->getVip_price(),
						'cur_price' => $value->getVip_price(),
						'stock'=>$value->getStockCount(),
						'life'=>$value->getProductDate(),
						'url'=>Url::to(['product/index','shop_code'=>$value->store_code,'product_code'=>$value->product_base_code])//'/'.$value->store_code."-".$value->product_base_code.".html"
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

}