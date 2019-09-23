<?php
namespace backend\models;


use api\models\V1\Customer;
use api\models\V1\Message;
use api\models\V1\MessageContent;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class MessageContentForm extends Model
{
    public $message_content_id;
    public $device;
    public $type;
    public $title;
    public $intro;
    public $image;
    public $link;
    public $item_id;
    public $date_added;

    public $filter_type;
    public $filter_textfield;
    public $filter_date_begin;
    public $filter_date_end;

    public function __construct($config = []){
        if(!empty($config)){
            $model = MessageContent::findOne($config['id']);
            if($model){
                $body = $model->body;
                $body = unserialize($body);
                $this->message_content_id = $model->message_content_id;
                $this->device = $model->device;
                $this->type = $model->type;
                $this->title = $body['title'];
                $this->intro = $body['intro'];
                $this->image = $body['image'];
                $this->link = $body['link'];

                $this->filter_type = $model->filter_type;
                $this->filter_textfield = $model->filter_textfield;
                $this->filter_date_begin = $model->filter_date_begin;
                $this->filter_date_end = $model->filter_date_end;

            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            // [['title', 'pid'], 'required'],
            [['title'], 'required'],
            [['device','type','title','intro','image','link','filter_textfield','filter_type','filter_date_begin','filter_date_end'],'string'],
        ];
    }

    public function save(){
        if ($this->validate()) {
            if(!empty($this->message_content_id)){
                $model = MessageContent::findOne($this->message_content_id);
            }else{
                $model = new MessageContent();
            }
            $model->type = $this->type;
            $model->device = $this->device;
            $model->title = $this->title;
            $body = array(
                'title' => $this->title,
                'intro' => $this->intro,
                'image' => $this->image,
                'link'  => $this->link,
            );
            $body = serialize($body);
            $model->body = $body;
            $model->date_added = date('Y-m-d H:i:s');
            $model->filter_textfield = $this->filter_textfield;
            $model->filter_type = $this->filter_type;
            $model->filter_date_begin = $this->filter_date_begin;
            $model->filter_date_end = $this->filter_date_end;
            $model->save();


            $model_customers = array();
            if(strtoupper($model->filter_type) == strtoupper('single_customer')){
                $model_customers = Customer::find()->where(['telephone'=>$model->filter_textfield])->all();
            }
            if(strtoupper($model->filter_type) == strtoupper('all')){
                $model_customers = Customer::find()->where(['approved'=>1])->all();

            }
            if(strtoupper($model->filter_type) == strtoupper('buy_product')){
                $sql = "SELECT o.customer_id  FROM jr_order_product op
LEFT JOIN jr_order o ON(op.order_id = o.order_id)
 WHERE o.sent_to_erp = 'Y'";
                if($this->filter_textfield){
                    $sql .=  " op.product_base_code =".$this->filter_textfield;
                }
                if($this->filter_date_begin ){
                    $sql .=  "  AND o.date_added >= '".$this->filter_date_begin."' ";
                }
                if($this->filter_date_end ){
                    $sql .=  "  AND o.date_added <= '".$this->filter_date_end."' ";
                }
                $sql .= "GROUP BY o.order_id ORDER BY o.customer_id ";
                $command = \Yii::$app->db->createCommand($sql);
                $model_customers = $command->queryAll();
            }
            $this->actionSendMessage($model_customers,$model->message_content_id);

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
    private function actionSendMessage($customers,$message_content_id){
        if(!empty($customers)){
            $message_content = MessageContent::findOne($message_content_id);
            if($message_content){
                foreach($customers as $customer){
                    if($customer->customer_id && $message_content_id){
                        if(is_object($customers)){
                            $customer_id = $customer->customer_id;
                        }
                        if(is_array($customers)){
                            $customer_id = $customer['customer_id'];
                        }
                        $message = new Message();
                        $message->message_type_id = 2;
                        //$message->content = $message_content->title;
                        $message->device = $message_content->device;
                        $message->message_content_id = $message_content_id;
                        $message->customer_id = $customer_id;
                        $message->is_read = 0;
                        $message->date_added = date('Y-m-d H:i:s');
                        $message->status = 1;
                        $message->save();
                    }
                }
            }

        }
    }
}
