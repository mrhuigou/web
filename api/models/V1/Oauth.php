<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%oauth}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $app_id
 * @property string $app_key
 * @property string $file
 * @property string $description
 * @property integer $is_close
 * @property string $logo
 */
class Oauth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'file'], 'required'],
            [['is_close'], 'integer'],
            [['name', 'file', 'description', 'logo'], 'string', 'max' => 80],
            [['app_id', 'app_key'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'app_id' => 'App ID',
            'app_key' => 'App Key',
            'file' => '接口文件名称',
            'description' => '描述',
            'is_close' => '是否关闭;0开启,1关闭',
            'logo' => 'logo',
        ];
    }
}
