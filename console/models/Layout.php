<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%layout}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $max_modules
 * @property string $supported_width
 * @property integer $is_must_dispay
 *
 * @property PageLayout[] $pageLayouts
 * @property Page[] $pages
 */
class Layout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['max_modules', 'is_must_dispay'], 'integer'],
            [['supported_width'], 'string'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '布局ID',
            'code' => '布局code',
            'name' => '布局名称',
            'max_modules' => '布局最大添加模块数量',
            'supported_width' => '布局支持的宽度',
            'is_must_dispay' => '是否必须出现',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageLayouts()
    {
        return $this->hasMany(PageLayout::className(), ['layout_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])->viaTable('{{%page_layout}}', ['layout_id' => 'id']);
    }
}
