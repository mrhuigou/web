<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_template_page}}".
 *
 * @property integer $id
 * @property string $code
 * @property integer $store_template_id
 * @property string $title
 * @property string $type
 * @property string $head
 * @property string $body
 * @property string $foot
 */
class StoreTemplatePage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_template_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_template_id'], 'integer'],
            [['head', 'body', 'foot'], 'string'],
            [['code', 'type'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 255]
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
            'store_template_id' => 'Store Template ID',
            'title' => 'Title',
            'type' => 'Type',
            'head' => 'Head',
            'body' => 'Body',
            'foot' => 'Foot',
        ];
    }
}
