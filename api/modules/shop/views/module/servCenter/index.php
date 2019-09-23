<div class="J_TModule"  data-title="客服中心">
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-other-customer-service">
    <s class="skin-box-tp"><b></b></s>
        <div class="skin-box-bd">
            <?php if(isset($data['display']['define_style']) && $data['display']['define_style'] ) { ?>
    <ul>
                        <li class="service-block">
                <h4>工作时间</h4>
                <ul class="service-content">
                                        <li>周一至周日：9:00-23:00</li>
                                                            <li>周六至周日：9:00-23:00</li>
                                    </ul>
            </li>
               <li class="service-block">
                <h4>在线咨询</h4>
                <ul  class="service-content service-group">
                <li class="group">
                    <span class="groupname">售后客服</span>
                    <a href="" target="_blank"><img src="http://amos.alicdn.com/grponline.aw?v=3&amp;uid=sdeer%C6%EC%BD%A2%B5%EA&amp;site=cntaobao&amp;gids=2107306&amp;s=1" alt="点击这里给我发消息"></a>
                </li>
                    <li class="group">
                        <span class="groupname">查件客服</span>
                        <a href="" target="_blank">
                        <img src="http://amos.alicdn.com/grponline.aw?v=3&amp;uid=sdeer%C6%EC%BD%A2%B5%EA&amp;site=cntaobao&amp;gids=2107294&amp;s=1" alt="点击这里给我发消息">
                        </a>
                    </li>
                </ul>
            </li>
                   </ul>
            <?php }else{
            echo isset($data['display']['content'])?$data['display']['content']:"";
            }?>
    </div>
    <s class="skin-box-bt"><b></b></s>
    </div>
</div>