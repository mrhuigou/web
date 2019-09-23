<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_shipping}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $begin_time
 * @property string $end_time
 * @property string $content
 * @property integer $total
 * @property integer $status
 */
class StoreShipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_shipping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'total', 'status'], 'integer'],
            [['begin_time', 'end_time'], 'safe'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'content' => 'Content',
            'total' => 'Total',
            'status' => 'Status',
        ];
    }
}
