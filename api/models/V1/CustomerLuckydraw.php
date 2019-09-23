<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_luckydraw}}".
 *
 * @property integer $customer_luckdraw_id
 * @property integer $customer_id
 * @property string $telephone
 * @property string $date_added
 * @property string $draw_sn
 */
class CustomerLuckydraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_luckydraw}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'integer'],
            [['date_added'], 'safe'],
            [['telephone'], 'string', 'max' => 15],
            [['draw_sn'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_luckdraw_id' => 'Customer Luckdraw ID',
            'customer_id' => 'Customer ID',
            'telephone' => 'Telephone',
            'date_added' => 'Date Added',
            'draw_sn' => 'Draw Sn',
        ];
    }
}
