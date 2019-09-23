<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_group}}".
 *
 * @property integer $customer_group_id
 * @property string $customer_group_code
 * @property integer $approval
 * @property integer $company_id_display
 * @property integer $company_id_required
 * @property integer $tax_id_display
 * @property integer $tax_id_required
 * @property integer $sort_order
 */
class CustomerGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['approval', 'company_id_display', 'company_id_required', 'tax_id_display', 'tax_id_required', 'sort_order'], 'required'],
            [['approval', 'company_id_display', 'company_id_required', 'tax_id_display', 'tax_id_required', 'sort_order'], 'integer'],
            [['customer_group_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_group_id' => 'Customer Group ID',
            'customer_group_code' => 'Customer Group Code',
            'approval' => 'Approval',
            'company_id_display' => 'Company Id Display',
            'company_id_required' => 'Company Id Required',
            'tax_id_display' => 'Tax Id Display',
            'tax_id_required' => 'Tax Id Required',
            'sort_order' => 'Sort Order',
        ];
    }
}
