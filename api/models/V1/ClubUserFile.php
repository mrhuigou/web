<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_file}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $filename
 * @property string $filetype
 * @property double $filesize
 * @property string $path
 * @property string $creat_at
 */
class ClubUserFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['filesize'], 'number'],
            [['creat_at'], 'safe'],
            [['filename', 'filetype', 'path'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'filename' => 'Filename',
            'filetype' => 'Filetype',
            'filesize' => 'Filesize',
            'path' => 'Path',
            'creat_at' => 'Creat At',
        ];
    }
}
