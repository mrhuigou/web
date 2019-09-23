<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_user_info}}".
 *
 * @property integer $id
 * @property integer $club_activity_user_id
 * @property string $key
 * @property string $value
 * @property integer $club_activity_kv_id
 * @property integer $activity_id
 * @property integer $customer_id
 */
class ClubActivityUserInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_user_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'club_activity_user_id', 'club_activity_kv_id', 'activity_id', 'customer_id'], 'integer'],
            [['key', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'club_activity_user_id' => 'Club Activity User ID',
            'key' => 'Key',
            'value' => 'Value',
            'club_activity_kv_id' => 'Club Activity Kv ID',
            'activity_id' => 'Activity ID',
            'customer_id' => 'Customer ID',
        ];
    }
}
