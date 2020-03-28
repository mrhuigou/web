<?php
namespace fx\controllers;

use api\models\V1\AffiliateCustomer;
use api\models\V1\Customer;
use api\models\V1\Order;
use common\component\Curl\Curl;
use common\component\Wx\WxSdk;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use affiliate\models\LoginForm;
use yii\filters\VerbFilter;
use affiliate\rbac;
use affiliate\models\ResetPasswordForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','reset-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'backColor'=>0xFFFFFF,  //背景颜色
                'minLength'=>4,  //最短为4位
                'maxLength'=>4,   //是长为4位
                'transparent'=>false,  //显示为透明,
                'offset'=>2,
                'height'=>35,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
		$customer_model=Customer::find()->where(['affiliate_id'=>Yii::$app->user->getId()]);
	    $customer_total_count=$customer_model->count();
		$customer_today_count=$customer_model->andWhere(['>','date_added',date('Y-m-d 00:00:00',time())])->count();
	    $order_model=Order::find()->where(['affiliate_id'=>Yii::$app->user->getId(),'sent_to_erp'=>'Y']);
	    $order_total_count=$order_model->count();
        $order_today_count=$order_model->andWhere(['>','date_added',date('Y-m-d',time())])->count();
        return $this->render('index',[
        	'customer_today_count'=>$customer_today_count,
	        'customer_total_count'=>$customer_total_count,
	        'order_today_count'=>$order_today_count,
	        'order_total_count'=>$order_total_count
        ]);
    }


    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $this->layout='@affiliate/views/layouts/loginmain.php';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionResetPassword()
    {
        
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');
            $model->resetPassword(Yii::$app->session->get('telephone'),$model->password);
            return $this->redirect('/site/reset-password');
        }
       return $this->render('password', [
            'model' => $model,
        ]);
    }
}
