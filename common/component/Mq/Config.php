<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/6
 * Time: 13:53
 */
namespace common\component\Mq;
class Config {
#您在控制台创建的Topic
	static $Topic = "topic_demo_mac";
#公测环境的URL
	static $URL = "http://publictest-rest.ons.aliyun.com";
#阿里云身份验证码
	static $Ak = "LTAIaHIHGcCLOzSL";
#阿里云身份验证密钥
	static $Sk = "uKntzlo8UKwkkhmLY1o4B2jLPgMseW";
#MQ控制台创建的Producer ID
	static $ProducerID = "PID_MSG_DEMO";
#MQ控制台创建的Consumer ID
	static $ConsumerID = "CID_USER_WEB";
}