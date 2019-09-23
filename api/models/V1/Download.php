<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%download}}".
 *
 * @property integer $download_id
 * @property string $filename
 * @property string $mask
 * @property integer $remaining
 * @property string $date_added
 */
class Download extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%download}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'mask'], 'required'],
            [['remaining'], 'integer'],
            [['date_added'], 'safe'],
            [['filename', 'mask'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'download_id' => 'Download ID',
            'filename' => 'Filename',
            'mask' => 'Mask',
            'remaining' => 'Remaining',
            'date_added' => 'Date Added',
        ];
    }
}
