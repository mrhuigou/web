<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_customer_custom_group}}".
 *
 * @property integer $c_group_id
 * @property integer $customer_id
 * @property integer $group_type
 * @property string $title
 * @property string $date_added
 * @property string $date_modified
 */
class ClubCustomerCustomGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_customer_custom_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'group_type', 'title'], 'required'],
            [['customer_id', 'group_type'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'c_group_id' => 'C Group ID',
            'customer_id' => 'Customer ID',
            'group_type' => 'Group Type',
            'title' => 'Title',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
