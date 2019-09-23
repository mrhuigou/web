<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_follow}}".
 *
 * @property integer $customer_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property string $add_time
 */
class ClubUserFollow extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_follow}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'type_name_id', 'content_id', 'add_time'], 'required'],
            [['customer_id', 'type_name_id', 'content_id'], 'integer'],
            [['add_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
            'add_time' => 'Add Time',
        ];
    }
}
