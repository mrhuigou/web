<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/9
 * Time: 16:45
 */
namespace h5\widgets\Tools;
use api\models\V1\CustomerAffiliate;
use yii\bootstrap\Widget;

class Share extends Widget{
	public $data;
	private $type;
	public function init()
	{
		$useragent=\Yii::$app->request->getUserAgent();
         if(!\Yii::$app->user->isGuest && !\Yii::$app->session->get('source_from_agent_wx_xcx')){
	         if(isset($this->data['link']) && $this->data['link']){
		         $this->data['link']=$this->add_querystring_var($this->data['link'],'follower_id',\Yii::$app->user->getId());
		         if($from_affiliate_uid=\Yii::$app->session->get('from_affiliate_uid')){
                     $this->data['link']=$this->add_querystring_var($this->data['link'],'from_affiliate_uid',$from_affiliate_uid);
                 }
		         $trace_code=md5(urlencode($this->data['link']));
		         $this->data['link']=$this->add_querystring_var($this->data['link'],'trace_code',$trace_code);
		         $this->data['trace_code']=$trace_code;
	         }
         }
		if(strpos(strtolower($useragent), 'jiaruncustomerapp')){
			$this->type="app";
		}elseif(strpos(strtolower($useragent), 'micromessenger')){
			$this->type="wx";
		}elseif (strpos(strtolower($useragent), 'zhqdapp')){
            $this->type="zhqd";
        }
		parent::init();
	}
	public function run(){
		if(!\Yii::$app->user->isGuest &&  !\Yii::$app->session->get('source_from_agent_wx_xcx')){
			if($this->type){
				if($this->type=='app'){
					$this->render('share-app',['data'=>$this->data]);
				}elseif($this->type=='wx'){
					$this->render('share-wx',['data'=>$this->data]);
				}elseif ($this->type=='zhqd'){
                    $this->render('share-zhqd',['data'=>$this->data]);
                }
			}
		}
	}
	private function add_querystring_var($url, $key, $value) {
		$url=preg_replace('/(.*)(?|&)'.$key.'=[^&]+?(&)(.*)/i','$1$2$4',$url.'&');
		$url=substr($url,0,-1);
		if(strpos($url,'?') === false){
			return ($url.'?'.$key.'='.$value);
		} else {
			return ($url.'&'.$key.'='.$value);
		}
	}
}