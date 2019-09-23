<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%module}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property string $name
 * @property string $config_str
 * @property string $data_str
 * @property integer $max_add
 * @property string $supported_width
 * @property integer $is_edit
 * @property integer $is_move
 * @property integer $is_delete
 * @property integer $is_rename
 * @property integer $is_must_display
 * @property string $thumbnail
 * @property string $description
 * @property string $creat_datetime
 * @property string $update_datetime
 *
 * @property PageModule[] $pageModules
 * @property Page[] $pages
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_str', 'data_str', 'supported_width', 'description'], 'string'],
            [['max_add', 'is_edit', 'is_move', 'is_delete', 'is_rename', 'is_must_display'], 'integer'],
            [['creat_datetime', 'update_datetime'], 'safe'],
            [['code', 'type'], 'string', 'max' => 50],
            [['name', 'thumbnail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '模块ID',
            'code' => '模块CODE',
            'type' => '模块类型',
            'name' => '模块名称',
            'config_str' => '模块参数',
            'data_str' => '模块数据',
            'max_add' => '模块最大添加数量',
            'supported_width' => '模块布局',
            'is_edit' => '是否可编辑',
            'is_move' => '是否可移动',
            'is_delete' => '是否可删除',
            'is_rename' => '是否可重命名',
            'is_must_display' => '是否必须出现',
            'thumbnail' => '缩略图',
            'description' => '描述',
            'creat_datetime' => '创建时间',
            'update_datetime' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageModules()
    {
        return $this->hasMany(PageModule::className(), ['module_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])->viaTable('{{%page_module}}', ['module_id' => 'id']);
    }
}
