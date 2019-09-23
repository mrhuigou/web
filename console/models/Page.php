<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $max_pages
 * @property integer $max_modules
 * @property integer $max_layouts
 * @property integer $is_rename
 * @property integer $is_must
 * @property integer $status
 *
 * @property PageLayout[] $pageLayouts
 * @property Layout[] $layouts
 * @property PageModule[] $pageModules
 * @property Module[] $modules
 * @property TemplatePage[] $templatePages
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['max_pages', 'max_modules', 'max_layouts', 'is_rename', 'is_must', 'status'], 'integer'],
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
            'id' => '页面id',
            'code' => '页面CODE',
            'type' => '页面类型',
            'name' => '页面名称',
            'max_pages' => '页面最大添加数量',
            'max_modules' => '页面最大模块添加数',
            'max_layouts' => '页面最大布局添加数',
            'is_rename' => '是否可重命名',
            'is_must' => '是否为必须出现',
            'status' => '状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageLayouts()
    {
        return $this->hasMany(PageLayout::className(), ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLayouts()
    {
        return $this->hasMany(Layout::className(), ['id' => 'layout_id'])->viaTable('{{%page_layout}}', ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageModules()
    {
        return $this->hasMany(PageModule::className(), ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::className(), ['id' => 'module_id'])->viaTable('{{%page_module}}', ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplatePages()
    {
        return $this->hasMany(TemplatePage::className(), ['page_id' => 'id']);
    }
}
