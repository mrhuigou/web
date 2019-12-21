<?php
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
use yii\captcha\Captcha;
?>
<?php if(Yii::$app->session->getFlash("merchant_message")){?>
<script>
    alert('<?php echo Yii::$app->session->getFlash("merchant_message");?>');
    <?php Yii::$app->session->removeFlash("merchant_message");?>
</script>
<?php }?>
<div class="" style="min-width:1200px;">
        <div class="w1100 bc pt10">
            <img src="/assets/images/merchant/zs2_banner.jpg" alt="招商" width="1100" height="377" class="bc db" />
            <div class="zs_box" style="width: 958px;">
                <h2 class="zs_tit">平台资源</h2>
                <div class="w450 fl">
                    <p class="f14 mt20 mb20 t2">经过自运营测试和功能完善，每日惠购互联网同城平台即将全面开放！在当下纷乱渐欲迷人眼的异地网购乱局中构建智慧城市生活综合服务体，对接本地品牌商家与消费者，全面助力本地品牌生产和品牌分销企业跨进互联网营销时代，轻松实现互联网精准营销能力，打造岛城人自己的互联网经济生态圈。</p>
                    <p class="f14 mt20 mb20 t2">公司核心管理团队由零售分销管理、互联网IT以及敏捷物流服务等领域专业人士组成，拥有丰富的企业管理和项目运营经验。平台汇集了互联网网购平台、分销零售ERP、WMS分拣中心、手持远程配送智能终端、手机APP应用和SNS营销等功能，为面向B2C垂直目类营销提供无缝、全方位分销对接服务。</p>
                    <img src="/assets/images/merchant/zs2_img2.jpg" alt="招商" width="516" height="691" class="mt25" />
                </div>
                <div class="w450 fr pb20">
                    <img src="/assets/images/merchant/zs2_img1.jpg" alt="招商" width="390" height="255" />
                    <h3 class="red f25 mt10 mb10">平台功能</h3>
                    <p class="gray6">
                        1、无缝零售业务一体化云应用平台，平台集互联网交易平台、网上订单处理发货、进销存管理为一体，保证企业级的数据对接安全，互联网业务管理更顺畅；<br />2、在线客服系统，呼叫中心与会员系统助力商家全面实践互联网时代精准营销的强大威力；<br />3、互联网、移动互联网、微信三个主流服务终端合一，企业全面近距离“接触”目标顾客，一步到位；<br />
                        4、提供企业信息系统对接接口，真正实现行业无缝分销和零售。
                    </p>
                    <h3 class="red f25 mt10 mb10">平台资源</h3>
                    <p class="gray6">
                        1、每日惠购同城平台凭借行业资源，让您即可拥有300万青岛网购人群，100万核心网购人群的推广渠道；<br />2、专业推广团队，全站营销活动策划与执行，店铺推广，参与即可分享平台流量（百万级日PV）红利；<br />3、丰富的线上线下推广通道，电子订阅、微信、微博、会员短信、异业互动以及O2O流程设计，让您的市场推广成本更低更有效。
                    </p>
                    <h3 class="red f25 mt10 mb10">同城智能供应链</h3>
                    <p class="gray6">
                        1、食品级中央智能分拣中心，支持动态仓储、快速PDA分拣、复核与打包作业；支持越库配送。<br />
                        2、同城专车快递收取与配送，智能便携终端GPS定位与路线跟踪，服务高效、准时。<br />
                        3、多种配送方案支持：24小时递送、休息日递送、限日配送、当日达、次日达、加速达等。
                    </p>
                </div>
                <div class="clear"></div>
                <img src="/assets/images/merchant/zs2_img3.jpg" alt="招商" width="889" height="275" class="db bc mb30 mt20" />

                <h2 class="zs_tit">简明入驻流程</h2>
                <img src="/assets/images/merchant/zs2_img5.jpg" alt="招商" width="945" height="127" class="db bc mb15" />
                <p class="mt20 ml10">
                    1、营业执照（包括个体工商户营业执照）、企业税务登记证副本复印件、组织机构代码证。<br />
                    2、拥有注册商标或者品牌的请提供相关证明材料。<br />
                    3、拥有国内知名品牌的品牌授权书
                </p>
                <img src="/assets/images/merchant/zs2_img8.jpg" alt="招商" width="945" height="128" class="db bc mt30 mb15" />
                <div class="zs_catalist clearfix">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="20%">店铺归类</th>
                            <th width="20%">经营目类</th>
                            <th width="20%">官方旗舰品牌</th>
                            <th width="20%">精品店铺品牌</th>
                            <th width="20%">技术服务费</th>
                        </tr>
                        <tr>
                            <td rowspan="6" class="zs_cata">餐饮外卖</td>
                            <td>西点烘焙</td>
                            <td>2</td>
                            <td>2</td>
                            <td rowspan="6">成交额的<br />3%—12%</td>
                        </tr>
                        <tr>
                            <td>鲜奶冰品</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>咖啡商务</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>快餐外卖</td>
                            <td>5</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>特色餐饮</td>
                            <td>7</td>
                            <td>8</td>
                        </tr>
                        <tr>
                            <td>工作餐盒</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="20%">店铺归类</th>
                            <th width="20%">经营目类</th>
                            <th width="20%">官方旗舰品牌</th>
                            <th width="20%">精品店铺品牌</th>
                            <th width="20%">技术服务费</th>
                        </tr>
                        <tr>
                            <td rowspan="6" class="zs_cata">食品酒水</td>
                            <td>红酒街</td>
                            <td>3</td>
                            <td>5</td>
                            <td rowspan="6">成交额的<br />5%—12%</td>
                        </tr>
                        <tr>
                            <td>特色杂货店</td>
                            <td>3</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>茶庄</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>干海产</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>保健品</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>药店</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="20%" rowspan="7" class="zs_cata">服装纺织</td>
                            <td width="20%">外贸服装</td>
                            <td width="20%">3</td>
                            <td width="20%">5</td>
                            <td width="20%" rowspan="7">成交额的<br />5%—15%</td>
                        </tr>
                        <tr>
                            <td>母婴</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>品牌服装</td>
                            <td>7</td>
                            <td>8</td>
                        </tr>
                        <tr>
                            <td>运动休闲</td>
                            <td>3</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>饰品服饰</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>眼镜钟表</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>海外代购</td>
                            <td></td>
                            <td>1</td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="20%" rowspan="7" class="zs_cata">社服娱乐</td>
                            <td width="20%">彩票</td>
                            <td width="20%">1</td>
                            <td width="20%">1</td>
                            <td width="20%" rowspan="7">成交额的<br />1%—10%</td>
                        </tr>
                        <tr>
                            <td>话费充值</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>游戏充值</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>书籍杂志</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>订票</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>家政</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>宠物用品</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="20%" rowspan="12" class="zs_cata">家电日百</td>
                            <td width="20%">日百</td>
                            <td width="20%">2</td>
                            <td width="20%">2</td>
                            <td width="20%" rowspan="12">成交额的<br />3%—8%</td>
                        </tr>
                        <tr>
                            <td>洗化</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>个人护理</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>手机、3C</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>小家电</td>
                            <td>3</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>运动用品</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>玩具、模型</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>箱包</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>汽车用品</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>手工艺</td>
                            <td>3</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>办公耗材</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>海外代购</td>
                            <td></td>
                            <td>2</td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="20%" rowspan="8" class="zs_cata">生鲜</td>
                            <td width="20%">熟食</td>
                            <td width="20%">2</td>
                            <td width="20%">2</td>
                            <td width="20%" rowspan="8">成交额的<br />1%—8%</td>
                        </tr>
                        <tr>
                            <td>水果</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>蔬菜农产</td>
                            <td>2</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>谷物杂粮</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>蛋奶</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>肉禽</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>水产</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>花店</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                    </table>

                </div>

                <table cellpadding="0" cellspacing="0" class="zs_count">
                    <tr>
                        <th colspan="3">每日惠购平台资费标准</th>
                    </tr>
                    <tr>
                        <td width="25%"></td>
                        <td width="25%">平台使用费</td>
                        <td>单品数</td>
                    </tr>
                    <tr>
                        <td>精品品牌店</td>
                        <td>10000元/年</td>
                        <td style="text-align:left;">首次入驻,每日惠购网免费设计制作商品展示位，单品数量不限，商家也可按照图片尺寸要求自行设计提交。</td>
                    </tr>
                    <tr>
                        <td>官方旗舰店</td>
                        <td colspan="2" style="padding:15px;">5000元起根据商家定制开发样式确定</td>
                    </tr>
                    <tr>
                        <td>展示型店铺</td>
                        <td>1000元/年</td>
                        <td style="text-align:left;">首次入驻,每日惠购网免费设计制作10个商品展示位，如需增加，按照100元/个收取成本制作费；商家也可按照图片尺寸要求自行设计免费提交。</td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="zs_count">
                    <tr>
                        <th colspan="5">配送服务收费标准</th>
                    </tr>
                    <tr>
                        <td width="15%"></td>
                        <td>&lt;5KG按票计费</td>
                        <td>&lt;10KG按票计费</td>
                        <td>&lt;15kg按票计费</td>
                        <td>&gt;=15KG按公斤计费</td>
                    </tr>
                    <tr>
                        <td>单票</td>
                        <td>7</td>
                        <td>10</td>
                        <td>13</td>
                        <td>6+重量*0.5/kg</td>
                    </tr>
                    <!--
                        <tr>
                            <td>合票</td>
                            <td>按成交金额分摊，最低1元</td>
                            <td>按综合货重分摊</td>
                            <td>按综合货重分摊</td>
                            <td>6+重量*0.5/kg</td>
                        </tr>
                    -->
                </table>

                <table border="0" cellspacing="0" cellpadding="0" width="100%" class="zs_count">
                    <tr>
                        <th colspan="7">仓储服务收费标准</th>
                    </tr>
                    <tr>
                        <td rowspan="2" width="10%">计费方法</td>
                        <td rowspan="2" width="11%">仓储类型</td>
                        <td colspan="5">收费明细</td>
                    </tr>
                    <tr>
                        <td width="14%">仓储费</td>
                        <td width="16%">分拣打包费</td>
                        <td width="7%"><p >单据费</p></td>
                        <td width="23%"><p >出入库操作费</p></td>
                    </tr>
                    <tr>
                        <td>独立分区</td>
                        <td>独立货位</td>
                        <td>120元/位/月</td>
                        <td>免</td>
                        <td>2元/单</td>
                        <td>已含</td>
                    </tr>
                    <tr>
                        <td>动态库位</td>
                        <td>标准货位</td>
                        <td>300元/10品/月</td>
                        <td>免</td>
                        <td>2元/单</td>
                        <td>已含</td>
                    </tr>
                    <tr>
                        <td>越库配送</td>
                        <td colspan="2">&nbsp;</td>
                        <td>免</td>
                        <td>免</td>
                        <td>免</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align:left;">备注：1、包装材料甲方自备；2、入库需要店铺商自行卸货；3、二次包装贴码等增值服务另行计费；4、越库配送需具备自贴单能力。</td>
                    </tr>
                </table>

                <p class="f14 ml50 mt5">说明：以上收费项目根据商家实际使用的服务项目收取费用。<br /></p>

                <p class="fb f14 pl50 pt10">
                    精品品牌店：品牌城市一级代理商，可经营多品牌<br />
                    官方旗舰店：品牌商标拥有者或品牌全网独家授权商，限单一品牌<br />
                    展示型店铺 ：生活服务类网上店铺，经营虚拟商品，限单一法人
                </p>

                <h2 class="zs_tit">联系我们</h2>

                <ul class="zs_contact clearfix f14">
                    <li><span class="fb f12">· </span> <span class="fb">生鲜、农产、餐饮外卖招商：</span>韩经理，电话：84856172转36</li>
                    <li><span class="fb f12">· </span> <span class="fb">特色食品、进口食品招商：</span>韩经理，电话：84860820转36</li>
                    <li><span class="fb f12">· </span> <span class="fb">服饰穿戴类招商：</span>李经理，电话：84860820转30</li>
                    <li><span class="fb f12">· </span> <span class="fb">娱乐、团购招商：</span>巩经理，电话：84856172转35</li>
                    <li><span class="fb f12">· </span> <span class="fb">家电、日百、洗护招商：</span>刘经理，电话：84860820转30</li>
                    <li><span class="fb f12">· </span> <span class="fb">酒水、冲饮招商：</span>韩经理，电话：84860820转35</li>

                </ul>

                
            </div>

            <img src="/assets/images/merchant/zs2_img7.jpg" alt="招商" width="1100" height="78" class="db bc mb15" />


        </div>
    </div>
