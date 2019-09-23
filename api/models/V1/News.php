<?php

namespace api\models\V1;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $news_id
 * @property integer $channel
 * @property integer $news_category_id
 * @property string $title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $link_url
 * @property string $description
 * @property string $image
 * @property integer $status
 * @property integer $sort_order
 * @property integer $highlight
 * @property string $date_added
 * @property string $date_modified
 * @property string $type;
 * @property string $tag
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_added',// 自己根据数据库字段修改
                'updatedAtAttribute' => 'date_modified', // 自己根据数据库字段修改, // 自己根据数据库字段修改
                //'value'   => new Expression('NOW()'),
                'value'   => function(){return date('Y-m-d H:i:s',time());},
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['news_category_id','channel', 'status', 'sort_order', 'highlight'], 'integer'],
            [['description','image','type','tag'], 'string'],
            [['date_added', 'date_modified'], 'safe'],
            [['title', 'meta_keywords', 'meta_description', 'link_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'news_id' => 'ID',
	        'type'=>'类型',
	        'tag'=>'标签',
            'channel'=>'终端',
            'news_category_id' => '分类',
            'title' => '标题',
            'meta_keywords' => 'Meta 关键字',
            'meta_description' => 'Meta 描述',
            'image'=>'图片',
            'link_url' => '链接',
            'description' => '内容',
            'status' => '状态',
            'sort_order' => '排序',
            'highlight' => '高亮',
            'date_added' => '发布时间',
            'date_modified' => '更新时间',
        ];
    }
}
