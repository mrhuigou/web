<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%country}}".
 *
 * @property integer $country_id
 * @property string $country_code
 * @property string $name
 * @property string $iso_code_2
 * @property string $iso_code_3
 * @property string $address_format
 * @property integer $postcode_required
 * @property integer $status
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code', 'name'], 'required'],
            [['address_format'], 'string'],
            [['postcode_required', 'status'], 'integer'],
            [['country_code', 'name'], 'string', 'max' => 128],
            [['iso_code_2'], 'string', 'max' => 2],
            [['iso_code_3'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'country_code' => 'Country Code',
            'name' => 'Name',
            'iso_code_2' => 'Iso Code 2',
            'iso_code_3' => 'Iso Code 3',
            'address_format' => 'Address Format',
            'postcode_required' => 'Postcode Required',
            'status' => 'Status',
        ];
    }
}
