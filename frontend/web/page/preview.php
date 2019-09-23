<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/11/10
 * Time: 15:35
 */
class preview {
    private $template_data=array();
    public function init($datas){
        foreach($datas as $key=>$data){
            $this->template_data[$key]=$this->{$key}($data);
        }
    }
    public function load($file,$data){
        if (file_exists(dirname(__FILE__).$file)) {
            extract($data);
            ob_start();
            require(dirname(__FILE__) .$file);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        } else {
            trigger_error('Error: Could not load template ' . dirname(__FILE__) .$file . '!');
            exit();
        }
    }
    public function hd($datas){
        $head_datas=array();
        foreach($datas as $key=>$data){
            $head_datas[]=$this->{$key}($data);
        }
        return $head_datas;
    }
    public function bd($datas){
        $body_datas=array();
        foreach($datas as $key=>$data){
            $body_datas[]=$this->{$key}($data);
        }
        return $body_datas;
    }
    public function ft($datas){
        $foot_datas=array();
        foreach($datas as $key=>$data){
            $foot_datas[]=$this->{$key}($data);
        }
        return $foot_datas;
    }
    public function layouts($datas){
        $layout_datas=array();
        foreach($datas as $data){
            if(isset($data['modules']) && $data['modules']){
                $result=array();
                foreach($data['modules'] as $key=>$model){
                    $result[$key]=$this->model($model);
                }
                $layout_datas[]= $this->load("/layouts/".$data['type'].".php",array("modeules"=>$result));
            }else{
                break;
            }
        }
        return implode("",$layout_datas);
    }
    public function model($datas){
        $model_datas=array();
        foreach($datas as $data){
            $model_datas[]=$this->load("/modules/".$data['moduleID']."/index.tpl",array());
        }
        return $model_datas;
    }
    public function render($data){
        echo $this->load("/".$data.".tpl",$this->template_data);
    }

}