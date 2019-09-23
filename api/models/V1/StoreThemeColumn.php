<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_theme_column}}".
 *
 * @property integer $store_theme_column_id
 * @property integer $store_theme_id
 * @property integer $store_id
 * @property string $store_code
 * @property integer $theme_column_id
 * @property string $theme_column_code
 * @property string $name
 * @property string $theme_column_type
 * @property integer $sort_order
 * @property integer $status
 * @property string $remark
 * @property string $url
 */
class StoreThemeColumn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_theme_column}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id','store_theme_id', 'theme_column_id','sort_order','status'], 'integer'],
            [['store_code', 'theme_column_code', 'theme_column_type'], 'string', 'max' => 32],
            [['name', 'remark','url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_theme_column_id' => 'Store Theme Column ID',
            'store_theme_id'=>'store_theme_id',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'theme_column_id' => 'Theme Column ID',
            'theme_column_code' => 'Theme Column Code',
            'name' => '栏目名称 商铺可以自行修改',
            'theme_column_type' => 'Theme Column Type',
            'status' => 'Status',
            'remark' => 'Remark',
        ];
    }
    public function getInfo(){
        return $this->hasMany(StoreThemeColumnInfo::className(),['store_theme_column_id'=>'store_theme_column_id'])->andOnCondition(['status'=>1])->orderBy('sort asc');
    }
    public function getStoreThemeColumnInfo(){
        return $this->hasMany(StoreThemeColumnInfo::className(),['store_theme_column_id'=>'store_theme_column_id'])->andOnCondition(['status'=>1])->orderBy('sort asc');
    }
}
