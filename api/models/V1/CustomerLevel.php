<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_level}}".
 *
 * @property integer $customer_level_id
 * @property string $name
 * @property integer $point_start
 * @property integer $point_end
 * @property integer $status
 * @property integer $sort_order
 */
class CustomerLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_level}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['point_start', 'point_end', 'status', 'sort_order'], 'integer'],
            [['name'], 'string', 'max' => 96]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_level_id' => 'Customer Level ID',
            'name' => 'Name',
            'point_start' => 'Point Start',
            'point_end' => 'Point End',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
        ];
    }
}
