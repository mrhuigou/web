<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%advertise_to_category}}".
 *
 * @property integer $id
 * @property string $category_code
 * @property string $advertise_position_code
 * @property integer $status
 */
class AdvertiseToCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertise_to_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['category_code', 'advertise_position_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_code' => 'Category Code',
            'advertise_position_code' => 'Advertise Position Code',
            'status' => 'Status',
        ];
    }
}
