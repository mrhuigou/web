<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_option_value}}".
 *
 * @property integer $id
 * @property integer $activity_option_id
 * @property string $name
 */
class ClubActivityOptionValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_option_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_option_id'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_option_id' => 'Activity Option ID',
            'name' => 'Name',
        ];
    }
}
