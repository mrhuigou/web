<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%industory}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $status
 *
 * @property Category[] $categories
 */
class Industory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%industory}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'code' => '编码',
            'name' => '名称',
            'status' => '状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['industory_id' => 'id']);
    }
}
