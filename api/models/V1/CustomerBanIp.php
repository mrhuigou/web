<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_ban_ip}}".
 *
 * @property integer $customer_ban_ip_id
 * @property string $ip
 */
class CustomerBanIp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_ban_ip}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['ip'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_ban_ip_id' => 'Customer Ban Ip ID',
            'ip' => 'Ip',
        ];
    }
}
