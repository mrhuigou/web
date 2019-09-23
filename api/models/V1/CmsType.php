<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%cms_type}}".
 *
 * @property integer $cms_type_id
 * @property string $name
 * @property string $description
 * @property integer $weight
 * @property integer $status
 */
class CmsType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description','weight'], 'required'],
            [['weight', 'status'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cms_type_id' => 'Cms Type ID',
            'name' => '名称',
            'description' => '描述',
            'weight' => '排序',
            'status' => '状态',
        ];
    }
}
