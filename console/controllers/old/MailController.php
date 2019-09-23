<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/4/21
 * Time: 23:39
 */

namespace console\controllers\old;
use api\models\V1\Email;
use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;

class MailController extends \yii\console\Controller
{

    public function actionSend($id){
        $model=Email::findOne(['id'=>$id,'status'=>0]);
        if($model){
            if($model->file_path && file_exists($model->file_path)){
                $attachment = file_get_contents($model->file_path);
                Yii::$app->mailer->compose()
                    ->setTo($model->to)
                    ->setFrom([\Yii::$app->params['supportEmail'] => "家润慧生活"])
                    ->setSubject($model->subject)
                    ->setTextBody($model->message)
                    ->attachContent($attachment,unserialize($model->file_option))
                    ->send();
                $model->status=1;
                $model->save();
                @unlink($model->file_path);//删除服务器生成的文件
            }else{
                Yii::$app->mailer->compose()
                    ->setTo($model->to)
                    ->setFrom([\Yii::$app->params['supportEmail'] => "家润慧生活"])
                    ->setSubject($model->subject)
                    ->setTextBody($model->message)
                    ->send();
                $model->status=1;
                $model->save();
            }
        }
    }
    public function actionNotice(){
	    $url='http://m.365jiarun.com/';
	    $message=[
	    	'title'=>'家润网双11钜惠即将结束!!!',
		    'name'=>'双11狂欢，1降到底',
		    'date_time'=>'2016年11月15号',
		    'special'=>'满111.1元送进口葡萄酒活动明日结束',
		    'remark'=>'青啤、白花蛇草、蒙牛震撼低价，多款爆品满百减20元。活动力度空前，仅剩36小时，错过再等一年，点击立即抢购！！！',

	    ];
	    $open_ids=[];
//	    $model=CustomerAuthentication::find()->where(['and',"provider='weixin'","openid is not null"])->all();
//	    foreach ($model as $user){
//		    $open_ids[]=$user->openid;
//	    }
		if($open_ids){
			$template_id="5u0WptS6P5y9Iy7Y4L80fysAN0iEqD_xIP0fquFV1ic";
			foreach ($open_ids as $open_id){
				$body = [
					'touser' => $open_id,
					'template_id' => $template_id,
					'url' => $url,
					'topcolor' => '#173177',
					'data' => $message
				];
				$this->send($body);
			}
		}
		echo "complate";
    }

	protected function send($body)
	{
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->getAccessToken();
		$http=new Client();
		$response=$http->post($url,Json::encode($body))->send();
		if ($response->isOk) {
			if($response->data['errcode']==0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function getAccessToken(){

		return \Yii::$app->wechat->getAccessToken();
	}
}