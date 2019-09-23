<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property string $id
 * @property integer $category_id
 * @property string $file_id
 * @property string $group_id
 * @property string $real_name
 * @property string $file_type
 * @property string $size
 * @property integer $file_size
 * @property string $storage_ip
 * @property integer $status
 * @property integer $date_added
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'file_size', 'status', 'date_added'], 'integer'],
            [['file_id', 'real_name', 'file_type', 'size'], 'string', 'max' => 255],
            [['group_id'], 'string', 'max' => 255],
            [['storage_ip'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'category_id' => 'Category ID',
            'file_id' => '文件ID',
            'group_id' => '组名',
            'real_name' => '真名',
            'file_type' => 'File Type',
            'size' => 'Size',
            'file_size' => 'File Size',
            'storage_ip' => 'ip',
            'status' => 'Status',
            'date_added' => 'Date Added',
        ];
    }
}
