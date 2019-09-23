<?php
namespace backend\models;

use api\models\V1\RechargeCard;
use Yii;
use yii\base\Model;
use common\component\Helper\RandomString;

/**
 * Login form
 */
class RechargecardForm extends Model
{
    public $quantity;
    public $card_no;
    public $value;
    public $card_code;
    public $start_time;
    public $end_time;
    public $status=0;
    public $title;
    public $isNewRecord=true;

    public function __construct($config = []){
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_no','value', 'card_code', 'card_pin', 'start_time', 'status', 'quantity','title'], 'required'],
            [['status', 'quantity'], 'integer'],
            [['value'],'number'],
            [['start_time', 'end_time', 'created_at'], 'safe'],
            [['card_code', 'card_pin'], 'string', 'max' => 32],
            ['card_no','string', 'max' => 255]
        ];
    }

    public function save(){
        // $lastone = RechargeCard::find()->orderBy(['id' => SORT_DESC])->one();
        // $last_id = $lastone->id;
        for ($i=0; $i < $this->quantity ; $i++) { 
            $model = new RechargeCard();
            $model->card_no=$this->card_no.sprintf("%05d", $i+1);
            $model->value = $this->value;
            $model->title = $this->title;
            $model->card_code = $this->card_code;
            $model->card_pin = RandomString::random_text('numeric',16);
            $model->start_time = $this->start_time;
            $model->end_time = $this->end_time;
            $model->created_at = date("Y-m-d H:i:s");
            $model->status = $this->status;
            $model->save();
        }
        return true;
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'card_no'=>'卡号',
            'value' => '金额',
            'title' => '名称',
            'card_code' => '编码',
            'card_pin' => '密码',
            'start_time' => '开始时间',
            'end_time' => '过期时间',
            'created_at' => '生成时间',
            'status' => '状态',
            'quantity' => '数量',
        ];
    }

    
}
