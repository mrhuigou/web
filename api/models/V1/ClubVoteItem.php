<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_vote_item}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $vote_id
 * @property string $title
 * @property string $image
 * @property string $content
 * @property integer $sort_order
 * @property string $creat_at
 * @property string $update_at
 * @property integer $click_count
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $share_count
 * @property integer $status
 */
class ClubVoteItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_vote_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'customer_id', 'vote_id', 'sort_order', 'click_count', 'like_count', 'comment_count', 'share_count', 'status'], 'integer'],
            [['content'], 'string'],
            [['creat_at', 'update_at'], 'safe'],
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
            'customer_id' => 'Customer ID',
            'vote_id' => 'Vote ID',
            'title' => 'Title',
            'image' => 'Image',
            'content' => 'Content',
            'sort_order' => 'Sort Order',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
            'click_count' => 'Click Count',
            'like_count' => 'Like Count',
            'comment_count' => 'Comment Count',
            'share_count' => 'Share Count',
            'status' => 'Status',
        ];
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
    public function getLikeStatus(){
            $model=ClubUserLike::findOne(['customer_id'=>Yii::$app->user->getId(),'type'=>'voteitem','type_id'=>$this->id]);
        if($model){
            return true;
        }else{
            return false;
        }
    }
}
