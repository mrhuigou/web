<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_type}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $sort_order
 */
class OrderType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'sort_order' => 'Sort Order',
        ];
    }
}
