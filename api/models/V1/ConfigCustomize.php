<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%config_customize}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string $date_added
 * @property string $date_start
 * @property string $date_end
 * @property integer $status
 */
class ConfigCustomize extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config_customize}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_added', 'date_start', 'date_end'], 'safe'],
            [['status'], 'integer'],
            [['key'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
            'date_added' => 'Date Added',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'status' => 'Status',
        ];
    }
}
