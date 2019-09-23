<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_big_thing}}".
 *
 * @property string $thing_id
 * @property integer $customer_id
 * @property integer $life_diary_id
 * @property integer $thing_type_id
 * @property string $image_array
 * @property string $description
 * @property string $address
 * @property string $create_time
 */
class ClubBigThing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_big_thing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'life_diary_id', 'thing_type_id'], 'integer'],
            [['thing_type_id', 'create_time'], 'required'],
            [['image_array', 'description'], 'string'],
            [['create_time'], 'safe'],
            [['address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'thing_id' => 'Thing ID',
            'customer_id' => 'Customer ID',
            'life_diary_id' => 'Life Diary ID',
            'thing_type_id' => 'Thing Type ID',
            'image_array' => 'Image Array',
            'description' => 'Description',
            'address' => 'Address',
            'create_time' => 'Create Time',
        ];
    }
}
