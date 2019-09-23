<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_cate}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $sort_order
 * @property integer $status
 * @property integer $creat_at
 * @property integer $update_at
 */
class CouponCate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_cate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sort_order', 'status', 'creat_at', 'update_at'], 'integer'],
            [['description'], 'string'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
        ];
    }
    public function getCoupon(){
        return $this->hasMany(CouponCateToCoupon::className(),['cate_id'=>'id']);
    }
}
