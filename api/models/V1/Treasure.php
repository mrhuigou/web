<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%treasure}}".
 *
 * @property integer $treasure_id
 * @property string $title
 * @property string $context
 * @property string $begin_time
 * @property string $end_time
 * @property integer $status
 * @property string $date_added
 * @property integer $valid_time
 */
class Treasure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%treasure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'context', 'begin_time', 'end_time', 'status'], 'required'],
            [['context'], 'string'],
            [['begin_time', 'end_time', 'date_added'], 'safe'],
            [['status', 'valid_time'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'treasure_id' => 'Treasure ID',
            'title' => 'Title',
            'context' => 'Context',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'valid_time' => '该游戏的游戏时间，从开始到结束的秒数',
        ];
    }
}
