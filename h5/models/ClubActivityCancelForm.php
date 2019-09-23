<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/29
 * Time: 10:32
 */
namespace h5\models;
use api\models\V1\ClubActivityUser;
use api\models\V1\ClubTry;
use api\models\V1\ReturnBase;
use api\models\V1\ReturnProduct;
use yii\base\Model;
use Yii;
use yii\web\NotFoundHttpException;
class ClubActivityCancelForm extends Model{
    public $data;
    public $comment;
    public function __construct($id,$config = [])
    {
        if($model=ClubActivityUser::findOne(['customer_id'=>Yii::$app->user->getId(),'id'=>$id,'status'=>1])){
            $this->data=$model;
        }else{
            throw new NotFoundHttpException("没有找到活动！");
        }
    }
    public function rules()
    {
        return [
            [['comment'],'required'],
            ['comment','string','max'=>100],
        ];
    }
    public function save(){
        if($this->validate()){
            if($this->data->order) {
                $return = new ReturnBase();
                $return->order_id = $this->data->order_id;
                $return->order_code = 'O' . $this->data->order_id;
                $return->order_no = $this->data->order->order_no;
                $return->date_ordered = $this->data->order->date_modified ? $this->data->order->date_modified : date('Y-m-d H:i:s', time());
                $return->customer_id = Yii::$app->user->getId();
                $return->firstname = Yii::$app->user->identity->firstname;
                $return->email = Yii::$app->user->identity->email;
                $return->telephone = Yii::$app->user->identity->telephone;
                $return->return_status_id = 1;
                $return->comment = $this->comment;
                $return->total = $this->data->total;
                $return->date_added = date('Y-m-d H:i:s', time());
                $return->date_modified = date('Y-m-d H:i:s', time());
                $return->send_status = 0;
                $return->is_all_return = 1;
                if ($return->save()) {
                    $return->return_code = 'RO' . $this->data->order_id . $return->return_id;
                    $return->save();
                }
                $returnProduct = new ReturnProduct();
                $returnProduct->return_id = $return->return_id;
                $returnProduct->return_code = $return->return_code;
                $returnProduct->model = 'Activity';
                $returnProduct->name = $this->data->activity->title;
                $returnProduct->quantity = $this->data->quantity;
                $returnProduct->total = $this->data->total;
                $returnProduct->product_total = $this->data->total;
                $returnProduct->opened = 0;
                $returnProduct->from_id = 0;
                $returnProduct->from_table = 'order_total';
                $returnProduct->save();
            }
         if($model=ClubActivityUser::findOne(['id'=>$this->data->id,'status'=>1])){
             $model->status=0;
             $model->save();
         }
            return true;
        }else{
            return null;
        }
    }
    public function attributeLabels()
    {
        return [
            'comment' => '取消报名原因',
        ];
    }
}