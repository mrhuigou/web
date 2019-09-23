<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_promotion}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $title
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 */
class CustomerPromotion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_promotion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time'], 'safe'],
            [['status'], 'integer'],
            [['code', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'title' => 'Title',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'status' => 'Status',
        ];
    }
}
