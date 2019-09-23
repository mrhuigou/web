<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_try}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $product_base_id
 * @property integer $product_id
 * @property string $image
 * @property string $price
 * @property integer $quantity
 * @property integer $limit_user
 * @property string $begin_datetime
 * @property string $end_datetime
 * @property integer $click_count
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $share_count
 * @property integer $sort_order
 * @property integer $status
 * @property string $creat_at
 * @property string $update_at
 */
class ClubTry extends \yii\db\ActiveRecord
{
    public $product_code;
    public $coupon;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_try}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_base_id', 'product_id', 'quantity', 'limit_user', 'limit_share_user','click_count', 'like_count', 'comment_count', 'share_count', 'sort_order', 'status'], 'integer'],
            [['description', 'product_code'], 'string'],
            [['price'], 'number'],
            [['begin_datetime', 'end_datetime', 'creat_at', 'update_at','coupon'], 'safe'],
            [['title', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'description' => '描述',
            'product_base_id' => 'Product Base ID',
            'product_id' => 'Product ID',
            'image' => '图片',
            'price' => '价格',
            'quantity' => '试吃数量',
            'coupon'=>'赠送优惠券',
            'limit_user' => '每人限制申请次数',
            'limit_share_user'=>'分享数量',
            'begin_datetime' => '开始时间',
            'end_datetime' => '结束时间',
            'click_count' => '点击数',
            'like_count' => '点赞数',
            'comment_count' => '评论数',
            'share_count' => '分享数',
            'sort_order' => '排序',
            'status' => '状态',
            'creat_at' => '创建时间',
            'update_at' => '最后修改时间',
        ];
    }
    public function getUser(){
        return $this->hasMany(ClubTryUser::className(),['try_id'=>'id'])->orderBy('creat_at desc');
    }
    public function getUserComment(){
        return $this->hasMany(ClubUserComment::className(),['type_id'=>'id'])->andOnCondition(['type'=>'try']);
    }
    public function getUserLike(){
        return $this->hasMany(ClubUserLike::className(),['type_id'=>'id'])->andOnCondition(['type'=>'try']);
    }
    public function getLikeStatus(){
        $model=ClubUserLike::findOne(['customer_id'=>Yii::$app->user->getId(),'type'=>'try','type_id'=>$this->id]);
        if($model){
            return true;
        }else{
            return false;
        }
    }
    public function getReceiveStatus(){
        $model=ClubTryUser::findOne(['customer_id'=>Yii::$app->user->getId(),'status'=>'1','try_id'=>$this->id]);
        if($model){
            return true;
        }else{
            return false;
        }
    }

    public function getJoin(){
        $model=ClubTryUser::findOne(['customer_id'=>Yii::$app->user->getId(),'try_id'=>$this->id]);
        if($model){
            return true;
        }else{
            return false;
        }
    }
    public function getTag(){
        return $this->hasMany(Tag::className(),['type_id'=>'id'])->andOnCondition(['type'=>'try']);
    }
    public function getTryCoupon(){
        return $this->hasMany(ClubTryCoupon::className(),['try_id'=>'id']);
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getUserInviteCount(){
        $total=0;
        if($model=ClubUserInvite::findOne(['type'=>'try','type_id'=>$this->id,'customer_id'=>Yii::$app->user->getId()])){
            if($model){
                $total=ClubUserInviteLog::find()->where(['invite_id'=>$model->id])->count();
            }
        }
        return $total;
    }
}
