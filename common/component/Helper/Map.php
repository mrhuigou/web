<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/9/12
 * Time: 14:50
 */
namespace common\component\Helper;
class Map {
	static $DEF_PI = 3.14159265359; // PI
	static $DEF_2PI = 6.28318530712; // 2*PI
	static $DEF_PI180 = 0.01745329252; // PI/180.0
	static $DEF_R = 6370693.5; // radius of earth

	//适用于近距离
	public static function GetShortDistance($lon1, $lat1, $lon2, $lat2)
	{
		// 角度转换为弧度
		$ew1 = $lon1 * static::$DEF_PI180;
		$ns1 = $lat1 * static::$DEF_PI180;
		$ew2 = $lon2 * static::$DEF_PI180;
		$ns2 = $lat2 * static::$DEF_PI180;
		// 经度差
		$dew = $ew1 - $ew2;
		// 若跨东经和西经180 度，进行调整
		if ($dew > static::$DEF_PI)
			$dew = static::$DEF_2PI - $dew;
		else if ($dew < -static::$DEF_PI)
			$dew = static::$DEF_2PI + $dew;
		$dx = static::$DEF_R * cos($ns1) * $dew; // 东西方向长度(在纬度圈上的投影长度)
		$dy = static::$DEF_R * ($ns1 - $ns2); // 南北方向长度(在经度圈上的投影长度)
		// 勾股定理求斜边长
		$distance = sqrt($dx * $dx + $dy * $dy);
		return $distance;
	}

//适用于远距离
	public static function GetLongDistance($lon1, $lat1, $lon2, $lat2)
	{
		// 角度转换为弧度
		$ew1 = $lon1 * static::$DEF_PI180;
		$ns1 = $lat1 * static::$DEF_PI180;
		$ew2 = $lon2 * static::$DEF_PI180;
		$ns2 = $lat2 * static::$DEF_PI180;
		// 求大圆劣弧与球心所夹的角(弧度)
		$distance = sin($ns1) * sin($ns2) + cos($ns1) * cos($ns2) * cos($ew1 - $ew2);
		// 调整到[-1..1]范围内，避免溢出
		if ($distance > 1.0)
			$distance = 1.0;
		else if ($distance < -1.0)
			$distance = -1.0;
		// 求大圆劣弧长度
		$distance = static::$DEF_R * acos($distance);
		return $distance;
	}

}