<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_customer_sub}}".
 *
 * @property integer $club_customer_sub_id
 * @property integer $customer_id
 * @property integer $sub_id
 * @property integer $display_order
 * @property string $date_added
 */
class ClubCustomerSub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_customer_sub}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'sub_id'], 'required'],
            [['customer_id', 'sub_id', 'display_order'], 'integer'],
            [['date_added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'club_customer_sub_id' => 'Club Customer Sub ID',
            'customer_id' => 'Customer ID',
            'sub_id' => 'Sub ID',
            'display_order' => 'Display Order',
            'date_added' => 'Date Added',
        ];
    }
    public function getSubject(){
        return $this->hasOne(ClubSubject::className(),['sub_id'=>'sub_id']);
    }
}
