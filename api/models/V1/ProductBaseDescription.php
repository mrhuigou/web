<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_base_description}}".
 *
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $language_id
 * @property string $name
 * @property string $description
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $tag
 */
class ProductBaseDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_base_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          //  [['product_base_id', 'language_id', 'name', 'description', 'meta_description', 'meta_keyword'], 'required'],
            [['product_base_id', 'language_id'], 'integer'],
            [['description', 'meta_description', 'tag'], 'string'],
            [['product_base_code', 'name', 'meta_keyword'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
            'meta_description' => 'Meta Description',
            'meta_keyword' => 'Meta Keyword',
            'tag' => 'Tag',
        ];
    }
}
