<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%geo_zone}}".
 *
 * @property integer $geo_zone_id
 * @property string $name
 * @property string $description
 * @property string $date_modified
 * @property string $date_added
 */
class GeoZone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo_zone}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['date_modified', 'date_added'], 'safe'],
            [['name'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'geo_zone_id' => 'Geo Zone ID',
            'name' => 'Name',
            'description' => 'Description',
            'date_modified' => 'Date Modified',
            'date_added' => 'Date Added',
        ];
    }
}
