<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace fx\widgets;
use common\component\Helper\Helper;
use yii\bootstrap\Widget;

class Header extends Widget{
    public $title;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
	    $useragent=\Yii::$app->request->getUserAgent();
        $device  = Helper::get_device_type();
        if($device == "android"){
            return $this->render('header',['title'=>$this->title]);
        }else{
//            if(strpos(strtolower($useragent), 'jiaruncustomerapp') || \Yii::$app->session->get('source_from_agent_wx_xcx')){
//                return $this->render('header-app',['title'=>$this->title]);
//            }else
            if(strpos(strtolower($useragent), 'micromessenger') && !\Yii::$app->session->get('source_from_agent_wx_xcx')){
                return $this->render('header-wx',['title'=>$this->title]);
            }elseif(strpos(strtolower($useragent), 'zhqdapp')){
                return $this->render('header-wx',['title'=>$this->title]);
            } else{
                return $this->render('header',['title'=>$this->title]);
            }
        }


    }
}