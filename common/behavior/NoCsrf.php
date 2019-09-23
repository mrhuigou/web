<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/21
 * Time: 10:49
 */
namespace common\behavior;
use Yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\web\Controller;

class NoCsrf extends Behavior {
	public $actions = [];
	public $controller;

	public function events()
	{
		return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
	}

	public function beforeAction($event)
	{
		$action = $event->action->id;
		if (in_array($action, $this->actions)) {
			$this->controller->enableCsrfValidation = false;
		}
	}
}