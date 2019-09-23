<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_invite_log}}".
 *
 * @property integer $invite_id
 * @property integer $customer_id
 * @property string $creat_at
 */
class ClubUserInviteLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_invite_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invite_id', 'customer_id'], 'integer'],
            [['creat_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invite_id' => 'Invite ID',
            'customer_id' => 'Customer ID',
            'creat_at' => 'Creat At',
        ];
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
