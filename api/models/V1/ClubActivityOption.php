<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_option}}".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property string $type
 * @property string $name
 * @property integer $is_require
 */
class ClubActivityOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'is_require'], 'integer'],
            [['type', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'type' => 'Type',
            'name' => 'Name',
            'is_require' => 'Is Require',
        ];
    }
    public function getOptionValue(){
        return $this->hasMany(ClubActivityOptionValue::className(),['activity_option_id'=>'id']);
    }
}
