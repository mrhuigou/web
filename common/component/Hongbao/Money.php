<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/17
 * Time: 16:12
 */
namespace common\component\Hongbao;
#红包生成的算法程序
class Money
{
	public $total;       //红包总额
	public $num;        // 分成8个红包，支持8人随机领取
	public $min=0.01; //每个人最少能收到0.01元
	public $result=[];        #红包结果集

	#初始化红包类
	public function __construct($total,$num)
	{   $this->total=$total;
		$this->num=$num;
	}

	#执行红包生成算法
	public function split()
	{
		for ($i=0;$i<$this->num;$i++)
		{
			if($i==$this->num-1){
				$this->result[]=round($this->total,2);
			}else{
				$max=$this->total/($this->num-$i)*2;
				$money=round((mt_rand(1,100)/100)*$max,2);
				$money = ($money <= $this->min ? $this->min: $money);
				$this->total=$this->total-$money;
				$this->result[]=$money;
			}
		}
		return $this->result;
	}

}