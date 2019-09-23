<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weixin_user_last}}".
 *
 * @property integer $id
 * @property string $open_id
 * @property integer $last_at
 */
class WeixinUserLast extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_user_last}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_at'], 'integer'],
            [['open_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'open_id' => 'Open ID',
            'last_at' => 'Last At',
        ];
    }
}
