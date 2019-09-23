<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_vote}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $begin_datetime
 * @property string $end_datetime
 * @property integer $select_type
 * @property integer $join_type
 * @property string $creat_at
 * @property string $update_at
 * @property integer $click_count
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $share_count
 * @property integer $sort_order
 * @property integer $status
 */
class ClubVote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_vote}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['begin_datetime', 'end_datetime', 'creat_at', 'update_at'], 'safe'],
            [['select_type', 'join_type', 'click_count', 'like_count', 'comment_count', 'share_count', 'sort_order', 'status'], 'integer'],
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
            'title' => 'Title',
            'description' => 'Description',
            'image' => 'Image',
            'begin_datetime' => 'Begin Datetime',
            'end_datetime' => 'End Datetime',
            'select_type' => 'Select Type',
            'join_type' => 'Join Type',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
            'click_count' => 'Click Count',
            'like_count' => 'Like Count',
            'comment_count' => 'Comment Count',
            'share_count' => 'Share Count',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
        ];
    }
    public function getUserCount(){
        $count=0;
      if($items=$this->items){
          foreach($items as $item){
              $count=$count+$item->like_count;
          }
      }
        return $count;
    }


    public function getUserComment(){
        return $this->hasMany(ClubUserComment::className(),['type_id'=>'id'])->andOnCondition(['type'=>'vote']);
    }
    public function getUserLike(){
        return $this->hasMany(ClubUserLike::className(),['type_id'=>'id'])->andOnCondition(['type'=>'vote']);
    }
    public function getItems(){
        return $this->hasMany(ClubVoteItem::className(),['vote_id'=>'id']);
    }
}
