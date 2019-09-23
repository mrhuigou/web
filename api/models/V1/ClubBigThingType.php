<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_big_thing_type}}".
 *
 * @property integer $thing_type_id
 * @property string $thing_title
 * @property string $thing_description
 * @property integer $thing_type
 * @property integer $p_id
 */
class ClubBigThingType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_big_thing_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['thing_type', 'p_id'], 'integer'],
            [['thing_title', 'thing_description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'thing_type_id' => 'Thing Type ID',
            'thing_title' => 'Thing Title',
            'thing_description' => 'Thing Description',
            'thing_type' => 'Thing Type',
            'p_id' => 'P ID',
        ];
    }
}
