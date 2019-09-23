<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/8/3
 * Time: 14:50
 */
namespace console\controllers\old;
use api\models\V1\ProductBase;
use Yii;
class ResolveController extends \yii\console\Controller
{

    public function actionProduct()
    {
        $model=ProductBase::find()->all();
        if($model){
            foreach($model as $product){
                if(!$product->online_status){
                    $product->beintoinv=0;
                    $product->date_modified=date('Y-m-d H:i:s',time());
                    $product->save();
                    echo 'product_id:'.$product->product_base_id."\n";
                }
            }
        }
    }


}