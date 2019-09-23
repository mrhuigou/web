<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weixin_kefu}}".
 *
 * @property integer $id
 * @property string $kf_id
 * @property string $kf_nick
 * @property string $kf_account
 * @property string $kf_password
 * @property string $kf_headimgurl
 */
class WeixinKefu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_kefu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kf_id', 'kf_nick', 'kf_account', 'kf_password', 'kf_headimgurl'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kf_id' => 'Kf ID',
            'kf_nick' => 'Kf Nick',
            'kf_account' => 'Kf Account',
            'kf_password' => 'Kf Password',
            'kf_headimgurl' => 'Kf Headimgurl',
        ];
    }
}
