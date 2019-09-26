<?php
namespace frontend\controllers;

use api\models\V1\CmsType;
use api\models\V1\Information;
use frontend\models\MerchantForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class InformationController extends Controller
{
     public $layout = 'main-remove';
    public function behaviors()
    {
        return [
//            [
//                'class' => 'yii\filters\PageCache',
//                'only' => ['information'],
//                'duration' => 3600,
//                'variations' => [
//                    \Yii::$app->request->getAbsoluteUrl(),
//                ],
//                'dependency' => [
//                    'class' => 'yii\caching\DbDependency',
//                    'sql' => 'SELECT COUNT(*) FROM jr_information',
//                ],
//            ],
        ];
    }
    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'backColor'=>0xFFFFFF,  //背景颜色
                'minLength'=>4,  //最短为4位
                'maxLength'=>4,   //是长为4位
                'transparent'=>false,  //显示为透明
                'offset'=>2,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function actionInformation(){
        $this->layout = 'main-remove';
        $infornation_id = Yii::$app->request->get("information_id");
        $infornation = Information::findOne($infornation_id);
       if(!empty($infornation)){
            $cms_types = CmsType::find()->orderBy('weight ASC')->limit(4)->asArray()->all();
            foreach($cms_types as $key=> $cms_type) {
                $cms_types[$key]['cms'] = Information::find()->leftJoin("jr_information_description","jr_information_description.information_id=jr_information.information_id")
                    ->where(['jr_information_description.type'=>$cms_type['cms_type_id'],'jr_information.status'=>1])
                    ->orderBy('sort_order ASC')->all();
            }
            //print_r($cms_types);exit;


            Yii::$app->getView()->params['breadcrumbs'][] = ['label' =>$infornation->description->title, 'url' => ['/information/information','information_id'=>$infornation->information_id],'template'=> "<li>{link}</li>"];
            return $this->render("information",['types'=>$cms_types,'information'=>$infornation]);
       }else{
           throw new NotFoundHttpException("没有找到该页面！");
       }

    }
    public function actionMerchant(){
        $this->view->registerCssFile('/assets/stylesheets/topic/topic.css');
        $model = new MerchantForm();
        if ($model->load(Yii::$app->request->post()) ) {
            $merchantform = Yii::$app->request->post("MerchantForm");
            $name = $merchantform["name"];
            $telephone =  $merchantform["telephone"];
            $fromemail =  $merchantform["email"];
            $merchant =  $merchantform["merchant"];
            $type =  $merchantform["type"];


            $content =  $merchantform["content"];
            $othercontacts =  $merchantform["othercontacts"];

            $message  = '<html dir="ltr" lang="en">' . "\n";
            $message .= '  <head>' . "\n";
            $message .= '    <title>' . $name.' '.$telephone .' '.$fromemail .'</title>' . "\n";
            $message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
            $message .= '  </head>' . "\n";
            $message .= '  <body>' ;
            $message .= '商家名称：'.html_entity_decode($merchant, ENT_QUOTES, 'UTF-8') .'<br/><br/>' ;
            $message .= '合作类型：'.html_entity_decode($type, ENT_QUOTES, 'UTF-8') .'<br/><br/>' ;
            $message .= '合作意向：'.html_entity_decode($content, ENT_QUOTES, 'UTF-8') .'<br/><br/>' ;
            $message .= '联系人：'.html_entity_decode($name, ENT_QUOTES, 'UTF-8') .'<br/>' ;
            $message .= '联系电话：'.html_entity_decode($telephone, ENT_QUOTES, 'UTF-8') .'<br/>' ;
            $message .= '联系邮箱：'.html_entity_decode($fromemail, ENT_QUOTES, 'UTF-8') .'<br/>' ;
            $message .= '其他联系方式：'.html_entity_decode($othercontacts, ENT_QUOTES, 'UTF-8') .'<br/>' ;
            $message .= '</body>' . "\n";
            $message .= '</html>' . "\n";
            /**/



            $email="biz@mrhuigou.com";
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom($fromemail)
                ->setSubject(html_entity_decode($name.' '.$telephone .' '.$fromemail , ENT_QUOTES, 'UTF-8'))
                ->setHtmlBody($message)
                ->send();
            Yii::$app->session->setFlash('merchant_message','申请成功，我们的客户经理将尽快与您联系');

        }
        return $this->render("merchant",[ 'model' => $model,]);
    }
}