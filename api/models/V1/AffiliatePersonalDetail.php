<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_personal_detail}}".
 *
 * @property integer $affiliate_personal_detail_id
 * @property integer $affiliate_personal_id
 * @property integer $product_id
 * @property string $product_code
 * @property string $commission
 * @property string $commission_type
 */
class AffiliatePersonalDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_personal_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_personal_detail_id'], 'required'],
            [['affiliate_personal_detail_id', 'affiliate_personal_id', 'product_id'], 'integer'],
            [['product_code', 'commission'], 'string', 'max' => 255],
            [['commission_type'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'affiliate_personal_detail_id' => 'Affiliate Personal Detail ID',
            'affiliate_personal_id' => 'Affiliate Personal ID',
            'product_id' => 'Product ID',
            'product_code' => 'Product Code',
            'commission' => 'Commission',
            'commission_type' => 'Commission Type',
        ];
    }
    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
    public function getCommissionTotal(){
        $total = 0;
        if($this->commission_type == 'F'){
            $total = $this->commission;
        }else{
            $product = $this->product;
            $product_total = $product->getPrice();
            if($this->commission <1 ){
                $total = bcmul($product_total,$this->commission,2);
            }

        }
        return $total;
    }
}
