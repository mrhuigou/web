<?php

namespace api\controllers\mall\v1;
use api\models\V1\Affiliate;
use api\models\V1\Customer;
use api\models\V1\CustomerAuthentication;
use api\models\V1\Point;
use api\models\V1\PointCustomer;
use api\models\V1\PointCustomerFlow;
use common\component\Helper\Xcrypt;
use yii\base\ErrorException;
use yii\db\Exception;
use \yii\rest\Controller;

class PointsController extends Controller {
    public function actionUpdatePoints(){
//        $token['sourcefrom'] = ''; //获取 affiliate身份
//        //$token['code'] = ''; //获取point身份
//        $token['telephone'] = ''; //获取用户身份
//        $token['points'] = ''; //本次积分变动数量
//        $token['description'] = '';//积分变动说明
//        $token['type'] = '1/0';//积分增加还是减少
//        $token['t'] = '';
//        //post['token'] post['sourcefrom']
        $token_encrypt = \Yii::$app->request->get('token');
        $point_code = \Yii::$app->request->post('code');//

        $point = Point::findOne(['code'=>$point_code,'status'=>1]);

        if($point){
            $key = $point->encrypt_key;//"1234567812345678";
            $iv = $point->encrypt_iv;// '1234567812345678';

            $crypt = new  Xcrypt($key,'cbc',$iv);
            $token_sring = $crypt->decrypt($token_encrypt);
            $token_arr = json_decode($token_sring,TRUE);
            if($token_arr['code'] == $point_code) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    //$point = Point::findOne(['point_id'=>$affiliate->point_id,'status'=>1]);
                    if($point){
                        $customer = Customer::findOne(['customer_id' => $token_arr['customer_id']]);

                            $point_customer = PointCustomer::findOne(['customer_id' => $customer->customer_id, 'point_id' => $point->point_id]);
                            if ($point_customer) {
                                $point_customer = new PointCustomer();
                                $point_customer->point_id = $point->point_id;
                                $point_customer->customer_id = $customer->customer_id;
                                $point_customer->points = 0;
                                $point_customer->date_added = date('Y-m-d H:i:s');
                            }
                            if (intval($token_arr['type']) == 1) {
                                $left_points = $point_customer->points + $token_arr['points'];
                                $amount = abs($token_arr['points']);
                            } else {
                                $left_points = $point_customer->points - abs($token_arr['points']);
                                $amount = -abs($token_arr['points']);
                            }

                            if ($left_points < 0) {
                                //$message = '错误：当前该用户积分为' . $left_points;
                                throw new \Exception("积分数量异常");
                            } else {
                                $point_customer->date_modified = date('Y-m-d H:i:s');
                                $point_customer->points = $left_points;
                                if (!$point_customer->save(false)) {
                                    throw new \Exception("积分客户保存异常2");
                                }
                                $point_customer_flow = new PointCustomerFlow();
                                $point_customer_flow->point_customer_id = $point_customer->point_customer_id;
                                $point_customer_flow->customer_id = $customer->customer_id;
                                $point_customer_flow->description = $token_arr['description'];
                                $point_customer_flow->amount = $amount;
                                $point_customer_flow->points = $left_points;
                                $point_customer_flow->remark = json_encode($token_arr);
                                $point_customer_flow->date_added = date('Y-m-d H:i:s');
                                $point_customer_flow->type = 'api_update';
                                $point_customer_flow->type_id = 0;
                                $point_customer_flow->save();
                                if (!$point_customer_flow->save(false)) {
                                    throw new \Exception("积分客户流水保存异常");
                                }
                            }
                            $transaction->commit();
                            $return['status'] = true;
                            $return['error_code'] = 0;
                            $return['msg'] = '更新成功';
                            return json_encode($return);


                    }else{
                        throw  new  Exception("数据错误");
                    }

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $return['status'] = false;
                    $return['error_code'] = 2;
                    $return['msg'] = $e->getMessage();
                  return json_encode($return);
                }
            }else{
                $return['status'] = false;
                $return['error_code'] = 3;
                $return['msg'] = '用户标识错误';
                return json_encode($return);
                //throw new ErrorException("用户标识错误");
            }

        }


    }
    public function actionGetPoints(){
        $token_encrypt = \Yii::$app->request->get('token');
        $point_code = \Yii::$app->request->post('code');//

        $point = Point::findOne(['code'=>$point_code,'status'=>1]);

        if($point){
            $key = $point->encrypt_key;//"1234567812345678";
            $iv = $point->encrypt_iv;// '1234567812345678';

            $crypt = new  Xcrypt($key,'cbc',$iv);
            $token_sring = $crypt->decrypt($token_encrypt);
            $token_arr = json_decode($token_sring,TRUE);
            if($token_arr['code'] == $point_code) {
                try {
                    if($point){
                        $customer = Customer::findOne(['customer_id' => $token_arr['customer_id']]);
                        $point_customer = PointCustomer::findOne(['customer_id' => $customer->customer_id, 'point_id' => $point->point_id]);
                        $points = $point_customer->getPoints();

                        $return['status'] = true;
                        $return['error_code'] = 0;
                        $return['data'] = $points;
                        return json_encode($return);
                    }else{
                        throw  new  Exception("数据错误");
                    }

                } catch (\Exception $e) {
                    $return['status'] = false;
                    $return['error_code'] = 2;
                    $return['msg'] = $e->getMessage();
                    return json_encode($return);
                }
            }else{
                $return['status'] = false;
                $return['error_code'] = 3;
                $return['msg'] = '用户标识错误';
                return json_encode($return);
                //throw new ErrorException("用户标识错误");
            }

        }
    }
}