<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%keyword}}".
 *
 * @property integer $keyword_id
 * @property string $name
 * @property integer $review
 * @property integer $weight
 * @property integer $status
 * @property string $color
 * @property string $url
 */
class Keyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%keyword}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['review', 'weight', 'status'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'keyword_id' => 'Keyword ID',
            'name' => '名称',
            'review' => 'Review',
            'weight' => '权重',
            'status' => '状态',
            'color' => '颜色',
            'url' => '链接',
        ];
    }
}
