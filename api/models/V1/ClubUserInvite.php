<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_invite}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $type
 * @property integer $type_id
 * @property string $code
 * @property string $creat_at
 */
class ClubUserInvite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_invite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'type_id'], 'integer'],
            [['creat_at'], 'safe'],
            [['type', 'code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'type' => 'Type',
            'type_id' => 'Type ID',
            'code' => 'Code',
            'creat_at' => 'Creat At',
        ];
    }
    public function getLog(){
        return $this->hasMany(ClubUserInviteLog::className(),['invite_id'=>'id']);
    }
}
