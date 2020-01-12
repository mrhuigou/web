<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\Customer;

/**
 * CustomerSearch represents the model behind the search form about `api\models\V1\Customer`.
 */
class CustomerSearch extends Customer
{
     public $order_all_count;
     public $order_success_count;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'store_id', 'email_validate', 'telephone_validate', 'zone_id', 'city_id', 'district_id', 'idcard_validate', 'newsletter', 'address_id', 'customer_group_id', 'status', 'approved', 'forget_link_validity', 'total_follow', 'total_follower', 'total_exp', 'total_comment', 'total_album', 'total_favorite_shares', 'total_favorite_albums', 'is_star', 'usergroup_id', 'credits', 'ext_credits_1', 'ext_credits_2', 'ext_credits_3', 'points', 'customer_level_id', 'custom', 'id', 'authen_business'], 'integer'],
            [['firstname', 'lastname', 'nickname', 'onmobile', 'email', 'telephone', 'gender', 'birthday', 'education', 'occupation', 'idcard', 'fax', 'password', 'salt', 'cart', 'wishlist', 'ip', 'token', 'date_added', 'code', 'password_reset_token', 'paymentpwd', 'psalt', 'favourite_stores', 'photo', 'signature', 'timeline_bg', 'source_from', 'company_name', 'company_no', 'legel_name', 'auth_key'], 'safe'],
            [['longitude', 'latitude'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        $Query = new \yii\db\Query();
        $Query->select(['*','(select count(order_id) from jr_order WHERE customer_id=tmp.customer_id )AS  order_count','(select count(order_id) from jr_order WHERE sent_to_erp="Y" and customer_id=tmp.customer_id ) AS  sent_order_count']);

        $subQuery = Customer::find();
//        print_r($query->limit(30)->all());exit;
        $subQuery->andFilterWhere([
//            'customer_id' => $this->customer_id,
            'customer_id' => $params?$this->customer_id:0,
//            'store_id' => $this->store_id,
//            'email_validate' => $this->email_validate,
//            'telephone_validate' => $this->telephone_validate,
//            'birthday' => $this->birthday,
//            'zone_id' => $this->zone_id,
//            'city_id' => $this->city_id,
//            'district_id' => $this->district_id,
//            'idcard_validate' => $this->idcard_validate,
//            'newsletter' => $this->newsletter,
//            'address_id' => $this->address_id,
//            'customer_group_id' => $this->customer_group_id,
//            'status' => $this->status,
//            'approved' => $this->approved,
//            'date_added' => $this->date_added,
//            'longitude' => $this->longitude,
//            'latitude' => $this->latitude,
//            'forget_link_validity' => $this->forget_link_validity,
//            'total_follow' => $this->total_follow,
//            'total_follower' => $this->total_follower,
//            'total_exp' => $this->total_exp,
//            'total_comment' => $this->total_comment,
//            'total_album' => $this->total_album,
//            'total_favorite_shares' => $this->total_favorite_shares,
//            'total_favorite_albums' => $this->total_favorite_albums,
//            'is_star' => $this->is_star,
//            'usergroup_id' => $this->usergroup_id,
//            'credits' => $this->credits,
//            'ext_credits_1' => $this->ext_credits_1,
//            'ext_credits_2' => $this->ext_credits_2,
//            'ext_credits_3' => $this->ext_credits_3,
//            'points' => $this->points,
//            'customer_level_id' => $this->customer_level_id,
//            'custom' => $this->custom,
//            'id' => $this->id,
//            'authen_business' => $this->authen_business,
        ]);

        $subQuery->andFilterWhere(['like', 'firstname', $this->firstname])
//            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
//            ->andFilterWhere(['like', 'onmobile', $this->onmobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telephone', $this->telephone]);
//            ->andFilterWhere(['like', 'gender', $this->gender])
//            ->andFilterWhere(['like', 'education', $this->education])
//            ->andFilterWhere(['like', 'occupation', $this->occupation])
//            ->andFilterWhere(['like', 'idcard', $this->idcard])
//            ->andFilterWhere(['like', 'fax', $this->fax])
//            ->andFilterWhere(['like', 'password', $this->password])
//            ->andFilterWhere(['like', 'salt', $this->salt])
//            ->andFilterWhere(['like', 'cart', $this->cart])
//            ->andFilterWhere(['like', 'wishlist', $this->wishlist])
//            ->andFilterWhere(['like', 'ip', $this->ip])
//            ->andFilterWhere(['like', 'token', $this->token])
//            ->andFilterWhere(['like', 'code', $this->code])
//            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
//            ->andFilterWhere(['like', 'paymentpwd', $this->paymentpwd])
//            ->andFilterWhere(['like', 'psalt', $this->psalt])
//            ->andFilterWhere(['like', 'favourite_stores', $this->favourite_stores])
//            ->andFilterWhere(['like', 'photo', $this->photo])
//            ->andFilterWhere(['like', 'signature', $this->signature])
//            ->andFilterWhere(['like', 'timeline_bg', $this->timeline_bg])
//            ->andFilterWhere(['like', 'source_from', $this->source_from])
//            ->andFilterWhere(['like', 'company_name', $this->company_name])
//            ->andFilterWhere(['like', 'company_no', $this->company_no])
//            ->andFilterWhere(['like', 'legel_name', $this->legel_name])
//            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        $Query->from(['tmp'=>$subQuery]);
        $dataProvider = new ActiveDataProvider([
            'query' => $Query->orderBy('customer_id desc'),
        ]);

        return $dataProvider;
    }
}
