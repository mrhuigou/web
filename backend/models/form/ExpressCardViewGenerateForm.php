<?php
namespace backend\models\form;
use api\models\V1\ExpressCard;
use api\models\V1\ExpressCardView;
use api\models\V1\Page;
use api\models\V1\PageDescription;
use common\component\Helper\Helper;
use yii\base\Model;
use Yii;
use yii\helpers\Html;

/**
 * AddressForm
 */
class ExpressCardViewGenerateForm extends Model {
    public $card_length; //卡号总长度 prefix length + 6位递增数字
    public $card_prefix; //卡前缀
    public $card_view_count; // 生成卡数量
    public $pwd_length=8;  // 密码长度
    public $status = 1;
    public $express_card_id; //提货卡id

    public function __construct($config = [])
    {

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_view_count', 'pwd_length', 'express_card_id','card_prefix'], 'required'],
            [['express_card_id'], 'integer'],
            [['card_prefix'],'string','max'=>10],
            [['card_view_count'],'integer','max'=>5000],
            [['pwd_length'],'integer','max'=>10,'min'=>6],

        ];
    }

    /**
     * AddressForm
     *
     * @return address|null the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $this->load(Yii::$app->request->post());
            if($this->express_card_id){
                $express_card = ExpressCard::findOne(['id'=>$this->express_card_id]);
                if($express_card && $this->card_view_count>0){
                    $max_index = ExpressCardView::find()->where(['card_prefix'=>$this->card_prefix])->max('card_index');
                    $i = 0;
                    $count = $this->card_view_count;
                    if($max_index){
                        $i = $i + $max_index;
                        $count = $this->card_view_count + $max_index;
                    }
                    for($i;$i< $count;$i++){
                        $model = new ExpressCardView();
                        $model->card_pwd = Helper::generate_code(8);
                        $model->card_no = $this->card_prefix.sprintf("%06d", $i+1);
                        $model->express_card_id = $this->express_card_id;
                        $model->status = $this->status;
                        $model->card_prefix = $this->card_prefix;
                        $model->card_index = $i;
                        $model->save();
                    }
                }
            }



            return true;
        }
        return null;
    }

    public function attributeLabels()
    {
        return [
            'card_view_count' => '生成数量',
            'express_card_id' => '所属提货卡',
            'status' => '状态',
            'pwd_length' => '密码长度',
            'card_prefix'=> '卡号前缀'

        ];
    }
}
