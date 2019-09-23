<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%verify_code}}".
 *
 * @property integer $verify_code_id
 * @property string $phone
 * @property string $code
 * @property integer $status
 * @property string $date_added
 */
class VerifyCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%verify_code}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'code', 'status'], 'required'],
            [['status'], 'integer'],
            [['date_added'], 'safe'],
            [['phone'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verify_code_id' => 'Verify Code ID',
            'phone' => 'Phone',
            'code' => 'Code',
            'status' => 'Status',
            'date_added' => 'Date Added',
        ];
    }
}
