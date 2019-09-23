<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%wx_notice_template}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $data
 * @property string $example
 */
class WxNoticeTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_notice_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data', 'example'], 'string'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'code' => '编码',
            'name' => '标题',
            'data' => '内容',
            'example' => '实例',
        ];
    }
}
