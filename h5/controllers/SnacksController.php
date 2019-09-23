<?php
namespace h5\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use api\models\V1\AdvertiseDetail;


/**
 * Site controller
 */
class SnacksController extends Controller
{
    public function actionIndex(){
	    $data = [];
	    $advertise = new AdvertiseDetail();
	    $focus_position = ['H5-2LXS-SLIDE'];
	    $data['swiper'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-DES1'];
	    $data['ad_1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-DES2'];
	    $data['ad_2'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-BRAND'];
	    $data['brand'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-1F01'];
	    $data['floor_1_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-2F01'];
	    $data['floor_2_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-3F01'];
	    $data['floor_3_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-4F01'];
	    $data['floor_4_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-5F01'];
	    $data['floor_5_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-6F01'];
	    $data['floor_6_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-7F01'];
	    $data['floor_7_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-8F01'];
	    $data['floor_8_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-9F01'];
	    $data['floor_9_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-10F01'];
	    $data['floor_10_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXS-11F01'];
	    $data['floor_11_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    return $this->render("index",$data);
    }
}