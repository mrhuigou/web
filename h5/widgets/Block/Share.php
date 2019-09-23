<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace h5\widgets\Block;
use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerSharePage;
use api\models\V1\CustomerSharePageLog;
use yii\bootstrap\Widget;
use yii\web\NotFoundHttpException;

class Share extends Widget{
	public function init()
	{
		parent::init();
	}
	public function run()
	{
		if(!\Yii::$app->user->isGuest){
			$from=\Yii::$app->request->get('from','other');
			$trace_code=\Yii::$app->request->get('trace_code');
			if($trace_code && $share_model=CustomerSharePage::findOne(['code'=>\Yii::$app->request->get('trace_code')])){
				$share_log=new CustomerSharePageLog();
				$share_log->customer_share_page_id=$share_model->id;
				$share_log->customer_id=\Yii::$app->user->getId();
				$share_log->from=$from;
				$share_log->url=\Yii::$app->request->getAbsoluteUrl();
				$share_log->create_at=time();
				$share_log->save();
			}
			if($model=CustomerAffiliate::findOne(['customer_id'=>\Yii::$app->user->getId(),'status'=>1])){
				return $this->render('share');
			}
		}
	}
}
