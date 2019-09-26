<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/4/21
 * Time: 23:39
 */
namespace console\controllers\old;
use api\models\V1\Email;
use common\component\Mq\HttpConsumer;
use common\component\Mq\HttpProducer;
use Yii;

class EmailController extends \yii\console\Controller {

	public function actionIndex()
	{
		//构造消息发布者
		$producer = new HttpProducer();
		//启动消息发布者
		$producer->process();
	}

	public function actionConsumer()
	{
		//构造消息订阅者
		$consumer = new HttpConsumer();
		//启动消息订阅者
		$consumer->process();
	}

	public function actionSend($id)
	{
		$model = Email::findOne(['id' => $id, 'status' => 0]);
		if ($model) {
			if ($model->file_path) {
				$attachment = file_get_contents($model->file_path);
				Yii::$app->mailer->compose()
					->setTo($model->to)
					->setFrom([\Yii::$app->params['supportEmail'] => "每日惠购"])
					->setSubject($model->subject)
					->setTextBody($model->message)
					->attachContent($attachment, unserialize($model->file_option))
					->send();
				$model->status = 1;
				$model->save();
				@unlink($model->file_path);//删除服务器生成的文件
			} else {
				Yii::$app->mailer->compose()
					->setTo($model->to)
					->setFrom([\Yii::$app->params['supportEmail'] => "每日惠购"])
					->setSubject($model->subject)
					->setTextBody($model->message)
					->send();
				$model->status = 1;
				$model->save();
			}
		}
	}
}