<?php

namespace api\controllers\club\v1;
use api\models\V1\Customer;
use api\models\V1\CustomerUmsauth;
use api\modules\oauth2\filters\auth\CompositeAuth;
use common\component\Helper\Helper;
use common\component\Helper\Mcrypt;
use common\component\image\Image;
use Exception;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;


class AuthenController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionUmsauthen(){
        //$tag = 'qdums';
        $tag = '365jiarun';
        $token = \Yii::$app->request->post("token");
        $customer_id = substr($token, 0, strpos($token,"|"));
        //$customer_id = \Yii::$app->request->post("customer_id");
        $name = \Yii::$app->request->post("name"); //明文
        $cert = \Yii::$app->request->post("cert");//身份证号；des加密后
        $card = \Yii::$app->request->post("card");//银行卡号；des加密后


        $mcrypt = new Mcrypt();
        $cert = $mcrypt->decrypt($cert);//解密转成明文
        $card = $mcrypt->decrypt($card); //解密转成明文

        $data=array(
            'tag' => $tag,
            'name' => trim(json_encode($name),'"'),
            'cert' => $cert,
            'card' => $card,
        );

        $result = Helper::Sendums($data);

        if($result){
            $result = json_decode($result);
            if($result->result->respCode == '00'){
                $customer_umsauth =CustomerUmsauth::find()->where(['customer_id'=>$customer_id]);
                if($customer_umsauth->count() > 0){
                    $cua = $customer_umsauth->one();
                    $cua->idcard = $cert;
                    $cua->union_card = $card;
                    $cua->status = 1;
                    $cua->save();
                }else{
                    $ums_auth = new CustomerUmsauth();
                    $ums_auth->customer_id = $customer_id;
                    $ums_auth->idcard = $cert;
                    $ums_auth->union_card = $card;
                    $ums_auth->status = 1;
                    $ums_auth->date_added = date("Y-m-d H:i:s");
                    $ums_auth->save();
                }
                /*老版本的做法，兼容*/
                $customer = Customer::find()->where(['customer_id'=>$customer_id])->one();
                $customer->idcard = $cert;
                $customer->idcard_validate = 2; //通过
                $customer->firstname = $name;
                $customer->save();
                /*老版本的做法，兼容*/
            }else{
                $customer_umsauth =CustomerUmsauth::find()->where(['customer_id'=>$customer_id]);
                if($customer_umsauth->count() > 0){
                    $cua = $customer_umsauth->one();
                    $cua->idcard = $cert;
                    $cua->union_card = $card;
                    $cua->status = -1;
                    $cua->save();
                }else{
                    $ums_auth = new CustomerUmsauth();
                    $ums_auth->customer_id = $customer_id;
                    $ums_auth->idcard = $cert;
                    $ums_auth->union_card = $card;
                    $ums_auth->status = -1; //认证失败
                    $ums_auth->date_added = date("Y-m-d H:i:s");
                    $ums_auth->save();
                }
                /*老版本的做法，兼容*/
                $customer = Customer::find()->where(['customer_id'=>$customer_id])->one();
                $customer->idcard = $cert;
                $customer->idcard_validate = 3; //认证失败
                $customer->firstname = $name;
                $customer->save();
                /*老版本的做法，兼容*/
            }
            return $result;
        }

    }
    public function actionGetumsautheninfo(){
        $customer_id = \Yii::$app->request->post("customer_id");
        $umsauth = CustomerUmsauth::find()->where(['customer_id'=>$customer_id])->asArray()->one(); //
        if(!empty($umsauth)){
            return $umsauth;
        }else{
            return array();
        }

    }
    //老版本的个人认证 实名认证
    public function actionPersonauth(){
        //个人认证
        $customer_id = \Yii::$app->request->post("customer_id");
        if(empty($customer_id)){
            $msg['msg'] = "客户不能为空";
            return $msg;
        }
        $name = \Yii::$app->request->post("name");
        $id_no = \Yii::$app->request->post("idno");
        $img1 = "";
        $img2 = "";
        if(isset($_FILES['image1'])){
            $image1 = $_FILES['image1'];
            $filename = basename(html_entity_decode($image1['name'], ENT_QUOTES, 'UTF-8'));
            $type = strrchr($filename, '.');
            $rand = rand(1000, 9999);
            $picname = date("YmdHis") . $rand . $type; //重命名图片


            $validate1 = Image::validateimage($image1,1024*1024*2,650,650);//验证图片
            if($validate1 == "success"){
                $upload1 = array('name'=>$picname,"tmp_name"=>$image1['tmp_name']);
                $data =  Image::uploadimage($upload1);
                if(!empty($data)){
                    $img1 = $data['group']."/".$data["path"];
                }
            }
        }else{
            $img1 = \Yii::$app->request->post("image1");
        }

        if(isset($_FILES['image2'])){
            $image2 = $_FILES['image2'];
            $filename = basename(html_entity_decode($image2['name'], ENT_QUOTES, 'UTF-8'));
            $type = strrchr($filename, '.');
            $rand = rand(1000, 9999);
            $picname = date("YmdHis") . $rand . $type; //重命名图片

            $validate2 = Image::validateimage($image2,1024*1024*2,650,650);//验证图片
            $img2 = "";
            if($validate2 == "success"){
                $upload2 = array('name'=>$picname,"tmp_name"=>$image2['tmp_name']);
                $data =  Image::uploadimage($upload2);
                if(!empty($data)){
                    $img2 = $data['group']."/".$data["path"];
                }
            }
        }else{
            $img2 = \Yii::$app->request->post("image2");
        }

        $result = array();
        if(empty($img1)){
            $result['status'] = "error";
            $result['data'] = "身份证正面必须上传";
            return $result;
        }
        if(empty($img2)){
            $result['status'] = "error";
            $result['data'] = "身份证反面必须上传";
            return $result;
        }
        $connection = \Yii::$app->db;
        $connection->open();
        $transaction = $connection->beginTransaction();

        try {
            $customer = Customer::findOne($customer_id);
            $customer->firstname = $name;
            $customer->idcard = $id_no;
            $customer->idcard_validate = 1 ;//待审核
            $customer->save();

            $connection->createCommand("DELETE FROM jr_customer_image  WHERE customer_id = ".$customer_id." AND (`code` = 'idcard_other' OR `code` = 'idcard_front')")->execute();
            $connection->createCommand("INSERT INTO jr_customer_image SET customer_id=".$customer_id." ,image= '".$img1. " ' ,`code`='idcard_front',date_added = NOW()")->execute();
            $connection->createCommand("INSERT INTO jr_customer_image SET customer_id=".$customer_id." ,image= '".$img2. " ' ,`code`='idcard_other',date_added = NOW()")->execute();
            // ... 执行其他 SQL 语句 ...
            $transaction->commit();
            $result['status'] = "success";
            $result['data'] = "";
            return $result;
        }catch( Exception $e) {
            $transaction->rollBack();
            $result['status'] = "success";
            $result['data'] = "数据错误";
            return $result;
        }
    }
    //商家认证
    public function actionBusinessauth(){
        //个人认证
        $customer_id = \Yii::$app->request->post("customer_id");
        $name = \Yii::$app->request->post("name");
        $id_no = \Yii::$app->request->post("idno");
        $legal = \Yii::$app->request->post("legal"); //法人
        $img1 = "";
        if(isset($_FILES['image1'])){
            $image1 = $_FILES['image1'];
            $filename = basename(html_entity_decode($image1['name'], ENT_QUOTES, 'UTF-8'));
            $type = strrchr($filename, '.');
            $rand = rand(1000, 9999);
            $picname = date("YmdHis") . $rand . $type; //重命名图片

            $validate1 = Image::validateimage($image1,1024*1024*2,650,650);//验证图片
            if($validate1 == "success"){
                $upload1 = array('name'=>$picname,"tmp_name"=>$image1['tmp_name']);
                $data =  Image::uploadimage($upload1);
                if(!empty($data)){
                    $img1 = $data['group']."/".$data["path"];
                }
            }
        }else{
            $img1 = \Yii::$app->request->post("image1");
        }

        $result = array();
        if(empty($img1)){
            $result['status'] = "error";
            $result['data'] = "营业执照必须上传";
            return $result;
        }

        $connection = \Yii::$app->db;
        $connection->open();
        $transaction = $connection->beginTransaction();

        try {
            $customer = Customer::findOne($customer_id);
            $customer->legel_name = $legal;
            $customer->company_name = $name;
            $customer->company_no = $id_no;

            $customer->idcard_validate = 1 ;//待审核
            $customer->save();

            $connection->createCommand("DELETE FROM jr_customer_image  WHERE customer_id = ".$customer_id." AND `code` = 'license'")->execute();
            $connection->createCommand("INSERT INTO jr_customer_image SET customer_id=".$customer_id." ,image= '".$img1. " ' ,`code`='license',date_added = NOW()")->execute();
            // ... 执行其他 SQL 语句 ...
            $transaction->commit();
            $result['status'] = "success";
            $result['data'] = "";
            return $result;
        }catch( Exception $e) {
            $transaction->rollBack();
            $result['status'] = "success";
            $result['data'] = "数据错误";
            return $result;
        }
    }


}
