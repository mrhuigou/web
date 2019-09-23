<?php
namespace h5\controllers;

use api\models\V1\AdvertiseDetail;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class WashingController extends Controller
{
    public function actionIndex(){
	    $data = [];
	    $advertise = new AdvertiseDetail();
	    $focus_position = ['H5-2LXH-SLIDE'];
	    $data['swiper'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
//	    $focus_position = ['H5-2LXH-NAV'];
//	    $data['nav'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-DES1'];
	    $data['ad_1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-DES2'];
	    $data['ad_2'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-1F01'];
	    $data['floor_1_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-1F02'];
	    $data['floor_1_02'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-2F01'];
	    $data['floor_2_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-2F02'];
	    $data['floor_2_02'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-3F01'];
	    $data['floor_3_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-3F02'];
	    $data['floor_3_02'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-4F01'];
	    $data['floor_4_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-4F02'];
	    $data['floor_4_02'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-5F01'];
	    $data['floor_5_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-5F02'];
	    $data['floor_5_02'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-6F01'];
	    $data['floor_6_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-6F02'];
	    $data['floor_6_02'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LXH-BRAND'];
	    $data['brand'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    return $this->render("index",$data);
    }
}