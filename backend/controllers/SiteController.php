<?php
namespace backend\controllers;

use api\models\V1\Customer;
use api\models\V1\Order;
use backend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;
use backend\rbac;

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
                        'actions' => ['logout', 'index','bob','go-show-stock'],
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
        $user_total=Customer::find()->where("date_added>'".date('Y-m-d',time())."'")->count("*");
        $order_count=Order::find()->where("date_added>'".date('Y-m-d',time())."'")->count("*");
        $user_count=Customer::find()->count("*");
        $order_total=Order::find()->where("date_added>'".date('Y-m-d',time())."'")->sum("total");
	    $product_total=Order::find()->alias('o')->joinWith('orderProducts op')->where("o.date_added>'".date('Y-m-d',time())."'")->sum("op.total");
		if($order_total<$product_total){
			$order_total=$product_total;
		}
        return $this->render('index',['model'=>['user_total'=>$user_total,'order_count'=>$order_count,'user_count'=>$user_count,'order_total'=>$order_total?$order_total:0]]);
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
            $this->layout='@backend/views/layouts/loginmain.php';
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
    public function actionGoShowStock(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        if($Model=User::findOne(['user_id'=>Yii::$app->user->getId()])){
            $Model->show_stock_token = Yii::$app->security->generateRandomString();
            $Model->save();
            return $this->redirect('https://m.mrhuigou.com/site/show-stock?token='.$Model->show_stock_token);
        }
    }
}
