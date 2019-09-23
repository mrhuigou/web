<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_place}}".
 *
 * @property integer $product_place_id
 * @property string $code
 * @property integer $pid
 * @property string $place_code
 * @property string $name
 * @property integer $weight
 * @property integer $status
 */
class ProductPlace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_place}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'weight', 'status'], 'integer'],
            [['code', 'place_code', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_place_id' => 'Product Place ID',
            'code' => 'Code',
            'pid' => 'Pid',
            'place_code' => 'Place Code',
            'name' => 'Name',
            'weight' => 'Weight',
            'status' => 'Status',
        ];
    }
}
