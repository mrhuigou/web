<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_to_download}}".
 *
 * @property integer $product_id
 * @property integer $download_id
 */
class ProductToDownload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_to_download}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'download_id'], 'required'],
            [['product_id', 'download_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'download_id' => 'Download ID',
        ];
    }
}
