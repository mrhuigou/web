<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weixin_menu}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $url
 * @property string $key
 * @property integer $pid
 * @property integer $status
 * @property integer $sort
 */
class Weixin2Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin2_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id', 'pid', 'status', 'sort'], 'integer'],
            [['type', 'name', 'key','media_id'], 'string', 'max' => 125],
            [['url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '按钮类型',
            'name' => '名称',
            'url' => 'Url',
            'key' => 'Key',
            'media_id'=>'媒体',
            'pid' => '上级',
            'status' => '状态',
            'sort' => '排序',
        ];
    }
}
