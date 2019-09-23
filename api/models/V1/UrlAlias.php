<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%url_alias}}".
 *
 * @property integer $url_alias_id
 * @property string $query
 * @property string $keyword
 */
class UrlAlias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%url_alias}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['query', 'keyword'], 'required'],
            [['query', 'keyword'], 'string', 'max' => 255],
            [['query'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url_alias_id' => 'Url Alias ID',
            'query' => 'Query',
            'keyword' => 'Keyword',
        ];
    }
}
