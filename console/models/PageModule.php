<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%page_module}}".
 *
 * @property integer $page_id
 * @property integer $module_id
 *
 * @property Module $module
 * @property Page $page
 */
class PageModule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_module}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'module_id'], 'required'],
            [['page_id', 'module_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => '页面表_页面id',
            'module_id' => '模块表_模块ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(Module::className(), ['id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
