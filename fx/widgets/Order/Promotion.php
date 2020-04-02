<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace fx\widgets\Order;
use yii\bootstrap\Widget;
class Promotion extends Widget
{   public $product;
    public function init()
    {
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('promotion',['model'=>$this->product->gift]);
    }
} 