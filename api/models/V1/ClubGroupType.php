<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_group_type}}".
 *
 * @property integer $group_type_id
 * @property string $group_type_name
 */
class ClubGroupType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_group_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_type_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_type_id' => 'Group Type ID',
            'group_type_name' => 'Group Type Name',
        ];
    }
}
