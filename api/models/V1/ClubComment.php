<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_comment}}".
 *
 * @property integer $comment_id
 * @property integer $customer_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property integer $reference_id
 * @property string $create_time
 * @property string $intro
 * @property string $content
 * @property integer $is_pop
 * @property integer $is_del
 */
class ClubComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'type_name_id', 'content_id', 'content'], 'required'],
            [['customer_id', 'type_name_id', 'content_id', 'reference_id', 'is_pop', 'is_del'], 'integer'],
            [['create_time'], 'safe'],
            [['intro', 'content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'customer_id' => 'Customer ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
            'reference_id' => 'Reference ID',
            'create_time' => 'Create Time',
            'intro' => 'Intro',
            'content' => 'Content',
            'is_pop' => 'Is Pop',
            'is_del' => 'Is Del',
        ];
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
    public function getReply(){
        return $this->hasMany(ClubComment::className(),['reference_id'=>'comment_id'])->andOnCondition(['is_del'=>'0']);
    }
}
