<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%promotion_product_group}}".
 *
 * @property integer $promotion_product_group_id
 * @property string $code
 * @property string $name
 */
class PromotionProductGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion_product_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 125]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_product_group_id' => 'Promotion Product Group ID',
            'code' => '组合编码',
            'name' => '组合名称',
        ];
    }
}
