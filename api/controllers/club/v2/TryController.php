<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 16:58
 */
namespace api\controllers\club\v2;
use api\models\V1\Address;
use api\models\V1\ClubTry;
use api\models\V1\ClubTryUser;
use api\models\V1\CustomerCoupon;
use common\component\image\Image;
use \yii\rest\Controller;
use api\controllers\club\v2\filters\AccessControl;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
use Yii;
class TryController extends Controller
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
            $query='end_datetime<Now()';
        }
        if( $run_status!=="0" && $run_status!=="2"){
            $query=['and','begin_datetime<=Now()','end_datetime>=Now()'];
        }
        if($run_status == 2){
            $query='begin_datetime>Now()';
        }
        $data=[];
        $model=ClubTry::find()->where(['status'=>1])->andWhere($query)->orderBy(['sort_order' => SORT_ASC, 'creat_at' => SORT_DESC]);
        $offset=\Yii::$app->request->post('offset')?\Yii::$app->request->post('offset'):0;
        $limit=\Yii::$app->request->post('limit')?\Yii::$app->request->post('limit'):5;
        $data['totalCount']=$model->count();
        $model = $model->offset($offset)->limit($limit)->all();
        $data['list']=[];
        if($model){
            foreach($model as $value){
                $status=1;
                if(time()>strtotime($value->end_datetime)){
                    $status=0;
                }
                if(time()<strtotime($value->begin_datetime)){
                    $status=2;
                }
                $data['list'][]=[
                    'id'=>$value->id,
                    'title'=>$value->title,
                    'image'=>Image::resize($value->image,100,100,9),
                    'begin_datetime'=>$value->begin_datetime,
                    'end_datetime'=>$value->end_datetime,
                    'product_base_id'=>$value->product_base_id,
                    'price'=>$value->price,
                    'quantity'=>$value->quantity,
                    'limit_user'=>$value->limit_user,
                    'user_number'=>count($value->user),
                    'click_count'=>$value->click_count,
                    'like_count'=>$value->like_count,
                    'comment_count'=>$value->comment_count,
                    'share_count'=>$value->share_count,
                    'user_invite_count'=>$value->userInviteCount,
                    'status'=>$status,
                    'join_status'=>$value->join,
                    'like_status'=>$value->likeStatus,
                    'receive_status'=>$value->receiveStatus,
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionDetail(){
        if($model=ClubTry::findOne(['id'=>\Yii::$app->request->post('try_id')])){
            $status=1;
            if(time()>strtotime($model->end_datetime)){
                $status=0;
            }
            if(time()<strtotime($model->begin_datetime)){
                $status=2;
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
                'begin_datetime'=>$model->begin_datetime,
                'end_datetime'=>$model->end_datetime,
                'product_base_id'=>$model->product_base_id,
                'price'=>$model->price,
                'quantity'=>$model->quantity,
                'limit_user'=>$model->limit_user,
                'user_number'=>count($model->user),
                'click_count'=>$model->click_count,
                'like_count'=>$model->like_count,
                'comment_count'=>$model->comment_count,
                'share_count'=>$model->share_count,
                'user_invite_count'=>$model->userInviteCount,
                'status'=>$status,
                'join_status'=>$model->join,
                'like_status'=>$model->likeStatus,
                'tag'=>$tag,
            ];
            if($model->receiveStatus==1){
            $data=array_merge($data,['receive_status'=>$model->receiveStatus]);
            }
            return Result::OK($data);
        }else{
            return Result::Error('找不到数据');
        }
    }
    public function actionUser(){
        $data=[];
        if($model=ClubTryUser::find()->where(['try_id'=>\Yii::$app->request->post('try_id')])->orderBy(['status' => SORT_DESC, 'creat_at' => SORT_DESC])->all()){
            foreach($model as $value){
                $data[]=[
                    'customer_id'=>$value->customer_id,
                    'nickname'=>$value->customer->nickname,
                    'photo'=>Image::resize($value->customer->photo,100,100),
                    'status'=>$value->status,
                ];
            }
        }
        return Result::OK(['total'=>count($data),'list'=>$data]);
    }
    public function actionMyJoin(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $model=ClubTryUser::find()->where(['customer_id'=>\Yii::$app->user->getId()]);
        $offset=\Yii::$app->request->post('offset')?\Yii::$app->request->post('offset'):0;
        $limit=\Yii::$app->request->post('limit')?\Yii::$app->request->post('limit'):5;
        $data['totalCount']=$model->count();
        $model = $model->offset($offset)->limit($limit)->all();
        $data['list']=[];
        if($model){
            foreach($model as $val){
                if($val->try){
                    $value=$val->try;
                }else{
                    continue;
                }
                $status=1;

                if(time()>strtotime($value->end_datetime)){
                    $status=0;
                }
                if(time()<strtotime($value->begin_datetime)){
                    $status=2;
                }

                $data['list'][]=[
                    'id'=>$value->id,
                    'title'=>$value->title,
                    'image'=>Image::resize($value->image,100,100,9),
                    'begin_datetime'=>$value->begin_datetime,
                    'end_datetime'=>$value->end_datetime,
                    'product_base_id'=>$value->product_base_id,
                    'price'=>$value->price,
                    'quantity'=>$value->quantity,
                    'limit_user'=>$value->limit_user,
                    'user_number'=>count($value->user),
                    'click_count'=>$value->click_count,
                    'like_count'=>$value->like_count,
                    'comment_count'=>$value->comment_count,
                    'share_count'=>$value->share_count,
                    'comment_count'=>$value->comment_count,
                    'status'=>$status,
                    'like_status'=>$value->likeStatus,
                    'receive_status'=>$value->receiveStatus,
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionApply(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        if(!$try_id=\Yii::$app->request->post('try_id')){
            Result::Error('缺少Try_id');
        }
        if(!$address_id=\Yii::$app->request->post('address_id')){
            Result::Error('缺少Address_id');
        }
        if($m=ClubTryUser::findOne(['customer_id'=>\Yii::$app->user->getId(),'try_id'=>$try_id])){
            Result::Error('您已经申请过了');
        }
        if(!$address=Address::findOne(['customer_id'=>\Yii::$app->user->getId(),'address_id'=>$address_id])){
            Result::Error('用户地址不存在');
        }
        $model=new ClubTryUser();
        $model->customer_id=\Yii::$app->user->getId();
        $model->try_id=$try_id;
        $model->zone_id=$address->zone_id;
        $model->city_id=$address->city_id;
        $model->district_id=$address->district_id;
        $model->address=$address->address_1;
        $model->shipping_name=$address->firstname;
        $model->shipping_telephone=$address->telephone;
        $model->postcode=$address->postcode;
        $model->status=0;
        $model->creat_at=date("Y-m-d H:i:s",time());
        if(!$model->save()){
            Result::Error('数据异常');
        }
        if($model->try->tryCoupon){
            foreach($model->try->tryCoupon as $v){
                $coupon=new CustomerCoupon();
                $coupon->customer_id=Yii::$app->user->getId();
                $coupon->coupon_id=$v->coupon_id;
                $coupon->description="";
                $coupon->is_use=0;
                $coupon->date_added=date('Y-m-d H:i:s',time());
                $coupon->save();
            }
        }
        return Result::OK();
    }

}