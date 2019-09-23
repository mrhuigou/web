<?php
namespace backend\models;

use api\models\V1\CategoryDisplay;
use yii\base\Model;

/**
 * Login form
 */
class DisplayCateBannerForm extends Model
{
    public $id;
    public $url;
    public $image;
    public function __construct($id=0,$config = []){

	    if($model=CategoryDisplay::findOne(['category_display_id'=>$id])){
	        $this->id=$model->category_display_id;
	        $this->url=$model->url;
	        $this->image=$model->image;
	    }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image','url'],'string'],
        ];
    }

    public function save(){
        if ($this->validate()) {
           if($model=CategoryDisplay::findOne(['category_display_id'=>$this->id])){
	           $model->image=$this->image;
	           $model->url=$this->url;
	           $model->save();
	           return $model;
           }
        }else{
            return false;
        }
    }
    public function attributeLabels(){
        return ['url'=>'链接',
            'image'=>'图片',
        ];
    }
}
