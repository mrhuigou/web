<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_album}}".
 *
 * @property integer $album_id
 * @property integer $customer_id
 * @property string $title
 * @property string $description
 * @property string $create_time
 * @property string $last_update_time
 * @property string $cover_image
 * @property string $total_exp
 * @property string $total_follower
 * @property string $total_comment
 * @property integer $permission
 * @property string $tag_id
 * @property integer $is_pop
 * @property integer $is_del
 * @property string $total_view
 */
class ClubAlbum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_album}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'title', 'create_time', 'cover_image'], 'required'],
            [['customer_id', 'total_exp', 'total_follower', 'total_comment', 'permission', 'is_pop', 'is_del', 'total_view'], 'integer'],
            [['description'], 'string'],
            [['create_time', 'last_update_time'], 'safe'],
            [['title', 'cover_image', 'tag_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'album_id' => 'Album ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'description' => 'Description',
            'create_time' => 'Create Time',
            'last_update_time' => 'Last Update Time',
            'cover_image' => 'Cover Image',
            'total_exp' => 'Total Exp',
            'total_follower' => 'Total Follower',
            'total_comment' => 'Total Comment',
            'permission' => 'Permission',
            'tag_id' => 'Tag ID',
            'is_pop' => 'Is Pop',
            'is_del' => 'Is Del',
            'total_view' => 'Total View',
        ];
    }
}
