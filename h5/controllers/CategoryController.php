<?php

namespace h5\controllers;

use api\models\V1\Advertise;
use api\models\V1\AdvertiseDetail;
use api\models\V1\CategoryDisplay;
use api\models\V1\CategoryDisplayToCategory;
use api\models\V1\ProductBase;
use yii\web\NotFoundHttpException;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {   $tree_data=[];
        $model=CategoryDisplay::find()->where(['status'=>1,'is_delete'=>0,'parent_id'=>790])->orderBy("sort_order asc,category_display_id asc")->all();
        if($model){
            $category=[];
            foreach($model as $value){
                $tree_data[]=[
                    'id'=>$value->category_display_id,
                    'name'=>$value->description->name,
                    'pid'=>$value->parent_id,
                    'image'=>$value->description->image,
                    'sort_order'=>$value->sort_order
                ];
            }
            //$tree_data=Helper::genTree($category);
        }

        return $this->render('index',['model'=>$tree_data]);
    }
    public function actionChildren(){
        $cate_id=\Yii::$app->request->get('cate_id');
        if($model=CategoryDisplay::find()->where(['parent_id'=>$cate_id,'status'=>1])->orderBy('sort_order')->all()){
            $parent=CategoryDisplay::findOne($cate_id);
            return $this->renderAjax('children',['model'=>$model,'parent'=>$parent]);
        }

    }
    public function actionMeatVegetable(){
        $content = "";
        $parent = CategoryDisplay::findOne(['category_display_code'=>'vegetable-meat']);
        if($parent){
            //底部文本广告，链接到外部
            $advertise = new AdvertiseDetail();
            $focus_position = 'H5-2LSC-TJYM';
            $ad_text = $advertise->getAdvertiserDetailByPositionCode($focus_position);

            $ad_img = $advertise->getAdvertiserDetailByPositionCode('H5-2LSC-AD');

            //顶部文字广告
            $top_advertises = Advertise::find()->where(['advertise_position_code'=>'H5-2LSC-JBTJ','status'=>1])->all();

            //中间分类
            $category_displays = CategoryDisplay::find()->where(['parent_id'=>$parent->category_display_id,'is_delete'=>0,'status'=>1])->orderBy('sort_order ,category_display_id')->all();
            if($category_display_id=\Yii::$app->request->get('cate_id')){
                $current = 'cate_id_'.$category_display_id;
                $content = $this->getChildrenProducts($category_display_id);
            }else{
                if($advertise_id = \Yii::$app->request->get('advertise_id')){
                    $current = 'adv_id_'.$advertise_id;
                    $content = $this->getChildrenProductsAdvertise($advertise_id);
                }else{
                    //取默认
                    $advertise_id = $top_advertises[0]->advertise_id;
                    $current = 'adv_id_'.$advertise_id;
                    $content = $this->getChildrenProductsAdvertise($advertise_id);
                }
            }
            return $this->render('meat-vagetable',['category_displays'=>$category_displays,'ad_text'=>$ad_text,'top_advertises'=>$top_advertises,'content'=>$content,'current'=>$current,'ad_img'=>$ad_img]);
        }else{
            throw  new NotFoundHttpException();
        }
    }
    private function getChildrenProducts($category_display_id){

        $product_bases = $this->getListProductBase($category_display_id);
        return $this->renderPartial('children-products',['product_bases'=>$product_bases]);
    }
    public function getChildrenProductsAdvertise($advertise_id){

        $advertise = Advertise::findOne(['advertise_id'=>$advertise_id,'status'=>1]);
        $advertise_detail = $advertise->getAvailableDetails();
        $advertise_details = $advertise_detail->all();
        //print_r($advertise_details->all());exit;
        $products = [];
        if($advertise_details){
            foreach ($advertise_details as $advertise_detail){
                $products[] = $advertise_detail->product;
            }
        }
        //print_r($products);exit;
        return $this->renderPartial('children-products-advertise',['products'=>$products]);
    }
    private function getListProductBase($category_display_id){
        if($category_display_id){
            $display_to_categorys = CategoryDisplayToCategory::find()->where(['category_display_id'=>$category_display_id ])->all();
            $category_ids = [];
            if($display_to_categorys){
                foreach ($display_to_categorys as $display_to_category){
                    $category_ids[] = $display_to_category->category_id;
                }
            }
//            $subQuery = new \yii\db\Query();
//            $subQuery->select('product_base_id')->from('jr_product_base_to_category')->where(['category_id'=>$category_ids]);
            $product_base_list = ProductBase::find()->where(['category_id'=>$category_ids])->all();
            return $product_base_list;

        }

    }
}
