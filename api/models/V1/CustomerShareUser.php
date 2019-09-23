<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_share_user}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $follower_id
 * @property integer $status
 * @property integer $creat_at
 */
class CustomerShareUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_share_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'follower_id', 'status', 'creat_at'], 'integer'],
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
            'follower_id' => 'Follower ID',
            'status' => 'Status',
            'creat_at' => 'Creat At',
        ];
    }
}
