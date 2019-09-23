<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_theme}}".
 *
 * @property integer $store_theme_id
 * @property integer $store_id
 * @property integer $theme_id
 * @property integer $status
 */
class StoreTheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_theme}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'store_id', 'theme_id'], 'required'],
            [['store_theme_id', 'store_id', 'theme_id', 'status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_theme_id' => 'Store Theme ID',
            'store_id' => 'Store ID',
            'theme_id' => 'Theme ID',
            'status' => 'Status',
        ];
    }
    public function getThemeInfo(){
        return $this->hasOne(Theme::className(),['theme_id'=>'theme_id'])->andOnCondition(['status'=>1]);
    }
    public function getInfo(){
        return $this->hasMany(StoreThemeColumn::className(),['store_theme_id'=>'store_theme_id'])->andOnCondition(['status'=>1])->orderBy('sort_order asc');
    }
    public function getStoreThemeColumn(){
        return $this->hasMany(StoreThemeColumn::className(),['store_theme_id'=>'store_theme_id'])->andOnCondition(['status'=>1]);
    }
}
