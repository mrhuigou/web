<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%manufacturer}}".
 *
 * @property integer $manufacturer_id
 * @property string $code
 * @property string $name
 * @property string $letter
 * @property integer $brand_id
 * @property integer $parent_id
 * @property string $parentcode
 * @property string $typecode
 * @property string $place
 * @property string $placezone
 * @property string $story
 * @property string $image
 * @property integer $sort_order
 * @property integer $status
 */
class Manufacturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manufacturer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code','name'], 'required'],
            [['parent_id','brand_id', 'sort_order', 'status'], 'integer'],
            [['story'], 'string'],
            [['code', 'letter', 'parentcode', 'typecode', 'place', 'placezone', 'image'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'manufacturer_id' => 'Manufacturer ID',
            'code' => 'Code',
            'name' => 'Name',
            'letter' => 'Letter',
            'brand_id' => 'Brand ID',
            'parentcode' => 'Parentcode',
            'typecode' => 'Typecode',
            'place' => 'Place',
            'placezone' => 'Placezone',
            'story' => 'Story',
            'image' => 'Image',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
        ];
    }
}
