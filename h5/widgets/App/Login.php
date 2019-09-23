<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace h5\widgets\App;
use yii\bootstrap\Widget;
class Login extends Widget
{
    public function init()
    {
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        $useragent=\Yii::$app->request->getUserAgent();
        if(strpos(strtolower($useragent), 'zhqdapp')){
            return $this->render('login');
        }

    }
}