<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/4/21
 * Time: 23:39
 */
namespace console\controllers\old;
use api\models\V1\Category;
use api\models\V1\CategoryDisplay;
use api\models\V1\CategoryDisplayToCategory;
use api\models\V1\CategoryStore;
use api\models\V1\CategoryStoreToProduct;
use api\models\V1\ProductBase;
use common\models\search\ItemSearch;
use Yii;

class DataController extends \yii\console\Controller {
	public function actionTest(){
		$model=new ItemSearch();
		$model->updateMapping();
	}
	public function actionIndex()
	{
		//$model=ProductBase::find()->where(['manufacturer_id'=>545])->all();
		$model = ProductBase::find()->where('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_modified) <=600')->orderBy("date_modified desc")->all();
		$this->DataInit($model);
	}

	public function actionInit()
	{
		$total = ProductBase::find()->count();
		$count = intval($total / 100);
		echo "Total:" . $total . "-- Page:" . $count . "\n";
		for ($i = 0; $i < $count + 1; $i++) {
			$product_base = ProductBase::find()->limit(100)->offset($i * 100)->orderBy("product_base_id desc")->all();
			echo "----------------Page:" . $i . "-------------------\n";
			$this->DataInit($product_base);
		}
		echo "Update query Finish \r\n";
	}

	private function DataInit($product_base)
	{
		if ($product_base) {
			foreach ($product_base as $value) {
				echo "Doc_id:" . $value->product_base_id . "\r\n";
				if (!$model = ItemSearch::get($value->product_base_id)) {
					$model = new ItemSearch();
					$model->primaryKey = $value->product_base_id;
				}
				$model->id = intval($value->product_base_id);
				$model->code = $value->product_base_code;
				$model->model = $value->product_model;
				$model->item_name = $value->description ? $value->description->name : '';
				$model->brand_id=$value->manufacturer_id;
				$model->brand_code = $value->manufacturer_code;
				//显示分类
				$cat_display_datas = [];
				if ($value->category_id) {
					$cat_displays = CategoryDisplayToCategory::find()->select('category_display_id')->where(['category_id' => $value->category_id])->all();
					if ($cat_displays) {
						foreach ($cat_displays as $cat_display) {
							if ($cat_display->category_display_id) {
								$cat_display_datas = $this->getFrontendCategory($cat_display->category_display_id, $cat_display_datas);
							}
						}
					}
				}
				$model->category = array_values(array_unique($cat_display_datas));
				$model->store_code = $value->store_code;
				//店铺展示分类
				$store_category_datas = [];
				$store_categorys = CategoryStoreToProduct::find()->where(['product_base_id' => $value->product_base_id])->all();
				if ($store_categorys) {
					foreach ($store_categorys as $store_category) {
						$store_category_datas = $this->getStoreCategory($store_category->category_store_code, $store_category_datas);
					}
				}
				$model->store_category = array_values(array_unique($store_category_datas));
				$model->attribute = $value->searchAttibute;
				$model->price = $value->getPrice() ? floatval($value->getPrice()) : 0;
				$model->review = $value->review ? intval($value->review) : 0;
				$model->record = $value->record ? intval($value->record) : 0;
				$model->favourite = $value->favourite?intval($value->favourite):0;
				$model->be_gift=$value->begift?1:0;
				$model->status = $value->online_status ? 1 : 0;
				$model->save();
			}
		}
	}

	public function getBackendCategory($id, $data = [])
	{
		if ($id != 0) {
			$cat = Category::findOne(['category_id' => $id]);
			if ($cat) {
				$data[] = $id;
				return $this->getBackendCategory($cat->parent_id, $data);
			}
		}
		return $data;
	}

	public function getStoreCategory($code, $data = [])
	{
		if ($code != "") {
			$cat = CategoryStore::find()->where(['and','category_store_code="'.$code.'"','parent_code!="'.$code.'"','status=1'])->one();
			if ($cat) {
					$data[] = $code;
					return $this->getStoreCategory($cat->parent_code, $data);
			}
		}
		return $data;
	}

	public function getFrontendCategory($id, $data = [])
	{
		if ($id != 0) {
			$cat = CategoryDisplay::findOne(['category_display_id' => $id]);
			if ($cat) {
				$data[] = $id;
				return $this->getFrontendCategory($cat->parent_id, $data);
			}
		}
		return $data;
	}
}