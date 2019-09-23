<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weixin_message}}".
 *
 * @property string $id
 * @property string $msgtype
 * @property string $key
 * @property string $title
 * @property string $author
 * @property string $content
 * @property string $description
 * @property string $url
 * @property string $picurl
 * @property integer $sort
 */
class WeixinMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'description'], 'string'],
            [['sort'], 'integer'],
            [['msgtype'], 'string', 'max' => 125],
            [['key', 'url', 'picurl'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 64],
            [['author'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'msgtype' => '信息类型',
            'key'=>'响应key',
            'title' => '标题',
            'author' => '作者',
            'content' => '文本内容',
            'description' => '描述内容',
            'url' => '链接地址',
            'picurl' => '图片',
            'sort' => '排序',
        ];
    }
}
