<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%tax_rate}}".
 *
 * @property integer $tax_rate_id
 * @property integer $geo_zone_id
 * @property string $name
 * @property string $rate
 * @property string $type
 * @property string $date_added
 * @property string $date_modified
 */
class TaxRate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tax_rate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['geo_zone_id'], 'integer'],
            [['name', 'type'], 'required'],
            [['rate'], 'number'],
            [['date_added', 'date_modified'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['type'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tax_rate_id' => 'Tax Rate ID',
            'geo_zone_id' => 'Geo Zone ID',
            'name' => 'Name',
            'rate' => 'Rate',
            'type' => 'Type',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
