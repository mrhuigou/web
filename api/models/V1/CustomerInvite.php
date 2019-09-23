<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_invite}}".
 *
 * @property integer $customer_invite_id
 * @property integer $customer_id
 * @property integer $invite_id
 * @property integer $status
 * @property string $date_added
 * @property string $date_complete
 * @property integer $order_id
 * @property string $order_no
 */
class CustomerInvite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_invite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'invite_id', 'status', 'order_id'], 'integer'],
            [['date_added', 'date_complete'], 'safe'],
            [['order_no'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_invite_id' => 'Customer Invite ID',
            'customer_id' => 'A邀请人',
            'invite_id' => '被邀请人, A邀请来的customer B',
            'status' => '0表示B完成注册，1表示B完成购买',
            'date_added' => 'Date Added',
            'date_complete' => '奖励时间',
            'order_id' => 'Order ID',
            'order_no' => 'Order No',
        ];
    }
}
