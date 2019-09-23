<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%cycle_store}}".
 *
 * @property string $cycle_store_id
 * @property string $code
 * @property string $cycle_name
 * @property string $every
 * @property integer $periods
 * @property integer $store_id
 * @property string $store_code
 * @property string $updated_at
 * @property string $created_at
 * @property integer $status
 */
class CycleStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cycle_store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periods', 'store_id', 'status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['code'], 'string', 'max' => 32],
            [['cycle_name'], 'string', 'max' => 255],
            [['every'], 'string', 'max' => 20],
            [['store_code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cycle_store_id' => 'Cycle Store ID',
            'code' => 'Code',
            'cycle_name' => 'Cycle Name',
            'every' => 'Every',
            'periods' => '周期期数，默认为1期',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'status' => '0为失效，1为生效',
        ];
    }
}
