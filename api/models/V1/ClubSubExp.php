<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_sub_exp}}".
 *
 * @property integer $sub_id
 * @property integer $exp_id
 */
class ClubSubExp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_sub_exp}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sub_id', 'exp_id'], 'required'],
            [['sub_id', 'exp_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sub_id' => 'Sub ID',
            'exp_id' => 'Exp ID',
        ];
    }
    public function getSubject(){
        return $this->hasOne(ClubSubject::className(),['sub_id'=>'sub_id']);
    }
    public function getExp(){
        return $this->hasOne(ClubExperience::className(),['exp_id'=>'exp_id']);
    }
}
