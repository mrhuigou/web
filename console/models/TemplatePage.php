<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%template_page}}".
 *
 * @property integer $template_id
 * @property integer $page_id
 * @property string $title
 * @property string $filename
 * @property string $thumbnail
 * @property string $description
 *
 * @property Page $page
 * @property Template $template
 */
class TemplatePage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'page_id'], 'required'],
            [['template_id', 'page_id'], 'integer'],
            [['description'], 'string'],
            [['title', 'filename', 'thumbnail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id' => '模板页面ID',
            'page_id' => '模板页面类型',
            'title' => '模板页面标题',
            'filename' => '模板页面文件名',
            'thumbnail' => '模板页面缩略图',
            'description' => '模板页面描述',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }
}
