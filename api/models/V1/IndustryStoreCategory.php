<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%industry_store_category}}".
 *
 * @property integer $industry_store_category_id
 * @property string $industry_store_category_code
 * @property integer $industry_id
 * @property string $industry_code
 * @property string $name
 * @property string $description
 * @property integer $status
 */
class IndustryStoreCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%industry_store_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['industry_id'], 'required'],
            [['industry_id', 'status'], 'integer'],
            [['industry_store_category_code', 'industry_code'], 'string', 'max' => 32],
            [['name', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'industry_store_category_id' => 'Industry Store Category ID',
            'industry_store_category_code' => 'Industry Store Category Code',
            'industry_id' => 'Industry ID',
            'industry_code' => 'Industry Code',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }
}
