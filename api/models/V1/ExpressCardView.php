<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%express_card_view}}".
 *
 * @property integer $id
 * @property integer $express_card_id
 * @property string $card_no
 * @property string $card_pwd
 * @property integer $status
 */
class ExpressCardView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express_card_view}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_no', 'card_pwd','express_card_id'], 'required'],
            [['express_card_id', 'status','version'], 'integer'],
            [['card_no', 'card_pwd'], 'safe'],
        ];
    }
	public function optimisticLock()
	{
		return 'version';
	}
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'express_card_id' => 'Express Card ID',
            'card_no' => 'Card No',
            'card_pwd' => 'Card Pwd',
            'status' => 'Status',
        ];
    }
    public function getCard(){
    	return $this->hasOne(ExpressCard::className(),['id'=>'express_card_id']);
    }
}
