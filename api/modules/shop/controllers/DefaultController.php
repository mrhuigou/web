<?php
namespace api\modules\shop\controllers;
use api\models\V1\ProductBase;
use api\models\V1\Store;
use api\models\V1\StoreTemplate;
use api\models\V1\StoreTemplatePage;
use dosamigos\qrcode\QrCode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\helpers\Json;
use Yii;
class DefaultController extends Controller
{  public $layout="@api/modules/shop/views/layouts/main.php";
    public function actionIndex()
    {
        $store=Store::findOne(['store_code'=>Yii::$app->request->get('shop_code')]);
        if($store){
            $store_template=StoreTemplate::findOne(['store_id'=>$store->store_id,'status'=>1]);
            if(!$store_template){
                $this->redirect("http://www.baidu.com/");
            }
            $page_id=$store_template->default_index;
            $page=StoreTemplatePage::findOne(['store_template_id'=>$store_template->id,'id'=>$page_id]);
            if($page){
                $this->getView()->title =$page->title;
                $this->getView()->params=['store'=>$store];
              $data=  $this->generatedpage(['hd'=>Json::decode($page->head),'bd'=>Json::decode($page->body),'ft'=>Json::decode($page->foot)]);
             return $this->render('index',['page_data'=>$data]);
            }
        }else{
            return $this->render('error',['name'=>'很抱歉，您查看的商品找不到了！','message'=>'']);
        }
    }


    public function actionDetail(){
        $product_base_code=Yii::$app->request->get('product_base_code');
        $shop_code=Yii::$app->request->get('shop_code');
        $store=Store::findOne(['store_code'=>$shop_code]);

        if(!$store ||  !ProductBase::findOne(['product_base_code'=>$product_base_code,'store_id'=>$store->store_id])){
            return $this->render('error',['name'=>'很抱歉，您查看的商品找不到了！','message'=>'']);
        }

        $store_template=StoreTemplate::findOne(['store_id'=>$store->store_id,'status'=>1]);
        $data=[];
        if($store_template){
            $page=StoreTemplatePage::findOne(['store_template_id'=>$store_template->id,'type'=>'DETAIL']);
            if($page){
                $this->getView()->title =$page->title;
                $this->getView()->params=['store'=>$store];
                $data=['hd'=>Json::decode($page->head),'bd'=>Json::decode($page->body),'ft'=>Json::decode($page->foot)];
            }
        }
        if(empty($data)){
            $data=['bd'=>['layouts'=>[
                ['type'=>'grid-m0',
                    'modules'=>['main'=>[['moduleID'=>'productbase','data'=>[]]]]
                ],
                ['type'=>'grid-s5m0',
                    'modules'=>['main'=>[['moduleID'=>'productinfo','data'=>[]]],
                                 'sub'=>[['moduleID'=>'shopArchive','data'=>[]]]]
                ],
              ]]];
        }
        $page_data=$this->generatedpage($data);
        return $this->render('index',['page_data'=>$page_data]);
    }
    public function actionCategory(){
        if(!$shop_code=Yii::$app->request->get('shop_code')){
            $shop_code='';
        }
        $data=[];
        $store=Store::findOne(['store_code'=>$shop_code]);
        if($store){
            $store_template=StoreTemplate::findOne(['store_id'=>$store->store_id,'status'=>1]);
            if($store_template){
                $page=StoreTemplatePage::findOne(['store_template_id'=>$store_template->id,'type'=>'CLASS']);
                if($page){
                    $this->getView()->title =$page->title;
                    $this->getView()->params=['store'=>$store];
                    $data=['hd'=>Json::decode($page->head),'bd'=>Json::decode($page->body),'ft'=>Json::decode($page->foot)];
                }
            }
        }else{
            return $this->render('error',['name'=>'很抱歉，您查看的商品找不到了！','message'=>'']);
        }
        if($data){
            $page_data=$this->generatedpage($data);
            return $this->render('index',['page_data'=>$page_data]);
        }
    }
    public function actionPage(){
        if(!$shop_code=Yii::$app->request->get('shop_code')){
            $shop_code='DP0001';
        }
        if(!$page_code=Yii::$app->request->get('page_code')){
            $page_code='';
        }
        $data=[];
        $store=Store::findOne(['store_code'=>$shop_code]);
        if($store){
            $store_template=StoreTemplate::findOne(['store_id'=>$store->store_id,'status'=>1]);
            if($store_template){
                $page=StoreTemplatePage::findOne(['store_template_id'=>$store_template->id,'type'=>'CUSTOM','code'=>$page_code]);
                if($page){
                    $this->getView()->title =$page->title;
                    $this->getView()->params=['store'=>$store];
                    $data=['hd'=>Json::decode($page->head),'bd'=>Json::decode($page->body),'ft'=>Json::decode($page->foot)];
                }
            }
        }
        if($data){
            $page_data=$this->generatedpage($data);
            return $this->render('index',['page_data'=>$page_data]);
        }
    }
    public function actionPreview(){
        //从店铺模板表中查找有效的店铺模板默认主页
        $this->getView()->title = "店铺测试页面";
        if(!$index_page_data=Yii::$app->request->post('data')){
            $index_page_data = array (
                "title" => "首页" ,
                "hd"=>array("layouts" => array ( array ( "type" => "grid-m0" , "modules" => array ( "main" => array ( array ( "moduleID" => "dianzhao" ),array ( "moduleID" => "daohang" ) ) ) ) ) ),
                "bd"=>array("layouts" => array (
                    array ( "type" => "grid-m0" , "modules" => array ( "main" => array ( array ( "moduleID" => "productbase" ) ) ) ),
                    array ( "type" => "grid-s5m0" , "modules" =>  array ( "main" => array ( array ( "moduleID" => "productinfo" ) ),"sub" => array ( array ( "moduleID" => "shopArchive" ),array ( "moduleID" => "sidecataVert" )  ) ) ) ,
                )
                ),
                "ft"=>array("layouts" => array ( array ( "type" => "grid-m0" , "modules" =>  array ( "main" => array ( array ( "moduleID" => "other" ))  ) ) ) ),
            );
        }else{
            $index_page_data=Json::decode(base64_decode($index_page_data));
        }
        $page_data=$this->generatedpage($index_page_data);

        return $this->render('index',['page_data'=>$page_data]);
    }
    private function generatedpage($index_page_data){
        //读取主页布局单元列表
        $page_data = array ();
       if($index_page_data){
           foreach($index_page_data as $key=>$page){
               if(isset($page['layouts']) && $page['layouts'] ){
                   foreach($page['layouts'] as $layout){
                       $page_data[$key][]=$this->Layout($layout);
                   }
               }
           }
       }
        return $page_data;
    }
    private  function Layout($data)
    {
        $layout_data = array ();
        foreach ( $data[ 'modules' ] as $key => $module ) {
            $layout_data[ $key ][ ] = $this->Module($module);
        }
        return $this->renderPartial("layouts/".$data['type'],['layout_data'=>$layout_data]);
    }

    private function Module( $data )
    {
        $model_data = array ();
        foreach ( $data as $value ) {
                $model_data[] =$this->run("/shop/module/index",['data'=>$value]);
        }
        return $this->renderPartial("modules",['model_data'=>$model_data]);
    }
    public function actionMview(){
        if(!Yii::$app->request->post()){
            return ;
        }else{
            $module_data= Yii::$app->request->post();
        }
        return $this->run("/shop/module/index",['data'=>$module_data]);
    }
    public function actionMprop(){
        $result=[];
        if($module_data=Yii::$app->request->post()){
            $result=$this->run("/shop/module/params",['data'=>$module_data]);
        }
        return Json::encode($result);
    }
    public function actionHeader(){
      return $this->renderPartial("header");
    }
    public function actionFooter(){
        return $this->renderPartial("footer");
    }
    public function actionImg(){
       if($product_base_code=Yii::$app->request->get('product_base_code')){
         echo   QrCode::png(Url::to(['/shop/detail','product_base_code'=>$product_base_code],true));exit;
       }else{
           return;
       }


    }
    public function actionPublish(){
        if(!$module_data=Yii::$app->request->post()){
            return false;
        }
        $status=1;
        $store=Store::findOne(['store_code'=>$module_data['shopcode']]);
        try {
            if ($store) {
                if (!$model = StoreTemplate::findOne(['code' => trim($module_data['code']), 'store_id' => $store->store_id])) {
                    $model = new StoreTemplate();
                }
                $model->code = strval($module_data['code']);
                $model->store_id = $store->store_id;
                $model->name = $module_data['name'];
                $model->status = $module_data['status']=="RUNNING"?1:0;
                if (!$model->save()) {
                    throw new \Exception();
                }
                $store_template_id = $model->id;
                if ($module_data['pages']) {
                    foreach ($module_data['pages'] as $page) {
                        if (!$model = StoreTemplatePage::findOne(['code' => $page['code'], 'store_template_id' => $store_template_id])) {
                            $model = new StoreTemplatePage();
                        }
                        if(!isset($page['hd'])){
                            $page['hd']=[];
                        }
                        if(!isset($page['bd'])){
                            $page['bd']=[];
                        }
                        if(!isset($page['ft'])){
                            $page['ft']=[];
                        }
                        $model->code = strval($page['code']);
                        $model->title = $page['title'];
                        $model->store_template_id = $store_template_id;
                        $model->type = $page['type'];
                        $model->head = Json::encode($page['hd']);
                        $model->body = Json::encode($page['bd']);
                        $model->foot = Json::encode($page['ft']);
                        if (!$model->save()) {
                            throw new \Exception();
                        }
                        if($model->type=='HOME'){
                            if($t_model=StoreTemplate::findOne(['id' =>$store_template_id])){
                                $t_model->default_index=$model->id;
                                $t_model->save();
                            }
                        }
                    }
                }

            }else{
                $status=0;
            }
        }catch (\Exception $e){
            $status=0;
        }
        return $status;
    }


    public function bindActionParams($action, $params){
        return $params;
    }
}
