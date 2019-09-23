<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%zone}}".
 *
 * @property integer $zone_id
 * @property string $code
 * @property string $country_code
 * @property integer $country_id
 * @property string $name
 * @property integer $status
 */
class Zone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zone}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'country_id', 'name'], 'required'],
            [['country_id', 'status'], 'integer'],
            [['code', 'country_code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'zone_id' => 'Zone ID',
            'code' => 'Code',
            'country_code' => 'Country Code',
            'country_id' => 'Country ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }
}
