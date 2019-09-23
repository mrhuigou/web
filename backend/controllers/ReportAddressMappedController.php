<?php

namespace backend\controllers;
use api\models\V1\HeatMapSearch;
use api\models\V1\Order;
use api\models\V1\OrderShipping;
use Yii;
use yii\web\Controller;
use api\models\V1\Address;
use common\component\MapGroups\AddressGroups;
class ReportAddressMappedController extends Controller
{
    public function actionSetlocation()
    {
        $start = 0;
        $addresses_count = OrderShipping::find()->where(['or', 'lng is null', 'lng > 120.708'])->count();
        $count = ceil($addresses_count/100);
        for ($i = 1; $i < $count; $i++) {
            $start = (($i - 1) * 100);
           // Yii::getLogger()->log("page to " . $i, Logger::LEVEL_INFO);
            $addresses = OrderShipping::find()->where(['or', 'lng is null', 'lng > 120.708'])->offset($start)->limit(100)->all();
            $hmp = new AddressGroups();
            $hmp->getHeatMapsData($addresses, 'set');
        }
    }

    public function actionHeatMaps()
    {
            $search_form = new HeatMapSearch();
            $model = $search_form->search(Yii::$app->request->queryParams);

            $addresses = array();
            $count_order = count($model['orders']);
            foreach($model['orders'] as  $order){
                $order_shipping = $order->orderShipping;
                if(!empty($order_shipping) && $order_shipping->lng && $order_shipping->lat){
                    $key = floatval(number_format(floatval($order_shipping->lng), 6)) . '-' . floatval(number_format(floatval($order_shipping->lat), 6));
                    $location = array(
                        'lng' =>  $order->orderShipping->lng,
                        'lat' => $order->orderShipping->lat
                    );
                    $addresses[$key][] = $location;
                }
            }
        $datas = array();
        $count = 0;
        if($addresses){
            foreach ($addresses as $result) {
                $datas[$count]['lng'] = floatval(number_format($result[0]['lng'], 6));
                $datas[$count]['lat'] = floatval(number_format($result[0]['lat'], 6));
                $datas[$count]['count'] = intval(count($result));
                $count++;
            }
        }
            return $this->render("heatmap", ['data' => $datas,'model'=>$search_form,'count_order'=>$count_order]);
    }
}
?>