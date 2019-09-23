<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity}}".
 *
 * @property integer $id
 * @property integer $activity_category_id
 * @property integer $customer_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $music
 * @property string $signup_end
 * @property string $begin_datetime
 * @property string $end_datetime
 * @property string $address
 * @property string $lat
 * @property string $lng
 * @property integer $quantity
 * @property string $fee
 * @property integer $click_count
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $share_count
 * @property integer $status
 * @property string $creat_at
 * @property string $update_at
 * @property string $meta_description
 */
class ClubActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_category_id', 'customer_id', 'quantity', 'click_count', 'like_count', 'comment_count', 'share_count', 'status', 'is_recommend'], 'integer'],
            [['description', 'address'], 'string'],
            [['signup_end', 'begin_datetime', 'end_datetime', 'creat_at', 'update_at'], 'safe'],
            [['fee'], 'number'],
            [['title', 'image','music','meta_description'], 'string', 'max' => 255],
            [['lat', 'lng'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_category_id' => 'Activity Category ID',
            'customer_id' => 'Customer ID',
            'title' => '活动名称',
            'description' => 'Description',
            'image' => 'Image',
            'signup_end' => 'Signup End',
            'begin_datetime' => 'Begin Datetime',
            'end_datetime' => 'End Datetime',
            'address' => 'Address',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'quantity' => 'Quantity',
            'fee' => 'Fee',
            'click_count' => 'Click Count',
            'like_count' => 'Like Count',
            'comment_count' => 'Comment Count',
            'share_count' => 'Share Count',
            'status' => 'Status',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
            'is_recommend' => '推荐活动',
        ];
    }
    public function getQty(){
        $total=$this->quantity;
        if(!$total){
            if($this->items){
                foreach($this->items as $v){
                    if($v->quantity==0){
                        break;
                    }else{
                        $total+=$v->quantity;
                    }

                }
            }
        }
        return $total;
    }
    public function getItems(){
        return $this->hasMany(ClubActivityItems::className(),['activity_id'=>'id'])->andOnCondition(['jr_club_activity_items.status'=>1]);
    }
    public function getOption(){
        return $this->hasMany(ClubActivityOption::className(),['activity_id'=>'id']);
    }
    public function getActivityCategory(){
        return $this->hasOne(ClubActivityCategory::className(),['activity_category_id'=>'activity_category_id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
    public function getUserComment(){
        return $this->hasMany(ClubUserComment::className(),['type_id'=>'id'])->andOnCondition(['type'=>'activity']);
    }
    public function getUserLike(){
        return $this->hasMany(ClubUserLike::className(),['type_id'=>'id'])->andOnCondition(['type'=>'activity']);
    }
    public function getTag(){
        return $this->hasMany(Tag::className(),['type_id'=>'id'])->andOnCondition(['type'=>'activity']);
    }
    public function getUser(){
        return  $this->hasMany(OrderActivity::className(),['activity_id'=>'id'])->joinWith(['order'=>function($query){ $query->andWhere(['not in','order_status_id',['1','7']]);}])->orderBy('order_activity_id desc');
    }

    public function getLikeStatus(){
        $model=ClubUserLike::findOne(['customer_id'=>Yii::$app->user->getId(),'type'=>'activity','type_id'=>$this->id]);
        if($model){
            return true;
        }else{
            return false;
        }
    }
    public function getUserInviteCount(){
        $total=0;
        if($model=ClubUserInvite::findOne(['type'=>'activity','type_id'=>$this->id,'customer_id'=>Yii::$app->user->getId()])){
            if($model){
                $total=ClubUserInviteLog::find()->where(['invite_id'=>$model->id])->count();
            }
        }
        return $total;
    }
    public function getJoin(){
        return false;
    }


    public function getTickets(){
        $total=0;
        if($this->user){
            foreach($this->user as $v){
                $total+=$v->quantity;
            }
        }
        return $total;
    }

    public function getActivitykv(){
        return $this->hasMany(ClubActivityKv::className(),['activity_id'=>'id']);
    }





}
