<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%pages}}".
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
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
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
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'max_pages' => 'Max Pages',
            'max_modules' => 'Max Modules',
            'max_layouts' => 'Max Layouts',
            'is_rename' => 'Is Rename',
            'is_must' => 'Is Must',
            'status' => 'Status',
        ];
    }
}
