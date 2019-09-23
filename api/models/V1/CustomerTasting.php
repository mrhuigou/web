<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_tasting}}".
 *
 * @property integer $tasting_id
 * @property integer $customer_id
 * @property string $email
 * @property string $telephone
 * @property string $company
 * @property string $job
 * @property string $gender
 * @property integer $type
 * @property string $name
 * @property string $date_added
 */
class CustomerTasting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_tasting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'type'], 'integer'],
            [['company', 'job'], 'string'],
            [['date_added'], 'safe'],
            [['email', 'name'], 'string', 'max' => 255],
            [['telephone'], 'string', 'max' => 12],
            [['gender'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tasting_id' => 'Tasting ID',
            'customer_id' => 'Customer ID',
            'email' => 'Email',
            'telephone' => 'Telephone',
            'company' => 'Company',
            'job' => 'Job',
            'gender' => 'Gender',
            'type' => '1代表葡萄酒爱好者， 2代表葡萄酒从业职，',
            'name' => 'Name',
            'date_added' => 'Date Added',
        ];
    }
}
