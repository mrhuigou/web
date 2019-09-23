<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/23
 * Time: 12:03
 */

namespace frontend\controllers;
use api\models\V1\AttributeDescription;
use api\models\V1\CategoryDisplay;
use api\models\V1\Manufacturer;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use common\component\image\Image;
use common\component\solr\SolrDataProvider;
use common\component\SolrProductList\SolrProduct;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class CategoryController  extends Controller {
    public function actionIndex()
    {
        if( \Yii::$app->request->get("per-page")){
            $per_page =  \Yii::$app->request->get("per-page");
            if($per_page > 60){
                $per_page = 60;
            }
            if($per_page < 1){
                $per_page = 30;
            }
        }else{
            $per_page =  30;
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

        $data = [];
        $data = SolrProduct::getProductList();
        $highlighting = $data['resultset']->getHighlighting();
        $list=[];
        $pages = new Pagination(['totalCount' =>$data['resultset']->getNumFound(), 'pageSize' => $per_page,'route'=>'/category/index']);
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
                    'price'=>$product_base->price,
                    'image'=>$product_base->defaultImage,
                    'sub_image'=> $this->getProductMainSku($product_base->product_base_id),
                ];
            }


        }

        $data['data']['products']=$list;

        return $this->render('index', [
            'data' => $data,
            'pages' =>$pages
        ]);
    }

    public function getProductMainSku($id){
        $product=Product::find()->where(['product_base_id'=>$id,'beintoinv'=>1])->all();
        if($product){
            return $product;
        }else{
            return ;
        }


    }



    public function bindActionParams($action, $params){
        return $params;
    }
}
