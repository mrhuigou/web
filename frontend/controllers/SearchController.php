<?php
namespace frontend\controllers;
use api\models\V1\AttributeDescription;
use api\models\V1\Manufacturer;
use common\models\search\ItemSearch;
use yii\elasticsearch\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

class SearchController extends \yii\web\Controller {
	public function actionIndex()
	{
		$params = \Yii::$app->request->getQueryParams();
		if (in_array(\Yii::$app->request->get('sort'), ['record', 'favourite', 'price'])) {
			if (\Yii::$app->request->get('order') && \Yii::$app->request->get('order') == 'desc') {
				$sort_order = "asc";
			} else {
				$sort_order = "desc";
			}
			$sort_selected = \Yii::$app->request->get('sort');
		} else {
			$sort_selected = 'score';
			$sort_order = 'desc';
		}
		$sort_data = [
			'score' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'score'])), true),
			'record' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'record'])), true),
			'favourite' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'favourite'])), true),
			'review' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'review'])), true),
			'price' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'price', 'order' => $sort_order])), true),
		];
		$query=[];
		if ($keyword = \Yii::$app->request->get('keyword')) {
			$query[] = [
				"multi_match" => [
					"query" => Html::encode($keyword),
					"fields" => ["item_name", "item_name.cname", "item_name.primitive"],
				]
			];
		}
		if ($cat_id = \Yii::$app->request->get('cat_id')) {
			$query[] =[
				'term'=>['category' => $cat_id]
			];
		}
		if ($shop_code = \Yii::$app->request->get('shop_code')) {
			$query[] =[
				'term'=>['store_code' => $shop_code]
			];
		}
		if ($store_cate_code = \Yii::$app->request->get('store_cate_code')) {
			$query[] =[
				'term'=>['store_category' => $store_cate_code]
			];
		}
		if ($brand_id = \Yii::$app->request->get('brand_id')) {
			$query[] =[
				'term'=>['brand_id' => $brand_id]
			];
		}
		if ($brand_code = \Yii::$app->request->get('brand_code')) {
			$query[] =[
				'term'=>['brand_code' => $brand_code]
			];
		}
		$attr_data = \Yii::$app->request->get('attr') ? \Yii::$app->request->get('attr') : [];
		if ($attr_data) {
			foreach ($attr_data as $key => $value) {
				if($value!==""){
					$query[] =[
						'term'=>['attribute' => $key . "-" . $value]
					];
				}
			}
		}
		$query[] =[
			'term'=>['status' => 1]
		];
		$query[] =[
			'term'=>['be_gift' => 0]
		];
		if(!$query){
			$query[] =[
				'match_all'=>[]
			];
		}
		$model = ItemSearch::find()->query([
			"bool" => [
				'must' => $query
			]
		]);
		$model->andFilterWhere(['model' => ['NORMAL', 'FRESH', 'COMBO']]);
		if (($lowPrice = \Yii::$app->request->get('lowPrice')) && $lowPrice>0) {
			$model->andFilterWhere([">",'price',$lowPrice]);
		}
		if (($highPrice = \Yii::$app->request->get('highPrice')) && $highPrice>0) {
			$model->andFilterWhere(["<",'price',$highPrice]);
		}

		$model->addAggregation('attr', 'terms', [
			'field' => 'attribute',
			'order' => ['_count' => 'desc'],
			'size' => 30,
		]);
		$model->addAggregation('brands', 'terms', [
			'field' => 'brand_code',
			'order' => ['_count' => 'desc'],
			'size' => 20,
		]);
		$model->highlight([
			"pre_tags" => [
				'<h>', '<h>'
			],
			"post_tags" => [
				'</h>', '</h>'
			],
			'fields' => [
				"item_name" => ['fragment_size' => 150, 'number_of_fragments' => 3,
				]
			]
		]);
		if($sort_selected == 'record'){
			$model->orderBy(['record' => SORT_DESC]);
		}
		if($sort_selected == 'favourite'){
			$model->orderBy(['favourite' => SORT_DESC ]);
		}
		if($sort_selected == 'review'){
			$model->orderBy(['review' => SORT_DESC]);
		}
		if($sort_selected == 'price'){
			$model->orderBy(['price' => ['order'=>$sort_order]]);
		}
		$dataProvider = new ActiveDataProvider([
			'query' => $model,
			'pagination' => [
				'pagesize' => 60,
			]
		]);
		$attr_selected=[];
		$brand_attr = [];
		if (($brands = $dataProvider->getAggregation('brands')) && $brands['buckets']) {
			foreach ($brands['buckets'] as $value) {
				if (!$brand = Manufacturer::findOne(['code' => $value['key']])) {
					continue;
				}
				if(isset($params["brand_code"])&&$params["brand_code"]==$brand->code){
					$attr_tem_data=$params;
					unset($attr_tem_data["brand_code"]);
					$attr_selected[]=[
						'name'=>'品牌:'.$brand->name,
						'url'=>Url::to(array_merge(['/search/index'],$attr_tem_data,['page'=>1])),
					];
				}
				$brand_attr[] = [
					'url' => Url::to(array_merge(['/search/index'], array_merge($params, ['brand_code' => $brand->code,'page'=>1])), true),
					'name' => $brand->name,
					'count' => $value['doc_count']
				];
			}
		};
		$attr_datas = [];
		if (($attrs = $dataProvider->getAggregation('attr')) && $attrs['buckets']) {
			foreach ($attrs['buckets'] as $value) {
				$pos = strpos($value['key'], "-");
				$key = substr($value['key'], 0, $pos);
				$name = substr($value['key'], $pos + 1, strlen($value['key']));
				//list($key,$name)=explode('-',$value);
				$attribute = AttributeDescription::findOne(['attribute_code' => $key]);
				$attr_fiter = \Yii::$app->request->get('attr') ? \Yii::$app->request->get('attr') : [];
				if (isset($attr_fiter[$key]) && $attr_fiter[$key] == $name) {
					$attr_tem_data = $attr_data;
					unset($attr_tem_data[$key]);
				}
				$attr_fiter = array_merge($attr_fiter, [$key => $name]);

				if(isset($attr_data[$key])&&$attr_data[$key]==$name){
					$attr_tem_data=$attr_data;
					unset($attr_tem_data[$key]);
					$attr_selected[]=[
						'name'=>$attribute->name.':'.$name,
						'url'=>Url::to(array_merge(['/search/index'],array_merge($params,['attr'=>$attr_tem_data,'page'=>1]))),
					];
				}


				$attr_datas[$attribute->name][]= [
					'name' => $name,
					'value' => $key . '-' . $name,
					'url' => Url::to(array_merge(['/search/index'], array_merge($params, ['attr' => $attr_fiter,'page'=>1]))),
					'count' => $value['doc_count']
				];
			}
			foreach($attr_datas as $key=>$value){
				if(count($attr_datas[$key])<2){
					unset($attr_datas[$key]);
				}
			}
		};

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'sort_data' => $sort_data, 'sort_order' => $sort_order, 'sort_selected' => $sort_selected,
			'attr_selected' => $attr_selected,
			'brand' => $brand_attr,
			'attr' => $attr_datas,
		]);
	}

}
