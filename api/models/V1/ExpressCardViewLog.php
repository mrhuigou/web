<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%express_card_view_log}}".
 *
 * @property integer $id
 * @property integer $express_card_view_id
 * @property integer $customer_id
 * @property string $user_agent
 * @property string $ip
 * @property integer $create_at
 */
class ExpressCardViewLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express_card_view_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['express_card_view_id', 'customer_id', 'create_at'], 'integer'],
            [['user_agent', 'ip'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'express_card_view_id' => 'Express Card View ID',
            'customer_id' => 'Customer ID',
            'user_agent' => 'User Agent',
            'ip' => 'Ip',
            'create_at' => 'Create At',
        ];
    }
}
