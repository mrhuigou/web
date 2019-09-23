<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_other}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $realname
 * @property string $telephone
 * @property string $company
 * @property integer $quantity
 * @property string $position
 * @property string $address
 * @property string $industry
 * @property string $service
 * @property string $remark
 * @property string $data_added
 * @property integer $status
 */
class CustomerOther extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_other}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'quantity','status'], 'integer'],
            [['service', 'remark'], 'string'],
            [['data_added'], 'safe'],
            [['realname', 'telephone', 'company', 'position', 'address', 'industry'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'customer_id' => '用户ID',
            'realname' => '真实姓名',
            'telephone' => '电话',
            'company' => '公司',
            'quantity'=>'参会人数',
            'position' => '职位',
            'address' => '地址',
            'industry' => '行业',
            'service' => '产品及服务',
            'remark' => '可提供资源',
            'data_added' => '创建时间',
            'status' => '状态',
        ];
    }
}
