<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_like}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $type
 * @property integer $type_id
 * @property string $creat_at
 */
class ClubUserLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_like}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'type_id'], 'integer'],
            [['creat_at'], 'safe'],
            [['type'], 'string', 'max' => 32]
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
            'creat_at' => 'Creat At',
        ];
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
