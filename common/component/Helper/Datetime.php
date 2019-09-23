<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/9/6
 * Time: 9:44
 */
namespace common\component\Helper;
class Datetime{
    static function getTimeAgo($the_time,$formart='m-d'){
        $show_time = strtotime($the_time);
        $dur = time() - $show_time;
        if($dur < 0){
            return "刚刚";
        }else{
            if($dur < 60){
                return $dur.'秒前';
            }else{
                if($dur < 3600){
                    return floor($dur/60).'分钟前';
                }else{
                    if($dur < 86400){
                        return floor($dur/3600).'小时前';
                    }else{
                        if($dur < 259200){ //3天内
                            return floor($dur/86400).'天前';
                        }else{
                            return date($formart,strtotime($the_time));
                        }
                    }
                }
            }
        }
    }
    static function getWeekDay($time,$prefix='周'){
        $weekarray=array("日","一","二","三","四","五","六");
        return $prefix.$weekarray[date("w",$time)];
    }
    //$timediff  时间相差值
    static function timediff($timediff)
    {
        //计算天数
        $days = intval($timediff/86400);
        //计算小时数
        $remain = $timediff%86400;
        $hours = intval($remain/3600);
        //计算分钟数
        $remain = $remain%3600;
        $mins = intval($remain/60);
        //计算秒数
        $secs = $remain%60;
        $res = array("day" => $days?$days.'天':'',"hour" => $hours?$hours."时":"","min" => $mins?$mins."分":'',"sec" => $secs?$secs.'秒':"");
        return implode('',$res);
    }


}