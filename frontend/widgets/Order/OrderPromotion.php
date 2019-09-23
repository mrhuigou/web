<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace frontend\widgets\Order;
use yii\bootstrap\Widget;
class OrderPromotion extends Widget
{   public $order;
    public function init()
    {
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('order_promotion',['model'=>$this->order->orderGifts]);
    }
} 