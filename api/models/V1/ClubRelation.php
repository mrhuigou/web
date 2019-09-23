<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_relation}}".
 *
 * @property string $relation_id
 * @property integer $customer_id
 * @property integer $friend_customer_id
 * @property integer $status
 * @property integer $c_group_id
 * @property string $created_at
 */
class ClubRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'friend_customer_id', 'status', 'c_group_id'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relation_id' => 'Relation ID',
            'customer_id' => 'Customer ID',
            'friend_customer_id' => 'Friend Customer ID',
            'status' => '0正在申请朋友 1已经是朋友 -1被删除的好友',
            'c_group_id' => 'C Group ID',
            'created_at' => 'Created At',
        ];
    }
}
