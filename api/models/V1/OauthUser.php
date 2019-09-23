<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%oauth_user}}".
 *
 * @property string $id
 * @property string $oauth_user_id
 * @property integer $oauth_id
 * @property string $customer_id
 * @property string $datetime
 */
class OauthUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oauth_user_id', 'oauth_id', 'customer_id', 'datetime'], 'required'],
            [['oauth_id', 'customer_id'], 'integer'],
            [['datetime'], 'safe'],
            [['oauth_user_id'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'oauth_user_id' => '第三方平台的用户唯一标识',
            'oauth_id' => '第三方平台id',
            'customer_id' => '系统内部的用户id',
            'datetime' => '绑定时间',
        ];
    }
}
