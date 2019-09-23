<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%information_description}}".
 *
 * @property integer $information_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 * @property integer $type
 * @property string $author
 * @property string $brand
 * @property string $image
 * @property string $date_added
 * @property string $date_modified
 * @property string $meta_keyword
 * @property string $meta_description
 */
class InformationDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%information_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['information_id', 'language_id', 'description'], 'required'],
            [['information_id', 'language_id', 'type'], 'integer'],
            [['description', 'meta_keyword', 'meta_description'], 'string'],
            [['date_added', 'date_modified'], 'safe'],
            [['title'], 'string', 'max' => 64],
            [['author', 'brand', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'information_id' => 'Information ID',
            'language_id' => 'Language ID',
            'title' => '标题',
            'description' => '描述',
            'type' => '分类',
            'author' => 'Author',
            'brand' => 'Brand',
            'image' => '图片',
            'date_added' => '创建时间',
            'date_modified' => '最后更新时间',
            'meta_keyword' => 'Meta 关键词',
            'meta_description' => 'Meta 描述',
        ];
    }

    public function getInformation(){
        return $this->hasOne(Information::className(),['information_id'=>'information_id']);
    }

    public function getCmsType(){
        return $this->hasOne(CmsType::className(),['cms_type_id'=>'type']);
    }
}
