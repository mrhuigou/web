<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_contact}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $username
 * @property string $telephone
 * @property string $creat_at
 * @property string $update_at
 */
class ClubUserContact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['creat_at', 'update_at'], 'safe'],
            [['username', 'telephone'], 'string', 'max' => 255]
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
            'username' => 'Username',
            'telephone' => 'Telephone',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
        ];
    }
    public function getMemberStatus(){
        if($model=Customer::findOne(['telephone'=>$this->telephone])){
            return true;
        }else{
            return false;
        }

    }
}
