<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_admin}}".
 *
 * @property integer $club_activity_id
 * @property integer $customer_id
 */
class ClubActivityAdmin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','activity_id', 'customer_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => 'Club Activity ID',
            'customer_id' => 'Customer ID',
        ];
    }
    public function getActivity(){
        return $this->hasOne(ClubActivity::className(),['id'=>'activity_id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
