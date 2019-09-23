<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_events_member}}".
 *
 * @property integer $r_id
 * @property integer $events_id
 * @property integer $customer_id
 * @property integer $status
 * @property string $created_at
 * @property integer $is_signin
 * @property string $signin_code
 */
class ClubEventsMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_events_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['events_id', 'customer_id'], 'required'],
            [['events_id', 'customer_id', 'status', 'is_signin'], 'integer'],
            [['created_at'], 'safe'],
            [['signin_code'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_id' => 'R ID',
            'events_id' => 'Events ID',
            'customer_id' => 'Customer ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'is_signin' => 'Is Signin',
            'signin_code' => 'Signin Code',
        ];
    }
}
