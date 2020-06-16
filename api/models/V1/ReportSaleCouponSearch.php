<?php

namespace api\models\V1;

use common\extensions\widgets\xlsxwriter\XLSXWriter;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReportSaleCouponSearch extends Model
{
    public $begin_date;
    public $end_date;
    public $coupons;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin_date','end_date'], 'required'],
            [['coupons'], 'safe']
        ];
    }


    public function attributeLabels()
    {
        return [
            'begin_date'=>'开始时间',
            'end_date'=>'结束时间',
	        'coupons'=>'优惠码'
        ];
    }



    public function search($params)
    {
        $this->load($params);
        $Query = new \yii\db\Query();
        $Query->select("c.`code`,c.`status`,c.`name`,c.`coupon_id`,(select count(*) from jr_customer_coupon where coupon_id=c.coupon_id) as user_count,COUNT(ch.coupon_id) AS coupon_count,(select count(*) from jr_customer_coupon where coupon_id=c.coupon_id and is_notice = 1) as notice_count,(select count(*) from jr_customer_coupon where coupon_id=c.coupon_id and is_notice = 2) as notice_fail_count,COUNT(DISTINCT ch.customer_id) AS customer_count,(select count(*) from jr_customer_coupon where coupon_id=c.coupon_id and is_use=0) as customer_not_use_count,SUM(ch.amount) AS coupon_total,COUNT(DISTINCT o.order_id) AS order_count,SUM(o.total) AS order_total")
	            ->from(['c'=>Coupon::tableName()])->leftJoin(['ch'=>CouponHistory::tableName()],'c.coupon_id=ch.coupon_id')
	           ->leftJoin(['o'=>Order::tableName()],'o.order_id=ch.order_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $Query->orderBy('c.coupon_id desc'),
        ]);
	    $Query->filterWhere(['= ','o.sent_to_erp','Y']);
        $this->begin_date = $this->begin_date?:date("Y-m-d");
        $this->end_date = $this->end_date?:date("Y-m-d");
        if($this->begin_date){
            $Query->andFilterWhere(['>= ','DATE(ch.date_added)',$this->begin_date]);
        }
        if($this->end_date){
            $Query->andFilterWhere(['<= ','DATE(ch.date_added)',$this->end_date]);
        }
	    if($this->coupons){
		    $sub_data = explode("\r\n", $this->coupons);
		    $Query->andFilterWhere(['in','c.code',$sub_data]);
	    }
        $Query->groupBy("ch.coupon_id");
        return $dataProvider;
    }
    public function export($params){
        $this->load($params);
        $Query = new \yii\db\Query();
        if(Yii::$app->request->get('type') == 'export_use_customer'){
            $filter_datas['is_use_status'] = [1,2];
            $filter_datas['coupon_id'] = Yii::$app->request->get('coupon_id');
            $result = $this->exportCustomerInfo($filter_datas);
        }
        if(Yii::$app->request->get('type') == 'export_not_use_customer'){
            $filter_datas['is_use_status'] = 0;
            $filter_datas['coupon_id'] = Yii::$app->request->get('coupon_id');
            $result = $this->exportCustomerInfo($filter_datas);
        }
        return $result;

    }
    private function exportCustomerInfo($filter_datas){
        $coupons = Coupon::find()->where(['coupon_id'=> $filter_datas['coupon_id']])->one();
//        if($coupons){
//            foreach ($coupons as $coupon){
//                $coupon_ids[] = $coupon->coupon_id;
//            }
//        }
        $subQuery = new \yii\db\Query();
        $subQuery->select('customer_id')->from(CustomerCoupon::tableName())->where(['coupon_id'=>$coupons->coupon_id,'is_use'=>$filter_datas['is_use_status']]);
        $customers = Customer::find()->select(['customer_id','telephone'])->where(['customer_id'=>$subQuery])->all();
        return $customers;
    }

}
