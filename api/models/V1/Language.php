<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%language}}".
 *
 * @property integer $language_id
 * @property string $name
 * @property string $code
 * @property string $locale
 * @property string $image
 * @property string $directory
 * @property string $filename
 * @property integer $sort_order
 * @property integer $status
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'locale', 'image', 'directory', 'filename', 'status'], 'required'],
            [['sort_order', 'status'], 'integer'],
            [['name', 'directory'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 5],
            [['locale'], 'string', 'max' => 255],
            [['image', 'filename'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => 'Language ID',
            'name' => 'Name',
            'code' => 'Code',
            'locale' => 'Locale',
            'image' => 'Image',
            'directory' => 'Directory',
            'filename' => 'Filename',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
        ];
    }
}
