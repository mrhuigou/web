<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_securityquestion}}".
 *
 * @property integer $customer_securityquestion_id
 * @property integer $customer_id
 * @property integer $securityquestion_id
 * @property string $securityanswer
 * @property string $date_added
 * @property string $date_modified
 */
class CustomerSecurityquestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_securityquestion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'securityquestion_id', 'securityanswer'], 'required'],
            [['customer_id', 'securityquestion_id'], 'integer'],
            [['securityanswer'], 'string'],
            [['date_added', 'date_modified'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_securityquestion_id' => 'Customer Securityquestion ID',
            'customer_id' => 'Customer ID',
            'securityquestion_id' => 'Securityquestion ID',
            'securityanswer' => 'Securityanswer',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
