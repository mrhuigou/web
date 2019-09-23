<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_display_path}}".
 *
 * @property integer $category_display_id
 * @property integer $path_id
 * @property integer $level
 */
class CategoryDisplayPath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_display_path}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_display_id', 'path_id', 'level'], 'required'],
            [['category_display_id', 'path_id', 'level'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_display_id' => 'Category Display ID',
            'path_id' => 'Path ID',
            'level' => 'Level',
        ];
    }
}
