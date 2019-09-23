<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%favourite}}".
 *
 * @property string $fav_id
 * @property string $from_table
 * @property integer $from_id
 * @property integer $customer_id
 * @property string $date_added
 * @property integer $platform_id
 * @property integer $count
 */
class Favourite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%favourite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_id', 'customer_id', 'platform_id', 'count'], 'integer'],
            [['date_added'], 'safe'],
            [['from_table'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fav_id' => 'Fav ID',
            'from_table' => 'p表示product表，e表示体验，a表示生活圈活动',
            'from_id' => 'From ID',
            'customer_id' => 'Customer ID',
            'date_added' => '首次添加时间',
            'platform_id' => '平台id',
            'count' => '点赞次数,默认为1次',
        ];
    }
}
