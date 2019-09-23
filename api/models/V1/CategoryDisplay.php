<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_display}}".
 *
 * @property string $category_display_id
 * @property string $category_display_code
 * @property integer $parent_id
 * @property integer $status
 * @property integer $sort_order
 * @property string $date_added
 * @property string $date_modified
 * @property string $keyword
 * @property string $search
 * @property string $image
 * @property string $url
 * @property integer $tochild
 * @property integer $platform_id
 * @property integer $recommend
 * @property integer $is_delete
 */
class CategoryDisplay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_display}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'sort_order'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['keyword', 'search','image','url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_display_id' => 'Category Display ID',
            'category_display_code' => 'Category Display Code',
            'parent_id' => 'Parent ID',
            'status' => 'Status',
            'sort_order' => 'Sort Order',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'keyword' => 'Keyword',
            'image' => '图标',
            'search' => '配置前台分类搜索字段',
            'tochild' => '表示筛选条件是否应用于显示子分类',
            'platform_id' => 'Platform ID',
            'recommend' => '推荐显示在首页的分类，1显示，0不显示',
        ];
    }
    public function getDescription(){
        return $this->hasOne(CategoryDisplayDescription::className(),['category_display_id'=>'category_display_id']);
    }
    public function getCategorys(){
        return $this->hasMany(CategoryDisplayToCategory::className(),['category_display_id'=>'category_display_id']);
    }
    public function getParent(){
        return $this->hasOne(CategoryDisplay::className(),['category_display_id'=>'parent_id']);
    }
    public function getParents($model,&$p_category=array()){
        if($model->parent_id != 0){

            $p = CategoryDisplay::find()->where(['category_display_id'=>$model->parent_id])->orderBy('sort_order')->one();
           $p_category[] = $p;
           // array_push($p_category,$p);
            if($p->parent_id != 0 ){
                $this->getParents($p,$p_category);
            }
        }
        //return CategoryDisplay::find()->where(['category_display_id'=>$model->parent_id])->one();
    }
    public function getChild(){
        return CategoryDisplay::find()->where(['parent_id'=>$this->category_display_id,'status'=>1])->orderBy('sort_order')->all();
    }
    public function getBrand(){
        return $this->hasMany(CategoryDisplayToBrand::className(),['category_display_id'=>'category_display_id']);
    }
}
