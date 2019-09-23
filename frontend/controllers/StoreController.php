<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/30
 * Time: 11:29
 */
namespace frontend\controllers;
use api\models\V1\CategoryStore;
use api\models\V1\CustomerCollect;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use api\models\V1\Promotion;
use api\models\V1\Review;
use api\models\V1\Store;
use api\models\V1\StoreTheme;
use api\models\V1\StoreThemeColumn;
use api\models\V1\StoreThemeColumnInfo;
use common\component\Helper\Helper;
use common\component\SolrProductList\SolrProduct;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
class StoreController extends Controller
{
    public $layout = 'main-store';
    public function actionStore(){
        return $this->actionIndex();
    }
    public function actionIndex(){
        $model = "";
        if(\Yii::$app->request->get('store_id')){
            $store = Store::findOne(['store_id'=>\Yii::$app->request->get('store_id')]);
            if(!empty($store)){
                $model = $store;
            }
        }
        if(\Yii::$app->request->get('shop_code')){
            $store = Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')]);
            if(!empty($store)){
                $model = $store;
            }
        }
        if(!empty($model)){
            if($model->store_id==1){
                return $this->redirect(['/site/index']);
            }
            if($model->online == 1){
                $shop_code = \Yii::$app->request->get('shop_code');

                $review_score = array();
                $review_score['delivery'] = Review::find()->where(['store_code'=>$shop_code])->average('delivery');
                $review_score['rating']  = Review::find()->where(['store_code'=>$shop_code])->average('rating');
                $review_score['service']  = Review::find()->where(['store_code'=>$shop_code])->average('service');



                $cate=CategoryStore::find()->where(['store_id'=>$model->store_id,'status'=>1])->orderBy('sort_order asc')->asArray()->all();
                if($cate){
                    $store_category = Helper::genTree($cate,'category_store_id','parent_id');
                }else{
                    $store_category=[];
                }
                $theme_color_code = 'greenbox';
                //if(empty($model->theme->themeInfo->theme_color_code)){
                //    $theme_color_code = 'greenBox';
                //}else{
                //    $theme_color_code = $model->theme->themeInfo->theme_color_code;
                //}

                $this->getView()->registerCssFile("/page/shop-temp/".$theme_color_code."/css/view.css",['depends'=>[\api\assets\AppAsset::className()]]);
                $this->getView()->registerCssFile("/page/shop-temp/".$theme_color_code."/css/foucsbox.css",['depends'=>[\api\assets\AppAsset::className()]]);
                $this->getView()->registerCssFile("/page/shop-temp/".$theme_color_code."/css/layout.css",['depends'=>[\api\assets\AppAsset::className()]]);

                $productbases = ProductBase::find()->where(['store_code'=>$shop_code,'beintoinv'=>1])->limit(20)->all();
                $list = array();
                // print_r($productbases);
                foreach ($productbases as $productbase) {


                    if(!empty($productbase)){
                        $list[]=[
                            'product_name'=>$productbase->description->name,
                            'text_name' =>$productbase->description->name,
                            'store_code'=>$productbase->store_code,
                            'product_code'=>$productbase->product_base_code,
                            'price'=>$productbase->price,
                            'image'=>$productbase->image,
                            'sub_image'=>$this->getProductMainSku($productbase->product_base_id)
                        ];
                    }


                }

                $header = $this->renderPartial("header",['model'=>$model,'review_score'=>$review_score,'theme_color_code'=>$theme_color_code]);

                return $this->render('index',['model'=>$model,'header'=>$header,'review_score'=>$review_score,'store_category'=>$store_category,'productbase'=>$list,'theme_color_code'=>$theme_color_code]);
            }else{
                return $this->redirect('/site/index');
            }

        }else{
            throw new NotFoundHttpException('没有找到店铺');
        }

    }
    public function getProductMainSku($id){
        $product=Product::find()->where(['product_base_id'=>$id,'beintoinv'=>1])->all();
        if($product){
            return $product;
        }else{
            return ;
        }


    }
    //店铺列表页
    public function actionSearch(){
        if( \Yii::$app->request->get("per-page")){
            $per_page =  \Yii::$app->request->get("per-page");
            if($per_page > 60){
                $per_page = 60;
            }
            if($per_page < 1){
                $per_page = 28;
            }
        }else{
            $per_page =  28;
        }
        if( \Yii::$app->request->get("page")){

            $page =  \Yii::$app->request->get("page");
            if($page < 1){
                $page = 1;
            }
            if($page > 1000000){
                $page = 1;
            }
        }else{
            $page =  1;
        }
        $solr_filter_data = array(
            'per_page' => $per_page
        );
        $data = [];
        $list=[];
        if($model=Store::findOne(['store_code'=>\Yii::$app->request->get('shop_code')])) {
            $theme_color_code = 'greenbox';
//            if(empty($model->theme->themeInfo->theme_color_code)){
//                $theme_color_code = 'greenbox';
//            }else{
//                $theme_color_code = $model->theme->themeInfo->theme_color_code;
//            }
            $this->getView()->registerCssFile("/page/shop-temp/".$theme_color_code."/css/layout.css",['depends'=>[\api\assets\AppAsset::className()]]);
            $this->getView()->registerCssFile("/page/shop-temp/".$theme_color_code."/css/view.css",['depends'=>[\api\assets\AppAsset::className()]]);
            $this->getView()->registerCssFile("/page/shop-temp/".$theme_color_code."/css/foucsbox.css",['depends'=>[\api\assets\AppAsset::className()]]);

            $cate=CategoryStore::find()->where(['store_id'=>$model->store_id,'status'=>1])->orderBy('sort_order asc')->asArray()->all();
            if($cate){
                $store_category = Helper::genTree($cate,'category_store_id','parent_id');
            }else{
                $store_category=[];
            }

            if ($model->online == 1) {
                $shop_code = \Yii::$app->request->get('shop_code');
                $review_score = array();

                $review_score['delivery'] = Review::find()->where(['store_code'=>$shop_code])->average('delivery');
                $review_score['rating']  = Review::find()->where(['store_code'=>$shop_code])->average('rating');
                $review_score['service']  = Review::find()->where(['store_code'=>$shop_code])->average('service');

                $data = SolrProduct::getProductList($solr_filter_data);
                $pages = new Pagination(['totalCount' =>$data['resultset']->getNumFound(), 'pageSize' => $per_page,'route'=>'/store/search']);
                $highlighting = $data['resultset']->getHighlighting();
                foreach ($data['resultset'] as $document) {
                    $highlightedDoc = $highlighting->getResult($document->id);
                    $product_name=$document->product_name;
                    $text_name = $document->product_name;
                    if($highlightedDoc){
                        foreach ($highlightedDoc as $field => $highlight) {
                            $product_name= implode('', $highlight);
                        }
                    }
                    $product_base=ProductBase::findOne(['product_base_id'=>$document->id]);
                    if(!empty($product_base)){
                        $list[]=[
                            'product_name'=>$product_name,
                            'text_name' =>$text_name,
                            'store_code'=>$document->store_code,
                            'product_code'=>$document->product_code,
                            'price'=>$document->price,
                            'image'=>$product_base->image,
                            'sub_image'=> $this->getProductMainSku($product_base->product_base_id),
                        ];
                    }

                }
            }else{
                return $this->redirect('site/index');
            }
        }

        $header = $this->renderPartial("header",['model'=>$model,'review_score'=>$review_score,'theme_color_code'=>$theme_color_code]);
        return $this->render('search',['model'=>$model,'header'=>$header,'review_score'=>$review_score,'productbase'=>$list,'store_category'=>$store_category,'theme_color_code'=>$theme_color_code,'pages'=>$pages]);

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