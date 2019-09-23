<?php
namespace backend\models;

use api\models\V1\Category;
use api\models\V1\CategoryDisplay;
use api\models\V1\CategoryDisplayDescription;
use api\models\V1\CategoryDisplayToCategory;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class DisplaycateForm extends Model
{
    public $id;
    public $title;
    public $pid;
    public $pid_name;
    public $relate_cate;
    public $sort = 0;
    public $status = 1;
    public $image;
    public $isNewRecord=true;
    public function __construct($config = []){
        if($config){
            $this->isNewRecord=false;
            if($model=CategoryDisplay::findOne(['category_display_id'=>$config['id'],'is_delete'=>0])){
                $this->id=$model->category_display_id;
                $this->title=$model->description->name;
                $this->sort=$model->sort_order;
                $this->status=$model->status;
                $this->pid=$model->parent_id;
                $this->pid_name=$this->getParentNames($model->parent_id,$model->description->name);
                $this->image=$model->image;
                if($model->categorys){
                    $cate=[];
                    foreach($model->categorys as $value){
                        $cate[]=$value->category_code;
                    }
                $this->relate_cate=implode("\n",$cate);
                }
            }
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['title'], 'required'],
            [['relate_cate','image'],'string'],
            [['sort','status','pid'],'integer']
        ];
    }

    public function save(){
        if ($this->validate()) {
           if(!$model=CategoryDisplay::findOne(['category_display_id'=>$this->id])){
               $model= new CategoryDisplay();
               $model->date_added=date('Y-m-d H:i:s',time());
           }
            $model->sort_order=$this->sort;
            $model->status=$this->status;
            $model->parent_id=$this->pid;
            $model->image=$this->image;
            $model->date_modified=date('Y-m-d H:i:s',time());
            $model->save();
            if(!$descripton=CategoryDisplayDescription::findOne(['category_display_id'=>$model->category_display_id])){
                $descripton=new CategoryDisplayDescription();
            }
            $descripton->category_display_id=$model->category_display_id;
            $descripton->name=$this->title;
            $descripton->language_id=2;
            $descripton->save();
            $relate_cate=explode("\r\n",$this->relate_cate);
            $relate_array=[];
            Yii::error('relate_cate:'.json_encode($relate_cate));
            if($relate_cate) {
                foreach ($relate_cate as $key => $value) {
                    if ($relate = Category::findOne(['code' => trim($value)])) {
                        Yii::error('relate:'.json_encode($relate));

                        $relate_array[$relate->category_id] = $relate->code;
                        if ($relate->children) {
                            foreach ($relate->children as $children) {
                                $relate_array[$children->category_id] = $children->code;
                            }
                        }
                    }
                }
            }
            Yii::error('relate:'.json_encode($relate_array));
            CategoryDisplayToCategory::deleteAll(['category_display_id'=>$model->category_display_id]);
            if($relate_array){
                foreach($relate_array as $key=>$value){
                    $cate=new CategoryDisplayToCategory();
                    $cate->category_display_id=$model->category_display_id;
                    $cate->category_id=$key;
                    $cate->category_code=$value;
                    $cate->save();
                    Yii::error('relate:'.json_encode($cate->errors));
                }
            }
            return $model;
        }else{
            return false;
        }
    }



    public function getParentNames($parent_id,$name=""){
        if($parent=CategoryDisplay::findOne(['category_display_id'=>$parent_id])){
            $name=$parent->description->name."-".$name;
            return $this->getParentNames($parent->parent_id,$name);
        }
        return $name;
    }




    public function attributeLabels(){
        return ['title'=>'分类名称',
            'image'=>'图片',
            'pid_name'=>'上级名称',
            'pid'=>'所属上级',
            'relate_cate' => '关联系统分类（每行一个系统分类编码，不能有空格）',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }
}
