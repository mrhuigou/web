<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_life_diary_detail}}".
 *
 * @property string $dd_id
 * @property integer $customer_id
 * @property integer $event_type_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property string $create_time
 * @property integer $is_public
 * @property string $deleted_at
 */
class ClubLifeDiaryDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_life_diary_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'event_type_id', 'type_name_id', 'content_id'], 'required'],
            [['customer_id', 'event_type_id', 'type_name_id', 'content_id', 'is_public'], 'integer'],
            [['create_time', 'deleted_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dd_id' => 'Dd ID',
            'customer_id' => 'Customer ID',
            'event_type_id' => 'Event Type ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
            'create_time' => 'Create Time',
            'is_public' => 'Is Public',
            'deleted_at' => 'Deleted At',
        ];
    }
}
