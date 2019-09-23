<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weixin_push}}".
 *
 * @property integer $id
 * @property string $touser
 * @property string $msgtype
 * @property string $data
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $status
 */
class WeixinPush extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_push}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['touser', 'data'], 'string'],
            [['create_at', 'update_at', 'status'], 'integer'],
            [['msgtype'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'touser' => 'Touser',
            'msgtype' => 'Msgtype',
            'data' => 'Data',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'status' => 'Status',
        ];
    }
}
