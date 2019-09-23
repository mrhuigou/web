<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/19
 * Time: 13:43
 */

namespace api\modules\shop\widgets;
use api\models\V1\CategoryStore;
use common\component\Helper\Helper;
use yii\base\Widget;
class Storecategory extends Widget{
    public function init()
    {
        parent::init();

    }
    public function run(){
        parent::run();
        if($store_code=\Yii::$app->request->get('shop_code')){
                $store_category=CategoryStore::find()->select('category_store_id,category_store_code,store_code,name,parent_id,sort_order')->where(['store_code'=>$store_code,'status'=>1]);
                if($categorys=$store_category->asArray()->all()){
                    $data=Helper::genTree($categorys,'category_store_id','parent_id');
                    return $this->render('/widgets/StoreCategory',['data'=>$data,'store_code'=>$store_code]);
                }

        }
    }
}