<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/8/12
 * Time: 14:26
 */
namespace api\controllers\mall\v1;
use api\controllers\mall\v1\filters\AccessControl;
use api\models\V1\CategoryStore;
use api\models\V1\CustomerCollect;
use api\models\V1\Promotion;
use api\models\V1\Store;
use api\models\V1\StoreTheme;
use api\models\V1\StoreThemeColumn;
use api\models\V1\StoreThemeColumnInfo;
use common\component\Helper\Helper;
use common\component\image\Image;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use \yii\rest\Controller;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
class ShopController extends  Controller{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }

    public function actionIndex(){
        $data = [];
        $page=[];
        if($model=Store::findOne(['store_code'=>\Yii::$app->request->post('shop_code')])){
            if($model->h5Theme && $model->h5Theme->info ){
                $theme = $model->h5Theme->info;
                $count = 0;
                foreach($theme as $val){
                        $StoreThemeColumn = array(
                            'id' => $val->store_theme_column_id,
                            'name' =>  $val->name,
                            'type' =>  $val->theme_column_type,
                        );
                        $StoreThemeColumninfo = array();
                        if(!empty($val->info)){
                            foreach($val->info as $key => $columninfo){
                                $StoreThemeColumninfo[$key] =  array(
                                    'title' => $columninfo->title,
                                    'contents' =>Html::encode($columninfo->contents),
                                    'image' => Image::resize($columninfo->image,200,200,9),
                                    'url' =>Url::to($columninfo->url),
                                    'product_base_id' => $columninfo->product?$columninfo->product->product_base_id:'',
                                    'product_name' =>$columninfo->product?$columninfo->product->description->name:'',
                                    'product_image'=>$columninfo->product?Image::resize($columninfo->product->image,200,200):'',
                                    'product_price'=>$columninfo->product?$columninfo->product->price:''
                                );
                            }
                        }
                    $page[$count] = $StoreThemeColumn;
                    $page[$count]['info'] = $StoreThemeColumninfo;
                        $count++;
                }

            }
            $data['shop_id']=$model->store_id;
            $data['shop_code']=$model->store_code;
            $data['shop_name']=$model->name;
            $data['logo']=Image::resize($model->logo,100,100);
            $data['address']=$model->address;
            $data['telephone']=$model->hotline;
            $data['page']=$page;
            $collect_count = count($model->collect);
            $collect_status = $model->myCollectStatus?1:0;
            $data['collect_status'] = $collect_status;
            $data['collect_count'] = $collect_count;

            return Result::OK($data);
        }else{
            return Result::Error('参数错误！');
        }
    }
    public function actionCategory(){
        if($model=Store::findOne(['store_code'=>\Yii::$app->request->post('shop_code')])){
            $cate=CategoryStore::find()->where(['store_id'=>$model->store_id,'status'=>1])->orderBy('sort_order asc')->all();
            if($cate){
                $data=[];
                foreach($cate as $val){
                    $data[]=[
                        'id'=>$val->category_store_id,
                        'code'=>$val->category_store_code,
                        'name'=>$val->name,
                        'parent_id'=>$val->parent_id,
                        'shop_code'=>$val->store_code,
                    ];
                }
                $data=Helper::genTree($data,'id','parent_id');
            }else{
                $data=[];
            }
            return Result::OK($data);
        }else{
            return Result::Error('参数错误！');
        }
    }

    public function actionHot(){
        if($store=Store::findOne(['store_code'=>\Yii::$app->request->post('shop_code')])){
            $data=[];
            $storeTheme=StoreTheme::findOne(['store_id'=>$store->store_id,'theme_id'=>$store->h5_theme_id,'status'=>1]);
            $store_theme_id=$storeTheme?$storeTheme->store_theme_id:0;
            $storeThemeColumn=StoreThemeColumn::findOne(['theme_column_type'=>'PRODUCT','store_theme_id'=>$store_theme_id,'status'=>1]);
            $store_theme_column_id=$storeThemeColumn?$storeThemeColumn->store_theme_column_id:0;
            $model=StoreThemeColumnInfo::find()->where(['store_theme_column_id'=>$store_theme_column_id,'status'=>1])->all();
            if(!empty($model)){
                foreach($model as $key=>$val){
                    $data[]=[
                        'product_base_id' => $val->product?$val->product->product_base_id:'',
                        'product_name' =>$val->product?$val->product->description->name:'',
                        'product_image'=>$val->product?Image::resize($val->product->image,200,200):'',
                        'product_price'=>$val->product?$val->product->price:''
                    ];
                }
            }
            return Result::OK($data);
        }else{
            return Result::Error('参数错误！');
        }
    }
    public function actionPromotion(){
        if($model=Store::findOne(['store_code'=>\Yii::$app->request->post('shop_code')])){
            $data=[];
            $model=Promotion::find()->where(['and',"store_id='".$model->store_id."'",'date_start<NOW()','date_end>NOW()','status=1'])->all();
            if($model){
                foreach($model as $value){
                    $detail=[];
                    if($value->details){
                        foreach($value->details as $val){
                            $detail[]=[
                                'product_base_id'=>$val->product->product_base_id,
                                'product_name'=>$val->product->description->name,
                                'product_image'=>Image::resize($val->product->productBase->image,200,200),
                                'product_price'=>$val->product->price
                            ];
                        }
                        $data[]=[
                            'title'=>  $value->name,
                            'type'=>$value->type,
                            'detail'=>$detail,
                        ];

                    }

                }

            }
            return Result::OK($data);
        }else{
            return Result::Error('参数错误！');
        }
    }

}