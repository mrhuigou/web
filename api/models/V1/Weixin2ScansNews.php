<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weixin2_scans_news}}".
 *
 * @property integer $id
 * @property integer $weixin_scans_id
 * @property string $title
 * @property string $description
 * @property string $pic_url
 * @property string $url
 * @property integer $sort_order
 */
class Weixin2ScansNews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin2_scans_news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weixin_scans_id', 'sort_order'], 'integer'],
            [['description'], 'string'],
            [['title', 'pic_url', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weixin_scans_id' => '二维码场景',
            'title' => '标题',
            'description' => '描述',
            'pic_url' => '图片',
            'url' => '链接',
            'sort_order' => '排序',
        ];
    }
    public function getScan(){
        return $this->hasOne(Weixin2Scans::className(),['id'=>'weixin_scans_id']);
    }
}
