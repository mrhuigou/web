<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_comment}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $type
 * @property integer $type_id
 * @property string $content
 * @property string $images
 * @property string $address
 * @property integer $like_count
 * @property integer $status
 * @property string $creat_at
 */
class ClubUserComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'type_id', 'like_count', 'status'], 'integer'],
            [['content', 'images','address'], 'string'],
            [['creat_at'], 'safe'],
            [['type'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'type' => 'Type',
            'type_id' => 'Type ID',
            'content' => 'Content',
            'images' => 'Images',
            'address' => 'Address',
            'like_count' => 'Like Count',
            'status' => 'Status',
            'creat_at' => 'Creat At',
        ];
    }
    public function getReply(){
        return $this->hasMany(ClubUserComment::className(),['type_id'=>'id'])->andOnCondition(['type'=>'comment']);
    }
    public function getUserLike(){
        return $this->hasMany(ClubUserLike::className(),['type_id'=>'id'])->andOnCondition(['type'=>'comment']);
    }

    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }

    public function getLikeStatus(){
        $model=ClubUserLike::findOne(['customer_id'=>Yii::$app->user->getId(),'type'=>'comment','type_id'=>$this->id]);
        if($model){
            return true;
        }else{
            return false;
        }
    }
    public function getTag(){
        return $this->hasMany(ClubUserCommentTag::className(),['comment_id'=>'id']);
    }
}
