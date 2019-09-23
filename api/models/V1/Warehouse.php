<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%warehouse}}".
 *
 * @property string $warehouse_id
 * @property string $name
 * @property string $warehouse_code
 * @property string $address
 * @property string $date_added
 * @property string $date_modified
 * @property integer $platform_id
 * @property string $platform_code
 * @property integer $status
 */
class Warehouse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['warehouse_code'], 'required'],
            [['address'], 'string'],
            [['date_added', 'date_modified'], 'safe'],
            [['platform_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['warehouse_code'], 'string', 'max' => 50],
            [['platform_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'warehouse_id' => 'Warehouse ID',
            'name' => 'Name',
            'warehouse_code' => 'Warehouse Code',
            'address' => 'Address',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'platform_id' => '仓库所属平台',
            'platform_code' => 'Platform Code',
            'status' => 'Status',
        ];
    }
}
