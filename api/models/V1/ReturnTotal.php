<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%return_total}}".
 *
 * @property integer $id
 * @property integer $return_id
 * @property string $code
 * @property string $title
 * @property string $text
 * @property string $value
 * @property integer $sort_order
 */
class ReturnTotal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%return_total}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_id', 'sort_order'], 'integer'],
            [['value'], 'number'],
            [['code', 'title', 'text'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'return_id' => 'Return ID',
            'code' => 'Code',
            'title' => 'Title',
            'text' => 'Text',
            'value' => 'Value',
            'sort_order' => 'Sort Order',
        ];
    }
}
