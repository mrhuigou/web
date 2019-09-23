<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_content_type}}".
 *
 * @property integer $type_name_id
 * @property string $type_name
 */
class ClubContentType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_content_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name_id', 'type_name'], 'required'],
            [['type_name_id'], 'integer'],
            [['type_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_name_id' => 'Type Name ID',
            'type_name' => 'Type Name',
        ];
    }
}
