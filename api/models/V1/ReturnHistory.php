<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%return_history}}".
 *
 * @property integer $return_history_id
 * @property integer $return_id
 * @property integer $return_status_id
 * @property integer $notify
 * @property string $comment
 * @property string $date_added
 */
class ReturnHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%return_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_id', 'return_status_id', 'notify', 'comment', 'date_added'], 'required'],
            [['return_id', 'return_status_id', 'notify'], 'integer'],
            [['comment'], 'string'],
            [['date_added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'return_history_id' => 'Return History ID',
            'return_id' => 'Return ID',
            'return_status_id' => 'Return Status ID',
            'notify' => 'Notify',
            'comment' => '备注',
            'date_added' => '时间',
        ];
    }

    public function getReturnStatus(){
        return $this->hasOne(ReturnStatus::className(), ['return_status_id' => 'return_status_id']);
    }
}
