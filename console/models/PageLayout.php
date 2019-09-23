<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%page_layout}}".
 *
 * @property integer $page_id
 * @property integer $layout_id
 *
 * @property Layout $layout
 * @property Page $page
 */
class PageLayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'layout_id'], 'required'],
            [['page_id', 'layout_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => '页面表_页面id',
            'layout_id' => '布局_布局ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLayout()
    {
        return $this->hasOne(Layout::className(), ['id' => 'layout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
