<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace h5\widgets;
use yii\bootstrap\Widget;
class StoreMainMenu extends Widget{
    public $code;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        return $this->render('store-main-menu',['code'=>$this->code]);
    }
}
