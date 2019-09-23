<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%login}}".
 *
 * @property integer $login_id
 * @property string $type
 * @property string $openid
 * @property string $access_token
 * @property integer $customer_id
 * @property string $date_added
 * @property string $date_lasttime
 */
class Login extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%login}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'openid', 'access_token', 'customer_id', 'date_added'], 'required'],
            [['customer_id'], 'integer'],
            [['date_added', 'date_lasttime'], 'safe'],
            [['type'], 'string', 'max' => 16],
            [['openid', 'access_token'], 'string', 'max' => 96]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login_id' => 'Login ID',
            'type' => 'Type',
            'openid' => 'Openid',
            'access_token' => 'Access Token',
            'customer_id' => 'Customer ID',
            'date_added' => 'Date Added',
            'date_lasttime' => 'Date Lasttime',
        ];
    }
}
