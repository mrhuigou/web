<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace h5\widgets\Wx;
use yii\bootstrap\Widget;

class Image extends Widget{

    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $useragent=\Yii::$app->request->getUserAgent();
        if(strpos(strtolower($useragent), 'micromessenger')){
            return $this->render('image');
        }
    }
}