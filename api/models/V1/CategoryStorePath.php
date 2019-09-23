<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_store_path}}".
 *
 * @property integer $category_store_id
 * @property string $category_store_code
 * @property integer $path_id
 * @property string $path_code
 * @property integer $level
 */
class CategoryStorePath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_store_path}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_store_id', 'category_store_code', 'path_id', 'path_code', 'level'], 'required'],
            [['category_store_id', 'path_id', 'level'], 'integer'],
            [['category_store_code', 'path_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_store_id' => 'Category Store ID',
            'category_store_code' => 'Category Store Code',
            'path_id' => 'Path ID',
            'path_code' => 'Path Code',
            'level' => 'Level',
        ];
    }
}
