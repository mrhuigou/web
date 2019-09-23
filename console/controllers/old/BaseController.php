<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/8/3
 * Time: 14:50
 */
namespace console\controllers\old;
use api\models\V1\City;
use api\models\V1\District;
use api\models\V1\IndustryStoreCategory;
use api\models\V1\LegalPerson;
use api\models\V1\Manufacturer;
use api\models\V1\Platform;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use api\models\V1\Store;
use api\models\V1\StoreDescription;
use api\models\V1\StoreTheme;
use api\models\V1\StoreThemeColumn;
use api\models\V1\StoreThemeColumnInfo;
use api\models\V1\StoreToWarehouse;
use api\models\V1\Theme;
use api\models\V1\ThemeColumn;
use api\models\V1\Warehouse;
use common\component\Helper\Helper;
use yii\helpers\Json;
use Yii;
class BaseController extends \yii\console\Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }
    //获取结果数据方法
    protected function getResult($data){
        $result=Json::decode($data,true);
        return $result;
    }

    //生成请求数据方法
    protected function CreatRequestParams($a,$d=array(),$v='1.0'){
        $t=time();
        $m='webservice';
        $key='asdf';
        $data=array('a'=>$a,'c'=>'NONE','d'=>$d,'f'=>'json','k'=>md5($t.$m.$key),'m'=>$m,'l'=>'CN','p'=>'soap','t'=>$t,'v'=>$v);
        return Json::encode($data);
    }
    //品牌数据
    public function actionBrand(){
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getBrandForJson');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception("数据解析失败");
            }
            $result=Json::decode($content);
            if(isset($result['data'])&& $result['data']){
                $data=Helper::genTree($result['data'],'CODE','PARENTCODE');
               $this->brand($data);
            }


        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        echo '品牌同步'.date('Y-m-d H:i:s');
    }
    private function brand($datas,$parent_id=0){
        if($datas){
            foreach($datas as $value){
                if(!$model=Manufacturer::findOne(['code'=>$value['CODE']])){
                    $model=new Manufacturer();
                }
                $model->code=$value['CODE'];
                $model->name=$value['NAME'];
                $model->parent_id=$parent_id;
                $model->parentcode=$value['PARENTCODE'];
                $model->story=$value['DESCRIPTION'];
                $model->image=$value['IMAGEURL'];
                $model->status=$value['STATUS']=='ACTIVE'?1:0;
                $model->sort_order=0;
                if(!$model->save()){
                    throw new \Exception(json_encode($model->errors));
                }
                if(isset($value['children']) && $value['children']){
                    $this->brand($value['children'],$model->manufacturer_id);
                }
            }
        }
    }
    //店铺数据
    public function actionTheme(){
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getTemplate');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception("数据解析失败");
            }
            $result=Json::decode($content);
            if(isset($result['data'])&& $result['data']){
                foreach($result['data'] as $data){
                    if(!$model=Theme::findOne(['theme_code'=>$data['CODE']])){
                        $model=new Theme();
                        $model->date_added=date('Y-m-d H:i:s',time());
                    }
                    $model->theme_name=$data['NAME'];
                    $model->theme_code=$data['CODE'];
                    $model->status=$data['STATUS']=='ACTIVE'?1:0;
                    $model->type=$data['TYPE'];
                    $model->theme_color_code=$data['COLORCODE'];
                    $model->date_modified=date('Y-m-d H:i:s',time());
                    if(!$model->save()){
                        throw new \Exception(json_encode($model->errors));
                    }
                    $theme_id=$model->theme_id;
                    if(isset($data['DETAILS']) && $data['DETAILS']){
                        foreach($data['DETAILS'] as $value){
                            if(!$model=ThemeColumn::findOne(['theme_id'=>$theme_id,'theme_column_code'=>$value['CODE']])){
                                $model=new ThemeColumn();
                            }
                            $model->theme_column_code=$value['CODE'];
                            $model->name=$value['NAME'];
                            $model->type=$value['TYPE'];
                            $model->status=$value['STATUS']=='ACTIVE'?1:0;
                            $model->theme_id=$theme_id;
                            $model->rowslimit=$value['ROWQTY'];
                            $model->remark=$value['REMARK'];
                            if(!$model->save()){
                                throw new \Exception(json_encode($model->errors));
                            }
                        }
                    }

                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        echo '模板同步'.date('Y-m-d H:i:s');
    }

    //店铺模板数据
    public function actionShop(){
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getShop');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception("数据解析失败");
            }
            $result=Json::decode($content);
            if(isset($result['data'])&& $result['data']){
                foreach($result['data'] as $data){
                    $template=[];
                    if(!$model=Store::findOne(['store_code'=>$data['CODE']])){
                        $model=new Store();
                        $model->date_added=$data['UPDATETIME'];
                    }
                    $model->store_code=$data['CODE'];
                    $model->name=$data['NAME'];
                    $model->store_type=$data['SHOPGRADE'];
                    $model->image=$data['IMGURL'];
                    $model->logo=$data['PICTUREURL'];
                    $model->app_logo=$data['APPLOGO'];
                    $Platform=Platform::findOne(['platform_code'=>$data['MARKETCODE']]);
                    $model->platform_id=$Platform?$Platform->platform_id:0;
                    $model->platform_code=$data['MARKETCODE'];
                    $LegalPerson=LegalPerson::findOne(['legal_no'=>$data['COMPANYCODE']]);
                    $model->legal_person_id=$LegalPerson?$LegalPerson->legal_person_id:0;
                    $model->legal_person_code=$data['COMPANYCODE'];
                    $Theme=Theme::findOne(['theme_code'=>$data['TEMPLATECODE']]);
                    $model->theme_id=$Theme?$Theme->theme_id:0;
                    if($Theme){
                        $template[]=$Theme->theme_id;
                    }
                    $model->theme_code=$data['TEMPLATECODE'];
                    $H5theme=Theme::findOne(['theme_code'=>isset($data['TEMPLATEH5CODE'])?$data['TEMPLATEH5CODE']:""]);
                    $model->h5_theme_id=$H5theme?$H5theme->theme_id:0;
                    if($H5theme){
                        $template[]=$H5theme->theme_id;
                    }
                    $model->recommend=$data['BEEXHIBIT'] ? 1 : 0;
                    $City=City::findOne(['code'=>$data['CITYCODE']]);
                    $model->city=$City?$City->city_id:0;
                    $District=District::findOne(['code'=>$data['CITYCODE']]);
                    $model->district=$District?$District->district_id:0;
                    $model->address=$data['ADDRESS'];
                    $model->longitude=$data['LONGITUDE'];
                    $model->latitude=$data['LATITUDE'];
                    $IndustryStoreCategory=IndustryStoreCategory::findOne(['industry_store_category_code'=>$data['SHOPTYPECODE']]);
                    $model->industry_store_category_id=$IndustryStoreCategory?$IndustryStoreCategory->industry_store_category_id:0;
                    $model->industry_store_category_code=$data['SHOPTYPECODE'];
                    $model->minbookcash=$data['MINBOOKCASH'];
                    $model->deliverycash=$data['DELIVERYCASH'];
                    $model->cycle_period=$data['MAXCYCLEPERIOD'];
                    $model->opening_hours=$data['OPENTIME'];
                    if( $data['OPENTIME'] && strpos($data['OPENTIME'],"-")){
                        list($max_open_hour,$min_open_hour)= explode("-",str_replace("：", ":", $data['OPENTIME']));
                        $model->max_open_hour=$max_open_hour;
                        $model->min_open_hour=$min_open_hour;
                    }
                    $model->delivery_hours=$data['DELIVERYTIME'];
                    if( $data['DELIVERYTIME']  && strpos($data['DELIVERYTIME'],"-")){
                        list($max_delivery_hour,$min_delivery_hour)= explode("-",str_replace("：", ":", $data['DELIVERYTIME']));
                        $model->max_delivery_hour=$max_delivery_hour;
                        $model->min_delivery_hour=$min_delivery_hour;
                    }
                    $model->is_merge=$data['BEALONE'] ? 0 : 1;
                    $model->online=$data['ONLINE'] ? 1 : 0;
                    $model->befreepostage=$data['BEFREEPOSTAGE'] ? 1 : 0;
                    $model->besupportpos=$data['BESUPPORTPOS'] ? 1 : 0;
                    $model->discount=$data['DISCOUNT'];
                    $model->hotline=$data['TELEPHONE'];
                    $model->max_user_number=$data['MAXUSERNUMBER'];
                    $model->business_code=$data['BUSINESSZONECODE'];
                    $model->business_name=$data['BUSINESSZONENAME'];
                    $model->notice=$data['NOTICE'];
                    $model->tags=$data['TAGS'];
                    $model->description=$data['DESCRIPTION'];
                    $model->status=$data['STATUS']=="ACTIVE"?1:0;
                    $model->date_modified=$data['UPDATETIME'];
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->errors));
                    }
                    $store_id=$model->store_id;
                    if(!$model=StoreDescription::findOne(['store_id'=>$store_id])){
                        $model=new StoreDescription();
                    }
                    $model->language_id=2;
                    $model->store_id=$store_id;
                    $model->title=$data['NAME'];
                    $model->description=$data['DESCRIPTION'];
                    $model->meta_description=$data['NOTICE'];
                    $model->meta_keyword=$data['NOTICE'];
                    if (!$model->save()) {
                        throw new \Exception(json_encode($model->errors));
                    }
                    if(isset($data['DETAILS']) && $data['DETAILS']){
                        foreach($data['DETAILS'] as $value){
                            $Warehouse=Warehouse::findOne(['warehouse_code'=>$value['WAREHOUSECODE']]);
                            if(!$model=StoreToWarehouse::findOne(['store_id'=>$store_id,'warehouse_id'=>$Warehouse?$Warehouse->warehouse_id:0])){
                                $model=new StoreToWarehouse();
                            }
                            $model->store_id=$store_id;
                            $model->store_code=$data['CODE'];
                            $model->warehouse_id=$Warehouse?$Warehouse->warehouse_id:0;
                            $model->warehouse_code=$value['WAREHOUSECODE'];
                            if (!$model->save()) {
                                throw new \Exception(json_encode($model->errors));
                            }
                        }
                    }
                    if($template){
                        foreach($template as $value){
                            if(!$model=StoreTheme::findOne(['store_id'=>$store_id,'theme_id'=>$value])){
                                $model=new StoreTheme();
                            }
                            $model->store_id=$store_id;
                            $model->theme_id=$value;
                            $model->status=1;
                            if (!$model->save()) {
                                throw new \Exception(json_encode($model->errors));
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        echo '店铺同步'.date('Y-m-d H:i:s');
    }
    //店铺模板数据
    public function actionShopTheme(){
        $client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], array('soap_version' => SOAP_1_1, 'exceptions' => false));
        try {
            $data=$this->CreatRequestParams('getShopColumn');
            $content = $client->getInterfaceForJson($data);
            if (is_soap_fault($content)) {
                throw new \Exception("数据解析失败");
            }
            $result=Json::decode($content);
            if(isset($result['data'])&& $result['data']){
               foreach($result['data'] as $data){
                   $Theme=Theme::findOne(['theme_code'=>isset($data['TEMPLATECODE'])?$data['TEMPLATECODE']:'']);
                   $Store=Store::findOne(['store_code' => $data['SHOPCODE']]);
                   $StoreTheme=StoreTheme::findOne(['store_id'=> $Store?$Store->store_id:0,'theme_id'=>$Theme?$Theme->theme_id:0]);
                   $ThemeColumn=ThemeColumn::findOne(['theme_column_code' => $data['CODE'],'theme_id'=>$Theme?$Theme->theme_id:0]);
                   $theme_column_id = $ThemeColumn?$ThemeColumn->theme_column_id:0;
                   $store_id = $Store?$Store->store_id:0;
                   if (!$model = StoreThemeColumn::findOne(['theme_column_id' => $theme_column_id, 'store_theme_id' =>$StoreTheme?$StoreTheme->store_theme_id:0])) {
                       $model = new StoreThemeColumn();
                   }
                   $model->store_id = $store_id;
                   $model->store_theme_id=$StoreTheme?$StoreTheme->store_theme_id:0;
                   $model->store_code = $data['SHOPCODE'];
                   $model->theme_column_id = $theme_column_id;
                   $model->theme_column_code = $data['CODE'];
                   $model->theme_column_type = $data['TEMPLATETYPE'];
                   $model->name = $data['NAME'];
                   $model->status = $data['STATUS'] == "ACTIVE" ? 1 : 0;
                   if (!$model->save()) {
                       throw new \Exception(json_encode($model->errors));
                   }
                   $store_theme_column_id = $model->store_theme_column_id;
                   if (isset($data['DETAILS']) && $data['DETAILS']) {
                       foreach ($data['DETAILS'] as $value) {
                           if (!$model = StoreThemeColumnInfo::findOne(['store_theme_column_id' => $store_theme_column_id, 'store_theme_column_info_code' => $value['CODE']])) {
                               $model = new StoreThemeColumnInfo();
                           }
                           $model->store_theme_column_info_code = $value['CODE'];
                           $model->store_theme_column_id = $store_theme_column_id;
                           $model->theme_column_type = $value['TYPE'];
                           $model->title = $value['TITLE'];
                           $model->contents = $value['CONTENT'];
                           $model->image = $value['IMAGE'];
                           $model->url = $value['URL'];
                           $ProductBase=ProductBase::findOne(['product_base_code' => $value['PCODE'], 'store_id' => $store_id]);
                           $product_base_id = $ProductBase?$ProductBase->product_base_id:0;
                           $Product=Product::findOne(['product_code' => $value['PUCODE'], 'product_base_id' => $product_base_id]);
                           $model->product_id = $Product?$Product->product_id:0;
                           $model->product_code = $value['PUCODE'];
                           $model->status = $value['STATUS'] == "ACTIVE" ? 1 : 0;
                           if (!$model->save()) {
                               throw new \Exception(json_encode($model->errors));
                           }
                       }
                   }

               }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
        echo '店铺模板信息'.date('Y-m-d H:i:s');
    }

}