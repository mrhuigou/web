<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%modules}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property string $name
 * @property string $config_data
 * @property string $souce_data
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
 */
class Modules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%modules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_data', 'souce_data', 'description'], 'string'],
            [['max_add', 'is_edit', 'is_move', 'is_delete', 'is_rename', 'is_must_display'], 'integer'],
            [['creat_datetime', 'update_datetime'], 'safe'],
            [['code', 'type'], 'string', 'max' => 50],
            [['name', 'supported_width', 'thumbnail'], 'string', 'max' => 255]
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
            'type' => 'Type',
            'name' => 'Name',
            'config_data' => 'Config Data',
            'souce_data' => 'Souce Data',
            'max_add' => 'Max Add',
            'supported_width' => 'Supported Width',
            'is_edit' => 'Is Edit',
            'is_move' => 'Is Move',
            'is_delete' => 'Is Delete',
            'is_rename' => 'Is Rename',
            'is_must_display' => 'Is Must Display',
            'thumbnail' => 'Thumbnail',
            'description' => 'Description',
            'creat_datetime' => 'Creat Datetime',
            'update_datetime' => 'Update Datetime',
        ];
    }
}
