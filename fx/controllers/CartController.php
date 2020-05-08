<?php
namespace fx\controllers;
use api\models\V1\Product;
use api\models\V1\Store;
use common\component\Track\Track;
use fx\widgets\Checkout\Promotion;
use yii\base\ErrorException;
use yii\helpers\Url;

class CartController extends \yii\web\Controller {
    public function actionIndex()
    {
        if (!\Yii::$app->fxcart->getIsEmpty()) {
            $cart = \Yii::$app->fxcart->getPositions();
            $cart_data = [];
            foreach ($cart as $key => $value) {
                $cart_data[$value->store_id]['base'] = Store::findOne(['store_id' => $value->store_id]);
                $cart_data[$value->store_id]['products'][$key] = $value;
            }
            return $this->render('index', ['cart' => $cart_data]);
        } else {
            return $this->render('empty');
        }
    }

    public function actionAddToCart()
    {
        try {
            if (\Yii::$app->request->getIsPost() && \Yii::$app->request->isAjax) {
                $sku = \Yii::$app->request->post('sku') ? \Yii::$app->request->post('sku') : '';
                $product_base_id = \Yii::$app->request->post('product_base_id') ? \Yii::$app->request->post('product_base_id') : 0;
                $qty = \Yii::$app->request->post('qty') ? \Yii::$app->request->post('qty') : 1;
                $params['affiliate_plan_id'] = \Yii::$app->request->post('affiliate_plan_id') ? \Yii::$app->request->post('affiliate_plan_id') : 0;
                if (strpos($sku, ":")) {
                    if (substr($sku, 0, 1) != 0) {
                        $model = Product::findOne(['sku' => $sku, 'product_base_id' => $product_base_id, 'beintoinv' => 1]);
                    } else {
                        $model = Product::findOne(['product_id' => substr($sku, 2), 'beintoinv' => 1]);
                    }
                } else {
                    $model = Product::findOne(['product_id' => $sku, 'beintoinv' => 1]);
                }

                if ($model && $qty > 0) {
                    if($stock_count=$model->getStockCount()){
                        if($limit_max_qty=$model->getLimitMaxQty(\Yii::$app->user->getId())){
                            $stock_count=min($limit_max_qty,$stock_count);
                        }
                    }
                    if ($stock_count > 0) {
                        if (\Yii::$app->fxcart->hasPosition($model->getCartPositionFx($params)->getId())) {
                            $position = \Yii::$app->fxcart->getPositionById($model->getCartPositionFx($params)->getId());
                            $quantity = $qty + $position->getQuantity();
                            $stock_count=$model->getStockCount($quantity);
                            if ($quantity > 100 || $quantity > $stock_count) {
                                throw new ErrorException('最大可购买' . min($stock_count, 100) . '件');
                            }
                        } else {
                            if ($qty > 100 || $qty > $stock_count) {
                                throw new ErrorException('最大可购买' . min($stock_count, 100) . '件');
                            }
                        }
                        Track::add($model->product_base_id,'add_cart');
                        \Yii::$app->fxcart->put($model->getCartPositionFx($params), $qty);
                        $data = ['status' => 1, 'data' =>\Yii::$app->fxcart->getCount()];
                    } else {
                        throw new ErrorException('库存不足');
                    }
                } else {
                    throw new ErrorException('商品不存在或者已经下架');
                }
            } else {
                throw new ErrorException('数据加载失败');
            }

        } catch (ErrorException $e) {
            $data = ['status' => 0, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    //分销方案商品添加购物车
    public function actionAddToCartFx()
    {
        try {
            if (\Yii::$app->request->getIsPost() && \Yii::$app->request->isAjax) {
                $product_code = \Yii::$app->request->post('product_code') ? \Yii::$app->request->post('product_code') : 0;
                $qty = \Yii::$app->request->post('qty') ? \Yii::$app->request->post('qty') : 0;
                $params['affiliate_plan_id'] = \Yii::$app->request->post('affiliate_plan_id') ? \Yii::$app->request->post('affiliate_plan_id') : 0;

                $model = Product::findOne(['product_code' => $product_code, 'beintoinv' => 1]);

                if ($model) {

                    if($qty > 0){
                        $stock_count = 10000; //库存验证
                        if ($stock_count > 0) {
                            //=======================库存验证========================
                            //=======================库存验证========================

                            Track::add($model->product_base_id,'add_cart');
                            if(\Yii::$app->fxcart->hasPosition($model->getCartPositionFx($params)->getId())){
                                \Yii::$app->fxcart->update($model->getCartPositionFx($params), $qty);
                            }else{
                                \Yii::$app->fxcart->put($model->getCartPositionFx($params), $qty);
                            }

                            $data = ['status' => 1, 'data' =>\Yii::$app->fxcart->getCount()];
                        } else {
                            throw new ErrorException('库存不足');
                        }
                    } else{ // 当前商品购买数量为0
                        if(\Yii::$app->fxcart->hasPosition($model->getCartPositionFx($params)->getId())){
                            \Yii::$app->fxcart->removeById($model->getCartPositionFx($params)->getId());
                            if (\Yii::$app->session->get('FirstBuy') == $model->getCartPositionFx($params)->getId()) {
                                \Yii::$app->session->remove('FirstBuy');
                            }
                        }
                        $data = ['status' => 1, 'data' =>\Yii::$app->fxcart->getCount()];
                    }


                } else {
                    throw new ErrorException('商品不存在或者已经下架');
                }
            } else {
                throw new ErrorException('数据加载失败');
            }

        } catch (ErrorException $e) {
            $data = ['status' => 0, 'message' => $e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    public function actionRemove()
    {
        if(($datas = \Yii::$app->request->post('data')) && count($datas)>0){
            foreach($datas as $data){
                if (\Yii::$app->fxcart->hasPosition($data)) {
                    \Yii::$app->fxcart->removeById($data);
                    if (\Yii::$app->session->get('FirstBuy') == $data) {
                        \Yii::$app->session->remove('FirstBuy');
                    }
                }
            }
        }
    }
    public function actionRemoveAll(){
        \Yii::$app->fxcart->removeAll();
    }

    public function actionUpdate(){
        $item=\Yii::$app->request->post('item');
        $qty=\Yii::$app->request->post('qty');
        if(\Yii::$app->fxcart->hasPosition($item) && $qty>0){
            $position=\Yii::$app->fxcart->getPositionById($item);
            if($stock_count=$position->product->getStockCount($qty)){
                if($limit_max_qty=$position->product->getLimitMaxQty(\Yii::$app->user->getId())){
                    $stock_count=min($limit_max_qty,$stock_count);
                }
            }
//
            $stock_tag='';
            if ($qty > 100 || $qty > $stock_count) {
                $stock_tag='最大可购买' . min($stock_count, 100) . '件';
                $qty=min($stock_count, 100);
            }
            Track::add($position->product->product_base_id,'update_cart');
            \Yii::$app->fxcart->update($position, $qty);
            $json=[
                'qty'=>$qty,
                'price'=>$position->getPrice(),
                'discount'=>bcsub($position->price,$position->getPrice(),2),
                'sub_total'=>floatval($position->getCost()),
                'stock_status'=>$stock_tag,
//                'promotion'=>Promotion::widget(['promotion'=>$position->getPromotion(),'qty'=>$position->getQuantity()]),
                'promotion'=>'',//促销方案 关闭
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $json;
        }
    }

    public function actionSubmit()
    {
        $data = \Yii::$app->request->post('data');
        $cart = [];
        $status = true;
        $msg = '';
        if ($data) {
            foreach ($data as $key => $value) {
                if(\Yii::$app->fxcart->hasPosition($value)){
                    $position = \Yii::$app->fxcart->getPositionById($value);
                    if (!$position->hasStock()) {
                        $status = false;
                        $msg = '库存不足，请查看';
                        break;
                    }
                    $cart[$value] = \Yii::$app->fxcart->getPositionById($value);
                    Track::add($cart[$value]->product->product_base_id, 'submit_cart');
                }
            }
            if($status){
                \Yii::$app->session->set('confirm_cart', $cart);
            }
        }
        $result['status'] = $status;
        $result['message'] = $msg;
        return json_encode($result);
    }

    public function actionBuynow(){
        try{
            if(\Yii::$app->request->getIsPost() && \Yii::$app->request->isAjax) {
                $sku = \Yii::$app->request->post('sku') ? \Yii::$app->request->post('sku') : '';
                $product_base_id = \Yii::$app->request->post('product_base_id') ? \Yii::$app->request->post('product_base_id') : 0;
                $qty = \Yii::$app->request->post('qty') ? \Yii::$app->request->post('qty') : 1;
                $params['affiliate_plan_id'] = \Yii::$app->request->post('affiliate_plan_id') ? \Yii::$app->request->post('affiliate_plan_id') : 0;
                if(strpos($sku,":")){
                    if(substr($sku,0,1)!=0){
                        $model=Product::findOne(['sku'=>$sku,'product_base_id'=>$product_base_id,'beintoinv'=>1]);
                    }else{
                        $model=Product::findOne(['product_id'=>substr($sku,2),'beintoinv'=>1]);
                    }
                }else{
                    $model=Product::findOne(['product_id'=>$sku ,'beintoinv'=>1]);
                }
                if ($model && $qty>0) {
                    if($stock_count=$model->getStockCount()){
                        if($limit_max_qty=$model->getLimitMaxQty(\Yii::$app->user->getId())){
                            $stock_count=min($limit_max_qty,$stock_count);
                        }
                    }
                    if($stock_count>0){
                        if($qty<=100 && $stock_count>=$qty){
                            $model->quantity = $qty;
                        }else{
                            throw new ErrorException('最大可购买'.min($stock_count,100).'件');
                        }
                    }else{
                        throw new ErrorException('库存不足');
                    }
                    Track::add($model->product_base_id,'buy_now');
                    \Yii::$app->fxcart->update($model->getCartPositionFx($params), $qty);
                    $data=['status'=>1,'data'=>Url::to(['/cart/index'])];
                }else{
                    throw new ErrorException('商品不存在或者已经下架');
                }
            }else{
                throw new ErrorException('数据加载失败');
            }
        }catch (ErrorException $e){
            $data=['status'=>0,'message'=>$e->getMessage()];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionResetSelectProductTotal()
    {
        $json = [];
        $json['result'] = true;
        if (\Yii::$app->request->post('cart_ids')) {
            $keys = \Yii::$app->request->post('cart_ids');
            $cart_ids = explode(",", $keys);
            $cart_products = \Yii::$app->fxcart->getPositions();
            $cart_select_products = [];
            foreach ($cart_products as $pt_key => $product) {
                if (in_array($pt_key, $cart_ids)) {
                    $cart_select_products[$pt_key] = $product;
                }
            }
            // Totals
            $order_total = $this->getTotal($cart_select_products);
            $json['total'] = $order_total;
            $json['text'] = "￥" . number_format($order_total, 2);
            $json['countproducts'] = count($cart_select_products);
        } else {
            $json['total'] = 0;
            $json['text'] = "￥0.00";
            $json['countproducts'] = 0;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $json;
    }

    public function getTotal($cart_selected_products)
    {
        $totals = 0;
        foreach ($cart_selected_products as $product) {
            $totals = $totals +  $product->getCost();
        }
        return $totals;
    }

}
