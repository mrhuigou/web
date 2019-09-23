<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%start_page}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property string $description
 * @property string $frequency
 * @property string $date_start
 * @property string $date_end
 * @property integer $status
 */
class StartPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%start_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','status','title','description','frequency','date_start','date_end'],'required'],
            [['status'], 'integer'],
            [['description'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['type', 'title', 'frequency'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'type' => '类型',
            'title' => '标题',
            'description' => '内容',
            'frequency' => '频次',
            'date_start' => '开始时间',
            'date_end' => '结束时间',
            'status' => '状态',
        ];
    }
}
