<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_user_ticket}}".
 *
 * @property integer $id
 * @property integer $activity_user_id
 * @property string $code
 * @property integer $status
 * @property string $creat_at
 * @property string $update_at
 */
class ClubActivityUserTicket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_user_ticket}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_user_id', 'status'], 'integer'],
            [['creat_at', 'update_at'], 'safe'],
            [['code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_user_id' => 'Activity User ID',
            'code' => 'Code',
            'status' => 'Status',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
        ];
    }
    public function getActivityUser(){
        return $this->hasOne(ClubActivityUser::className(),['id'=>'activity_user_id']);
    }
}
