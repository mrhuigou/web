<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace h5\widgets;
use yii\bootstrap\Widget;

class MainMenu extends Widget {
	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$cur_param=\Yii::$app->request->getPathInfo();
		switch($cur_param){
			case "site/index":
				$cur="home";
				break;
			case "product/category":
				$cur="category";
				break;
            case "coupon/index":
				$cur="coupon";
				break;
            case "cart/index":
				$cur="cart";
				break;
			case "user/index":
				$cur="user";
				break;
			default:
				$cur="home";
				break;
		}
		return $this->render('main-menu',['cur'=>$cur]);
	}
}