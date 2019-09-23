<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%advertise_media}}".
 *
 * @property integer $advertise_media_id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property string $description
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class AdvertiseMedia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertise_media}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['code'], 'string', 'max' => 40],
            [['name'], 'string', 'max' => 125],
            [['type'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advertise_media_id' => 'Advertise Media ID',
            'code' => 'Code',
            'name' => 'Name',
            'type' => '媒体类型 IMAGETEXTPACKAGESHOPBRAND',
            'description' => '描述',
            'status' => '有效状态，0=无效，1=生效',
            'date_added' => '创建日期',
            'date_modified' => '修改日期',
        ];
    }
}
