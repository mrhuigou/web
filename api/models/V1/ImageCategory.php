<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%image_category}}".
 *
 * @property integer $category_id
 * @property integer $parent_id
 * @property string $name
 * @property integer $platform_id
 * @property integer $status
 */
class ImageCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'platform_id', 'status'], 'integer'],
            [['name', 'platform_id'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'platform_id' => 'Platform ID',
            'status' => 'Status',
        ];
    }
    public function getImage(){
        return $this->hasMany(Image::className(),['category_id'=>'category_id']);
    }
    public function getChildren(){
        return $this->hasMany(ImageCategory::className(),['parent_id'=>'category_id']);
    }
}
