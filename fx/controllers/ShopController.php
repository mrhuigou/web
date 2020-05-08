<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/30
 * Time: 11:29
 */
namespace fx\controllers;
use api\models\V1\CategoryStore;
use api\models\V1\CustomerCollect;
use api\models\V1\Keyword;
use api\models\V1\Promotion;
use api\models\V1\Store;
use api\models\V1\StoreTheme;
use api\models\V1\StoreThemeColumn;
use api\models\V1\StoreThemeColumnInfo;
use common\component\Helper\Helper;
use common\component\SolrProductList\SolrProductListH5;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
class ShopController extends Controller
{
    public function actionIndex(){
        if($model=Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')])){
            if($model->store_id==1){
                return $this->redirect(['/site/index']);
            }else{
                if($model->h5Theme){
                    if($model->h5Theme->themeInfo->theme_code == 'store_h5_ztsj'){
                        return $this->render('index_template',['model'=>$model]);
                    }
                }
                return $this->render('index',['model'=>$model]);
            }
        }else{
            throw new NotFoundHttpException('没有找到店铺');
        }
    }
    public function actionSearch(){

        if($model=Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')])){
            $params=\Yii::$app->request->get();
            $sort_order="desc";
            $sort_selected='score';
            if(in_array(\Yii::$app->request->get('sort'),['record','favourite','price']) && $sort_selected=\Yii::$app->request->get('sort')){
                if($sort_selected==='price') {
                    if(\Yii::$app->request->get('order')=='desc'){
                        $data['data']['sort_order']="asc";
                        $sort_order="asc";
                    }else{
                        $data['data']['sort_order']="desc";
                        $sort_order="desc";
                    }
                }
            }
            $sort_data=[
                'score'=>Url::to(array_merge(['/shop/search'],array_merge($params,['sort'=>'score']))),
                'record'=>Url::to(array_merge(['/shop/search'],array_merge($params,['sort'=>'record']))),
                'favourite'=>Url::to(array_merge(['/shop/search'],array_merge($params,['sort'=>'favourite']))),
                'review'=>Url::to(array_merge(['/shop/search'],array_merge($params,['sort'=>'review']))),
                'price'=>Url::to(array_merge(['/shop/search'],array_merge($params,['sort'=>'price','order'=>$sort_order]))),
            ];
            $data = SolrProductListH5::getProductListH5();

            $hot_keyword=Keyword::find()->where(['status'=>1])->orderBy("weight desc")->all();
            return $this->render('search',[ 'shop'=>$model,
                'keyword'=>$hot_keyword,
                'model'=>$data['query'],'dataProvider'=> $data['dataProvider'],
                'sort_data' => $sort_data, 'sort_order' => $data['sort_order'],'sort_selected'=> $data['sort_selected'],
                'attr_selected' => $data['attr_selected'],
                'brand' => $data['brand'],
                'attr' => $data['attr']
            ]);
        }else{
            throw new NotFoundHttpException('没有找到店铺');
        }

    }
    public function actionCategory(){
        if($model=Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')])){
            $cate=CategoryStore::find()->where(['store_id'=>$model->store_id,'status'=>1])->orderBy('sort_order asc')->asArray()->all();
            if($cate){
            $data=Helper::genTree($cate,'category_store_id','parent_id');
            }else{
            $data=[];
            }
            return $this->render('category',['model'=>$data,'store'=>$model]);
        }else{
            throw new NotFoundHttpException('没有找到店铺');
        }
    }
    public function actionHot(){
        if($store=Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')])){
            $storeTheme=StoreTheme::findOne(['store_id'=>$store->store_id,'theme_id'=>$store->h5_theme_id,'status'=>1]);
            $store_theme_id=$storeTheme?$storeTheme->store_theme_id:0;
            $storeThemeColumn=StoreThemeColumn::findOne(['theme_column_type'=>'PRODUCT','store_theme_id'=>$store_theme_id,'status'=>1]);
            $store_theme_column_id=$storeThemeColumn?$storeThemeColumn->store_theme_column_id:0;
            $model=StoreThemeColumnInfo::find()->where(['store_theme_column_id'=>$store_theme_column_id,'status'=>1]);
            $dataProvider = new ActiveDataProvider([
                'query' => $model->orderBy([ 'sort' => SORT_DESC]),
                'pagination' => [
                    'pagesize' => '4',
                ]
            ]);
            return $this->render('hot',['dataProvider'=>$dataProvider,'model'=>$model,'store'=>$store]);
        }else{
            throw new NotFoundHttpException('没有找到店铺');
        }
    }

    public function actionPromotion(){
        if($model=Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')])){

            $data=Promotion::find()->where(['and',"store_id='".$model->store_id."'",'date_start<NOW()','date_end>NOW()','status=1'])->all();

            return $this->render('promotion',['model'=>$data,'store'=>$model]);
        }else{
            throw new NotFoundHttpException('没有找到店铺');
        }
    }
    public function actionCollect(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $type_id=\Yii::$app->request->post('data-type-id');
        $result=[];
          if($model=CustomerCollect::findOne(['customer_id'=>\Yii::$app->user->getId(),'type_id'=>2,'store_id'=>$type_id])){
            $model->delete();
              $result=['result'=>0];
          }else{
              $model=new CustomerCollect();
              $model->customer_id=\Yii::$app->user->getId();
              $model->store_id=$type_id;
              $store=Store::findOne(['store_id'=>$type_id]);
              $model->store_code=$store?$store->store_code:'';
              $model->type_id=2;
              $model->platform_id=1;
              $model->platform_code='PT0001';
              $model->date_added=date('Y-m-d H:i:s',time());
              $model->save();
              $result=['result'=>1];
          }
        return json_encode($result);
    }
}