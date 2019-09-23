<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_subject}}".
 *
 * @property integer $sub_id
 * @property string $title
 * @property string $description
 * @property string $cover_image
 * @property integer $is_pop
 * @property integer $display_order
 * @property string $tag_id
 * @property integer $is_del
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $icon_image
 */
class ClubSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_subject}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_pop', 'display_order', 'is_del'], 'integer'],
            [['title', 'description', 'cover_image', 'tag_id', 'meta_keyword', 'meta_description', 'icon_image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sub_id' => 'Sub ID',
            'title' => 'Title',
            'description' => 'Description',
            'cover_image' => 'Cover Image',
            'is_pop' => 'Is Pop',
            'display_order' => 'Display Order',
            'tag_id' => 'Tag ID',
            'is_del' => 'Is Del',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'icon_image' => 'Icon Image',
        ];
    }
    public function getExps(){
        return $this->hasMany(ClubSubExp::className(),['sub_id'=>'sub_id']);
    }
}
