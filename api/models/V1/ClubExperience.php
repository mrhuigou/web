<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_experience}}".
 *
 * @property integer $exp_id
 * @property string $title
 * @property string $intro
 * @property string $content
 * @property integer $customer_id
 * @property string $image_array
 * @property string $cover_image
 * @property integer $item_id
 * @property integer $order_product_id
 * @property integer $event_id
 * @property string $create_time
 * @property string $last_update_time
 * @property integer $is_pop
 * @property integer $total_image
 * @property string $total_click
 * @property string $total_comment
 * @property string $total_collect
 * @property integer $permission
 * @property string $tag_id
 * @property integer $is_del
 * @property integer $examine_id
 * @property string $meta_keyword
 * @property string $meta_description
 */
class ClubExperience extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_experience}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'image_array'], 'string'],
            [['customer_id', 'item_id', 'order_product_id', 'is_pop', 'total_image', 'total_click', 'total_comment', 'total_collect', 'permission', 'is_del', 'examine_id'], 'integer'],
            [['create_time', 'last_update_time'], 'safe'],
            [['title', 'intro', 'cover_image', 'tag_id', 'meta_keyword', 'meta_description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exp_id' => 'Exp ID',
            'title' => 'Title',
            'intro' => 'Intro',
            'content' => 'Content',
            'customer_id' => 'Customer ID',
            'image_array' => 'Image Array',
            'cover_image' => 'Cover Image',
            'item_id' => '商城商品id，product表主键',
            'order_product_id' => 'order_product表主键',

            'create_time' => 'Create Time',
            'last_update_time' => 'Last Update Time',
            'is_pop' => 'Is Pop',
            'total_image' => 'Total Image',
            'total_click' => 'Total Click',
            'total_comment' => 'Total Comment',
            'total_collect' => 'Total Collect',
            'permission' => '阅读权限。0=公开，1=私密，2=部分公开',
            'tag_id' => 'Tag ID',
            'is_del' => '0=未删除（有效），1=删除（无效）',
            'examine_id' => '0=未审核，1=审核通过，2=审核未通过（is_del = 1）',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
        ];
    }
    public function getSubject(){
        return $this->hasOne(ClubSubExp::className(),['exp_id'=>'exp_id']);
    }
    public function getCustomer(){
        return $this->hasOne(Customer::className(),['customer_id'=>'customer_id']);
    }
}
