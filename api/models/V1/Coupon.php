<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $coupon_id
 * @property integer $cate_id
 * @property string $code
 * @property string $name
 * @property string $comment
 * @property string $image_url
 * @property string $model
 * @property string $type
 * @property string $discount
 * @property string $min_discount
 * @property string $max_discount
 * @property integer $shipping
 * @property integer $limit_min_quantity
 * @property integer $limit_max_quantity
 * @property string $total
 * @property string $limit_max_total
 * @property string $date_type
 * @property string $date_start
 * @property string $date_end
 * @property integer $expire_seconds
 * @property integer $quantity
 * @property integer $user_limit
 * @property integer $is_entity
 * @property integer $is_open
 * @property integer $is_prize
 * @property integer $status
 * @property integer $store_id
 * @property integer $platform_id
 * @property string $date_added
 * @property string $receive_begin_date
 * @property string $receive_end_date
 */
class Coupon extends \yii\db\ActiveRecord
{
    public $in_range;
    public $province;
    public $city;
    public $district;
    public $address;
    public $username;
    public $telephone;
    public $is_default;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount', 'max_discount', 'min_discount', 'total','limit_max_total'], 'number'],
            [['cate_id','shipping','limit_min_quantity','limit_max_quantity','expire_seconds', 'quantity', 'user_limit','is_entity', 'is_open', 'status', 'store_id', 'platform_id'], 'integer'],
            [['date_start', 'date_end', 'date_added','receive_begin_date','receive_end_date'], 'safe'],
            [['code', 'name', 'comment', 'image_url', 'model', 'type','date_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => '编号',
            'cate_id'=>'分类',
            'code' => '编码',
            'name' => '名称',
            'comment' => '描述',
            'image_url' => '图片',
            'model' => '模型',
            'type' => '类型',
            'discount' => '优惠金额',
            'max_discount' => '抵用Max金额',
            'min_discount' => '抵用Min金额',
            'shipping' => '免邮',
            'limit_min_quantity' => '最小数量',
            'limit_max_quantity' => '最大数量',
            'total' => '最低限额',
            'limit_max_total' => '最高限额',
            'date_type'=>'时间类型',
            'date_start' => '开始',
            'date_end' => '结束',
            'expire_seconds' => '有效期',
            'quantity' => '库存',
            'user_limit' => '用户',
            'is_entity' => '实物',
            'is_open' => '公开',
            'status' => '状态',
            'store_id' => '店铺ID',
            'platform_id' => 'Platform ID',
            'date_added' => '创建',
            'receive_begin_date'=>'发放开始时间',
            'receive_end_date'=>'发放结束时间'
        ];
    }
    public function getRealDiscount(){
        if($this->type!='F'){
            return floatval((1-min($this->discount,1))*10);
        }else{
            return floatval($this->discount);
        }
    }
	public function getRealDiscountName(){
		if($this->type!='F'){
			return ((1-min($this->discount,1))*10)."折";
		}else{
			return $this->discount."元";
		}
	}
    public function getDescription(){
        $data='';
        if($this->total>0){
            $data.='满'.$this->total.'元';
        }
        if($this->limit_min_quantity>0){
            if($data){
                $data.="且";
            }
            $data.=$this->limit_min_quantity."件以上";
        }
        if($data){
            $data.="可用";
        }else{
            $data="无门槛使用";
        }
        return $data;
    }
    public function getCategory(){
        return $this->hasMany(CouponCategory::className(),['coupon_id'=>'coupon_id']);
    }
    public function getProduct(){
        return $this->hasMany(CouponProduct::className(),['coupon_id'=>'coupon_id'])->orderBy('sort_order asc, coupon_product_id desc');
    }
    public function getGift(){
        return $this->hasMany(CouponGift::className(),['coupon_id'=>'coupon_id']);
    }
    public function getHistory(){
        return $this->hasMany(CouponHistory::className(),['coupon_id'=>'coupon_id']);
    }
    public function getHistoryCount(){
        return CouponHistory::find()->where(['coupon_id'=>$this->coupon_id])->count();
    }
    public function getUsers(){
        return $this->hasMany(CustomerCoupon::className(),['coupon_id'=>'coupon_id']);
    }
    public function getIsHade($customer_id){
        $status=false;
        if($this->user_limit){
            $count=CustomerCoupon::find()->where(['customer_id'=>$customer_id,'coupon_id'=>$this->coupon_id])->count();
            $status=$count < $this->user_limit? false:true;
        }
        return $status;
    }
    public function getUsedStatus($customer_id){
        $status=true;
        if($this->user_limit){
            if(CustomerCoupon::find()->where(['customer_id'=>$customer_id,'coupon_id'=>$this->coupon_id,'is_use'=>0])->andWhere(['>',"end_time",date('Y-m-d H:i:s',time())])->one()){
                $status=false;
            }
        }
        return $status;
    }
}
