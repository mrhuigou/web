<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_affiliate}}".
 *
 * @property string $aff_id
 * @property integer $from_customer_id
 * @property integer $to_customer_id
 * @property string $type_name_id
 * @property integer $content_id
 * @property string $token
 * @property string $valid_time
 * @property integer $status
 */
class ClubAffiliate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_affiliate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_customer_id', 'to_customer_id', 'content_id', 'status'], 'integer'],
            [['valid_time'], 'safe'],
            [['type_name_id'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aff_id' => 'Aff ID',
            'from_customer_id' => 'From Customer ID',
            'to_customer_id' => 'To Customer ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
            'token' => 'Token',
            'valid_time' => 'Valid Time',
            'status' => 'Status',
        ];
    }
}
