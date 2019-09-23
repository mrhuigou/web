<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_group_member}}".
 *
 * @property integer $r_id
 * @property integer $group_id
 * @property integer $customer_id
 * @property integer $status
 * @property string $created_at
 */
class ClubGroupMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_group_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'customer_id', 'status'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_id' => 'R ID',
            'group_id' => 'Group ID',
            'customer_id' => 'Customer ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
