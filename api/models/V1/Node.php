<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%node}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $link
 * @property integer $status
 * @property string $remark
 * @property integer $sort
 * @property integer $pid
 * @property integer $level
 */
class Node extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name', 'unique', 'message' => '应用已经存在！'],
            [['status', 'sort', 'pid', 'level'], 'integer'],
            [['name', 'title_prefix', 'title', 'link', 'remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'name' => '应用名称',
            'title_prefix' => '标题前缀',
            'title' => '显示名称',
            'link' => '链接',
            'status' => '状态',
            'remark' => '备注',
            'sort' => '排序',
            'pid' => '上级',
            'level' => '层级',
        ];
    }
}
