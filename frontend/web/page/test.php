<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/11/10
 * Time: 11:10
 */
include_once("preview.php");
$page=new preview();
$data=array(
    //页头数据
    'hd'=>array(
        //布局
        'layouts'=>array(
            //布局单元
            array(
                'type'=>'grid-m0',
                    //模块
                    'modules'=>array(
                                "main"=>array(
                                    array("moduleID"=>"dianzhao"),//模块单元
                                    array("moduleID"=>"daohang")//模块单元
                                )
                            
                    ),
            ),
            //布局单元增加 array('type'=>"","modules"=>array())
        )
    ),
    'bd'=>array(
        //布局 开始
        'layouts'=>array(
            //布局单元开始
            array(
                'type'=>'grid-s5m0',
                //模块
                'modules'=>array(
                    "main"=>array(
                        array("moduleID"=>"goodlist"),//模块单元
                        array("moduleID"=>"topGoods")//模块单元
                    ),
                    "sub"=>array(
                        array("moduleID"=>"shopsearch"),//模块单元
                        array("moduleID"=>"sidecataVert"),//模块单元
                        array("moduleID"=>"sidecataPer"),//模块单元
                        array("moduleID"=>"servCenter"),//模块单元
                        array("moduleID"=>"topGoods")//模块单元
                    ),
                    "extra"=>array(
                        // array("moduleID"=>"huandengpian")//模块单元
                    )
                ),
            ),
            //布局单元结束
			
			//布局单元开始
            array(
                'type'=>'grid-s5m0',
                //模块
                'modules'=>array(
                    "main"=>array(
                        array("moduleID"=>"goodrecommend"),//模块单元
                        //array("moduleID"=>"other")//模块单元
                    ),
                    "sub"=>array(
                        array("moduleID"=>"huandengpian")//模块单元
                    ),
                    "extra"=>array(
                         array("moduleID"=>"twoDimensionalCode")//模块单元
                    )
                ),
            ),
			
			//布局单元开始
			
			//布局单元开始
            array(
                'type'=>'grid-s5m0e5',
                //模块
                'modules'=>array(
                    "main"=>array(
                        array("moduleID"=>"huandengpian"),//模块单元
                        //array("moduleID"=>"other")//模块单元
                    ),
                    "sub"=>array(
                        array("moduleID"=>"goodrecommend")//模块单元
                    ),
                    "extra"=>array(
                         array("moduleID"=>"shopArchive"),//模块单元
                         array("moduleID"=>"twoDimensionalCode")//模块单元
                    )
                ),
            ),
			
			//布局单元开始
			
            array(
                'type'=>'grid-m0',
                //模块
                'modules'=>array(
                    "main"=>array(
                        array("moduleID"=>"goodlist5"),//模块单元
                        //array("moduleID"=>"other")//模块单元
                    ),
                    "sub"=>array(
                        //array("moduleID"=>"huandengpian")//模块单元
                    ),
                    "extra"=>array(
                       //  array("moduleID"=>"huandengpian")//模块单元
                    )
                ),
            ),
			
        )
        //布局 结束
		
		
		
    ),
    'ft'=>array(
        //布局 开始
        'layouts'=>array(
            //布局单元开始
            array(
                'type'=>'grid-m0',
                //模块
                'modules'=>array(
                    "main"=>array(
                       // array("moduleID"=>"daohang"),//模块单元
                        //array("moduleID"=>"other")//模块单元
                    ),
                ),
            ),
            //布局单元结束
        )
        //布局 结束
    ),
);
$page->init($data);
$page->render("test");
