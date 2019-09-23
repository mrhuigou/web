<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%advertise_position}}".
 *
 * @property integer $advertise_position_id
 * @property string $code
 * @property string $name
 * @property string $size
 * @property integer $parent_id
 * @property string $parent_code
 * @property integer $uplimit_quantity
 * @property integer $category_display_id
 * @property string $group_type
 * @property integer $priority
 * @property integer $width
 * @property integer $height
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class AdvertisePosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertise_position}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['parent_id', 'uplimit_quantity', 'category_display_id', 'priority', 'width', 'height', 'status'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['code', 'size', 'parent_code', 'group_type'], 'string', 'max' => 40],
            [['name'], 'string', 'max' => 125]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advertise_position_id' => 'Advertise Position ID',
            'code' => 'Code',
            'name' => 'Name',
            'size' => 'Size',
            'parent_id' => '父级ID',
            'parent_code' => 'Parent Code',
            'uplimit_quantity' => '上限数量',
            'category_display_id' => '显示分类ID',
            'group_type' => '显示分类类型 FOCUS,BRANDS,GROUP,FIX',
            'priority' => '优先级',
            'width' => '图片宽度',
            'height' => '图片高度',
            'status' => '有效状态，0=无效，1=生效',
            'date_added' => '创建日期',
            'date_modified' => '修改日期',
        ];
    }
}
