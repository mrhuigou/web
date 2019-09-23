<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/11/21
 * Time: 14:29
 */
namespace console\controllers\old;
use api\models\V1\Category;
use api\models\V1\CategoryDisplay;
use api\models\V1\CategoryDisplayToCategory;
use api\models\V1\CategoryStore;
use api\models\V1\CategoryStoreToProduct;
use api\models\V1\ProductBase;
use common\component\OpenSearch\CloudsearchClient;
use common\component\OpenSearch\CloudsearchDoc;
use common\component\OpenSearch\CloudsearchSearch;

class OpenController extends \yii\console\Controller {
	public function actionIndex()
	{
		$access_key = \Yii::$app->params['open_search']['access_key'];
		$secret = \Yii::$app->params['open_search']['secret'];
		$host = "http://opensearch-cn-qingdao.aliyuncs.com";//根据自己的应用区域选择API
		$key_type = "aliyun";  //固定值，不必修改
		$opts = ['host' => $host];
		$client = new CloudsearchClient($access_key, $secret, $opts, $key_type);
		$doc_obj = new CloudsearchDoc("jiarun_item_db", $client);
		$model=ProductBase::find();
		$total=$model->count();
		$page=intval($total/500);
		echo "Total:".$total."-- Page:".$page."\n";
		for($i=0;$i<$page+1;$i++) {
			echo "Total:".$total."-- Page:".$i."\n";
			$items=$model->limit(500)->offset($i * 500)->orderBy("date_modified desc")->all();
			if($items){
				$docs_to_upload = [];
				foreach ($items as $value) {
					if($value->description){
						$product_name=$value->description->name;
					}else{
						$product_name="";
					}
					//显示分类
					$cat_display_datas=[];
					if($value->category_id){
						$category_ids=$this->getDefaultCategoryList($value->category_id);
						$category_ids=array_unique($category_ids,SORT_NUMERIC);
						$cat_displays=CategoryDisplayToCategory::find()->where(['category_id'=>$category_ids])->groupBy("category_display_id")->all();
						if($cat_displays){
							foreach($cat_displays as $cat_display){
								if($cat_display->category_display_id){
									$cat_display_datas=$this->getCategoryList($cat_display->category_display_id,$cat_display_datas);
								}
							}
						}
					}
					$cat_display_datas=array_values(array_unique($cat_display_datas,SORT_NUMERIC));
					//店铺展示分类
					$store_category_datas=[];
					$store_categorys=CategoryStoreToProduct::find()->where(['product_base_id'=>$value->product_base_id])->groupBy("category_store_id")->all();
					if($store_categorys){
						foreach($store_categorys as $store_category){
							$store_category_datas=$this->getStoreCategoryList($store_category->category_store_code,$store_category_datas);
						}
					}
					$store_category_datas=array_values(array_unique($store_category_datas));
                    $stock_count = $value->stockCount;//所有包装库存之和 不表示确切的库存， 仅表示当前是否有库存
                    if($stock_count > 0){
                        $stock_status = 1;
                    }else{
                        $stock_status = 0;
                    }
					$item = [];
					//指定文档操作类型为：添加
					$item['cmd'] = 'ADD';
					//添加文档内容
					$item["fields"] = [
						"id" => $value->product_base_id,
						"product_name" => $product_name,
						"product_code" => $value->product_base_code,
						'product_model'=>$value->product_model,
						'store_code'=>$value->store_code,
						'brand_id'=>$value->manufacturer_id,
						'brand_code'=>$value->manufacturer_code,
						'price'=>$value->price,
						'record'=>$value->record?intval($value->record):0,
						'favourite'=>$value->favourite?intval($value->favourite):0,
						'review'=>$value->review?intval($value->review):0,
						'be_gift'=>$value->begift?intval($value->begift):0,
						'status'=>$value->online_status?1:0,
						'attribute'=>$value->SearchAttibute,
						'category'=>$cat_display_datas,
						'store_category'=>$store_category_datas,
                        'stock_count'=>$stock_status
					];
					$docs_to_upload[] = $item;
				}
				//生成json格式字符串
				$json = json_encode($docs_to_upload);
				// 将文档推送到main表中
				echo $doc_obj->add($json, "item")."\r\n";
			}
		}
	}
	public function getCategoryList($id,$data=[]){
		if($id!==0){
			$cat= CategoryDisplay::findOne(['category_display_id'=>$id]);
			if($cat && !in_array($id,$data)){
				$data[]=$id;
				return $this->getCategoryList($cat->parent_id,$data);
			}
		}
		return $data;
	}

	public function getDefaultCategoryList($id,$data=[]){
		if($id!==0){
			$cat= Category::findOne(['category_id'=>$id]);
			if($cat && !in_array($id,$data)){
				$data[]=$id;
				return $this->getDefaultCategoryList($cat->parent_id,$data);
			}
		}
		return $data;
	}
	public function getStoreCategoryList($code,$data=[]){
		if($code!=""){
			$cat= CategoryStore::findOne(['category_store_code'=>$code]);
			if($cat && !in_array($code,$data)){
				$data[]=$code;
				return $this->getStoreCategoryList($cat->parent_code,$data);
			}
		}
		return $data;
	}


	public function actionUpdate()
	{
		$access_key = \Yii::$app->params['open_search']['access_key'];
		$secret = \Yii::$app->params['open_search']['secret'];
		$host = "http://opensearch-cn-qingdao.aliyuncs.com";//根据自己的应用区域选择API
		$key_type = "aliyun";  //固定值，不必修改
		$opts = ['host' => $host];
		$client = new CloudsearchClient($access_key, $secret, $opts, $key_type);
		$doc_obj = new CloudsearchDoc("jiarun_item_db", $client);
		$model=ProductBase::find()->where('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_modified) <=60')->orderBy("date_modified desc");
		$total=$model->count();
		$page=intval($total/100);
		echo "Total:".$total."-- Page:".$page."\n";
		for($i=0;$i<$page+1;$i++) {
			echo "Total:".$total."-- Page:".$i."\n";
			$items=$model->limit(100)->offset($i * 100)->orderBy("date_modified desc")->all();
			if($items){
				$docs_to_upload = [];
				foreach ($items as $value) {
					if($value->description){
						$product_name=$value->description->name;
					}else{
						$product_name="";
					}
					//显示分类
					$cat_display_datas=[];
					if($value->category_id){
						$category_ids=$this->getDefaultCategoryList($value->category_id);
						$category_ids=array_unique($category_ids,SORT_NUMERIC);
						$cat_displays=CategoryDisplayToCategory::find()->where(['category_id'=>$category_ids])->groupBy("category_display_id")->all();
						if($cat_displays){
							foreach($cat_displays as $cat_display){
								if($cat_display->category_display_id){
									$cat_display_datas=$this->getCategoryList($cat_display->category_display_id,$cat_display_datas);
								}
							}
						}
					}
					$cat_display_datas=array_values(array_unique($cat_display_datas,SORT_NUMERIC));
					//店铺展示分类
					$store_category_datas=[];
					$store_categorys=CategoryStoreToProduct::find()->where(['product_base_id'=>$value->product_base_id])->groupBy("category_store_id")->all();
					if($store_categorys){
						foreach($store_categorys as $store_category){
							$store_category_datas=$this->getStoreCategoryList($store_category->category_store_code,$store_category_datas);
						}
					}
					$store_category_datas=array_values(array_unique($store_category_datas));
                    $stock_count = $value->stockCount;//所有包装库存之和 不表示确切的库存， 仅表示当前是否有库存
                    if($stock_count > 0){
                        $stock_status = 1;
                    }else{
                        $stock_status = 0;
                    }
					$item = [];
					//指定文档操作类型为：添加
					$item['cmd'] = 'ADD';
					//添加文档内容
					$item["fields"] = [
						"id" => $value->product_base_id,
						"product_name" => $product_name,
						"product_code" => $value->product_base_code,
						'product_model'=>$value->product_model,
						'store_code'=>$value->store_code,
						'brand_id'=>$value->manufacturer_id,
						'brand_code'=>$value->manufacturer_code,
						'price'=>$value->price,
						'record'=>$value->record?intval($value->record):0,
						'favourite'=>$value->favourite?intval($value->favourite):0,
						'review'=>$value->review?intval($value->review):0,
						'be_gift'=>$value->begift?intval($value->begift):0,
						'status'=>$value->online_status?1:0,
						'attribute'=>$value->SearchAttibute,
						'category'=>$cat_display_datas,
						'store_category'=>$store_category_datas,
                        'stock_count'=>$stock_status
					];
					$docs_to_upload[] = $item;
				}
				//生成json格式字符串
				$json = json_encode($docs_to_upload);
				// 将文档推送到main表中
				echo $doc_obj->add($json, "item")."\r\n";
			}
		}
	}
	private function getStockCount($product_base_id){

    }

}