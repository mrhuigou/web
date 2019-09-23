<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/20
 * Time: 10:29
 */

namespace console\controllers\old;
use api\models\V1\Category;
use api\models\V1\CategoryDisplay;
use api\models\V1\CategoryDisplayToCategory;
use api\models\V1\CategoryStore;
use api\models\V1\CategoryStoreToProduct;
use api\models\V1\ProductBase;
use api\models\V1\ProductBaseAttribute;

class SolrController extends \yii\console\Controller
{
    public function actionIndex(){
        $product_base=ProductBase::find()->where('UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(date_modified) <=600')->orderBy("date_modified desc")->all();
        //$product_base=ProductBase::find()->where(['product_base_code'=>'511687'])->orderBy("date_modified desc")->all();
            if($product_base){
                $client=\Yii::$app->solr;
                $update = $client->createUpdate();
                // create a new document for the data
                $docs=[];
                foreach($product_base as $value){
                    echo "Doc_id:".$value->product_base_id."\r\n";
                    $doc = $update->createDocument();
                    $doc->id = $value->product_base_id;
                    $doc->product_code=$value->product_base_code;
                    if($value->description){
                        $product_name=$value->description->name;
                    }else{
                        $product_name="";
                    }
                    $doc->product_name =$product_name;
                    $doc->product_model =$value->product_model;
                    $doc->store_code=$value->store_code;
                    $doc->brand_id=$value->manufacturer_id;
                    $doc->price = $value->price;
                    $doc->record=$value->record?intval($value->record):0;
                    $doc->favourite=$value->favourite?intval($value->favourite):0;
                    $doc->review=$value->review?intval($value->review):0;
                    $doc->be_gift=$value->begift?intval($value->begift):0;
                    $doc->status=$value->online_status?1:0;
                    $doc->sort_order=$value->sort_order;
                    //商品属性
                    $doc->attribute =$value->SearchAttibute;

                    //显示分类
                    $cat_display_datas=[];
                    if($value->category_id){
                        $category_ids=$this->getDefaultCategoryList($value->category_id);
                        $cat_displays=CategoryDisplayToCategory::find()->where(['category_id'=>$category_ids])->all();
                        if($cat_displays){
                            foreach($cat_displays as $cat_display){
                                if($cat_display->category_display_id){
                                    $cat_display_datas=$this->getCategoryList($cat_display->category_display_id,$cat_display_datas);
                                }
                            }
                        }
                    }
                    $doc->category=array_unique($cat_display_datas);


                    //店铺展示分类
                    $store_category_datas=[];
                    $store_categorys=CategoryStoreToProduct::find()->where(['product_base_id'=>$value->product_base_id])->all();
                    if($store_categorys){
                        foreach($store_categorys as $store_category){
                            $store_category_datas=$this->getStoreCategoryList($store_category->category_store_code,$store_category_datas);

                        }
                    }
                    $doc->store_category=$store_category_datas;
                    $docs[]=$doc;
                }
                $update->addDocuments($docs);
                $update->addCommit();
                // optimize the index
                $update->addOptimize(true, false, count($product_base));
                // this executes the query and returns the result
                $result = $client->update($update);
                echo "Update query executed \r\n";
                echo "Query status: ". $result->getStatus(). "\r\n";
                echo 'Query time: ' . $result->getQueryTime(). "\r\n";
            }else{
                echo "Update query Finish \r\n";
            }
    }
    public function getCategoryList($id,$data=[]){
        if($id!=0){
          $cat= CategoryDisplay::findOne(['category_display_id'=>$id]);
            if($cat){
                $data[]=$id;
               return $this->getCategoryList($cat->parent_id,$data);
            }
        }
            return $data;
    }
    public function getDefaultCategoryList($id,$data=[]){
        if($id!=0){
            $cat= Category::findOne(['category_id'=>$id]);
            if($cat){
                $data[]=$id;
                return $this->getDefaultCategoryList($cat->parent_id,$data);
            }
        }
        return $data;
    }
    public function getStoreCategoryList($code,$data=[]){
        if($code!=""){
            $cat= CategoryStore::findOne(['category_store_code'=>$code]);
            if($cat){
                $data[]=$code;
                return $this->getStoreCategoryList($cat->parent_code,$data);
            }
        }
        return $data;
    }
    public function actionAll(){
        $total=ProductBase::find()->count();
        $count=intval($total/100);
        echo "Total:".$total."-- Page:".$count."\n";
        for($i=0;$i<$count+1;$i++) {
            $product_base = ProductBase::find()->limit(100)->offset($i * 100)->orderBy("date_modified desc")->all();
            if ($product_base) {
                $client = \Yii::$app->solr;
                $update = $client->createUpdate();
                // create a new document for the data
                $docs=[];
                foreach($product_base as $value){
                    echo "Doc_id:".$value->product_base_id."\r\n";
                    $doc = $update->createDocument();
                    $doc->id = $value->product_base_id;
                    $doc->product_code=$value->product_base_code;
                    if($value->description){
                        $product_name=$value->description->name;
                    }else{
                        $product_name="";
                    }
                    $doc->product_name =$product_name;
                    $doc->product_model =$value->product_model;
                    $doc->store_code=$value->store_code;
                    $doc->brand_id=$value->manufacturer_id;
                    $doc->price = $value->price;
                    $doc->record=$value->record?intval($value->record):0;
                    $doc->favourite=$value->favourite?intval($value->favourite):0;
                    $doc->review=$value->review?intval($value->review):0;
                    $doc->be_gift=$value->begift?intval($value->begift):0;
                    $doc->status=$value->online_status?1:0;
                    $doc->sort_order=$value->sort_order;
                    //商品属性
                    $doc->attribute =$value->SearchAttibute;

                    //显示分类
                    $cat_display_datas=[];
                    if($value->category_id){
                        $category_ids=$this->getDefaultCategoryList($value->category_id);
                        $cat_displays=CategoryDisplayToCategory::find()->where(['category_id'=>$category_ids])->all();
                        if($cat_displays){
                            foreach($cat_displays as $cat_display){
                                if($cat_display->category_display_id){
                                    $cat_display_datas=$this->getCategoryList($cat_display->category_display_id,$cat_display_datas);
                                }
                            }
                        }
                    }
                    $doc->category=array_unique($cat_display_datas);
                    //店铺展示分类
                    $store_category_datas=[];
                    $store_categorys=CategoryStoreToProduct::find()->where(['product_base_id'=>$value->product_base_id])->all();
                    if($store_categorys){
                        foreach($store_categorys as $store_category){
                            $store_category_datas=$this->getStoreCategoryList($store_category->category_store_code,$store_category_datas);

                        }
                    }
                    $doc->store_category=$store_category_datas;
                    $docs[]=$doc;
                }
                $update->addDocuments($docs);
                $update->addCommit();
                // optimize the index
                $update->addOptimize(true, false, count($product_base));
                // this executes the query and returns the result
                $result = $client->update($update);
                echo "Update query executed \r\n";
                echo "Query status: ". $result->getStatus(). "\r\n";
                echo 'Query time: ' . $result->getQueryTime(). "\r\n";
            }
        }
      echo "Update query Finish \r\n";
    }
}