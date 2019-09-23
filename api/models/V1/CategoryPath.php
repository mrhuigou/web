<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_path}}".
 *
 * @property integer $category_id
 * @property string $category_code
 * @property integer $path_id
 * @property string $path_code
 * @property integer $level
 */
class CategoryPath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_path}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'category_code', 'path_id', 'path_code', 'level'], 'required'],
            [['category_id', 'path_id', 'level'], 'integer'],
            [['category_code', 'path_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'category_code' => 'Category Code',
            'path_id' => 'Path ID',
            'path_code' => 'Path Code',
            'level' => 'Level',
        ];
    }
}
