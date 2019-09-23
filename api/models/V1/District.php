<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%district}}".
 *
 * @property integer $district_id
 * @property string $code
 * @property string $city_code
 * @property integer $city_id
 * @property string $name
 * @property integer $status
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%district}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'city_id', 'name'], 'required'],
            [['city_id', 'status'], 'integer'],
            [['code', 'city_code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'district_id' => 'District ID',
            'code' => 'Code',
            'city_code' => 'City Code',
            'city_id' => 'City ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }
}
