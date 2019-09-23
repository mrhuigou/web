<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_customer_shell}}".
 *
 * @property integer $club_customer_shell_id
 * @property integer $customer_id
 * @property integer $content_id
 * @property string $description
 * @property integer $points
 * @property integer $type_id
 * @property string $date_start
 * @property string $date_end
 * @property string $date_added
 */
class ClubCustomerShell extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_customer_shell}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'content_id', 'points', 'type_id'], 'integer'],
            [['description', 'date_start', 'date_end'], 'required'],
            [['description'], 'string'],
            [['date_start', 'date_end', 'date_added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'club_customer_shell_id' => 'Club Customer Shell ID',
            'customer_id' => 'Customer ID',
            'content_id' => 'Content ID',
            'description' => 'Description',
            'points' => 'Points',
            'type_id' => '获取类型,1=普通类型',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'date_added' => 'Date Added',
        ];
    }
}
