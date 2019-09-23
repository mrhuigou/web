<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_wishlist_item}}".
 *
 * @property string $id
 * @property integer $wishlist_id
 * @property integer $item_id
 * @property string $item_name
 * @property string $upc
 * @property string $image
 * @property string $brand
 * @property integer $category_id_1
 * @property integer $category_id_2
 * @property string $created_at
 * @property string $updated_at
 */
class ClubWishlistItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_wishlist_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wishlist_id', 'item_id', 'created_at'], 'required'],
            [['wishlist_id', 'item_id', 'category_id_1', 'category_id_2'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_name', 'upc', 'image'], 'string', 'max' => 255],
            [['brand'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wishlist_id' => 'Wishlist ID',
            'item_id' => 'Item ID',
            'item_name' => 'Item Name',
            'upc' => 'Upc',
            'image' => 'Image',
            'brand' => 'Brand',
            'category_id_1' => 'Category Id 1',
            'category_id_2' => 'Category Id 2',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
