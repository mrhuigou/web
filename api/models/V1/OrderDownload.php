<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%order_download}}".
 *
 * @property integer $order_download_id
 * @property integer $order_id
 * @property integer $order_product_id
 * @property string $name
 * @property string $filename
 * @property string $mask
 * @property integer $remaining
 */
class OrderDownload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_download}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_product_id', 'name', 'filename', 'mask'], 'required'],
            [['order_id', 'order_product_id', 'remaining'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['filename', 'mask'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_download_id' => 'Order Download ID',
            'order_id' => 'Order ID',
            'order_product_id' => 'Order Product ID',
            'name' => 'Name',
            'filename' => 'Filename',
            'mask' => 'Mask',
            'remaining' => 'Remaining',
        ];
    }
}
