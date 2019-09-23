<?php

namespace common\component\oauth;

use yii\authclient\OAuth2;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\helpers\Json;
/**
 * QQ OAuth
 * @author xjflyttp <xjflyttp@gmail.com>
 */
class QqAuth extends OAuth2 implements IAuth {

    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    public $apiBaseUrl = 'https://graph.qq.com';

    public function init() {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'get_user_info',
            ]);
        }
    }

    protected function initUserAttributes() {
        return  $this->api('oauth2.0/me', 'GET');
    }
    /**
     * get UserInfo
     * @return []
     * @see http://wiki.connect.qq.com/get_user_info
     */
    public function getUserInfo() {
        $openid = $this->getUserAttributes();
        return $this->api("user/get_user_info", 'GET', [
            'oauth_consumer_key' => $openid['client_id'],
            'openid' => $openid['openid'
            ]]);
    }

    protected function defaultName() {
        return 'QQ';
    }

    protected function defaultTitle() {
        return 'QQ';
    }

    protected function defaultViewOptions() {
        return [
            'popupWidth' => 800,
            'popupHeight' => 500,
        ];
    }
}
