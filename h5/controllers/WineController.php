<?php
namespace h5\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use api\models\V1\AdvertiseDetail;

/**
 * Site controller
 */
class WineController extends Controller
{
    public function actionIndex(){
	    $data = [];
	    $advertise = new AdvertiseDetail();
	    $focus_position = ['H5-2LJS-SLIDE'];
	    $data['swiper'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-NAV'];
	    $data['nav'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-DES1'];
	    $data['ad_1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-DES2'];
	    $data['ad_2'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-DES3'];
	    $data['ad_3'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-1F01'];
	    $data['floor_1_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-2F01'];
	    $data['floor_2_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-3F01'];
	    $data['floor_3_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-4F01'];
	    $data['floor_4_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-5F01'];
	    $data['floor_5_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-6F01'];
	    $data['floor_6_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-7F01'];
	    $data['floor_7_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-8F01'];
	    $data['floor_8_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-9F01'];
	    $data['floor_9_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-10F01'];
	    $data['floor_10_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    $focus_position = ['H5-2LJS-11F01'];
	    $data['floor_11_01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
	    return $this->render("index",$data);
    }
}