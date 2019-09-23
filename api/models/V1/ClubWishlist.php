<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_wishlist}}".
 *
 * @property integer $wishlist_id
 * @property integer $customer_id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 * @property integer $permission
 * @property string $deleted_at
 */
class ClubWishlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_wishlist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'title', 'created_at'], 'required'],
            [['customer_id', 'permission'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wishlist_id' => 'Wishlist ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'permission' => 'Permission',
            'deleted_at' => 'Deleted At',
        ];
    }
}
