<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 16:58
 */
namespace api\controllers\club\v2;
use api\controllers\club\v2\filters\AccessControl;
use api\models\V1\ClubActivity;
use api\models\V1\ClubActivityAdmin;
use api\models\V1\ClubActivityItems;
use api\models\V1\ClubActivityUser;
use api\models\V1\ClubActivityUserTicket;
use api\models\V1\Order;
use api\models\V1\OrderMerge;
use common\component\image\Image;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
use \yii\rest\Controller;
use Yii;

class ActivityController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }
    public function actionList(){
        $run_status=\Yii::$app->request->post('status');
        $query=[];
        if($run_status === "0"){
            $query='signup_end<Now()';
        }
        if( $run_status!=="0"){
            $query='signup_end>=Now()';
        }
        $data=[];
        $model=ClubActivity::find()->where(['status'=>1])->andWhere($query)->orderBy([ 'creat_at' => SORT_DESC]);
        $offset=\Yii::$app->request->post('offset')?\Yii::$app->request->post('offset'):0;
        $limit=\Yii::$app->request->post('limit')?\Yii::$app->request->post('limit'):5;
        $data['totalCount']=$model->count();
        $model = $model->offset($offset)->limit($limit)->all();
        $data['list']=[];
        if($model){
            foreach($model as $value){
                $status=1;
                if(time()>strtotime($value->signup_end)){
                    $status=0;
                }
                $data['list'][]=[
                    'id'=>$value->id,
                    'title'=>$value->title,
                    'image'=>Image::resize($value->image,100,100,9),
                    'signup_end'=>$value->signup_end,
                    'begin_datetime'=>$value->begin_datetime,
                    'end_datetime'=>$value->end_datetime,
                    'address'=>$value->address,
                    'limit'=>$value->qty,
                    'fee'=>$value->fee,
                    'user_number'=>$value->tickets,
                    'click_count'=>$value->click_count,
                    'like_count'=>$value->like_count,
                    'comment_count'=>$value->comment_count,
                    'share_count'=>$value->share_count,
                    'user_invite_count'=>0,
                    'status'=>$status,
                    'join_status'=>$value->join?1:0,
                    'like_status'=>$value->likeStatus?1:0,
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionDetail(){
        if($model=ClubActivity::findOne(['id'=>\Yii::$app->request->post('activity_id')])){
            $status=1;
            if(time()>strtotime($model->signup_end)){
                $status=0;
            }
            $tag=[];
            if($model->tag){
                foreach($model->tag as $t){
                    $tag[]=[
                        'id'=>$t->id,
                        'name'=>$t->name,
                        'total_count'=>$t->total_count,
                    ];
                }
            }
            $data=[
                'id'=>$model->id,
                'title'=>$model->title,
                'description'=>$model->description,
                'image'=>Image::resize($model->image,100,100,9),
                'signup_end'=>$model->signup_end,
                'begin_datetime'=>$model->begin_datetime,
                'end_datetime'=>$model->end_datetime,
                'address'=>$model->address,
                'limit'=>$model->qty,
                'fee'=>$model->fee,
                'user_number'=>$model->tickets,
                'click_count'=>$model->click_count,
                'like_count'=>$model->like_count,
                'comment_count'=>$model->comment_count,
                'share_count'=>$model->share_count,
                'user_invite_count'=>0,
                'status'=>$status,
                'join_status'=>$model->join?1:0,
                'like_status'=>$model->likeStatus?1:0,
                'tag'=>$tag,
            ];
            return Result::OK($data);
        }else{
            return Result::Error('找不到数据');
        }

    }
    public function actionItems(){
        $data=[];
        $model=ClubActivityItems::find()->where(['activity_id'=>\Yii::$app->request->post('activity_id'),'status'=>1])->all();
        if($model){
            foreach($model as $value){
                $data[]=[
                    'id'=>$value->id,
                    'activity_id'=>$value->activity_id,
                    'name'=>$value->name,
                    'quantity'=>$value->quantity?$value->quantity:$value->activity->qty,
                    'user_number'=>$value->qty,
                    'fee'=>$value->fee
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionUser(){
        $data=[];
        if($model=ClubActivityUser::find()->where(['activity_id'=>\Yii::$app->request->post('activity_id'),'status'=>1])->orderBy('creat_at desc')->all()){
            foreach($model as $value){
                $data[]=[
                    'customer_id'=>$value->customer_id,
                    'nickname'=>$value->customer->nickname,
                    'photo'=>Image::resize($value->customer->photo,100,100),
                    'status'=>$value->status,
                    'username'=>$value->username,
                    'telephone'=>$value->telephone,
                    'activity_title'=>$value->activity->title,
                    'activity_item'=>$value->activityItems?$value->activityItems->name:'免费报名',
                    'quantity'=>$value->quantity,
                    'total'=>$value->total,
                     'tickets_count'=>count($value->tickets),
                     'tickets_use_count'=>count($value->useTickets),
                     'creat_at'=>$value->creat_at
                ];
            }
        }
        return Result::OK(['total'=>count($data),'list'=>$data]);
    }
    public function actionMyJoin(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $model=ClubActivityUser::find()->where(['customer_id'=>\Yii::$app->user->getId(),'status'=>1]);
        $offset=\Yii::$app->request->post('offset')?\Yii::$app->request->post('offset'):0;
        $limit=\Yii::$app->request->post('limit')?\Yii::$app->request->post('limit'):5;
        $data['totalCount']=$model->count();
        $model = $model->offset($offset)->limit($limit)->all();
        $data['list']=[];
        if($model){
            foreach($model as $activity){
                if($activity->activity){
                    $value=$activity->activity;
                }else{
                    continue;
                }

                $status=1;
                if(time()>strtotime($value->signup_end)){
                    $status=0;
                }
                $data['list'][]=[
                    'id'=>$value->id,
                    'title'=>$value->title,
                    'image'=>Image::resize($value->image,100,100,9),
                    'signup_end'=>$value->signup_end,
                    'begin_datetime'=>$value->begin_datetime,
                    'end_datetime'=>$value->end_datetime,
                    'address'=>$value->address,
                    'limit'=>$value->qty,
                    'fee'=>$value->fee,
                    'user_number'=>$value->tickets,
                    'click_count'=>$value->click_count,
                    'like_count'=>$value->like_count,
                    'comment_count'=>$value->comment_count,
                    'share_count'=>$value->share_count,
                    'user_invite_count'=>$value->userInviteCount,
                    'status'=>$status,
                    'like_status'=>$value->likeStatus?1:0,
                ];
            }
        }
        return Result::OK($data);
    }

    public function actionTicket(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        if(!$activity_id=\Yii::$app->request->post('activity_id')){
            Result::Error('缺少活动ID');
        }
        $data=[];
        $model=ClubActivityUser::findOne(['customer_id'=>\Yii::$app->user->getId(),'activity_id'=>$activity_id,'status'=>1]);
        if($model){
            $ticket=ClubActivityUserTicket::find()->where(['activity_user_id'=>$model->id])->all();
            $ticket_data=[];
            if($ticket){
                foreach($ticket as $val){
                    $ticket_data[]=[
                        'code'=>$val->code,
                        'status'=>$val->status,
                        'creat_at'=>$val->creat_at,
                        'update_at'=>$val->update_at,
                    ];
                }
            }
            $data[]=[
                'username'=>$model->username,
                'telephone'=>$model->telephone,
                 'activity_name'=>$model->activity->title,
                 'activity_items'=>$model->activityItems?$model->activityItems->name:"免费报名",
                  'quantity'=>$model->quantity,
                  'total'=>$model->total,
                'ticket_data'=>$ticket_data,
                'status'=>$model->status,
                'creat_at'=>$model->creat_at,
                'update_at'=>$model->update_at,
            ];
        }
        return Result::OK($data);
    }
    public function actionApply(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $activity_id=\Yii::$app->request->post('activity_id');
        $activity_items_id=\Yii::$app->request->post('activity_items_id');
        $quantity=\Yii::$app->request->post('quantity');
        $username=\Yii::$app->request->post('username');
        $telephone=\Yii::$app->request->post('telephone');
        $remark=\Yii::$app->request->post('remark');
        if(!$activity_id){
            Result::Error('缺少活动ID');
        }
        if(!$quantity){
            Result::Error('缺少报名数量');
        }
        if($quantity<1){
            Result::Error('报名数量大于0的整数');
        }
        if(!$username){
            Result::Error('用户姓名不能为空');
        }
        if(!$telephone){
            Result::Error('手机不能为空');
        }
        if(!$model=ClubActivity::findOne(['id'=>$activity_id,'status'=>1])){
            Result::Error('活动不存在');
        }
        if(strtotime($model->signup_end)<time()){
            Result::Error('活动报名已经截止');
        }
        $total=0;
        if($model->items){
            if(!$items=ClubActivityItems::findOne(['activity_id'=>$activity_id,'id'=>$activity_items_id,'status'=>1])){
                Result::Error('活动项目ID不存在');
            }
            if($items->quantity && $quantity>($items->quantity - $items->qty)){
                Result::Error('活动名额不足');
            }else{
                if($model->qty &&  $quantity>($model->qty - $model->tickets)){
                    Result::Error('活动名额不足');
                }
            }
            $total=$quantity*$items->fee;
        }else{
            if($model->qty &&  $quantity>($model->qty - $model->tickets)){
                Result::Error('活动名额不足');
            }
            $total=$quantity*$model->fee;
        }
        if($data=ClubActivityUser::findOne(['customer_id'=>\Yii::$app->user->getId(),'activity_id'=>$activity_id,'status'=>1])){
            Result::Error('你已经报过名了');
        }

        $AuModel=new ClubActivityUser();
        $AuModel->customer_id=\Yii::$app->user->getId();
        $AuModel->activity_id=$activity_id;
        $AuModel->activity_items_id=$activity_items_id;
        $AuModel->username=$username;
        $AuModel->telephone=$telephone;
        $AuModel->quantity=$quantity;
        $AuModel->total=$total;
        $AuModel->remark=$remark;
        if($total==0){
            $AuModel->ticket_code=md5(time());
            $AuModel->status=1;
            $AuModel->update_at=date('Y-m-d H:i:s',time());
        }else{
            $AuModel->status=0;
        }
        $AuModel->creat_at=date('Y-m-d H:i:s',time());
        if(!$AuModel->save()){
            Result::Error('数据异常');
        }
        if($total==0){
            for($i=0;$i<$quantity;$i++){
                $t=new ClubActivityUserTicket();
                $t->activity_user_id=$AuModel->id;
                $t->code= md5($AuModel->id.'-'.$i);
                $t->creat_at=date('Y-m-d H:i:s',time());
                $t->save();
            }
        }
        if($total){
            //创建活动订单
            //订单主数据
            $Order_model = new Order();
            $Order_model->order_no = date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $Order_model->order_type_code = strtoupper('activity');
            $Order_model->platform_id = 1;
            $Order_model->platform_name = "智慧生活";
            $Order_model->platform_url = "http://m.mrhuigou.com/";
            $Order_model->store_id = 1;
            $Order_model->store_name = '每日惠购自营店';
            $Order_model->store_url = '';
            $Order_model->customer_group_id = Yii::$app->user->identity['customer_group_id'];
            $Order_model->customer_id = Yii::$app->user->getId();
            $Order_model->firstname = Yii::$app->user->identity['firstname'];
            $Order_model->lastname = Yii::$app->user->identity['lastname'];
            $Order_model->email = Yii::$app->user->identity['email'];
            $Order_model->telephone = Yii::$app->user->identity['telephone'];
            $Order_model->gender = Yii::$app->user->identity['gender'];
            $Order_model->payment_method = "";
            $Order_model->payment_code = "";
            $Order_model->total = $total;
            $Order_model->comment ='';
            $Order_model->order_status_id = 1;
            $Order_model->affiliate_id = 0;
            $Order_model->commission = 0;
            $Order_model->language_id = 2;
            $Order_model->currency_id = 4;
            $Order_model->currency_code = 'CNY';
            $Order_model->currency_value = 1;
            $Order_model->ip = Yii::$app->request->userIP;
            $Order_model->user_agent = Yii::$app->request->userAgent;
            $Order_model->accept_language = Yii::$app->request->getPreferredLanguage();
            $Order_model->date_added = date("Y-m-d H:i:s", time());
            $Order_model->invoice_temp = "不需要发票";
            $Order_model->invoice_title = "";
            $Order_model->trade_account = "";
            $Order_model->use_date = "";
            $Order_model->time_range = "";
            $Order_model->use_nums = "";
            $Order_model->use_code = "";
            $Order_model->delivery_type = "1";
            $Order_model->in_cod = 0;
            $Order_model->sent_to_erp = "N";
            $Order_model->sent_time = "";
            $Order_model->parent_id = "";
            $Order_model->cycle_store_id = "";
            $Order_model->cycle_id = "";
            $Order_model->periods = "";
            if (!$Order_model->save(false)) {
                Result::Error('订单数据异常');
            }
            $AuModel->order_id=$Order_model->order_id;
            $AuModel->save();
            $merge=new OrderMerge();
            $merge->merge_code=date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $merge->order_ids=$Order_model->order_id;
            $merge->total=$total;
            $merge->customer_id=Yii::$app->user->getId();
            $merge->date_added=date('Y-m-d H:i:s',time());
            $merge->status=0;
            $merge->save();
            return Result::OK(['order_no'=>$merge->merge_code,'total'=>$total]);
        }
        return Result::OK();
    }
    public function actionMyCheckActivity(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $data=[];
        $model=ClubActivityAdmin::find()->where(['customer_id'=>Yii::$app->user->getId()])->all();
        if($model){
            foreach($model as $value){
                $data[]=[
                    'activity_id'=>$value->activity->id,
                    'title'=>$value->activity->title,
                    'image'=>Image::resize($value->activity->image,100,100,9),
                    'signup_end'=>$value->activity->signup_end,
                    'begin_datetime'=>$value->activity->begin_datetime,
                    'end_datetime'=>$value->activity->end_datetime,
                    'address'=>$value->activity->address,
                    'fee'=>$value->activity->fee,
                    'tickets_count'=>$value->activity->tickets,
                    'tickets_use_count'=>$value->activity->useTickets,
            ];
            }
        }
        return Result::OK($data);
    }
    public function actionCheckTicket(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        if($activity_id=Yii::$app->request->post('activity_id')){
            if(!$model=ClubActivityAdmin::findOne(['activity_id'=>$activity_id,'customer_id'=>Yii::$app->user->getId()])){
                Result::Error('您没有检票权限！');
            }
        }else{
            Result::Error('缺少activity_id');
        }
        if(!$data=Yii::$app->request->post('data')){
            Result::Error('缺少data');
        }
        if($model=ClubActivityUserTicket::findOne(['code'=>$data,'status'=>0])){
            $model->status=1;
            $model->update_at=date('Y-m-d H:i:s',time());
            $model->save();
            return Result::OK();
        }else{
            Result::Error('此票已失效！');
        }
    }
    public function actionSelectTicket(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        if($activity_id=Yii::$app->request->post('activity_id')){
            if(!$model=ClubActivityAdmin::findOne(['activity_id'=>$activity_id,'customer_id'=>Yii::$app->user->getId()])){
                Result::Error('您没有检票权限！');
            }
        }else{
            Result::Error('缺少activity_id');
        }
        if(!$data=Yii::$app->request->post('data')){
            Result::Error('缺少data');
        }
        if($model=ClubActivityUserTicket::findOne(['code'=>$data,'status'=>0])){
            $data=[
                'customer_id'=>$model->activityUser->customer_id,
                'nickname'=>$model->activityUser->customer->nickname,
                'photo'=>Image::resize($model->activityUser->customer->photo,100,100),
                'username'=>$model->activityUser->username,
                'telephone'=>$model->activityUser->telephone,
                'activity_title'=>$model->activityUser->activity->title,
                'activity_item'=>$model->activityUser->activityItems?$model->activityUser->activityItems->name:'免费报名',
                'quantity'=>$model->activityUser->quantity,
                'total'=>$model->activityUser->total,
                'tickets_count'=>count($model->activityUser->tickets),
                'tickets_use_count'=>count($model->activityUser->useTickets),
                'creat_at'=>$model->activityUser->creat_at
            ];
            return Result::OK($data);
        }else{
            Result::Error('此票已失效！');
        }
    }

}