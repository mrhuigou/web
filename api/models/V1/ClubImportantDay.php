<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_important_day}}".
 *
 * @property integer $day_id
 * @property integer $customer_id
 * @property string $title
 * @property string $date
 * @property string $description
 * @property integer $releted_customer_id
 * @property string $releted_mobile
 * @property string $releted_email
 * @property integer $c_group_id
 * @property integer $is_del
 * @property string $date_added
 * @property string $date_modified
 */
class ClubImportantDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_important_day}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'title', 'date', 'releted_customer_id', 'c_group_id', 'is_del'], 'required'],
            [['customer_id', 'releted_customer_id', 'c_group_id', 'is_del'], 'integer'],
            [['date', 'date_added', 'date_modified'], 'safe'],
            [['description'], 'string'],
            [['title', 'releted_email'], 'string', 'max' => 255],
            [['releted_mobile'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'day_id' => 'Day ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'date' => 'Date',
            'description' => 'Description',
            'releted_customer_id' => 'Releted Customer ID',
            'releted_mobile' => 'Releted Mobile',
            'releted_email' => 'Releted Email',
            'c_group_id' => 'C Group ID',
            'is_del' => 'Is Del',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
