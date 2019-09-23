<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%download_description}}".
 *
 * @property integer $download_id
 * @property integer $language_id
 * @property string $name
 */
class DownloadDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%download_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['download_id', 'language_id', 'name'], 'required'],
            [['download_id', 'language_id'], 'integer'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'download_id' => 'Download ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}
