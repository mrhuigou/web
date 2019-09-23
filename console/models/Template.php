<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%template}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $version
 * @property string $thumbnail
 * @property string $type
 * @property string $directoryname
 * @property string $description
 *
 * @property StoreTemplate[] $storeTemplates
 * @property TemplatePage[] $templatePages
 * @property Page[] $pages
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['code'], 'string', 'max' => 32],
            [['name', 'thumbnail', 'directoryname'], 'string', 'max' => 255],
            [['version'], 'string', 'max' => 125],
            [['type'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '模板ID',
            'code' => '模板编码',
            'name' => '模板名称',
            'version' => '模板版本',
            'thumbnail' => '模板缩略图',
            'type' => '模板类型',
            'directoryname' => '目录名称',
            'description' => '模板备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreTemplates()
    {
        return $this->hasMany(StoreTemplate::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplatePages()
    {
        return $this->hasMany(TemplatePage::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])->viaTable('{{%template_page}}', ['template_id' => 'id']);
    }
}
