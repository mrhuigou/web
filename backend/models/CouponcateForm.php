<?php
namespace backend\models;

use api\models\V1\Category;
use api\models\V1\CouponCategory;
use api\models\V1\CategoryDisplay;
use api\models\V1\CategoryDisplayDescription;
use api\models\V1\CategoryDisplayToCategory;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class CouponcateForm extends Model
{
    public $id;
    public $title;
    public $pid;
    public $pid_data;
    public $relate_cate;
    public $relate_cate_data;
    public $sort = 0;
    public $status = 1;
    public $isNewRecord=true;
    public $coupon_id;
    public function __construct($config = []){
        if($config){
            $this->isNewRecord=false;
            $model=CouponCategory::find()->where(['coupon_id'=>$config['id']])->all();
            if($model){
                    $cate=[];
                    foreach($model as $value){
                        $cate[]=$value->category_id;
                    }
                $this->relate_cate=implode(',',$cate);
            }
        }

        
        $Category=Category::find()->orderBy('sort_order asc')->all();
        $tree=[];
        foreach($Category as $value){
            if(in_array($value['category_id'],explode(",",$this->relate_cate))){
                $tree[]=[
                    'id'=>$value->category_id,
                    'pid'=>$value->parent_id,
                    'sort'=>$value->sort_order,
                    'text'=>$value->description->name,
                    "state"=>['selected'=>true]
                ];
            }else{
                $tree[]=[
                    'id'=>$value->category_id,
                    'pid'=>$value->parent_id,
                    'sort'=>$value->sort_order,
                    'text'=>$value->description->name,
                ];
            }

        }
        $this->relate_cate_data=\common\component\Helper\Helper::genTree($tree);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            // [['title', 'pid'], 'required'],
            ['relate_cate','string'],
            [['sort','status'],'integer']


        ];
    }

    public function save(){
        if ($this->validate()) {
            
            CouponCategory::deleteAll(['coupon_id'=>$this->id]);
            $category=explode(",",$this->relate_cate);
            foreach($category as $key=>$value){
                $cate=new CouponCategory();
                $cate->coupon_id=$this->id;
                $cate->category_id=$value;
                $cate->save();
            }
            return true;
        }else{
            return false;
        }
    }

    public function attributeLabels(){
        return ['title'=>'分类名称',
            'pid'=>'所属上级',
            'relate_cate' => '关联分类',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }
}
