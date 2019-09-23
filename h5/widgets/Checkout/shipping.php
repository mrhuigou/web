<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace h5\widgets\Checkout;
use yii\bootstrap\Widget;
class shipping extends Widget
{   public $time;
    public $exclude;
    public $datas;
    public function init()
    {
        if(!$this->time){
            $this->time=time();
        }
        if($this->exclude){
            if($this->time >= $this->exclude['start_time']  && $this->time <  $this->exclude['end_time']){
                $this->datas=[
                    'data_date'=>$this->exclude['target_time'],
                    'data_time'=>'08:00-13:00',
                    'data_times'=>['08:00-13:00','13:00-18:00','18:00-22:00']
                ];
            }
        }
        if(!$this->datas){
            if($this->time>strtotime(date('Y-m-d 17:00:00',$this->time))){
                $this->datas=[
                    'data_date'=>date('Y-m-d', strtotime('+ 1 day',$this->time)),
                    'data_time'=>'08:00-13:00',
                    'data_times'=>['08:00-13:00','13:00-18:00','18:00-22:00']
                ];
            }else{
                if($this->time<strtotime(date('Y-m-d 12:00:00',$this->time))){
                    $this->datas=[
                        'data_date'=>date('Y-m-d', $this->time),
                        'data_time'=>'13:00-18:00',
                        'data_times'=>['13:00-18:00','18:00-22:00']
                    ];
                }else{
                    $this->datas=[
                        'data_date'=>date('Y-m-d', $this->time),
                        'data_time'=>'18:00-22:00',
                        'data_times'=>['18:00-22:00']
                    ];
                }
            }
        }
        parent::init();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('shipping',['datas'=>$this->datas]);

    }
} 