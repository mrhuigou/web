<?php

namespace h5\controllers;

use api\models\V1\CheckoutOrder;
use api\models\V1\ClubActivity;
use api\models\V1\ClubActivityAdmin;
use api\models\V1\ClubActivityUser;
use api\models\V1\ClubActivityUserTicket;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubUserInvite;
use api\models\V1\ClubUserInviteLog;
use api\models\V1\ClubUserLike;
use api\models\V1\Email;
use api\models\V1\Order;
use api\models\V1\OrderActivity;
use api\models\V1\OrderMerge;
use api\models\V1\OrderScan;
use dosamigos\qrcode\QrCode;
use h5\models\ClubActivityCancelForm;
use h5\models\ClubActivityForm;
use h5\models\ActivityForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use common\component\Curl\Curl;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;

class ClubActivityController extends \yii\web\Controller
{
    public function actions(){
        return [
            'download' => [
                'class' => 'h5\widgets\Wx\ImageAction',
            ]
        ];
    }
    public function actionIndex()
    {
	    if (\Yii::$app->user->isGuest) {
		    return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
	    }
        $run_status=\Yii::$app->request->get('run_status');
        $query=[];
        if($run_status == -1){
            $query='signup_end<Now()';
        }
        if(!$run_status){
            $query='signup_end>=Now()';
        }
       $model=ClubActivity::find()->where(['status'=>1]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->where($query)->andWhere(['status'=>1])->orderBy([ 'end_datetime' => SORT_DESC]),
            'pagination' => [
                'pagesize' => '4',
            ]
        ]);
        return $this->render('index',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionDetail(){
	    if (\Yii::$app->user->isGuest) {
		    return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
	    }
        $model=ClubActivity::findOne(['id'=>\Yii::$app->request->get('id')]);
        if($model){
            $model->click_count= $model->click_count+1;
            $model->save();
            $comment=ClubUserComment::find()->where(['type'=>'activity','type_id'=>$model->id])->orderBy('creat_at desc')->limit(5)->all();
            return $this->render('detail',['model'=>$model,'comments'=>$comment]);
        }else{
            throw new NotFoundHttpException("没有找到活动！");
        }
    }
    public function actionQrcode(){
        if (!\Yii::$app->user->isGuest) {
            $code=Yii::$app->request->get('code');
            Yii::$app->response->format = Response::FORMAT_RAW;
            QrCode::png($code,false,0,6,2);
        }
    }
    public function actionCancel(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model=Order::findOne(['order_id'=>Yii::$app->request->get('order_id'),'order_type_code'=>'activity','customer_id'=>Yii::$app->user->getId(),'status'=>1]);
        if($model){
            $model->order_status_id=7;
            $model->date_modified=date('Y-m-d H:i:s',time());
            $model->save();
            return $this->redirect(['club-activity/my-info','order_id'=>Yii::$app->request->get('order_id')]);
        }else{
            throw new NotFoundHttpException("没有找到活动！");
        }
    }
    public function actionApply(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
            $model=new ClubActivityForm(\Yii::$app->request->get('id'));
            if($model->load(\Yii::$app->request->post()) && $trade_no=$model->save()){
	            if($order_merage_model=OrderMerge::findOne(['merge_code'=>$trade_no])) {
		            if ($order_merage_model->total == 0) {
                        $order_merage_model=new CheckoutOrder();
                        $order_merage_model->out_trade_no=$trade_no;
                        $order_merage_model->transaction_id=$trade_no;
                        $order_merage_model->staus=2;
                        $order_merage_model->payment_method="免费支付";
                        $order_merage_model->payment_code="free_checkout";
                        $order_merage_model->remak=$trade_no;
                        $order_merage_model->save();
			            return $this->redirect(['/club-activity/complate','redirect'=>Url::to(['/club-activity/detail','id'=>\Yii::$app->request->get('id')])]);
		            }else{
                        return $this->redirect(['/payment/index', 'trade_no' => $trade_no,'redirect'=>Url::to(['/club-activity/detail','id'=>\Yii::$app->request->get('id')])]);
                    }
	            }
            }
            return $this->render('apply',['model'=>$model]);
    }
    public function actionComplate(){
	    if (\Yii::$app->user->isGuest) {
		    return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
	    }

	    return $this->render('complate',['back_url'=>\Yii::$app->request->get('redirect')]);
    }
    public function actionUser(){
        $model=OrderActivity::find()->joinWith(['order'=>function($query){ $query->andWhere(['not in','order_status_id',['1','7']]);}]);
        $model=$model->where(['activity_id'=>\Yii::$app->request->get('id')]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->orderBy('order_activity_id desc'),
            'pagination' => [
                'pagesize' => '8',
            ]
        ]);
        return $this->render('user',['dataProvider'=>$dataProvider,'model'=>$model]);
    }
    public function actionMy(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if(\Yii::$app->request->get('type') == 'created'){
            $type='created';
        }else{
            $type='joined';
        }
        if($type == 'created'){
            $list_model=ClubActivityAdmin::find()->where(['customer_id'=>\Yii::$app->user->getId()])->all();
            $act_data=[];
           if($list_model){
               foreach($list_model as $list){
                   $act_data[]=$list->activity_id;
               }
           }
            $model=ClubActivity::find()->where(['customer_id'=>\Yii::$app->user->getId()])->orWhere(['id'=>$act_data]);
            $dataProvider = new ActiveDataProvider([
                'query' => $model->orderBy('creat_at desc'),
                'pagination' => [
                    'pagesize' => '8',
                ]
            ]);
            return $this->render('my-created',['dataProvider'=>$dataProvider,'model'=>$model,'type'=>$type]);
        }else{
            $model=Order::find()->where(['customer_id'=>\Yii::$app->user->getId(),'order_type_code'=>'activity']);
            $dataProvider = new ActiveDataProvider([
                'query' => $model->orderBy('order_id desc'),
                'pagination' => [
                    'pagesize' => '8',
                ]
            ]);
            return $this->render('my-joined',['dataProvider'=>$dataProvider,'model'=>$model,'type'=>$type]);
        }
    }
    public function actionMyInfo(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if($model=Order::findOne(['order_id'=>Yii::$app->request->get('order_id'),'order_type_code'=>'activity','customer_id'=>Yii::$app->user->getId()])){
            return $this->render('my-info',['model'=>$model]);
        }else{
            throw new NotFoundHttpException("没有找到订单！");
        }
    }



    public function actionManage(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
        }
        $view=Yii::$app->request->get('view');
        $list_model=ClubActivityAdmin::find()->where(['activity_id'=>\Yii::$app->request->get('id')])->all();
        $admin_user_data=[];
        if($list_model){
            foreach($list_model as $list){
                $admin_user_data[]=$list->customer_id;
            }
        }
        if($model=ClubActivity::findOne(['id'=>\Yii::$app->request->get('id')])){
            array_push($admin_user_data,$model->customer_id);
            if(!in_array(Yii::$app->user->getId(),$admin_user_data)){
                throw new NotFoundHttpException("没有找到活动！");
            }
            if($view=='enroll'){
                $users=OrderActivity::find()->joinWith(['order'=>function($query){ $query->andWhere(['not in','order_status_id',['1','7']]);}]);
                $users->where(['activity_id'=>$model->id]);
                $dataProvider = new ActiveDataProvider([
                    'query' => $users->orderBy('order_activity_id desc'),
                    'pagination' => [
                        'pagesize' => '8',
                    ]
                ]);
                return $this->render('manage-enroll',['dataProvider'=>$dataProvider,'model'=>$users,'view'=>$view]);
           }elseif($view=='comment'){
                $comment=ClubUserComment::find()->where(['type'=>'activity','type_id'=>$model->id]);
                $dataProvider = new ActiveDataProvider([
                    'query' => $comment->orderBy('creat_at desc'),
                    'pagination' => [
                        'pagesize' => '8',
                    ]
                ]);
                return $this->render('manage-comment',['dataProvider'=>$dataProvider,'model'=>$model,'comment'=>$comment,'view'=>$view]);
            }elseif($view=='like'){
                $like=ClubUserLike::find()->where(['type'=>'activity','type_id'=>$model->id]);
                $dataProvider = new ActiveDataProvider([
                    'query' => $like->orderBy('creat_at desc'),
                    'pagination' => [
                        'pagesize' => '8',
                    ]
                ]);
                return $this->render('manage-like',['dataProvider'=>$dataProvider,'model'=>$model,'like'=>$like,'view'=>$view]);
            }else{
                return $this->render('manage',['model'=>$model,'view'=>$view]);
            }
        }else{
            throw new NotFoundHttpException("没有找到活动！");
        }
    }
    public function actionCreate(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model = new ActivityForm();
        if($model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['club-activity/manage',"id"=>$model->id]);
        }
        return $this->render('create',['model'=>$model]);
    }

    public function actionClose(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model=ClubActivity::findOne(['id'=>\Yii::$app->request->get('id'),'customer_id'=>Yii::$app->user->getId()]);
        if($model){
            $model->status=0;
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->save();
            return $this->redirect(['/club-activity/manage','id'=>\Yii::$app->request->get('id')]);
        }
    }
    public function actionOpen(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model=ClubActivity::findOne(['id'=>\Yii::$app->request->get('id'),'customer_id'=>Yii::$app->user->getId()]);
        if($model){
            $model->status=1;
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->save();
            return $this->redirect(['/club-activity/manage','id'=>\Yii::$app->request->get('id')]);
        }
    }
    public function actionChecked(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        try{
            if($data=Yii::$app->request->post('data') && Yii::$app->request->post('id')){
               if($model=OrderScan::findOne(['scan_data'=>$data,'status'=>0])){
                  if( $model->order->activity->activity_id!=Yii::$app->request->post('id')){
                       throw new \Exception("此票不属于当前活动");
                   }else{
                       if(Yii::$app->request->post('act')=='confirm'){
                           $model->status=1;
                           $model->date_modified=date('Y-m-d H:i:s',time());
                           $model->save();
                           $json=[
                               'result'=>1,
                               'data'=>"检票成功",
                           ];
                       }else{
                           $json=[
                               'result'=>1,
                               'data'=> $model->order->activity->activity_item_name."[".$model->order->activity->quantity."]",
                           ];
                       }
                   }
               }else{
                   throw new \Exception("此票已经检过了！");
               }
            }else{
                throw new \Exception("参数不合法");
            }
        } catch (\Exception $e) {
            $json=[
                'result'=>0,
                'data'=>$e->getMessage(),
            ];
        }
        echo Json::encode($json);
    }

    public function actionSendmail(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if($model = ClubActivity::findOne(['id'=>\Yii::$app->request->get('id'),'customer_id'=>\Yii::$app->user->getId()])){
            $activity_users=OrderActivity::find()->joinWith(['order'=>function($query){ $query->andWhere(['not in','order_status_id',['1','7']]);}]);
            $activity_users->where(['activity_id'=>$model->id]);
            $header = array(
              '姓名'=>'string',
              '电话'=>'string',
              '名称'=>'string',
              '项目'=>'string',
              '数量'=>'string',
              '单价'=>'string',
              '总计'=>'string',
              '备注'=>'string',
              '创建时间'=>'string',
            );

            $writer = new XLSXWriter();
            $writer->writeSheetHeader('Sheet1', $header );
            foreach ($activity_users as $key => $value) {
                # code...
                $data=[
                    $value->order->firstname,
                    $value->order->telephone,
                    $value->activity_name,
                    $value->activity_item_name,
                    $value->quantity,
                    $value->price,
                    $value->total,
                    $value->order->remark,
                    $value->order->date_added,
                ];
                $writer->writeSheetRow('Sheet1', $data);
            }
            $pre = date("YmdHis").rand(1000,9999);
            $writer->writeToFile('/tmp/'.$pre.'.xlsx');

            if(isset($data['email']) && preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $data['email'])){
                $message="附件是您创建的活动「".$model->title."」的已报名用户名单，请注意查收。感谢使用家润慧生活，祝您生活愉快。";
                if(file_exists('/tmp/'.$pre.'.xlsx')){
                    $attachment = file_get_contents('/tmp/'.$pre.'.xlsx');
                    Yii::$app->mailer->compose()
                        ->setTo($data['email'])
                        ->setFrom([\Yii::$app->params['supportEmail'] => "家润慧生活"])
                        ->setSubject('活动「'.$model->title.'」已报名用户名单')
                        ->setTextBody('尊敬的会员：'.$message)
                        ->attachContent($attachment,['fileName'=>$pre.'.xlsx','contentType'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->send();
                }
                return '发送成功';
            }else{
                return "请输入正确的邮箱";
            }
        }else{
            return "数据错误";
        }
        
        
    }


    public function actionFind()
    {
        // $model = CustomerMap::find()->select(['latitude','longitude'])->orderBy(['data_added'=>'desc'])->groupBy(['customer_id'])->limit(100)->asArray()->all();
        $post = Yii::$app->request->post();
        $url = 'http://api.map.baidu.com/telematics/v3/local';
        $curl=new Curl();
        $result=$curl->get($url,["location"=>$post["lng"].','.$post["lat"],"keyWord"=>$post["keyword"],"output"=>'json',"ak"=>"F3c76e3a3028c5a74c04b18bc09173aa","out_coord_type"=>"bd09ll"]);
        $data=Json::decode($result);
        return $result;
    }

}
