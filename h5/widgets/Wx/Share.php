<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace h5\widgets\Wx;
use yii\bootstrap\Widget;

class Share extends Widget{
    public $title;
    public $desc;
    public $link;
    public $imgUrl;
    public $go_url;
    public $back_url;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $useragent=\Yii::$app->request->getUserAgent();
        if(strpos(strtolower($useragent), 'micromessenger')){
            $model=[
              'title'=>$this->title,
                'desc'=>$this->desc,
                'link'=>$this->link,
                'imgUrl'=>$this->imgUrl,
                'go_url'=>$this->go_url,
                'back_url'=>$this->back_url
            ];
            return $this->render('share',['model'=>$model]);
        }
    }
}
