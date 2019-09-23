<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_personal}}".
 *
 * @property integer $affiliate_personal_id
 * @property string $name
 * @property integer $status
 * @property string $date_start
 * @property string $date_end
 * @property string $date_added
 * @property string $description
 * @property string $date_update
 * @property string $type
 * @property string $code
 */
class AffiliatePersonal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_personal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_personal_id'], 'required'],
            [['affiliate_personal_id', 'status'], 'integer'],
            [['date_start', 'date_end', 'date_added'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'affiliate_personal_id' => 'Affiliate Personal ID',
            'name' => 'Name',
            'status' => 'Status',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'date_added' => 'Date Added',
        ];
    }
    public function getDetails(){
        return $this->hasMany(AffiliatePersonalDetail::className(),['affiliate_personal_id'=>'affiliate_personal_id']);
    }
}
