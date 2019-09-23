<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%exercise}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $title
 * @property string $description
 * @property string $begin_time
 * @property string $end_time
 * @property integer $status
 */
class Exercise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exercise}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['begin_time', 'end_time'], 'safe'],
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
            'code' => '编码',
            'title' => '名称',
            'description' => '描述',
            'begin_time' => '开始时间',
            'end_time' => '结束时间',
            'status' => '状态',
        ];
    }
}
