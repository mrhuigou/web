<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%post_type}}".
 *
 * @property integer $post_type_id
 * @property string $name
 * @property string $code
 * @property string $value
 * @property integer $sort_order
 * @property integer $status
 */
class PostType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order', 'status'], 'integer'],
            [['name', 'code', 'value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_type_id' => 'Post Type ID',
            'name' => 'Name',
            'code' => 'Code',
            'value' => 'Value',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
        ];
    }
}
