<?php

namespace api\models\V1;

use common\component\Helper\Xcrypt;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\httpclient\Client;

/**
 * This is the model class for table "{{%point}}".
 *
 * @property integer $point_id
 * @property string $display_name
 * @property string $company_name
 * @property string $rate
 * @property integer $status
 */
class Point extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%point}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate'], 'number'],
            [['status'], 'integer'],
            [['display_name', 'company_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'point_id' => 'Point ID',
            'display_name' => 'Display Name',
            'company_name' => 'Company Name',
            'rate' => 'Rate',
            'status' => 'Status',
        ];
    }
    public function getPointByCurl(){
        $point_total = 0;
        try{
            if($this->init_points_url){
                $key = $this->encrypt_key;//"1234567812345678";
                $iv = $this->encrypt_iv;// '1234567812345678';
                list($msec, $sec) = explode(' ', microtime());
                $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $token['telephone'] = Yii::$app->user->identity->telephone;
                $token['t'] = $msectime;

                $crypt = new  Xcrypt($key, 'cbc', $iv);
                $token_en = $crypt->encrypt(json_encode($token));
                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('get')
                    ->setUrl($this->init_points_url) //获取当前可用接口
                    ->setData(['token' => $token_en])
                    ->addData(['t'=>$token['t']])
                    ->send();
                if($response){
                    $response = $response->getContent();
                    $response = json_decode($response);
                    if($response->status){
                        $point_total = intval($response->data) ;//当前可用积分
                    }
                }
            }


        }catch ( ErrorException $e){
            Yii::error('getPointsError================>'.$e->getMessage());
        }

        return $point_total;
    }
    public function notice($data){
        $key = $this->encrypt_key;//"1234567812345678";
        $iv = $this->encrypt_iv;// '1234567812345678';
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        $token_data['telephone'] = $data['telephone'];
        $token_data['changeType'] = $data['changeType']; //1增加 2扣除
        $token_data['description'] = $data['description'];
        $token_data['creditValue'] = abs($data['creditValue']);
        $token_data['changeDate'] = $data['changeDate'];
        $token_data['changeResource'] = 6;
        $token_data['orderId'] =  $data['orderId'] ;
        $token_data['count'] =  $data['count'];
        $token_data['status'] =  isset($data['status']) ? $data['status']: 1 ;
        $token_data['t'] = $msectime;
        $token['token'] = $token_data;
        $token['t'] = $msectime;

        $crypt = new  Xcrypt($key, 'cbc', $iv);
        $token_en = $crypt->encrypt(json_encode($token_data));
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($this->update_points_url) //更新接口
            ->setData(['token' => $token_en,'t'=>$token['t']])
            ->send();
        $response = $response->getContent();
        $response = json_decode($response);
        if($response && $response->status ){
            $point_customer_flow = PointCustomerFlow::findOne(['point_customer_flow_id'=>$data['point_customer_flow_id']]);
            $point_customer_flow->is_notice = 1;
            $point_customer_flow->status = isset($data['status']) ? $data['status']: 0 ;
            $point_customer_flow->save();
        }else{
            Yii::error("update point=========>".json_encode($response));
            throw new  Exception($response->msg);
        }
    }
}
